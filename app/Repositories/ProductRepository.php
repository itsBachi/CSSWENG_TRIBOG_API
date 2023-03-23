<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Eloquent\EloquentRepository;

class ProductRepository extends EloquentRepository
{
    public function model(): string
    {
        return Product::class;
    }

    protected function getBaseQueryForSearch($attribute, $value, $operation = '=', $existingQuery = null)
    {
        $query = (!empty($existingQuery)) ? $existingQuery : $this->model->newQuery();

        $query->orderByDesc('created_at');

        if ($attribute == 'keyword') {
            return $query->where(function ($keywordSubquery) use ($value) {
                $keyword = "%$value%";
                $keywordSubquery
                    ->where('product_name', 'like', $keyword);
            });
        } else {
            return parent::getBaseQueryForSearch($attribute, $value, $operation, $query);
        }
    }
}
