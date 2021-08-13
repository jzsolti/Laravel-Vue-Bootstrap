<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\User, App\Models\Article;

class UserArticlesTest extends TestCase
{
    public function test_user__articles_index()
    {
        $users = User::has('articles')->inRandomOrder()->limit(10)->get();
        foreach ($users as $user) {
            $response = $this->actingAs($user)->getJson('/api/user/articles?page=1&column=created_at&order=desc&per_page=5');
            $response->assertStatus(200);
        }
    }

    public function test_user__article()
    {
        $user = User::has('articles')->inRandomOrder()->first();
        $response = $this->actingAs($user)->getJson('/api/user/articles/' . $user->articles->first()->id);
        $response->assertStatus(200);
    }

    public function test_user_article_delete()
    {
        $user = User::has('articles')->inRandomOrder()->first();
        $response = $this->actingAs($user)->deleteJson('/api/user/articles/' . $user->articles->first()->id);
        $response->assertStatus(200)->assertJson(['deleted' => true]);
    }

    public function test_user_cannot_delete_other_users_article()
    {
        $user = User::has('articles')->inRandomOrder()->first();
        $article = Article::where('user_id', '<>', $user->id)->first();
        $response = $this->actingAs($user)->deleteJson('/api/user/articles/' . $article->id);
        $response->assertStatus(404);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_user_create_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->make(['user_id' => $user->id]);

        $response = $this->actingAs($user)->postJson('/api/user/articles/create', $article->toArray());

        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) => $json->whereType('id', 'integer'));
    }

    public function test_user_update_article()
    {
        $user = User::has('articles')->inRandomOrder()->first();
        $article = $user->articles->first();
        $article->title = $article->title . ' updated';

        $response = $this->actingAs($user)->putJson('/api/user/articles/' . $article->id, $article->toArray());

        $response->assertStatus(200)->assertJson(['updated' => true]);
    }

    public function test_user_cannot_update_other_users_article()
    {
        $user = User::has('articles')->first();
        $article = Article::where('user_id', '<>', $user->id)->first();

        $response = $this->actingAs($user)->putJson('/api/user/articles/' . $article->id, Article::factory()->make()->toArray());

        $response->assertStatus(404);
    }
}
