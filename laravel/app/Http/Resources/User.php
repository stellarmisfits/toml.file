<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'uuid'                 => $this->uuid,
            'name'                 => $this->name,
            'email'                => $this->email,
            'email_verified_at'    => $this->email_verified_at,
            'photo_url'            => $this->photo_url,
            'current_team_id'      => $this->current_team_id,
            'agreed_to_terms'      => $this->agreed_to_terms,
            'updated_at'           => $this->current_team_id,
        ];
    }
}
