<?php

namespace API\V1\Users;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UsersTest extends \TestCase
{
    public function setUp():void
    {
        parent::setUp();

        $this->artisan('migrate:refresh');
    }

    public function test_it_can_create_a_new_user()
    {
        $newUser = [
            'full_name' => 'aliseymi',
            'email' => 'ali@gmail.com',
            'mobile' => '09121234567',
            'password' => '123456'
        ];

        $response = $this->call('post', 'api/v1/users', $newUser);

        $this->assertEquals(201, $response->status());

        $newUser['password'] = json_decode($response->getContent(), true)['data']['password'];

        $this->seeInDatabase('users', $newUser);

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
        $user = $this->createUsers()[0];

        $response = $this->call('PUT', 'api/v1/users', [
            'id' => $user->getId(),
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
        $user = $this->createUsers()[0];

        $response = $this->call('put', 'api/v1/users/change-password', [
            'id' => $user->getId(),
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

    public function test_it_can_throw_exception_if_we_dont_send_parameters_to_update_password()
    {
        $response = $this->call('put', 'api/v1/users/change-password', []);

        $this->assertEquals('422', $response->status());
    }

    public function test_should_delete_user()
    {
        $user = $this->createUsers()[0];

        $response = $this->call('delete', 'api/v1/users', [
            'id' => $user->getId()
        ]);

        $this->assertEquals(200, $response->status());

        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_should_get_users()
    {
        $this->createUsers(30);

        $pagesize = 3;

        $response = $this->call('get', 'api/v1/users', [
            'page' => 1,
            'pagesize' => $pagesize
        ]);

        $data = json_decode($response->getContent(), true);

        $this->assertCount($pagesize, $data['data']);

        $this->assertEquals(200, $response->status());
    }

    public function test_Should_get_filtered_user()
    {
        $pagesize = 3;

        $userEmail = 'ali@gmail.com';

        $response = $this->call('get', 'api/v1/users', [
            'search' => 'ali@gmail.com',
            'page' => 1,
            'pagesize' => $pagesize
        ]);

        $data = json_decode($response->getContent(), true);

        foreach($data['data'] as $user){
            $this->assertEquals($user['email'], $userEmail);
        }

        $this->assertEquals(200, $response->status());
    }

    private function createUsers(int $count = 1): array
    {
        $userRepository = $this->app->make(UserRepositoryInterface::class);

        $userData = [
            'full_name' => 'aliseymi',
            'email' => 'ali@gmail.com',
            'mobile' => '09121234567'
        ];

        $users = [];

        foreach(range(0 ,$count) as $item){
            $users[] = $userRepository->create($userData);
        }

        return $users;
    }
}
