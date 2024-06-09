<?php

namespace Tests\Fixtures;

use App\Models\Account;
use ZuluCrypto\StellarSdk\Server;
use \ZuluCrypto\StellarSdk\Keypair;
use \ZuluCrypto\StellarSdk\XdrModel\Operation\SetOptionsOp;
use \ZuluCrypto\StellarSdk\XdrModel\SignerKey;
use \ZuluCrypto\StellarSdk\XdrModel\Signer;
use App\Services\StellarAccountService;

/**
 * @package App\Services\Account
 */
class StellarHelpers
{

    /**
     * @var Server
     */
    protected $server;

    /**
     * Stellar constructor.
     */
    public function __construct()
    {
        $this->server = Server::testNet();
    }

    /**
     * @return Keypair
     */
    public function getAccount1(): Keypair {
        return $this->getKeypair('SECRET_KEY1');
    }

    /**
     * @param string $key
     * @return Keypair
     * @throws
     */
    protected function getKeypair(string $key): Keypair {
        $keypair = Keypair::newFromSeed(env($key));
        $this->ensureTestAccountExists($keypair->getPublicKey());
        return $keypair;
    }

    /**
     * @param string $publicKey
     * @throws \ErrorException
     * @throws
     */
    protected function ensureTestAccountExists(string $publicKey){
        if($this->server->accountExists($publicKey)){
            return;
        }

        file_get_contents('https://friendbot.stellar.org/?addr=' . $publicKey);
    }

    /**
     * @param Keypair $keypair
     * @param string $home_domain
     * @throws
     */
    public function setHomeDomain(Keypair $keypair, $home_domain){
        $optionsOperation = new SetOptionsOp();
        $optionsOperation->setHomeDomain($home_domain);

        $this->server->buildTransaction($keypair->getPublicKey())
            ->addOperation($optionsOperation)
            ->submit($keypair->getSecret());
    }
}
