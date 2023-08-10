<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Store;
use Validator;

class StoreController extends Controller
{
    public function getListStore(Request $request){
        $currentUser =$request->user()->first();
        $pageSize = $request->input('page-size');
        $term = $request->input('term');
        $stores = $currentUser->listStore();
        if (!empty($term)) {
            $stores = $stores->where('stores.name', 'like', '%' . $term . '%')
                ->orWhere('stores.address', 'like', '%' . $term . '%');
        }
        return response()->json($stores->paginate($pageSize), 200);
    }
    public function detailStore(Request $request){
        $currentUser =$request->user()->first();
        $store = $currentUser->listStore()
        ->where('stores.id', '=', $request->id)->first();
        return response()->json(['store' => $store], 200);
    }


    public function deleteStore(Request $request){
        $currentUser =$request->user()->first();
        $store = $currentUser->listStore()
        ->where('stores.id', '=', $request->id)->first();
        if(!$store){
            return response()->json(['mesage' => 'Store not found!'], 404);
        }
        $store->listProduct()->delete();
        $store->delete();
        
        return response()->json([
            'message' => 'Store deleted!'
        ], 200);
    }

    public function updateStore(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'string|between:2,100',
            'address' => 'string',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $currentUser =$request->user()->first();
        $name = $request->input('name');
        $address = $request->input('address');

        $store = $currentUser->listStore()
        ->where('stores.id', '=', $request->id)->first();
        if(!$store){
            return response()->json(['mesage' => 'Store not found!'], 404);
        }
        if($name){
            $store->name = $name;
        }
        if($address){
            $store->address = $address;
        }
        $store->save();
        return response()->json([
            'message' => 'Update successfully!',
            'store' => $store
        ], 200);
    }
    public function createStore(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'address' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $currentUser =$request->user()->first();
        $name = $request->input('name');
        $ownerId =$currentUser->id;
        $address = $request->input('address');
        $store = Store::create([
            'name' => $name,
            'owner_id' => $ownerId,
            'address' => $address,
        ]);
        return response()->json([
            'message' => 'Create successfully!',
            'store' => $store
        ], 200);
    }
}
