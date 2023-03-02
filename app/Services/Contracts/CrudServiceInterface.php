<?php

namespace App\Services\Contracts;

use Illuminate\Support\Collection;

interface CrudServiceInterface
{
    public function getConnection();

    public function all(array $sortItems = []) : Collection;

    public function allPaginated(
        $page = 1,
        $limit = 25,
        $sortItems = []
    );

    public function create($data);

    public function firstOrCreate($data);

    public function findById($id);

    public function findByAttribute($attribute, $value);

    public function search(array $searchArgs, $sortItems = []);

    public function paginatedSearch(
        array $searchArgs,
        $page = 1,
        $limit = 25,
        $sortItems = []
    );

    public function updateById($id, $data);

    public function updateByAttribute($attribute, $value, $data);

    public function deleteById($id);

    public function deleteByAttribute($attribute, $value);

    public function getLoggerModuleName();

    public function getCount(array $searchArgs = []);
}
