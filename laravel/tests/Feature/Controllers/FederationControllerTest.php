<?php

namespace Tests\Feature\Controllers\Organization;

use Tests\TestCase;

class FederationControllerTest extends TestCase
{

    /**
     * GET Collection
     */
    public function testFederationControllerIndexId()
    {
        $account = $this->seeder->seedAccount();
        $account->verified = true;
        $account->save();

        $this->get(route('federation', [
            'q' => $account->public_key,
            'type' => 'id'
        ]))->assertStatus(200);
    }

    /**
     * GET Collection
     */
    public function testFederationControllerIndexName()
    {
        $account = $this->seeder->seedAccount();
        $account->verified = true;
        $account->save();

        $this->get(route('federation', [
            'q' => $account->alias,
            'type' => 'name'
        ]))->assertStatus(200);
    }
}
