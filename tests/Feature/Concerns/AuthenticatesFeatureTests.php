<?php

namespace Tests\Feature\Concerns;

use App\Models\User;

trait AuthenticatesFeatureTests
{
    protected User $authenticatedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticatedUser = User::factory()->create();
        $this->actingAs($this->authenticatedUser);
    }
}
