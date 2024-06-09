<?php

namespace App\Http\Controllers\Principal;

use App\Http\Resources\Principal as PrincipalResource;
use App\Models\Account;
use App\Models\Principal;
use App\Repositories\PrincipalRepository;
use App\Rules\Sha256;
use App\Rules\ValidateUuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PrincipalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $request->validate([
            'linked_organization_uuid'      => ['nullable', new ValidateUuid, 'exists:organizations,uuid'],
            'unlinked_organization_uuid'    => ['nullable', new ValidateUuid, 'exists:organizations,uuid'],
        ]);

        $assets = auth()
            ->user()
            ->currentTeam()
            ->principals()
            ->linkedOrganizationUuidFilter($request->linked_organization_uuid)
            ->unlinkedOrganizationUuidFilter($request->unlinked_organization_uuid)
            ->get();

        return PrincipalResource::collection($assets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return PrincipalResource
     * @throws
     */
    public function store(Request $request): PrincipalResource
    {
        $data = $request->validate([
            'name'          =>  'required|string|max:50',
            'email'         =>  'required|email',
            'keybase'       =>  'nullable|alpha_dash|max:32',
            'telegram'      =>  'nullable|alpha_dash|min:5|max:32',
            'twitter'       =>  'nullable|alpha_dash|max:32',
            'github'        =>  'nullable|alpha_dash|max:32',
            'id_photo_hash' =>  ['nullable', new Sha256],
            'verification_photo_hash' =>  ['nullable', new Sha256],
        ]);

        $p = new Principal;
        $p->team_id = auth()->user()->currentTeam()->id;
        $p->fill($data);
        $p->save();

        return new PrincipalResource($p);
    }

    /**
     * Display the specified resource.
     *
     * @param  Principal $principal
     * @return PrincipalResource
     */
    public function show(Principal $principal): PrincipalResource
    {
        return new PrincipalResource($principal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Principal $principal
     * @return Response
     */
    public function update(Request $request, Principal $principal): PrincipalResource
    {
        $data = $request->validate([
            'name'          =>  'required|string|max:50',
            'email'         =>  'required|email',
            'keybase'       =>  'nullable|alpha_dash|max:32',
            'telegram'      =>  'nullable|alpha_dash|min:5|max:32',
            'twitter'       =>  'nullable|alpha_dash|max:32',
            'github'        =>  'nullable|alpha_dash|max:32',
            'id_photo_hash' =>  ['nullable', new Sha256],
            'verification_photo_hash' =>  ['nullable', new Sha256],
        ]);

        $principal->update($data);

        return new PrincipalResource($principal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Principal $principal
     * @return Response
     * @throws
     */
    public function destroy(Principal $principal)
    {
        \DB::transaction(function () use ($principal) {
            $principal->organizations()->detach();
            $principal->delete();
        });

        return response()->json(null, 204);
    }
}
