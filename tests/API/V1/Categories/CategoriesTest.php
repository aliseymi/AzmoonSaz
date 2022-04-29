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

    private function createCategories(int $count = 1): array
    {
        $categoryRepository = $this->app->make(CategoryRepositoryInterface::class);

        $categories = [];

        foreach(range(0, $count) as $item){
            $categories[] = $categoryRepository->create([
                'name' => 'new category',
                'slug' => 'new-category'
            ]);
        }

        return $categories;
    }
}