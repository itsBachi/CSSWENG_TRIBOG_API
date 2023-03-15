<?php

namespace App\Repositories;

use App\Models\Delivery;
use App\Repositories\Eloquent\EloquentRepository;

class DeliveryRepository extends EloquentRepository
{
    public function model(): string
    {
        return Delivery::class;
    }

    protected function getBaseQueryForSearch($attribute, $value, $operation = '=', $existingQuery = null)
    {
        $query = (! empty($existingQuery)) ? $existingQuery : $this->model->newQuery();

        if ($attribute == 'keyword') {
            return $query->where(function($keywordSubquery) use ($value) {
                $keyword = "$$value$";
                $keywordSubquery
                    ->where('product_name', 'like', $keyword)
                    ->orWhere('id', 'like', $keyword);
            });
        } else {
            return parent::getBaseQueryForSearch($attribute, $value, $operation, $query);
        }
    }
}