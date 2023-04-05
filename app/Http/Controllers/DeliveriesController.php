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

    // create
    public function create(Request $request)
    {
        $data = $this->deliveryService->create(
            $request->only([
                'product_id',
                'expected_quantity',
                'status',
                'current_quantity',
            ])
        );
        
        if ($data->status == "Complete")
        {
            $product = $data->product;
            $product->quantity += $data->expected_quantity;
            $product->save();
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }


    // update
    public function update(Request $request, $id)
    {
        $data = $this->deliveryService->findByID($id);
        $old_status = $data->status;
        $product = $data->product;
        if($request->status == "Complete" && $old_status != "Complete")
        {
            $product->quantity += $data->expected_quantity;
            $product->save();
        }
        else if($request->status != "Complete" && $old_status == "Complete")
        {
            $product->quantity -= $data->expected_quantity;
            $product->save();
        }

        $this->deliveryService->updateById(
            $id,
            $request->only(
                'expected_quantity',
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
