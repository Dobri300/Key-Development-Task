<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\models\Products;
use App\models\departments;
use Illuminate\Support\Facades\DB;



class ProductsController extends Controller
{
    public function postProducts()
    {
        $productsCount = 0;
        if(!is_null(DB::table('products')->count()))
            $productsCount = DB::table('products')->count();
        $page = $productsCount/20;

        if($productsCount > 0)
            $response = Http::get('https://demoshopapi.keydev.eu/api/v1/products?page='.$page);
        else
            $response = Http::get('https://demoshopapi.keydev.eu/api/v1/products');
        \Log::info($response);


        $data = json_decode($response);

        foreach($data->data as $product)
        {
            $product_name = $product->product_name;
            $product_image = str_replace("/", " ",$product->product_image_sm);
            $product_type = $product->product_type;
            $product_department = $product->product_department;
            $product_departmentId = $product->product_departmentId;
            $product_stock = $product->product_stock;
            $product_color = $product->product_color;
            $product_price = $product->product_price;
            $product_material = $product->product_material;
            $product_ratings = $product->product_ratings;
            $product_sales = $product->product_sales;

            $newProduct = new Products;
            $newProduct->name = $product_name;
            $newProduct->image = str_replace(" ", "/",$product->product_image_sm);$product_image;
            $newProduct->type = $product_type;
            $newProduct->department = $product_department;
            $newProduct->departmentId = $product_departmentId;
            $newProduct->stock = $product_stock;
            $newProduct->color = $product_color;
            $newProduct->price = $product_price;
            $newProduct->material = $product_material;
            $newProduct->rating = $product_ratings;
            $newProduct->sales = $product_sales;

            $newProduct->save();
            $department = DB::table('departments')
            ->select('name')
            ->having('name', 'LIKE', $product_departmentId)
            ->get();



            if(count($department) == 0)
            {
                $newDepartment = new departments;
                $newDepartment -> name = $product_departmentId;
                $newDepartment->save();
            }

        }
        

            echo "<div class=\"pop-up-message-container\"><h4 class=\"pop-up-message\">20 new products were just imported to the Database</h4></div>";
        return view('welcome');
    }

    public function getAllProducts()
    {
        $getProduct = DB::table('products')
                        ->select('name', 'image', 'type', 'department', 'departmentId',
                         'stock', 'color', 'price', 'material', 'rating', 'sales')
                        ->paginate(10);



        $data = [
        'data' => $getProduct->items(),
        'current_page' => $getProduct->currentPage(),
        'last_page' => $getProduct->lastPage(),
        'per_page' => $getProduct->perPage(),
    ];

        return response()->json($getProduct);


    }

    public function getAllCategories()
    {
        $getProduct = DB::table('departments')
                        ->select('name')
                       ->paginate(10);



        $data = [
        'data' => $getProduct->items(),
        'current_page' => $getProduct->currentPage(),
        'last_page' => $getProduct->lastPage(),
        'per_page' => $getProduct->perPage(),
    ];

        return response()->json($getProduct);


    }

}
