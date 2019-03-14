<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Userlogin extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
        use WithFaker,RefreshDatabase;
     public function testLoginPage()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }


    public function testUserLogin(){

        $attributes = [
                'name' => $this->faker->name,
                'username' => $this->faker->sentence,
                'image' => $this->faker->sentence,
                'trello_id' => $this->faker->sentence,
                'trello_url' => $this->faker->sentence,
                'confirmed' => $this->faker->sentence,
                'memberType' => $this->faker->sentence,
                'email' => $this->faker->sentence,
                'token' => $this->faker->sentence,
                'total_board' =>$this->faker->sentence,
                'last_api_hit' => $this->faker->sentence,
        ];
        
    }



}
