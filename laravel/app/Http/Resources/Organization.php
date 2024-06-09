<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Organization extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid'                          => $this->uuid,
            'published'                     => $this->published,
            'alias'                         => $this->alias,
            'custom_url'                    => $this->custom_url,
            'hosted_url'                    => $this->hosted_url,

            // begin sep-0001 global properties
            'federation_server'             => $this->federation_server,
            'auth_server'                   => $this->auth_server,
            'transfer_server'               => $this->transfer_server,
            'kyc_server'                    => $this->kyc_server,
            'web_auth_endpoint'             => $this->web_auth_endpoint,
            'signing_key_uuid'              => optional($this->signing_key)->uuid,
            'horizon_url'                   => $this->horizon_url,
            'uri_request_signing_key_uuid'  => optional($this->uri_request_signing_key)->uuid,

            // begin sep-0001 documentation properties
            'name'                          => $this->name,
            'dba'                           => $this->dba,
            'url'                           => $this->url,
            'logo'                          => $this->logo,
            'description'                   => $this->description,
            'address'                       => $this->address,
            'address_attestation'           => $this->address_attestation,
            'phone'                         => $this->phone,
            'phone_attestation'             => $this->phone_attestation,
            'keybase'                       => $this->keybase,
            'twitter'                       => $this->twitter,
            'github'                        => $this->github,
            'email'                         => $this->email,
            'licensing_authority'           => $this->licensing_authority,
            'license_type'                  => $this->license_type,
            'license_number'                => $this->license_number,
            // end sep-0001 properties

            'created_at'    => $this->created_at->toAtomString(),
            'updated_at'    => $this->updated_at->toAtomString()
        ];
    }
}
