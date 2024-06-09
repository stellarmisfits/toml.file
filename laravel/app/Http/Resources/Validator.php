<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Validator extends JsonResource
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
            'uuid'                  => $this->uuid,
            'name'                  => $this->name,
            'account_uuid'          => $this->account->uuid,
            'account_public_key'    => $this->account->public_key,
            'alias'                 => $this->alias,
            'host'                  => $this->host,
            'history'               => $this->history,
            'created_at'            => $this->created_at->toAtomString(),
            'updated_at'            => $this->updated_at->toAtomString(),
        ];
    }
}
