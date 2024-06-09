<?php

namespace Tests\Feature\Controllers\Organization;

use App\Models\Organization;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class TomlControllerTest extends TestCase
{

    /**
     * GET Collection
     */
    public function testToml()
    {
        $org = $this->seeder->seedOrganization();
        $user = $this->seeder->seedUserWithTeam($org->team);
        $this->actingAs($user);

        $this->get('/toml/'. $org->alias .'/.well-known/stellar.toml')
            ->assertStatus(404);

        $org->published = true;
        $org->save();

        $this->get('/toml/'. $org->alias .'/.well-known/stellar.toml')
            ->assertStatus(200);
    }
}
