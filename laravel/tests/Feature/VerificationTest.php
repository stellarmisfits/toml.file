<?php

namespace Tests\Feature;

use App\Models\User ;
use Tests\TestCase;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

class VerificationTest extends TestCase
{
    /** @test */
    public function canVerifyEmail()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), ['user' => $user->uuid]);

        Event::fake();

        $this->postJson($url)
            ->assertSuccessful()
            ->assertJsonFragment(['status' => 'Your email has been verified!']);

        Event::assertDispatched(Verified::class, function (Verified $e) use ($user) {
            return $e->user->is($user);
        });
    }

    /** @test */
    public function canNotVerifyIfAlreadyVerified()
    {
        $user = factory(User::class)->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), ['user' => $user->uuid]);

        $this->postJson($url)
            ->assertStatus(400)
            ->assertJsonFragment(['status' => 'The email is already verified.']);
    }

    /** @test */
    public function canNotVerifyIfUrlHasInvalidSignature()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);

        $this->postJson("/api/email/verify/{$user->uuid}")
            ->assertStatus(400)
            ->assertJsonFragment(['status' => 'The verification link is invalid.']);
    }

    /** @test */
    public function resendVerificationNotification()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);

        Notification::fake();

        $this->postJson('/api/email/resend', ['email' => $user->email])
            ->assertSuccessful();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function canNotResendVerificationNotificationIfEmailDoesNotExist()
    {
        $this->postJson('/api/email/resend', ['email' => 'foo@bar.com'])
            ->assertStatus(422)
            ->assertJsonFragment(['errors' => ['email' => ['We can\'t find a user with that e-mail address.']]]);
    }

    /** @test */
    public function canNotResendVerificationNotificationIfEmailAlreadyVerified()
    {
        $user = factory(User::class)->create();

        Notification::fake();

        $this->postJson('/api/email/resend', ['email' => $user->email])
            ->assertStatus(422)
            ->assertJsonFragment(['errors' => ['email' => ['The email is already verified.']]]);

        Notification::assertNotSentTo($user, VerifyEmail::class);
    }
}
