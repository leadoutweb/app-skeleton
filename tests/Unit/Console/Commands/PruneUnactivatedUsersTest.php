<?php

namespace Tests\Unit\Console\Commands;

use App\Authentication\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class PruneUnactivatedUsersTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2023-01-20 01:00:00');
    }

    /** @test */
    public function it_prunes_unactivated_users()
    {
        $user = User::factory()->create(['activated_at' => null, 'created_at' => '2023-01-12 23:59:59']);

        $this->artisan('app:prune-unactivated-users');

        $this->assertModelMissing($user);
    }

    /** @test */
    public function it_does_not_prune_users_that_are_younger_than_a_week()
    {
        $user = User::factory()->create(['activated_at' => null, 'created_at' => '2023-01-13 00:00:00']);

        $this->artisan('app:prune-unactivated-users');

        $this->assertModelExists($user);
    }

    /** @test */
    public function it_does_not_prune_users_that_are_activated()
    {
        $user = User::factory()->create(['activated_at' => Carbon::now(), 'created_at' => '2023-01-12 23:59:59']);

        $this->artisan('app:prune-unactivated-users');

        $this->assertModelExists($user);
    }
}
