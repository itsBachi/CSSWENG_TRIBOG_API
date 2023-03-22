<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Eloquent\EloquentRepository;

class TransactionRepository extends EloquentRepository
{
    public function model(): string
    {
        return Transaction::class;
    }

    protected function getBaseQueryForSearch($attribute, $value, $operation = '=', $existingQuery = null)
    {
        $query = (! empty($existingQuery)) ? $existingQuery : $this->model->newQuery();

        if ($attribute == 'keyword') {
            return $query->where(function($keywordSubquery) use ($value) {
                $keyword = "%$value%";
                $keywordSubquery
                    ->whereHas("product",function($q)use($keyword){
                        $q->where("product_name","like",$keyword);
                    })
                    ->orWhere('id', 'like', $keyword);
            });
        } else {
            return parent::getBaseQueryForSearch($attribute, $value, $operation, $query);
        }
    }
}