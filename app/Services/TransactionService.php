<?php


namespace App\Services;


use App\Repositories\Contracts\Eloquent\EloquentRepositoryInterface;
use App\Repositories\TransactionRepository;
use App\Repositories\Eloquent\EloquentRepository;
use App\Services\Contracts\TransactionServiceInterface;

class TransactionService extends CrudService implements TransactionServiceInterface
{
    protected function getMainRepository(): EloquentRepositoryInterface
    {
        return app(TransactionRepository::class);
    }

    public function update(array $data, $id, $attribute = "id")
    {
        $this->getMainRepository()->update($data, $id, $attribute);
    }
}