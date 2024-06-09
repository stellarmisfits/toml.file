<?php

namespace App\Http\Controllers\Organization;

use App\Http\Resources\Organization as OrganizationResource;
use App\Models\Organization;
use App\Repositories\OrganizationRepository;
use App\Rules\Alias;
use App\Rules\ValidateUuid;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $request->validate([
            'slug'             => 'nullable|string|max:15|regex:/^[a-z-].*$/|exists:organizations,slug',
            'resource_uuid'    =>  ['nullable', new ValidateUuid],
            'resource_type'    =>  ['nullable', 'in:accounts,assets,principals,validators'],
            'resource_query'   =>  ['nullable', 'in:linked,unlinked'],
        ]);

        $org = auth()
            ->user()
            ->currentTeam()
            ->organizations()
            ->resourceFilter($request->resource_uuid, $request->resource_type, $request->resource_query)
            ->get();

        return OrganizationResource::collection($org);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  OrganizationRepository  $or
     * @return OrganizationResource
     */
    public function store(Request $request, OrganizationRepository $or): OrganizationResource
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'min:3', 'max:23'],
            'alias'        => ['required', 'string', 'min:4', 'max:20', 'unique:organizations', new Alias],
        ]);

        $org = $or->create(auth()->user(), $data);

        return new OrganizationResource($org);
    }

    /**
     * Display the specified resource.
     *
     * @param  Organization  $organization
     * @return OrganizationResource
     * @throws AuthorizationException
     */
    public function show(Organization $organization): OrganizationResource
    {
        $this->authorize('view', $organization);
        return new OrganizationResource($organization);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Organization $organization
     * @param  OrganizationRepository $or
     * @return  OrganizationResource
     * @throws AuthorizationException
     */
    public function update(Request $request, Organization $organization, OrganizationRepository $or): OrganizationResource
    {
        $this->authorize('update', $organization);

        $data = $request->validate([
            'name'          => ['required', 'string', 'min:3', 'max:23'],
            'alias'         => ['required', 'string', 'min:5', 'max:20', new Alias, Rule::unique('organizations', 'alias')->ignore($organization->id)],
            'custom_url'    => ['nullable', 'max:255'],
            'description'   => ['nullable', 'string', 'max:255'],
            'dba'           => ['nullable', 'string', 'max:50'],
        ]);

        $organization = $or->update($organization, $data);

        return new OrganizationResource($organization);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Organization  $organization
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function destroy(Organization $organization)
    {
        $this->authorize('delete', $organization);

        \DB::transaction(function () use ($organization) {
            $organization->accounts->each(function($account){
                $account->organization()->dissociate();
                $account->save();
            });

            $organization->principals()->detach();
            $organization->validators()->detach();
            $organization->delete();
        });

        return response()->json(null, 204);
    }
}
