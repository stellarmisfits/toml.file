<?php
namespace App\Services;

use App\Services\Contracts\StellarAccountContract;
use Illuminate\Validation\ValidationException;
use ZuluCrypto\StellarSdk\Server;
use ZuluCrypto\StellarSdk\Model\Account as StellarAccount;

class StellarAccountService
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
        if (config('stellar.horizon.type') === 'testnet'){
            $this->server = Server::testNet();
        }else{
            $this->server = Server::publicNet();
        }
    }

    /**
     * @return Server
     */
    public function getServer(){
        return $this->server;
    }

    /**
     * @param string $publicKey
     * @return StellarAccount
     * @throws
     */
    public function getAccount(string $publicKey): StellarAccount{
        try{
            $sAccount = $this->server->getAccount($publicKey);
        }catch (\InvalidArgumentException $e){
            throw ValidationException::withMessages([
                'account_uuid' => 'The given account could not be located on the stellar network. Make sure the account is funded/created.'
            ]);
        }

        return $sAccount;
    }
}
