<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use Validator;

class ProductController extends Controller
{
    public function getListProduct(Request $request){
        $currentUser =$request->user()->first();
        $pageSize = $request->input('page-size');
        $term = $request->input('term');
        $store = $currentUser->listStore()
        ->where('stores.id', '=', $request->id)->first();

        if(!$store){
            return response()->json(['mesage' => 'Store not found!'], 404);
        }
        $products = $store->where('stores.id', '=', $request->id)->first()->listProduct();
        if (!empty($term)) {
            $products = $products->where('products.name', 'like', '%' . $term . '%')
                ->orWhere('products.description', 'like', '%' . $term . '%');
        }
        return response()->json($products->paginate($pageSize), 200);
    }
    public function detailProduct(Request $request){
        $currentUser =$request->user()->first();
        $store = $currentUser->listStore()
        ->where('stores.id', '=', $request->id)->first();

        if(!$store){
            return response()->json(['mesage' => 'Store not found!'], 404);
        }
        $product = $store->first()->listProduct()
        ->where('products.id', '=', $request->productId)->first();
        return response()->json(['product' => $product], 200);
    }


    public function deleteProduct(Request $request){
        $currentUser =$request->user()->first();
        $store = $currentUser->listStore()
        ->where('stores.id', '=', $request->id)->first();

        if(!$store){
            return response()->json(['mesage' => 'Store not found!'], 404);
        }
        $product = $store->where('stores.id', '=', $request->id)->first()
        ->listProduct()
        ->where('products.id', '=', $request->productId)->first();
        if(!$product){
            return response()->json(['mesage' => 'Product not found!'], 404);
        }
        $product->delete();
        
        return response()->json([
            'message' => 'Product deleted!'
        ], 200);
    }

    public function updateProduct(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'string|between:2,100',
            'description' => 'string',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $currentUser =$request->user()->first();
        $name = $request->input('name');
        $description = $request->input('description');

        $store = $currentUser->listStore()
        ->where('stores.id', '=', $request->id)->first();

        if(!$store){
            return response()->json(['mesage' => 'Store not found!'], 404);
        }

        $product = $store->where('stores.id', '=', $request->id)->first()->listProduct()
            ->where('products.id', '=', $request->productId)
            ->first();
        if(!$product){
            return response()->json(['mesage' => 'Product not found!'], 404);
        }
        if($name){
            $product->name = $name;
        }
        if($description){
            $product->description = $description;
        }
        $product->save();
        return response()->json([
            'message' => 'Update successfully!',
            'product' => $product
        ], 200);
    }
    public function createProduct(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'description' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        
        $currentUser =$request->user()->first();
        $store = $currentUser->listStore()
        ->where('stores.id', '=', $request->id)->first();
        if(!$store){
            return response()->json(['mesage' => 'Store not found!'], 404);
        }
        $name = $request->input('name');
        $description = $request->input('description');
        $product = Product::create([
            'name' => $name,
            'store_id' => $request->id,
            'description' => $description,
        ]);
        return response()->json([
            'message' => 'Create successfully!',
            'product' => $product
        ], 200);
    }
}
