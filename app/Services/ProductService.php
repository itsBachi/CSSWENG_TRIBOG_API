<?php


namespace App\Services;


use App\Repositories\Contracts\Eloquent\EloquentRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Services\Contracts\ProductServiceInterface;

class ProductService extends CrudService implements ProductServiceInterface
{
    protected function getMainRepository(): EloquentRepositoryInterface
    {
        return app(ProductRepository::class);
    }

    public function update(array $data, $id, $attribute = "id")
    {
        $this->getMainRepository()->update($data, $id, $attribute);
    }
}
