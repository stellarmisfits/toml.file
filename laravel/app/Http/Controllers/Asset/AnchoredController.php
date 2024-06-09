<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Http\Resources\Asset as AssetResource;
use App\Models\Account;
use App\Models\Asset;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class AnchoredController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  Asset $asset
     * @return  AssetResource
     * @throws AuthorizationException
     */
    public function update(Request $request, Asset $asset)
    {
        $this->authorize('update', $asset);

        $data = $request->validate([
            'anchored'             =>  ['required', 'boolean']
        ]);

        $asset->anchored = $data['anchored'];
        $asset->save();

        return new AssetResource($asset);
    }
}
