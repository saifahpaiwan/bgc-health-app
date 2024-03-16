<?php

namespace App\Http\Controllers;

use App\Models\categcts;
use App\Models\plants;
use App\Models\product_items;
use App\Models\products;
use App\Models\units;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductsController extends Controller
{

    // function __construct()
    // {
    //     $this->middleware('permission:products-list|products-create|products-edit|products-delete', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:products-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:products-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:products-delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    {
        $query = products::query();

        if (isset($request->keyword) && !empty($request->keyword)) {
            $query->where('product_code', 'like', "%$request->keyword%") 
                ->orWhere('product_name', 'like', "%$request->keyword%");
        }

        if (isset($request->categcts['id']) && !empty($request->categcts['id'])) {
            $query->where('category_id', $request->categcts['id']);
        }

        if (isset($request->status['id']) && !empty($request->status['id'])) {
            $status = ($request->status['id'] == -1) ? '0' : $request->status['id'];
            $query->where('status', $status);
        } 

        $products = $query->orderBy('id', 'DESC')->get();
        $categcts = categcts::select('id', 'category_name')->where('status', 1)->get();
        return Inertia::render('Products/Index', [
            'categcts' => $categcts,
            'products' => $products
        ]);
    }

    public function create()
    {
        $units  = units::select('id', 'unit_name')->where('status', 1)->get();
        $plants = plants::select('id', 'plant')->where('status', 1)->get();
        $categcts = categcts::select('id', 'category_name')->where('status', 1)->get();
        return Inertia::render('Products/Create', [
            'categcts' => $categcts,
            'units'  => $units,
            'plants' => $plants,
        ]);
    }

    public function store(Request $request)
    {
        $validate = [
            'product_code'      => 'required',
            'product_name'      => 'required|string|max:255',
             
            "category_id"    => "required|array|min:1",
            "category_id.*"  => "required|min:1",
 
            "unit_id"    => "required|array|min:1",
            "unit_id.*"  => "required|min:1",
 
            'quantity_per_unit'    => 'required|integer',
            'quantity_per_subunit' => 'required|integer',
        ];
 
        request()->validate($validate);
        $file_name = null;
        if (isset($request->filename) && !empty($request->filename)) {
            $base64_image = $request->filename;
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                $file = substr($base64_image, strpos($base64_image, ',') + 1);
                $file = base64_decode($file);
                $file_name = hexdec(uniqid()) . '.jpg';
                Storage::disk('public')->put('images/products/' . $file_name, $file);
            }
        }

        $input = [];
        $input['product_code']    = $request->product_code;
        $input['product_name']    = $request->product_name;
        $input['description']     = $request->description;
        $input['default_price']   = $request->default_price;
        $input['product_images'] = $file_name;
        $input['category_id']    = $request->category_id['id'];

        $input['quantity_per_unit']   = $request->quantity_per_unit;
        $input['unit_id']             = $request->unit_id['id'];
        $input['quantity_per_subunit']   = $request->quantity_per_subunit;
        $input['subunit_id']             = (isset($request->subunit_id['id']) && !empty($request->subunit_id['id']))? $request->subunit_id['id'] : null;

        $input['weight']              = $request->weight;
        $input['weight_unitID']       = (isset($request->weight_unit_id['id']) && !empty($request->weight_unit_id['id']))? $request->weight_unit_id['id'] : null;

        $input['status']         = $request->status;
        $latestID = products::create($input);
        $input_sub = [];
        if (isset($request->data_plants) && count($request->data_plants) > 0) {
            foreach ($request->data_plants as $row) {
                $input_sub['product_id'] = $latestID->id;
                $input_sub['plant_id']   = $row['plant_id']; 
                $input_sub['minimum_quantity']    = $row['quantity'];
                $input_sub['minimum_unitID']      = $row['unit_id'];
                product_items::create($input_sub);
            }
        }

        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | Product created successfully' . json_encode($request->all(), true));
        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function edit($id)
    {
        $productQuery = products::find($id);
        $productQueryItems = product_items::where('product_id', $id)->where('status', 1)->get();
        $productItems = [];
        $n = 0;
        $product['id']              = $productQuery->id;
        $product['product_code']    = $productQuery->product_code;
        $product['product_name']    = $productQuery->product_name;
        $product['category_id']     = $productQuery->category_id;
        $product['description']     = $productQuery->description;
        $product['status']          = $productQuery->status;
        $product['filename']        = $productQuery->product_images;
        $product['default_price']   = $productQuery->default_price;
        $product['unit_id']         = $productQuery->unit_id;
        $product['subunit_id']      = $productQuery->subunit_id;
        $product['quantity_per_unit']    = $productQuery->quantity_per_unit;
        $product['quantity_per_subunit'] = $productQuery->quantity_per_subunit;
        $product['weight']               = $productQuery->weight;
        $product['weight_unit_id']       = $productQuery->weight_unitID;
        if (isset($productQueryItems) && count($productQueryItems) > 0) {
            foreach ($productQueryItems as $row) {
                $productItems[$n]['plant_id'] = $row->plant_id;
                $productItems[$n]['quantity'] = $row->minimum_quantity;
                $productItems[$n]['unit_id']  =  $row->minimum_unitID;
                $n++;
            }
        }

        $units  = units::select('id', 'unit_name')->where('status', 1)->get();
        $plants = plants::select('id', 'plant')->where('status', 1)->get();
        $categcts = categcts::select('id', 'category_name')->where('status', 1)->get();
        return Inertia::render('Products/Edit', [
            'product'  => $product,
            'productItems' => $productItems,
            'categcts' => $categcts,
            'units'  => $units,
            'plants' => $plants,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validate = [
            'product_code'      => 'required',
            'product_name'      => 'required|string|max:255',
            'category_id.*'     => 'required|min:1',
            'unit_id.*'         => 'required|min:1',
            'quantity_per_unit' => 'required|integer',
            'quantity_per_subunit' => 'required|integer',
            'data_plants.*'        => 'required|min:1',
        ];

        request()->validate($validate);

        $product = products::find($id);
        $file_name = $product->product_images;
        if (isset($request->filename) && !empty($request->filename)) {
            if (isset($product->product_images) && !empty($product->product_images)) {
                $uploade_location = 'storage/images/products/';
                if (!empty($product->product_images)) {
                    @unlink($uploade_location . $product->product_images);
                }
            }

            $base64_image = $request->filename;
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                $file = substr($base64_image, strpos($base64_image, ',') + 1);
                $file = base64_decode($file);
                $file_name = hexdec(uniqid()) . '.jpg';
                Storage::disk('public')->put('images/products/' . $file_name, $file);
            }
        }

        $input = [];
        $input['product_code']    = $request->product_code;
        $input['product_name']    = $request->product_name;
        $input['description']     = $request->description;
        $input['default_price']   = $request->default_price;
        $input['product_images']  = $file_name;
        $input['category_id']     = $request->category_id['id'];

        $input['quantity_per_unit']   = $request->quantity_per_unit;
        $input['unit_id']             = $request->unit_id['id'];
        $input['quantity_per_subunit']   = $request->quantity_per_subunit;
        $input['subunit_id']             = $request->subunit_id['id'];

        $input['weight']              = $request->weight;
        $input['weight_unitID']       = $request->weight_unit_id['id'];

        $input['status']         = $request->status;
        $product->update($input);
        $input_sub = [];
        if (isset($request->data_plants) && count($request->data_plants) > 0) {
            foreach ($request->data_plants as $row) {
                $count = product_items::where('product_id', $id)
                ->where('plant_id', $row['plant_id']) 
                ->count(); 
                $input_sub['product_id'] = $id;
                $input_sub['plant_id']   = $row['plant_id'];
                $input_sub['minimum_quantity']    = $row['quantity'];
                $input_sub['minimum_unitID']      = $row['unit_id'];
                if ($count > 0) { 
                    product_items::where('product_id', $id)
                        ->where('plant_id', $row['plant_id'])
                        ->update($input_sub);
                } else {
                    product_items::create($input_sub);
                }
            }
        }

        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | Product updated successfully' . json_encode($request->all(), true));
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(string $id)
    {
        $products = products::find($id);
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | Product & Items deleted successfully : ' . json_encode($products, true));
        $uploade_location = 'storage/images/products/';
        if (!empty($products->images_name)) {
            @unlink($uploade_location . $products->images_name);
        }
        product_items::where('product_id', $id)->delete();
        $products->delete(); 
        return redirect()->route('products.index')->with('success', 'Product & Items deleted successfully');
    } 
}
