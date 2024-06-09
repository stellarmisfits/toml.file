<?php
namespace App\Services;

use App\Models\Account;
use App\Services\Contracts\StellarAccountContract;
use ErrorException;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use mysql_xdevapi\Exception;
use phpseclib\Math\BigInteger;
use Throwable;
use ZuluCrypto\StellarSdk\Horizon\ApiClient;
use ZuluCrypto\StellarSdk\Keypair;
use ZuluCrypto\StellarSdk\Model\ManageDataOperation;
use ZuluCrypto\StellarSdk\Server;
use ZuluCrypto\StellarSdk\Model\Account as StellarAccount;
use ZuluCrypto\StellarSdk\Transaction\Transaction;
use ZuluCrypto\StellarSdk\Transaction\TransactionBuilder;
use ZuluCrypto\StellarSdk\Xdr\XdrBuffer;
use ZuluCrypto\StellarSdk\XdrModel\AccountId;
use ZuluCrypto\StellarSdk\XdrModel\DecoratedSignature;
use ZuluCrypto\StellarSdk\XdrModel\Operation\ManageDataOp;
use ZuluCrypto\StellarSdk\XdrModel\Operation\Operation;
use ZuluCrypto\StellarSdk\XdrModel\TransactionEnvelope;

/**
 * Class ChallengeTransactionService
 * @package App\Services
 */
class ChallengeTransactionService
{
    /**
     * @var Keypair
     */
    protected $account;

    /**
     * @var Keypair
     */
    protected $serverSigningKey;

    /**
     * @var ApiClient
     */
    protected $apiClient;

    /**
     * @var string
     */
    protected $networkPassphrase;


    /**
     * ChallengeTransactionService constructor.
     */
    public function __construct()
    {
        $this->apiClient = (config('stellar.horizon.type') === 'testnet') ? ApiClient::newTestnetClient() : ApiClient::newPublicClient();
        $this->networkPassphrase = $this->apiClient->getNetworkPassphrase();
        $this->serverSigningKey  = Keypair::newFromSeed(config('stellar.signing_key.secret'));
    }

    /**
     * @return string
     */
    public function getNetworkPassphrase() {
        return $this->networkPassphrase;
    }

    /**
     * @param Keypair $account
     */
    public function setAccount(Keypair $account) {
        $this->account = $account;
    }

    /**
     * @return TransactionEnvelope
     * @param Keypair $account
     * @throws ErrorException
     */
    public function getChallenge(Keypair $account): TransactionEnvelope{
        $this->account = $account;
        $accountId = new AccountId($this->account->getAccountId());

        $op = new ManageDataOp('astrify auth');
        $op->setSourceAccount($accountId);
        $op->setValue(base64_encode(random_bytes ( 48 )));

        $txB = new TransactionBuilder($this->serverSigningKey);
        $txB->setApiClient($this->apiClient);
        $txB->addOperation($op);
        $txB->setLowerTimebound(Carbon::now()->toDateTime());
        $txB->setUpperTimebound(Carbon::now()->addHour()->toDateTime());
        $txB->setSequenceNumber(new BigInteger(0));

        $txE = $txB->getTransactionEnvelope();

        $txE->sign($this->serverSigningKey->getSecret());
        return $txE;
    }


    /**
     * @param string $base64
     * @param Keypair $account
     * @throws ErrorException
     * @throws Throwable
     */
    public function verifyChallenge(string $base64, Keypair $account) {
        $this->account = $account;
        $xdr = base64_decode($base64);
        $tx = Transaction::fromXdr(new XdrBuffer($xdr));
        $txE = TransactionEnvelope::fromXdr(new XdrBuffer($xdr));

        /*
        |--------------------------------------------------------------------------
        | SEP: 0010 Challenge Transaction Verification
        |--------------------------------------------------------------------------
        | Verification steps: https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0010.md#token
        |
        */

        // verify that transaction source account is equal to the server's signing key;
        $this->validateTransactionSourceAccount($tx);

        // verify that transaction has time bounds set, and that current time is between the minimum and maximum bounds;
        $this->validateTimeBounds($tx);

        // verify that transaction contains a single Manage Data operation and it's source account is not null
        $this->validateManageDataOperation($tx);

        // verify that transaction envelope has a correct signature by server's signing key;
        $this->validateServerSignature($tx, $txE);

        // verify that transaction envelope has a correct signature by the operation's source account;
        $this->validateAccountSignature($tx, $txE);

        // verify that transaction sequenceNumber is equal to zero;
        $this->validateSequence($tx);
    }

