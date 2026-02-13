<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_can_be_rendered()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }

    public function test_contact_page_can_be_rendered()
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(200);
    }
}
