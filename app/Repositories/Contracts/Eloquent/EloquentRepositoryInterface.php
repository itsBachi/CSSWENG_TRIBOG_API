<?php

namespace App\Repositories\Contracts\Eloquent;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;

interface EloquentRepositoryInterface
{
    /**
     * Returns the class name of model attached to repository
     *
     * @return string
     */
    public function model(): string;

    /**
     * Creates model instance and return it
     *
     * @return mixed
     */
    public function makeModel();

    /**
     * Returns a collection of all records
     *
     * @param array $sortItems
     * @return Collection
     */
    public function all(array $sortItems = []): Collection;

    public function allPaginated(
        $page = 1,
        $limit = 25,
        $sortItems = []
    );

    /**
     * Creates a new record and return it
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Creates multiple records and returns them
     *
     * @param array $data
     * @return mixed[]
     */
    public function createMany(array $data): array;

    /**
     * Updates existing record and return it
     *
     * @param array $data
     * @param mixed $id
     * @param string $attribute
     */
    public function update(array $data, $id, $attribute = "id"): void;

    /**
     * Deletes existing record(s)
     *
     * @param mixed $id
     * @param string $attribute
     */
    public function delete($id, $attribute = 'id'): void;

    /**
     * Returns count based on searchArgs
     *
     * @param array $searchArgs
     * @return int
     */
    public function getCount(array $searchArgs = []): int;

    /**
     * Searches for records and returns them
     *
     * @param array $searchArgs
     * @param array $sortItems
     * @return Collection
     */
    public function search(array $searchArgs, $sortItems = []): Collection;

    /**
     * Returns a number of records depending on a specific page
     *
     * @param array $searchArgs
     * @param int $page
     * @param int $limit
     * @param array $sortItems
     * @return LengthAwarePaginator
     */
    public function paginatedSearch(
        array $searchArgs,
        $page = 1,
        $limit = 25,
        $sortItems = []
    );

    /**
     * Searches for a record based on primary key and return it
     *
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Returns the first result of a search condition
     *
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function findBy($attribute, $value);

    /**
     * Returns records based on a search condition
     *
     * @param $attribute
     * @param $value
     * @return Collection
     */
    public function findMany($attribute, $value): Collection;

    /**
     * Creates a new expression and return it
     *
     * @param $string
     * @return Expression
     */
    public function raw($string): Expression;

    /**
     * Searches for a record and return it or create if not found
     *
     * @param $attributes
     * @return mixed
     */
    public function firstOrCreate($attributes);
}
