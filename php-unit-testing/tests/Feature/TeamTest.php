<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_team_has_a_name()
    {
        $team = new Team(['name' => 'Acme']);

        $this->assertEquals('Acme', $team->name);
    }

    /** @test */
    public function a_team_can_add_members()
    {
        $team = Team::factory()->create();

        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        $team->add($user);
        $team->add($userTwo);

        $this->assertEquals(2, $team->count());
    }

    /** @test */
    public function a_team_can_add_multiple_members_at_once()
    {
        $team = Team::factory()->create();

        $users = User::factory(2)->create();

        $team->add($users);

        $this->assertEquals(2, $team->count());
    }

    /** @test */
    public function a_team_has_maximum_size()
    {
        $team = Team::factory()->create(['size' => 2]);

        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        $team->add($user);
        $team->add($userTwo);

        $this->assertEquals(2, $team->count());

        $this->expectException('Exception');

        $userThree = User::factory()->create();
        $team->add($userThree);
    }

    /** @test */
    public function when_adding_many_members_at_once_may_not_exceed_team_maximum_size()
    {
        $team = Team::factory()->create(['size' => 2]);

        $users = User::factory(3)->create();

        $this->expectException('Exception');
        $team->add($users);
    }

    /** @test */
    public function a_team_can_remove_a_member()
    {
        $team = Team::factory()->create();

        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        $team->add($user);
        $team->add($userTwo);

        $team->remove($user->id);
        $this->assertEquals(1, $team->count());

        $team->remove($userTwo->id);
        $this->assertEquals(0, $team->count());
    }

    /** @test */
    public function a_team_can_remove_more_than_a_member_at_once()
    {
        $team = Team::factory()->create();

        $user = User::factory()->create();
        $userTwo = User::factory()->create();
        $userThree = User::factory()->create();

        $team->add($user);
        $team->add($userTwo);
        $team->add($userThree);


        $team->remove([$user->id, $userTwo->id]);
        $this->assertEquals(1, $team->count());
    }

    /** @test */
    public function a_team_can_remove_all_member_at_once()
    {
        $team = Team::factory()->create();

        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        $team->add($user);
        $team->add($userTwo);

        $team->removeAll();
        $this->assertEquals(0, $team->count());
    }
}
