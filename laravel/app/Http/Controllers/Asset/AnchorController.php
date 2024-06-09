<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Http\Resources\Asset as AssetResource;
use App\Models\Asset;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class AnchorController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  Asset $asset
     * @return  AssetResource
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function update(Request $request, Asset $asset)
    {
        $this->authorize('update', $asset);

        throw_unless($asset->anchored, ValidationException::withMessages([
            'asset_uuid' => 'This asset is not anchored.'
        ]));

        $data = $request->validate([
            'anchor_asset_type'                     => ['required', 'in:fiat,stock,bond,commodity,realestate,other'],
            'anchor_asset'                          => ['required', 'string', 'max:255'],
            'redemption_instructions'               => ['required', 'string', 'max:1000'],
            'collateral_addresses'                  => ['required', 'string', 'max:1000'],
            'collateral_address_messages'           => ['required', 'string', 'max:1000'],
            'collateral_address_signatures'         => ['required', 'string', 'max:1000'],
        ]);

        $asset->update($data);

        return new AssetResource($asset);
    }
}
