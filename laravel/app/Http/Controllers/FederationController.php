<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class FederationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'q'       => ['required', 'string'],
            'type'    => ['required', 'string', 'in:name,id'],
        ]);

        $column = ($data['type'] === 'name') ? 'alias' : 'public_key';
        $account = (new Account())->where([
            $column => $data['q'],
            'verified' => true
        ])->firstOrFail();

        return response()->json([
            'stellar_address' => $account->alias . '*astrify.com',
            'account_id' => $account->public_key,
        ], 200);
    }
}
