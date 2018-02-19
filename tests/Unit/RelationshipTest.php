<?php

namespace Tests\Unit;

use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $user = factory(User::class)->create();
        $posts = factory(Post::class, 5)->make();

        $user->posts()->saveMany($posts);
        $this->user = $user->fresh();
    }

    /** @test */
    public function hasManyQueryBuilderToInstanceVariable()
    {
        $this->assertEquals(5, Post::count());
        $this->assertEquals($this->user->posts()->get(), $this->user->posts);
    }

    /** @test */
    public function belongsToQueryBuilderToInstanceVariable()
    {
        $this->assertEquals($this->user, Post::first()->user);
    }
}
