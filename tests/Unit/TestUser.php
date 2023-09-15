<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Tests\TestCase;

class TestUser extends TestCase {

    use RefreshDatabase;

    public function testUserAuth()
    {
        $response = $this->post('/auth', ['email' => 'Kali90@gmail.com','password'=>'Kali90@gmail.com',"remember"=>false]);

        error_log($response->getContent());

        $this->assertEquals(true,$response->getStatusCode());
    }
}


