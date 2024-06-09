<?php

namespace App\Http\Controllers\Account;

use App\Models\Organization;
use App\Repositories\AccountRepository;
use App\Rules\Alias;
use App\Rules\PublicKey;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Ramsey\Uuid\Uuid;
use ZuluCrypto\StellarSdk\Keypair;
use App\Models\Account;
use App\Http\Resources\Account as AccountResource;
use Illuminate\Validation\ValidationException;
use App\Rules\ValidateUuid;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Account::class);

        $request->validate([
            'public_key'                    => ['nullable', 'string', 'size:56', new PublicKey],
            'linked_organization_uuid'      => ['nullable', new ValidateUuid, 'exists:organizations,uuid'],
            'unlinked_organization_uuid'    => ['nullable', new ValidateUuid, 'exists:organizations,uuid'],
        ]);

        $account = auth()
            ->user()
            ->currentTeam()
            ->accounts()
            // ->publicKeyFilter($request->public_key)
            ->linkedOrganizationUuidFilter($request->linked_organization_uuid)
            ->unlinkedOrganizationUuidFilter($request->unlinked_organization_uuid)
            ->get();

        return AccountResource::collection($account);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param  AccountRepository  $ac
     * @return AccountResource
     * @throws AuthorizationException
     */
    public function store(Request $request, AccountRepository $ac): AccountResource
    {
        $this->authorize('create', Account::class);

        $data = $request->validate([
            'name'         => 'required|string|max:50',
            'alias'        => ['required', 'string', 'min:5', 'max:20', 'unique:accounts', new Alias],
            'public_key'   => ['required', 'string', 'size:56', new PublicKey, Rule::unique('accounts', 'public_key')]
        ]);

        $a = $ac->create(auth()->user(), $data);

        return new AccountResource($a);
    }

    /**
     * Display the specified resource.
     *
     * @param  Account  $account
     * @return AccountResource
     * @throws AuthorizationException
     */
    public function show(Account $account): AccountResource
    {
        $this->authorize('view', $account);
        return new AccountResource($account);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Account  $account
     * @param  AccountRepository $ac
     * @return AccountResource
     * @throws AuthorizationException
     */
    public function update(Request $request, Account $account,  AccountRepository $ac): AccountResource
    {
        $this->authorize('update', $account);

        $data = $request->validate([
            'name'         =>  'required|string|max:50',
            'alias'        => ['required', 'string', 'min:5', 'max:20', new Alias, Rule::unique('accounts', 'alias')->ignore($account->id)],
            'public_key'   => ['required', 'string', new PublicKey, Rule::unique('accounts', 'public_key')->ignore($account->id)]
        ]);

        $account = $ac->update($account, $data);

        return new AccountResource($account);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Account  $account
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);

        \DB::transaction(function () use ($account) {
            $account->delete();
        });

        return response()->json(null, 204);
    }
}
