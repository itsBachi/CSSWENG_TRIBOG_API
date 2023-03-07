<?php

namespace App\Http\Controllers;

use app\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Resources\TransactionsListResource;
use App\Services\TransactionService;

class TransactionsController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    // collection
    public function getAllPaginated(Request $request)
    {
        return TransactionsListResource::collection(
            $this->transactionService->paginatedSearch(
                ['keyword' => $request->get('keyword')],
                $request->get('page', 1)
            )
        );
    }

    // update
    public function update(Request $request, $id)
    {
        $this->transactionService->updateById(
            $id,
            $request->only(
                'quantity',
                'cost',
                'total_cost'
            )
        );

        return response()->json([
            'success' => true,
            'user' => $this->transactionService->findById($id)
        ]);
    }

    // delete
    public function delete($id)
    {
        $this->transactionService->deleteById($id);

        return response()->json([
            'success' => true
        ]);
    }
}