<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_send_event(): void
    {
        $response = $this->post('/event');

        $response->assertStatus(200);
    }
}
