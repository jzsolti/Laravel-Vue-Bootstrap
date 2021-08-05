<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class UserProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query  = Product::where('user_id', $request->user()->id)->orderBy($request->column, $request->order);
        $products = $query->paginate($request->per_page ?? 10);

        return ProductResource::collection($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProductRequest $request)
    {
        $product = Product::create([
            'user_id' => $request->user()->id,
            'name' => $request->input('name'),
            'status' => $request->input('status')
        ]);
        $product->categories()->attach( $request->input('categories'));

        return response(['success' => true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        if($product->user_id !== $request->user()->id){
            abort(404);
        }
        $product->update([
            'user_id' => $request->user()->id,
            'name' => $request->input('name'),
            'status' => $request->input('status')
        ]);
        $product->categories()->sync( $request->input('categories'));
        return response(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Product $product)
    {
        if($product->user_id !== $request->user()->id){
            abort(404);
        }
        $product->delete();
        return response(['success' => true]);
    }
}