    /**
     * @param Transaction $tx
     * @throws Throwable
     */
    protected function validateTransactionSourceAccount(Transaction $tx){
        $signingKey = config('stellar.signing_key.public');
        $sourceAccount = $tx->getSourceAccountId()->getAccountIdString();
        throw_unless(config('stellar.signing_key.public') === $sourceAccount, new \Exception(
            'The source account in the given transaction (' . $sourceAccount . ') is not equal to the server\'s signing key (' . $signingKey .').'
        ));
    }

    /**
     * @param Transaction $tx
     * @throws Throwable
     */
    protected function validateTimeBounds(Transaction $tx){
        $minTime = $tx->getTimeBounds()->getMinTime();
        $maxTime = $tx->getTimeBounds()->getMaxTime();
        $now = new \DateTime('now');

        throw_unless($minTime, new \Exception(
            'A min time was not provided in the given transaction.'
        ));

        throw_unless($maxTime, new \Exception(
            'A max time was not provided in the given transaction.'
        ));

        throw_unless($minTime < $now && $now < $maxTime, new \Exception(
            'The transaction was given outside of its minimum and maximum timebounds.'
        ));
    }

    /**
     * @param Transaction $tx
     * @return Operation
     * @throws Throwable
     */
    protected function validateManageDataOperation(Transaction $tx) {
        $ops = $tx->getOperations();
        throw_unless(count($ops) === 1, new \Exception(
            count($ops) . ' operations were found in the given transaction. Exactly one operation is required.'
        ));

        $op = $ops[0];
        throw_unless($op->getType() === 10, new \Exception(
            'The operation in the given transaction is not a Manage Data operation.'
        ));

        throw_unless($op->getSourceAccount(), new \Exception(
            'The manage data operation in the given transaction does not contain a source account.'
        ));

        throw_unless($op->getSourceAccount()->getAccountIdString() === $this->account->getAccountId(), new \Exception(
            'The source account (' .
            $op->getSourceAccount()->getAccountIdString() .
            ') in the manage data operation of the given transaction does not match the account you are attempting to verify ('.
            $this->account->getAccountId() . ')'));

        return $op;
    }

    /**
     * @param Transaction $tx
     * @param TransactionEnvelope $txE
     * @throws Throwable
     */
    protected function validateServerSignature(Transaction $tx, TransactionEnvelope $txE){
        throw_unless($this->hasSignature($tx, $txE, $this->serverSigningKey), new \Exception(
            'The given transaction has not been signed by Astrify\'s server signing key (' . $this->serverSigningKey->getAccountId() . ')'
        ));
    }

    /**
     * @param Transaction $tx
     * @param TransactionEnvelope $txE
     * @throws Throwable
     */
    protected function validateAccountSignature(Transaction $tx, TransactionEnvelope $txE){
        throw_unless($this->hasSignature($tx, $txE, $this->account), new \Exception(
            'The given transaction has not been signed by the account you are attempting to verify (' . $this->account->getAccountId() . ')'
        ));
    }

    /**
     * @param Transaction $tx
     * @throws Throwable
     */
    protected function validateSequence(Transaction $tx){
        throw_if($tx->getSequenceNumber()->compare(new BigInteger(0)), new \Exception(
            'The sequence number for the given transaction is not equal to 0'
        ));
    }

    /**
     * @param Transaction $tx
     * @param TransactionEnvelope $txE
     * @param Keypair $keypair
     * @return bool
     */
    protected function hasSignature(Transaction $tx, TransactionEnvelope $txE, Keypair $keypair) {

        $signatures = collect($txE->getDecoratedSignatures());
        $txB = $tx->toTransactionBuilder();
        $txB->setApiClient($this->apiClient);

        return $signatures->reduce(function ($carry, $signature) use ($keypair, $txB) {
            if($carry) {
                return $carry;
            }

            return $keypair->verifySignature($signature->getRawSignature(), $txB->hash());
        });
    }
}
