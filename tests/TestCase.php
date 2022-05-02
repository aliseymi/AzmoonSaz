<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use App\Repositories\Contracts\CategoryRepositoryInterface;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    
    protected function createCategories(int $count = 1): array
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
