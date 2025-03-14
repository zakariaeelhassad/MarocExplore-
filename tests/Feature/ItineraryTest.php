<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItineraryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_itinerary_creation()
    {
        $user = User::factory()->create(); 
        $response = $this->actingAs($user)
                        ->postJson('/api/itineraries/add', [
                            'title' => 'Trip to Morocco',
                            'category' => 'beach',
                            'duration' => 7,
                            'image' => 'image_url',
                            'user_id' => $user->id,
                        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id', 'title', 'category', 'duration', 'image', 'user_id',
        ]);
    }


}
