<?php
namespace App\Services;

use App\Models\Organization;
use Yosymfony\Toml\TomlBuilder;

class TomlService
{

    /**
     * @var Organization
     */
    protected $org;
    /**
     * @var TomlBuilder
     */
    protected $tb;

    /**
     * TomlService constructor.
     * @param Organization $org
     */
    public function __construct(Organization $org)
    {
        $this->org = $org;
        $this->tb = new TomlBuilder();
    }

    /**
     * @return TomlBuilder
     */
    public function generateToml(): TomlBuilder
    {
        $passphrase = (config('stellar.horizon.type') === 'testnet') ? 'Test SDF Network ; September 2015' : 'Public Global Stellar Network ; September 2015';
        $this->tb->addValue('NETWORK_PASSPHRASE', $passphrase);

        $this->addGlobals();
        $this->addAccounts();
        $this->tb->addValue('VERSION', '2.0.0');

        $this->addDocumentation();
        $this->addPrincipals();
        $this->addAssets();
        $this->addValidators();

        return $this->tb;
    }

    /**
     *
     */
    protected function addGlobals()
    {
        $this->add($this->org, 'federation_server', 'FEDERATION_SERVER');
        $this->add($this->org, 'auth_server', 'AUTH_SERVER');
        $this->add($this->org, 'transfer_server', 'TRANSFER_SERVER');
        $this->add($this->org, 'kyc_server', 'KYC_SERVER');
        $this->add($this->org, 'web_auth_endpoint', 'WEB_AUTH_ENDPOINT');
        $this->add($this->org, 'signing_key', 'SIGNING_KEY');
        $this->add($this->org, 'horizon_url', 'HORIZON_URL');
        $this->add($this->org, 'uri_request_signing_key', 'HORIZON_URL');
    }

    /**
     *
     */
    protected function addDocumentation()
    {
        $this->tb->addTable('DOCUMENTATION');
        $this->add($this->org, 'name', 'ORG_NAME');
        $this->add($this->org, 'dba', 'ORG_DBA');
        $this->add($this->org, 'url', 'ORG_URL');
        $this->add($this->org, 'logo', 'ORG_LOGO');
        $this->add($this->org, 'description', 'ORG_DESCRIPTION');
        $this->add($this->org, 'address', 'ORG_PHYSICAL_ADDRESS');
        $this->add($this->org, 'address_attestation', 'ORG_PHYSICAL_ADDRESS_ATTESTATION');
        $this->add($this->org, 'phone', 'ORG_PHONE_NUMBER');
        $this->add($this->org, 'phone_attestation', 'ORG_PHONE_NUMBER_ATTESTATION');
        $this->add($this->org, 'keybase', 'ORG_KEYBASE');
        $this->add($this->org, 'twitter', 'ORG_TWITTER');
        $this->add($this->org, 'github', 'ORG_GITHUB');
        $this->add($this->org, 'email', 'ORG_OFFICIAL_EMAIL');
        $this->add($this->org, 'licensing_authority', 'ORG_LICENSING_AUTHORITY');
        $this->add($this->org, 'license_type', 'ORG_LICENSE_TYPE');
        $this->add($this->org, 'license_number', 'ORG_LICENSE_NUMBER');
    }

    /**
     *
     */
    protected function addAccounts()
    {
        $keys = $this->org->accounts->pluck('public_key');
        $this->tb->addValue('ACCOUNTS', $keys->toArray());
    }

    /**
     *
     */
    protected function addPrincipals()
    {
        $this->org->principals->each(function ($principal) {
            $this->tb->addArrayOfTable('PRINCIPALS');
            $this->add($principal, 'name', 'name');
            $this->add($principal, 'email', 'email');
            $this->add($principal, 'keybase', 'keybase');
            $this->add($principal, 'twitter', 'twitter');
            $this->add($principal, 'github', 'github');
            $this->add($principal, 'id_photo_hash', 'id_photo_hash');
            $this->add($principal, 'verification_photo_hash', 'verification_photo_hash');
        });
    }

    /**
     *
     */
    protected function addValidators()
    {
        $this->org->validators->each(function ($validator) {
            $this->tb->addArrayOfTable('VALIDATORS');
            $this->add($validator, 'slug', 'ALIAS');
            $this->add($validator, 'name', 'DISPLAY_NAME');
            $this->add($validator, 'host', 'HOST');
            $this->add($validator->account, 'public_key', 'PUBLIC_KEY');
            $this->add($validator, 'history', 'HISTORY');
        });
    }

    /**
     *
     */
    protected function addAssets()
    {
        $this->org->assets->each(function ($asset) {
            $this->tb->addArrayOfTable('CURRENCIES');
            $this->add($asset, 'code', 'code');
            $this->add($asset, 'code_template', 'code_template');
            $this->add($asset->account, 'public_key', 'issuer');
            $this->add($asset, 'status', 'status');
            $this->add($asset, 'display_decimals', 'display_decimals');
            $this->add($asset, 'name', 'name');
            $this->add($asset, 'description', 'desc');
            $this->add($asset, 'conditions', 'conditions');
            $this->add($asset, 'logo', 'image');
            $this->add($asset, 'fixed_number', 'fixed_number');
            $this->add($asset, 'is_unlimited', 'is_unlimited');

            $this->add($asset, 'anchored', 'is_asset_anchored');
            if ($asset->anchored) {
                $this->add($asset, 'anchor_asset_type', 'anchor_asset_type');
                $this->add($asset, 'anchor_asset', 'anchor_asset');
                $this->add($asset, 'redemption_instructions', 'redemption_instructions');
                $this->add($asset, 'collateral_addresses', 'collateral_addresses');
                $this->add($asset, 'collateral_address_messages', 'collateral_address_messages');
                $this->add($asset, 'collateral_address_signatures', 'collateral_address_signatures');
            }

            $this->add($asset, 'regulated', 'regulated');
            if ($asset->regulated) {
                $this->add($asset, 'approval_server', 'approval_server');
                $this->add($asset, 'approval_criteria', 'approval_criteria');
            }
        });
    }

    /**
     * @param $model
     * @param $model_key
     * @param $toml_key
     */
    protected function add($model, $modelKey, $tomlKey)
    {
        if ($model && $model->$modelKey) {
            $this->tb->addValue($tomlKey, $model->$modelKey);
        }
    }
}
