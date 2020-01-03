<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Post;

class PostToTimelineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_post_a_text_post()
    {
        $this->withoutExceptionHandling();

        //Criamos um usuario on the fly
        $this->actingAs($user = factory(User::class)->create(),'api');

        $response = $this->post('/api/posts',[
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'body' => 'Testing body',

                ]
            ]
        ]);

        $post = Post::first();

        $response->assertStatus(201);

    }
    
}
