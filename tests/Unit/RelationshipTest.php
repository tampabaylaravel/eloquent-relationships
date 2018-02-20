<?php

namespace Tests\Unit;

use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
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
    public function relationshipWithParensIsDifferentThanWithout()
    {
        $this->assertNotEquals($this->user->posts(), $this->user->posts);
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

    /** @test */
    public function conditionalRelationship()
    {
        Post::first()->update([
            'created_at' => now()->subDays(8),
        ]);

        $this->assertCount(4, $this->user->recentPosts);
    }

    /** @test */
    public function createRelatedRecords()
    {
        $user = factory(User::class)->create();

        $postA = factory(Post::class)->create([
            'user_id' => $user->id,
        ]);

        $postB = $user->posts()->save(factory(Post::class)->make());

        $postC = $user->posts()->create([]);

        $postD = factory(Post::class)->create()->user()->associate($user)->save();

        $this->assertCount(4, $user->posts);
    }

    /**
     * @test
     *
     * @expectedException BadMethodCallException
     */
    public Function whyDoesThisHappen()
    {
        $this->user->posts->create([]);
    }

    /** @test */
    public function lazyLoading()
    {
        $queryCount = 0;

        DB::listen(function ($query) use (&$queryCount) {
            $queryCount++;
        });

        $posts = Post::get();

        foreach ($posts as $post) {
            $post->user;
        }

        $this->assertEquals(6, $queryCount);
    }

    /** @test */
    public function eagerLoading()
    {
        $queryCount = 0;

        DB::listen(function ($query) use (&$queryCount) {
            $queryCount++;
        });

        $posts = Post::with('user')->get();

        foreach ($posts as $post) {
            $post->user;
        }

        $this->assertEquals(2, $queryCount);
    }

    /** @test */
    public function lazyEagerLoading()
    {
        $queryCount = 0;

        DB::listen(function ($query) use (&$queryCount) {
            $queryCount++;
        });

        $posts = Post::get();
        $posts->load('user');

        foreach ($posts as $post) {
            $post->user;
        }

        $this->assertEquals(2, $queryCount);
    }

    /** @test */
    public function howManyQueries()
    {
        $queryCount = 0;

        DB::listen(function ($query) use (&$queryCount) {
            $queryCount++;
        });

        $posts = Post::get();
        $posts->load('user');

        foreach ($posts as $post) {
            $post->user()->first();
        }

        $this->assertNotEquals(2, $queryCount);
    }
}
