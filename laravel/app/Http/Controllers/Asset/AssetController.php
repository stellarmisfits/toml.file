<?php

namespace App\Http\Controllers\Asset;

use App\Models\Account;
use App\Models\Asset;
use App\Repositories\AssetRepository;
use App\Rules\ValidateUuid;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Asset as AssetResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Asset::class);

        $request->validate([
            'account_uuid'                  => ['nullable', new ValidateUuid, 'exists:accounts,uuid'],
            'linked_organization_uuid'      => ['nullable', new ValidateUuid, 'exists:organizations,uuid'],
            'unlinked_organization_uuid'    => ['nullable', new ValidateUuid, 'exists:organizations,uuid'],
        ]);

        $assets = auth()
            ->user()
            ->currentTeam()
            ->assets()
            ->accountUuidFilter($request->account_uuid)
            ->linkedOrganizationUuidFilter($request->linked_organization_uuid)
            ->unlinkedOrganizationUuidFilter($request->unlinked_organization_uuid)
            ->get();

        return AssetResource::collection($assets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @param  AssetRepository  $ar
     * @return AssetResource
     * @throws AuthorizationException
     */
    public function store(Request $request, AssetRepository $ar)
    {
        $this->authorize('create', Asset::class);

        $data = $request->validate([
            'name'                  =>  ['required', 'string', 'max:20'],
            'code'                  =>  ['required', 'string', 'max:12'],
            'description'           =>  ['required', 'string', 'max:255'],
            'account_uuid'          =>  ['required', new ValidateUuid, 'exists:accounts,uuid'],
        ]);

        $asset = $ar->create(auth()->user(), $data);

        return new AssetResource($asset);
    }

    /**
     * Display the specified resource.
     *
     * @param  Asset  $asset
     * @return AssetResource
     * @throws AuthorizationException
     */
    public function show(Asset $asset)
    {
        $this->authorize('view', $asset);
        return new AssetResource($asset);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  Asset $asset
     * @param AssetRepository $ar
     * @return  AssetResource
     * @throws AuthorizationException
     */
    public function update(Request $request, Asset $asset, AssetRepository $ar)
    {
        $this->authorize('update', $asset);

        $data = $request->validate([
            'name'                  =>  ['required', 'string', 'max:20'],
            'code'                  =>  ['required', 'string', 'max:12'],
            'description'           =>  ['required', 'string', 'max:255'],
            'account_uuid'          =>  ['required', new ValidateUuid, 'exists:accounts,uuid'],
        ]);

        $asset = $ar->update($asset, $data);

        return new AssetResource($asset);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Asset  $asset
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function destroy(Asset $asset)
    {
        $this->authorize('delete', $asset);

        \DB::transaction(function () use ($asset) {
            $asset->delete();
        });

        return response()->json(null, 204);
    }
}
