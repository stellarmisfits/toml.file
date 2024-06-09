<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Principal extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request = null)
    {
        return [
            'uuid'                      => $this->uuid,
            'name'                      => $this->name,
            'email'                     => $this->email,
            'photo_url'                 => $this->photo_url,
            'keybase'                   => $this->keybase,
            'telegram'                  => $this->telegram,
            'twitter'                   => $this->twitter,
            'github'                    => $this->github,
            'id_photo_hash'             => $this->id_photo_hash,
            'verification_photo_hash'   => $this->verification_photo_hash,
            'created_at'                => $this->created_at->toAtomString(),
            'updated_at'                => $this->updated_at->toAtomString(),
        ];
    }
}
