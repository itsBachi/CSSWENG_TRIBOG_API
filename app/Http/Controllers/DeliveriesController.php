<?php

namespace App\Http\Controllers;

use app\Models\Delivery;
use Illuminate\Http\Request;
use App\Http\Resources\DeliveriesListResource;
use App\Services\DeliveryService;

class DeliveriesController extends Controller
{
    protected $deliveryService;

    public function __construct(DeliveryService $deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }

    // collection
    public function getAllPaginated(Request $request)
    {
        return DeliveriesListResource::collection(
            $this->deliveryService->paginatedSearch(
                ['keyword' => $request->get('keyword')],
                $request->get('page', 1)
            )
        );
    }

    // update
    public function update(Request $request, $id)
    {
        $this->deliveryService->updateById(
            $id,
            $request->only(
                'status',
                'current_quantity',
                'updated_at'
            )
        );

        return response()->json([
            'success' => true,
            'user' => $this->deliveryService->findById($id)
        ]);
    }

    // delete
    public function delete($id)
    {
        $this->deliveryService->deleteById($id);

        return response()->json([
            'success' => true
        ]);
    }
}
