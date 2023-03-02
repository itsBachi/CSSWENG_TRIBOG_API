<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\ErrorExceptions\CreateConstraintExceptions\FailedToCreateException;
use App\Exceptions\ErrorExceptions\DeleteConstraintExceptions\FailedToDeleteException;
use App\Exceptions\ErrorExceptions\EditConstraintExceptions\FailedToEditException;
use App\Repositories\Contracts\Eloquent\EloquentRepositoryInterface;
use App\Repositories\Exceptions\RepositoryException;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;

/**
 * Class NewRepository
 * @package App\Repositories\Eloquent
 */
abstract class EloquentRepository implements EloquentRepositoryInterface
{
    /**
     * @var App
     */
    private $app;

    /**
     * @var Model
     */
    protected $model;

    /**
     * Repository constructor.
     *
     * @param App $app
     * @throws RepositoryException
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    public function all(array $sortItems = []): Collection
    {
        $query = $this->model->newQuery();
        if (!empty($sortItems)) {
            foreach ($sortItems as $sort) {
                $sort = (array)$sort;
                $this->orderByQueryBuilder($query, $sort['property'], $sort['direction']);
            }
        }

        return $query->get();
    }

    public function allPaginated(
        $page = 1,
        $limit = 25,
        $sortItems = []
    ) {
        $query = $this->model->newQuery();
        if (!empty($sortItems)) {
            foreach ($sortItems as $sort) {
                $sort = (array)$sort;
                $this->orderByQueryBuilder($query, $sort['property'], $sort['direction']);
            }
        }

        return $query->paginate(
            $limit,
            [$this->model->getTable() . '.*'],
            'page',
            $page
        );
    }

    /**
     * Creates a new record and return it
     *
     * @param array $data
     * @return mixed
     * @throws FailedToCreateException
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Creates multiple records and returns them
     *
     * @param array $data
     * @return array
     * @throws FailedToCreateException
     */
    public function createMany(array $data): array
    {
        $result = [];

        foreach ($data as &$datum) {
            $result[] = $this->create($datum);
            unset($datum);
        }

        return $result;
    }

    /**
     * @param $attribute
     * @param mixed $value
     * @param $operation
     * @param Builder|null $existingQuery
     * @return Builder
     */
    protected function getBaseQueryForSearch($attribute, $value, $operation = '=', $existingQuery = null)
    {
        $query = (!empty($existingQuery)) ? $existingQuery : $this->model->newQuery();
        if (is_array($value)) {
            if ($operation === '!=') {
                return $query->whereNotIn($attribute, $value);
            }
            return $query->whereIn($attribute, $value);
        }

        return $query->where($attribute, $operation, $value);
    }

    /**
     * @param array $searchArgs
     * @param Builder|null $existingQuery
     * @return Builder
     */
    protected function getBaseQueryForMultipleSearch(array $searchArgs, $existingQuery = null)
    {
        $query = (!empty($existingQuery)) ? $existingQuery : $this->model->newQuery();
        foreach ($searchArgs as $attribute => $searchArg) {
            $value = $searchArg;
            $operation = '=';
            if (is_array($searchArg)) {
                if (isset($searchArg['value'])) {
                    $value = $searchArg['value'];
                }
                if (isset($searchArg['operation'])) {
                    $operation = $searchArg['operation'];
                }
            }

            $query = $this->getBaseQueryForSearch($attribute, $value, $operation, $query);
        }

        return $query;
    }

    /**
     * Updates existing record and return it
     *
     * @param array $data
     * @param mixed $id
     * @param string $attribute
     * @throws FailedToEditException
     */
    public function update(array $data, $id, $attribute = "id"): void
    {
        $query = $this->getBaseQueryForSearch($attribute, $id);
        $query->update($data);
    }

    /**
     * Deletes existing record
     *
     * @param mixed $id
     * @param string $attribute
     * @throws FailedToDeleteException
     */
    public function delete($id, $attribute = 'id'): void
    {
        $query = $this->getBaseQueryForSearch($attribute, $id);
        $query->delete();
    }

    public function getCount(array $searchArgs = []): int
    {
        $query = $this->model->newQuery();
        if (!empty($searchArgs)) {
            $query = $this->getBaseQueryForMultipleSearch($searchArgs, $query);
        }

        return $query->count();
    }

    public function search(array $searchArgs, $sortItems = []): Collection
    {
        $query = $this->getBaseQueryForMultipleSearch($searchArgs);

        if (!empty($sortItems)) {
            foreach ($sortItems as $sort) {
                $sort = (array)$sort;
                $this->orderByQueryBuilder($query, $sort['property'], $sort['direction']);
            }
        }

        return $query->get();
    }

    public function paginatedSearch(
        array $searchArgs,
        $page = 1,
        $limit = 25,
        $sortItems = []
    ) {
        $query = $this->getBaseQueryForMultipleSearch($searchArgs);
        if (!empty($sortItems)) {
            foreach ($sortItems as $sort) {
                $sort = (array) $sort;
                $this->orderByQueryBuilder($query, $sort['property'], $sort['direction']);
            }
        }

        return $query->paginate(
            $limit,
            [$this->model->getTable() . '.*'],
            'page',
            $page
        );
    }

    /**
     * @param Builder $query
     * @param int $start
     * @param int $limit
     */
    protected function paginateQueryBuilder(&$query, $start = 0, $limit = 25)
    {
        if ($limit != 0) {
            $query->skip($start)->take($limit);
        }
    }

    /**
     * @param Builder $query
     * @param $property
     * @param $direction
     */
    protected function orderByQueryBuilder(&$query, $property, $direction)
    {
        $query->orderBy($property, $direction);
    }

    public function find($id)
    {
        return $this->model
            ->newQuery()
            ->where(
                $this->model->getKeyName(),
                $id
            )->first();
    }

    public function findBy($attribute, $value)
    {
        return $this
            ->getBaseQueryForSearch($attribute, $value)
            ->first();
    }

    public function findMany($attribute, $value): Collection
    {
        return $this
            ->getBaseQueryForSearch($attribute, $value)
            ->get();
    }

    /**
     * Creates model instance and return it
     *
     * @return mixed
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
        $model->timestamps = false;

        return $this->model = $model;
    }

    public function raw($string): Expression
    {
        return new Expression($string);
    }

    /**
     * Searches for a record and return it or create if not found
     *
     * @param $attributes
     * @return mixed
     * @throws FailedToCreateException
     */
    public function firstOrCreate($attributes)
    {
        $record = $this
            ->getBaseQueryForMultipleSearch($attributes)
            ->first();
        if (!$record) {
            return $this->create($attributes);
        }
        return $record;
    }

    public function searchAndUpdate(array $searchArgs, array $updateArgs)
    {
        $this
            ->getBaseQueryForMultipleSearch($searchArgs)
            ->update($updateArgs);
    }

    abstract public function model(): string;
}
