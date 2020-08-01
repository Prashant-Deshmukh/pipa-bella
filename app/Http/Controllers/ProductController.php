<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\Redis;

class ProductController extends Controller
{
    public function index()
    {
        $products = NULL;
        if (Redis::get('products')) {
            info("In Cache");
            $products = json_decode(Redis::get('products'));
        } else {
            info("In DB");
            $products = Product::take(60)->get();
            Redis::set('products',$products);
            
        }

        return view('load-more-data.load-more',['products'=>$products]);
    }

    public function more_data(Request $request) {
        if ($request->ajax()) {
            $products = NULL;
            if (Redis::get('products_append')) {
                Redis::set('products_append',NUll);
            } 
            $skip=$request->skip;
            $take=60;
            $products = Product::skip($skip)->take($take)->get();
            Redis::set('products_append',$products);
        
            return response()->json($products);
        } else {
            return response()->json('Direct Access Not Allowed!!');
        }
    }

    public function deleteCache()
    {
        Redis::del('products');
        Redis::del('products_append');
        return "Redis Cache Clear Successfully";
    }
}
