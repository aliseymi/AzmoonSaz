<?php

namespace API\V1\Users;

class UsersTest extends \TestCase
{
    public function test_it_can_create_a_new_user()
    {
        $response = $this->call('post', 'api/v1/users', [
            'fullname' => 'aliseymi',
            'email' => 'ali@gmail.com',
            'mobile' => '09121234567',
            'password' => '123456'
        ]);

        $this->assertEquals(201, $response->status());

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'fullname',
                'email',
                'mobile',
                'password'
            ]
        ]);
    }
}
