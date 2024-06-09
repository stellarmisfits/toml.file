<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\Account as AccountResource;
use App\Models\Account;
use App\Repositories\AccountRepository;
use App\Services\ChallengeTransactionService;
use App\Services\StellarAccountService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;
use ZuluCrypto\StellarSdk\Keypair;
use ZuluCrypto\StellarSdk\Server;

class VerificationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Account $account
     * @param ChallengeTransactionService $cts
     * @return AccountResource
     * @throws Throwable
     */
    public function show(Account $account, ChallengeTransactionService $cts)
    {
        $this->authorize('update', $account);

        throw_if($account->verified, ValidationException::withMessages([
            'account_uuid' => 'This account has already been verified.'
        ]));

        $cts = new ChallengeTransactionService();
        $txE = $cts->getChallenge(Keypair::newFromPublicKey($account->public_key));

        return response()->json([
            'transaction' => $txE->toBase64(),
            'network_passphrase' => $cts->getNetworkPassphrase()
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Account $account
     * @param ChallengeTransactionService $cts
     * @return AccountResource
     * @throws Throwable
     */
    public function store(Request $request, Account $account, ChallengeTransactionService $cts)
    {
        $data = $request->validate([
            'transaction'  => 'required|string|max:1000',
        ]);

        throw_if($account->verified, ValidationException::withMessages([
            'account_uuid' => 'This account has already been verified.'
        ]));

        try{
            $cts->verifyChallenge($request->transaction, Keypair::newFromPublicKey($account->public_key));
        }catch(\Exception $e){
            throw ValidationException::withMessages([
                'transaction' => $e->getMessage()
            ]);
        }

        $account->verified = true;
        $account->verification_tx = $request->transaction;
        $account->save();

        return new AccountResource($account);
    }

}
