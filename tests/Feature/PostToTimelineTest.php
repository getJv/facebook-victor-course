<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostToTimelineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function a_user_can_post_a_text_post()
    {
        //$this->withoutExceptionHandling();

        //Criamos um usuario on the fly
        $this->actingAs($user = factory(User::class)->create(), 'api');

        $response = $this->post('/api/posts', [
            'body' => 'Testing body',

        ]);

        $post = Post::first();

        $this->assertCount(1, Post::all());
        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals('Testing body', $post->body);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'type' => 'posts',
                'post_id' => $post->id,
                'attributes' => [
                    'posted_by' => [
                        'data' => [
                            'attributes' => [
                                'name' => $user->name,
                            ],
                        ],
                    ],
                    'body' => 'Testing body',
                ],
            ],
            'links' => [
                'self' => url('/posts/' . $post->id),
            ],
        ]);
    }

    /** @test */
    public function a_user_can_post_a_image_post()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');

        $file = UploadedFile::fake()->image('user-post.png');

        $response = $this->post('/api/posts', [
            'body' => 'Testing body',
            'image' => $file,
            'width' => 100,
            'height' => 100

        ]);

        Storage::disk('public')->assertExists('post-images/' . $file->hashName());

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'attributes' => [

                    'body' => 'Testing body',
                    'image' => url('/post-images/' . $file->hashName()),
                ],
            ],
        ]);
    }
}
