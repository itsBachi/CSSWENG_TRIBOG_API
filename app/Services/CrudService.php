<?php

namespace App\Services;

use App\Logger\CrudLogger;
use App\Repositories\Contracts\Eloquent\EloquentRepositoryInterface;
use App\Services\Contracts\CrudServiceInterface;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;

abstract class CrudService implements CrudServiceInterface
{
    abstract protected function getMainRepository() : EloquentRepositoryInterface;

    /**
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return app(ConnectionInterface::class);
    }

    public function getLoggerModuleName() : string
    {
        // get current class name without "Service" and namespace
        $moduleName = str_replace('Service', '', class_basename($this));

        // change casing to "This Casing"
        return preg_replace('/(?<! )(?<!^)[A-Z]/',' $0', $moduleName);
    }

    public function all(array $sortItems = []): Collection
    {
        $loggerData = ['id' => 'all'];
        try {
            CrudLogger::setModule($this->getLoggerModuleName());
            CrudLogger::beforeGet($loggerData);
            $foundData = $this->getMainRepository()->all($sortItems);
            CrudLogger::get($loggerData);

            return $foundData;
        } catch (\Exception $e) {
            CrudLogger::errorGet($loggerData);
            throw $e;
        }
    }

    public function allPaginated(
        $page = 1,
        $limit = 25,
        $sortItems = []
    ) {
        $loggerData = ['id' => 'all'];
        try {
            CrudLogger::setModule($this->getLoggerModuleName());
            CrudLogger::beforeGet($loggerData);
            $foundData = $this->getMainRepository()->allPaginated(
                $page, $limit, $sortItems
            );
            CrudLogger::get($loggerData);

            return $foundData;
        } catch (\Exception $e) {
            CrudLogger::errorGet($loggerData);
            throw $e;
        }
    }

    public function create($data)
    {
        try {
            CrudLogger::setModule($this->getLoggerModuleName());
            CrudLogger::beforeCreate($data);
            $createdData = $this->getMainRepository()->create($data);
            CrudLogger::create($createdData->id);

            return $createdData;
        } catch (\Exception $e) {
            CrudLogger::errorCreate($data);
            throw $e;
        }
    }

    public function firstOrCreate($data)
    {
        try {
            CrudLogger::setModule($this->getLoggerModuleName());
            CrudLogger::beforeFirstOrCreate($data);
            $foundOrCreatedData = $this->getMainRepository()->firstOrCreate($data);
            CrudLogger::firstOrCreate($foundOrCreatedData->id);

            return $foundOrCreatedData;
        } catch (\Exception $e) {
            CrudLogger::errorFirstOrCreate($data);
            throw $e;
        }
    }

    public function findById($id)
    {
        $loggerData = ['id' => $id];
        try {
            CrudLogger::setModule($this->getLoggerModuleName());
            CrudLogger::beforeGet($loggerData);
            $foundData = $this->getMainRepository()->find($id);
            CrudLogger::get($loggerData);

            return $foundData;
        } catch (\Exception $e) {
            CrudLogger::errorGet($loggerData);
            throw $e;
        }
    }

    public function findByAttribute($attribute, $value)
    {
        $loggerData = [$attribute => $value];
        try {
            CrudLogger::setModule($this->getLoggerModuleName());
            CrudLogger::beforeGet($loggerData);
            $foundData = $this->getMainRepository()->findMany($attribute, $value);
            CrudLogger::get($loggerData);

            return $foundData;
        } catch (\Exception $e) {
            CrudLogger::errorGet($loggerData);
            throw $e;
        }
    }

    public function search(array $searchArgs, $sortItems = [])
    {
        try {
            CrudLogger::setModule($this->getLoggerModuleName());
            CrudLogger::beforeGet($searchArgs);
            $foundData = $this->getMainRepository()->search($searchArgs, $sortItems);
            CrudLogger::get($searchArgs);

            return $foundData;
        } catch (\Exception $e) {
            CrudLogger::errorGet($searchArgs);
            throw $e;
        }
    }

    public function paginatedSearch(
        array $searchArgs,
        $page = 1,
        $limit = 25,
        $sortItems = []
    ) {
        try {
            CrudLogger::setModule($this->getLoggerModuleName());
            CrudLogger::beforeGet($searchArgs);
            $foundData = $this->getMainRepository()->paginatedSearch(
                $searchArgs,
                $page,
                $limit,
                $sortItems
            );
            CrudLogger::get($searchArgs);

            return $foundData;
        } catch (\Exception $e) {
            CrudLogger::errorGet($searchArgs);
            throw $e;
        }
    }

    public function getCount(array $searchArgs = [])
    {
        try {
            CrudLogger::setModule($this->getLoggerModuleName());
            CrudLogger::beforeGet($searchArgs);
            $count = $this->getMainRepository()->getCount($searchArgs);
            CrudLogger::get($searchArgs);

            return $count;
        } catch (\Exception $e) {
            CrudLogger::errorGet($searchArgs);
            throw $e;
        }
    }

    public function updateById($id, $data)
    {
        $this->updateByAttribute('id', $id, $data);
    }

    public function updateByAttribute($attribute, $value, $data)
    {
        try {
            CrudLogger::setModule($this->getLoggerModuleName());
            CrudLogger::beforeUpdateByAttribute($attribute, $value);
            $this->getMainRepository()->update($data, $value, $attribute);
            CrudLogger::updateByAttribute($attribute, $value);
        } catch (\Exception $e) {
            CrudLogger::errorUpdateByAttribute($attribute, $value);
            throw $e;
        }
    }

    public function deleteById($id)
    {
        $this->deleteByAttribute('id', $id);
    }

    public function deleteByAttribute($attribute, $value)
    {
        try {
            CrudLogger::setModule($this->getLoggerModuleName());
            CrudLogger::beforeDeleteByAttribute($attribute, $value);
            $this->getMainRepository()->delete($value, $attribute);
            CrudLogger::deleteByAttribute($attribute, $value);
        } catch (\Exception $e) {
            CrudLogger::errorDeleteByAttribute($attribute, $value);
            throw $e;
        }
    }
}
