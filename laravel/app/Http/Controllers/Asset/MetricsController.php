<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Http\Resources\Asset as AssetResource;
use App\Models\Asset;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class MetricsController extends Controller
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

        $data = $request->validate([
            'display_decimals'    => ['required', 'integer', 'min:0', 'max:7'],
            'is_unlimited'        => ['required', 'boolean'],
            'fixed_number'        => ['nullable', 'integer'],
            'max_number'          => ['nullable', 'integer'],
        ]);

        throw_if($data['fixed_number'] && $data['max_number'], ValidationException::withMessages([
            'fixed_number' => 'An asset cannot have both a fixed number and a max number.'
        ]));

        throw_if($data['is_unlimited'] && $data['max_number'], ValidationException::withMessages([
            'is_unlimited' => 'An asset cannot be unlimited and have a max number.'
        ]));

        throw_if($data['is_unlimited'] && $data['fixed_number'], ValidationException::withMessages([
            'is_unlimited' => 'An asset cannot be unlimited and have a fixed number.'
        ]));

        dd($data);

        $asset->update($data);

        return new AssetResource($asset);
    }
}
