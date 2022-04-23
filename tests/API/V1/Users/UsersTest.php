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
}
