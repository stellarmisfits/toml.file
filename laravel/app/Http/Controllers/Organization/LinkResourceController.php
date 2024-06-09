<?php

namespace App\Http\Controllers\Organization;

use App\Models\Account;
use App\Models\Asset;
use App\Models\Organization;
use App\Models\Principal;
use App\Models\Validator;
use App\Repositories\OrganizationRepository;
use App\Rules\ValidateUuid;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class LinkResourceController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param  Organization $organization
     * @return Response
     * @throws AuthorizationException
     */
    public function store(Request $request, Organization $organization)
    {
        $this->authorize('update', $organization);

        $request->validate([
            'resource_uuid'     =>  ['required', new ValidateUuid],
            'resource_type'     =>  ['required', 'in:account,principal,validator']
        ]);

        $or = new OrganizationRepository();

        switch ($request->resource_type) {
            case 'account':
                $account = (new Account)->whereUuid($request->resource_uuid)->firstOrFail();
                $this->authorize('update', $account);
                $or->addAccount($organization, $account);
                break;
            case 'principal':
                $principal = (new Principal)->whereUuid($request->resource_uuid)->firstOrFail();
                $this->authorize('update', $principal);
                $or->addPrincipal($organization, $principal);
                break;
            case 'validator':
                $validator = (new Validator())->whereUuid($request->resource_uuid)->firstOrFail();
                $this->authorize('update', $validator);
                $or->addValidator($organization, $validator);
                break;
        }

        return response()->json(null, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  Organization $organization
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Organization $organization)
    {
        $this->authorize('update', $organization);

        $request->validate([
            'resource_uuid'     =>  ['required', new ValidateUuid],
            'resource_type'     =>  ['required', 'in:account,principal,validator']
        ]);

        $or = new OrganizationRepository();

        switch ($request->resource_type) {
            case 'account':
                $account = (new Account)->whereUuid($request->resource_uuid)->firstOrFail();
                $this->authorize('update', $account);
                $or->removeAccount($organization, $account);
                break;
            case 'principal':
                $principal = (new Principal)->whereUuid($request->resource_uuid)->firstOrFail();
                $this->authorize('update', $principal);
                $organization->principals()->detach($principal->id);
                break;
            case 'validator':
                $validator = (new Validator())->whereUuid($request->resource_uuid)->firstOrFail();
                $this->authorize('update', $validator);
                $organization->validators()->detach($validator->id);
                break;
        }

        return response()->json(null, 204);
    }
}
