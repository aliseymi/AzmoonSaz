<?php

namespace API\V1\Users;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use Prophecy\Call\Call;
use TestCase;

class CategoriesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:refresh');
    }

    public function test_ensure_we_can_create_a_new_category()
    {
        $newCategory = [
            'name' => 'category 1',
            'slug' => 'category-1'
        ];

        $response = $this->call('POST', 'api/v1/categories', $newCategory);

        $this->assertEquals(201, $response->status());

        $this->seeInDatabase('categories', $newCategory);

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'name',
                'slug'
            ]
        ]);
    }

    public function test_we_can_delete_a_category()
    {
        $category = $this->createCategories()[0];

        $response = $this->call('delete', 'api/v1/categories', [
            'id' => $category->getId()
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_ensure_we_can_update_a_category()
    {
        $category = $this->createCategories()[0];

        $categoryData = [
            'id' => $category->getId(),
            'name' => $category->getName() . 'updated',
            'slug' => $category->getSlug() . '-updated',
        ];

        $response = $this->call('put', 'api/v1/categories', $categoryData);

        $this->assertEquals(200, $response->getStatusCode());

        $this->seeInDatabase('categories', $categoryData);

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'name',
                'slug'
            ]
        ]);
    }

    public function test_should_get_categories()
    {
        $this->createCategories(30);

        $pagesize = 3;

        $response = $this->call('get', 'api/v1/categories', [
            'page' => 1,
            'pagesize' => $pagesize
        ]);

        $data = json_decode($response->getContent(), true);

        $this->assertCount($pagesize, $data['data']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_should_get_filtered_categories()
    {
        $this->createCategories(30);

        $pagesize = 3;

        $categorySlug = 'new-category';

        $response = $this->call('get', 'api/v1/categories', [
            'page' => 1,
            'pagesize' => $pagesize
        ]);

        $data = json_decode($response->getContent(), true);

        foreach($data['data'] as $category){
            $this->assertEquals($categorySlug, $category['slug']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }
}