<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductsListResource;
use App\Services\ProductService;

class ProductsController extends Controller
{
  protected $productService;

  public function __construct(ProductService $productService)
  {
    $this->productService = $productService;
  }

  // collection
  public function getAllPaginated(Request $request)
  {
    return ProductsListResource::collection(
      $this->productService->paginatedSearch(
        ['keyword' => $request->get('keyword')],
        $request->get('page', 1)
      )
    );
  }

  // create
  public function create(Request $request)
  {
    $data = $this->productService->create(
      $request->only([
        'product_name',
        'product_line',
        'quantity',
        'cost',
        'quantity_sold'
      ])
    );

    return response()->json([
      'success' => true,
      'data' => $data
    ]);
  }

  // update
  public function update(Request $request, $id)
  {
    $this->productService->updateById(
      $id,
      $request->only(
        'quantity',
        'cost',
        'quantity_sold',
        'updated_at'
      )
    );

    return response()->json([
      'success' => true,
      'user' => $this->productService->findById($id)
    ]);
  }

  // delete
  public function delete($id)
  {
    $this->productService->deleteById($id);

    return response()->json([
      'success' => true
    ]);
  }
}
