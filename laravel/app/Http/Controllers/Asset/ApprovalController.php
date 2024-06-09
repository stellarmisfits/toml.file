<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Http\Resources\Asset as AssetResource;
use App\Models\Asset;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class ApprovalController extends Controller
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

        throw_unless($asset->regulated, ValidationException::withMessages([
            'asset_uuid' => 'This asset is not regulated.'
        ]));

        $data = $request->validate([
            'approval_server'     => ['required', 'url', 'max:255'],
            'approval_criteria'   => ['required', 'string', 'max:1000'],
        ]);

        $asset->update($data);

        return new AssetResource($asset);
    }
}
