<?php
// @codingStandardsIgnoreFile

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Team;

class TeamTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_a_user_can_create_a_team()
    {
        $user = factory(User::class)->create();

        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit(route('profile.show'))
                ->assertSee('My Teams')
                ->assertDontSee('NEWTEAM')
                ->clickLink('Add new team')
                ->type('name', 'NEWTEAM')
                ->press('Create')
                ->assertSee('Team details')
                ->assertSee('NEWTEAM')
                ->assertSee($user->username)
                ->visit(route('profile.show'))
                ->assertSee('NEWTEAM');
        });
    }

    public function test_a_user_can_edit_a_team()
    {
        $user = factory(User::class)->create();
        $team = factory(Team::class)->make();
        $user->teams()->save($team);
        $this->browse(function ($browser) use ($user, $team) {
            $browser->loginAs($user)
                ->visit(route('profile.show'))
                ->assertSee($team->name)
                ->clickLink($team->name)
                ->clickLink('Edit')
                ->type('name', 'NEWNAME')
                ->press('Update')
                ->assertSee('Team details')
                ->assertSee('NEWNAME');
        });
    }
/*
    public function test_a_user_can_associate_a_job_with_a_team()
    {
        $user = factory(User::class)->create();
        $team = factory(Team::class)->make();
        $job = $this->createRunningJob($user);
        $user->teams()->save($team);
        $this->actingAs($user)
            ->visit(route('job.show', $job->id))
            ->dontassertSee($team->name)
            ->visit(route('job.edit', $job->id))
            ->select($team->id, 'team_id')
            ->press('Update')
            ->visit(route('job.show', $job->id))
            ->assertSee($team->name);
    }

    public function test_a_user_can_edit_a_job_which_is_allocated_to_one_of_their_teams()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $team = factory(Team::class)->make();
        $user1->teams()->save($team);
        $user2->teams()->save($team);
        $job = $this->createRunningJob($user1, ['team_id' => $team->id]);
        $this->actingAs($user2)
            ->visit(route('job.edit', $job->id))
            ->assertSee('Edit job ' . $job->name)
            ->type('QPQPQPQP', 'name')
            ->press('Update')
            ->assertSee('Your jobs')
            ->assertSee('QPQPQPQP');
    }

    public function test_a_user_can_add_and_remove_members_to_a_team()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();
        $team = factory(Team::class)->create();
        $team->members()->attach([$user1->id, $user2->id]);

        $this->actingAs($user1)
            ->visit(route('team.show', $team->id))
            ->assertSee($user1->username)
            ->assertSee($user2->username)
            ->dontassertSee($user3->username)
            ->visit(route('teammember.edit', $team->id))
            ->check("remove[{$user2->id}]")
            ->select($user3->id, 'add')
            ->press('Update')
            ->assertSee($user3->username)
            ->dontassertSee($user2->username);
    }

    public function test_a_user_cant_alter_a_team_they_are_not_on()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();
        $team = factory(Team::class)->create();
        $team->members()->attach([$user1->id, $user2->id]);

        $this->actingAs($user3)
            ->get(route('team.show', $team->id))
            ->assertSeeStatusCode(403);
        $this->actingAs($user3)
            ->get(route('team.edit', $team->id))
            ->assertSeeStatusCode(403);
        $this->actingAs($user3)
            ->get(route('teammember.edit', $team->id))
            ->assertSeeStatusCode(403);
    }

    public function test_a_user_cant_be_added_to_a_team_twice()
    {
        $user1 = factory(User::class)->create();
        $team = factory(Team::class)->create();

        $team->addMember($user1->id);
        $this->assertEquals(1, $team->members->count());

        $team->addMember($user1->id);
        $team->load('members');     // force laravel to reload the members relation fresh
        $this->assertEquals(1, $team->members->count());
    }

    public function test_an_admin_can_view_all_teams()
    {
        $admin = factory(User::class)->create(['is_admin' => true]);
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();

        $this->actingAs($admin)
            ->visit(route('job.index'))
            ->click('Teams')
            ->assertSee('All teams')
            ->assertSee($team1->name)
            ->assertSee($team2->name);
    }
*/
}
