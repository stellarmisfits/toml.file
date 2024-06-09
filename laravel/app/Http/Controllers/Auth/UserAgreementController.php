<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAgreement;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;

class UserAgreementController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return UserResource
     * @throws
     */
    public function store(Request $request): UserResource
    {
        $request->validate([
            'accepted'     =>  'required|accepted'
        ]);

        $user = \DB::transaction(function () {
            $user = auth()->user();
            $user->current_agreement = config('app.current_agreement');
            $user->save();

            $ua = new UserAgreement();
            $ua->user_id = $user->id;
            $ua->sha = config('app.current_agreement');
            $ua->save();

            return $user;
        });

        return new UserResource($user);
    }
}
