<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Resources\Organization as OrganizationResource;
use App\Models\Organization;
use App\Repositories\OrganizationRepository;
use App\Rules\Alias;
use App\Rules\E164;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumber;

class DocumentationController extends Controller
{
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
            'address'               => ['nullable', 'string', 'max:255'],
            'address_attestation'   => ['nullable', 'url',    'max:255'],
            'phone'                 => ['nullable', 'string', 'max:255', new E164],
            'phone_attestation'     => ['nullable', 'url',    'max:255'],
            'keybase'               => ['nullable', 'string', 'max:255'],
            'twitter'               => ['nullable', 'string', 'max:255'],
            'github'                => ['nullable', 'string', 'max:255'],
            'email'                 => ['nullable', 'string', 'max:255'],
            'licensing_authority'   => ['nullable', 'string', 'max:255'],
            'license_type'          => ['nullable', 'string', 'max:255'],
            'license_number'        => ['nullable', 'string', 'max:255'],
        ]);

        $organization->update($data);

        return new OrganizationResource($organization);
    }
}
