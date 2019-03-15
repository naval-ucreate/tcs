<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
class UserloginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use WithFaker,RefreshDatabase;
    protected $user;
     public function testLoginPage()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Login With Trello');
    }

    public function get_user(){
        // $insert_data=[];
        // $insert_data['trello_token']=env('TRELLO_TOKEN');
        // $this->user = factory(App\Models\User::class)->create( $insert_data);
        // dd($this->user);
    }


    public function testUserLogin(){
        $this->withoutExceptionHandling();
        $insert_data=[];
        $insert_data['trello_token']=env('TRELLO_TOKEN');
        $response  =    $this->post('ajax_login',$insert_data);
        $this->user= $response ;
        $response->assertStatus(200);
    }

    public function testBoard(){
       $this->testUserLogin();
       $this->withoutExceptionHandling();
       $this->be($this->user);
       $response = $this->get('/boards');
       $response->assertStatus(200);
    }

    



}
