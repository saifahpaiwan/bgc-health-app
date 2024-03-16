<?php

namespace App\Http\Controllers;

use App\Models\product_items;
use Illuminate\Http\Request;

class ProductsItemsController extends Controller
{
    public function update(Request $request, string $id)
    {  
        product_items::where('product_id', $id)->delete();
        if(isset($request->data_plants) && count($request->data_plants) > 0){
            foreach($request->data_plants as $row){
                product_items::where('product_id', $id)->where('plant_id', $row['plant_id'])->delete();
            }
        } 
        return redirect()->route('products.edit', $id)->with('success', 'Product Items deleted successfully');
    } 
}
