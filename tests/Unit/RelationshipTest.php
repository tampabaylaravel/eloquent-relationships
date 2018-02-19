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

        $this->user = factory(User::class)->create();
        $posts = factory(Post::class, 5)->make();

        $this->user->posts()->saveMany($posts);
    }

    /** @test */
    public function hasManyQueryBuilderToInstanceVariable()
    {
        $this->assertEquals(5, Post::count());
        $this->assertEquals($this->user->posts()->get(), $this->user->posts);
    }
}
