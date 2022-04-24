<?php

namespace API\V1\Users;

class UsersTest extends \TestCase
{
    public function test_it_can_create_a_new_user()
    {
        $response = $this->call('post', 'api/v1/users', [
            'full_name' => 'aliseymi',
            'email' => 'ali@gmail.com',
            'mobile' => '09121234567',
            'password' => '123456'
        ]);

        $this->assertEquals(201, $response->status());

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'full_name',
                'email',
                'mobile',
                'password'
            ]
        ]);
    }

    public function test_it_can_throw_exception_if_we_dont_send_parameters()
    {
        $response = $this->call('post', 'api/v1/users', []);

        $this->assertEquals('422', $response->status());
    }

    public function test_should_update_the_information_of_user()
    {
        $response = $this->call('PUT', 'api/v1/users', [
            'id' => 938,
            'full_name' => 'ali',
            'email' => 'seymi@gmail.com',
            'mobile' => '09121234568'
        ]);

        $this->assertEquals(200, $response->status());

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'full_name',
                'email',
                'mobile'
            ]
        ]);
    }

    public function test_it_can_throw_exception_if_we_dont_send_parameters_to_update_info()
    {
        $response = $this->call('put', 'api/v1/users', []);

        $this->assertEquals('422', $response->status());
    }

    public function test_should_update_password()
    {
        $response = $this->call('put', 'api/v1/users/change-password', [
            'id' => 938,
            'password' => '1234567890',
            'password_repeat' => '1234567890'
        ]);

        $this->assertEquals(200, $response->status());

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'full_name',
                'email',
                'mobile'
            ]
        ]);
    }
}
