<?php


namespace App\Services;


use App\Repositories\Contracts\Eloquent\EloquentRepositoryInterface;
use App\Repositories\DeliveryRepository;
use App\Repositories\Eloquent\EloquentRepository;
use App\Services\Contracts\DeliveryServiceInterface;

class DeliveryService extends CrudService implements DeliveryServiceInterface
{
    protected function getMainRepository(): EloquentRepositoryInterface
    {
        return app(DeliveryRepository::class);
    }

    public function update(array $data, $id, $attribute = "id")
    {
        $this->getMainRepository()->update($data, $id, $attribute);
    }
}