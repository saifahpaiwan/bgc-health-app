<?php

namespace App\Http\Controllers;

use App\Models\product_items;
use App\Models\products;
use App\Models\stock_transaction_items;
use App\Models\stock_transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ImportStockController extends Controller
{ 
    // function __construct()
    // {
    //     $this->middleware('permission:import-stock-list|import-stock-create|import-stock-edit|import-stock-delete', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:import-stock-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:import-stock-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:import-stock-delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    {
        $query = stock_transactions::query();
        $query->where('transaction_type', 'I');

        if (isset($request->keyword) && !empty($request->keyword)) {
            $query->where('description', 'like', "%$request->keyword%");
        }
        
        if (isset($request->date) && !empty($request->date)) { 
            $from = date("Y-m-d 00:00:00", strtotime($request->date));
            $to = date("Y-m-d 23:59:59", strtotime($request->date));
            $query->whereBetween('transaction_date', [$from, $to]);
        } 
 
        $data = $query->orderBy('id', 'DESC')->get();

        $stock_transactions = [];
        if(isset($data) && count($data) > 0){
            foreach($data as $row){
                $stock_transactions[$row->id]['id'] = $row->id;
                $stock_transactions[$row->id]['transaction_date'] = $row->transaction_date;
                $stock_transactions[$row->id]['description'] = $row->description;
                $stock_transactions[$row->id]['plant_id']   = $row->rPlant?->plant;
                $stock_transactions[$row->id]['create_uid'] = $row->rCreate?->username;
                $stock_transactions[$row->id]['updated_at'] = $row->updated_at;
                $stock_transactions[$row->id]['countitems'] = $row->mStockTransactionItems?->count(); 
            }
        }  
        rsort($stock_transactions);

        return Inertia::render('ImportStock/Index', [
            'stock_transactions' => $stock_transactions,
        ]);
    }

    public function show(string $id)
    {
        $queryStockTransactions   = stock_transactions::find($id);
        $queryStockTransactionsItems   = stock_transaction_items::where('transaction_id', $id)->get();
        $stock_transactions = [];
        $stock_transactions['id']               = $queryStockTransactions->id;
        $stock_transactions['transaction_date'] = $queryStockTransactions->transaction_date;
        $stock_transactions['plant']            = $queryStockTransactions->rPlant?->plant;
        $stock_transactions['create']           = $queryStockTransactions->rCreate?->username;
        $stock_transactions['description']      = $queryStockTransactions->description;
        $stock_transactions_items = [];
        if(isset($queryStockTransactionsItems) && count($queryStockTransactionsItems) > 0){
            foreach($queryStockTransactionsItems as $row){
                $stock_transactions_items[$row->id]['id'] = $row->id;
                $stock_transactions_items[$row->id]['product_images'] = $row->rProduct?->product_images;
                $stock_transactions_items[$row->id]['product_code'] = $row->rProduct?->product_code;
                $stock_transactions_items[$row->id]['product_name'] = $row->rProduct?->product_name;
                $stock_transactions_items[$row->id]['product_description'] = $row->rProduct?->description;
                $stock_transactions_items[$row->id]['quantity_changed'] = $row->quantity_changed;
                $stock_transactions_items[$row->id]['units']            = $row->rUnits?->unit_name;
                $stock_transactions_items[$row->id]['expires_at']       =  date("Y-m-d", strtotime($row->expires_at)); 
            }
        }

        return Inertia::render('ImportStock/Show', [
            'stock_transactions' => $stock_transactions,
            'stock_transactions_items' => $stock_transactions_items
        ]);
    }

    public function create()
    {
        $data = products::where('status', 1)->get();
        $products = [];
        if(isset($data) && count($data) > 0){
            foreach($data as $row){
                $products[$row->id]['id'] = $row->id;
                $products[$row->id]['product_code']   = $row->product_code;
                $products[$row->id]['product_name']   = $row->product_name;
                $products[$row->id]['product_description'] = $row->description;
                $products[$row->id]['product_images'] = $row->product_images;
                $products[$row->id]['category_name']  = $row->rCategorys?->category_name;

                $products[$row->id]['unit_id']       = $row->rUnits?->id;
                $products[$row->id]['unit_name']     = $row->rUnits?->unit_name;

                $products[$row->id]['subunit_id']    = $row->rSubunits?->id;
                $products[$row->id]['subunit_name']  = $row->rSubunits?->unit_name;
  
                // Add Data //
                $products[$row->id]['units']      = 0;
                $products[$row->id]['quantity']   = 0;
                $products[$row->id]['expires_at'] = null; 
            }
        }

        // ================ //
        // Check Plant ID   //
        // ================ //
        $plantID    = helperCheckPlant();
        $CheckPlant = null;
        if(empty($plantID)){
            $CheckPlant = "โปรดระบุ PLANT ในการจัดการข้อมูล";
        }

        return Inertia::render('ImportStock/Create', [
            'CheckPlant' => $CheckPlant,
            'products'   => $products
        ]);
    }
 
    public function store(Request $request)
    { 
        $validate = [ 
            'transaction_date' => 'required|date', 
            "product_items"    => "required|array|min:1",
            "product_items.*"  => "required|min:1",
        ];  
        
        request()->validate($validate); 

        // ================ //
        // Check Plant ID   //
        // ================ //
        $plantID = helperCheckPlant();  
        if(empty($plantID)){
            request()->validate(['plant_id' => 'required'], ['plant_id.required' => 'โปรดระบุ PLANT ในการจัดการข้อมูล.']); 
        }
 
        $input = [];
        $input['plant_id'] = $plantID;
        $input['transaction_date'] = $request->transaction_date;
        $input['description']      = $request->description;
        $input['transaction_type'] = "I"; 
        $input['create_uid']       = auth()->user()->id;
        $latestID = stock_transactions::create($input); 
        if(isset($request->product_items) && count($request->product_items) > 0){
            foreach($request->product_items as $row){
                $input_sub = [];  
                $ProductItems = product_items::where('plant_id', $plantID)->where('product_id', $row['id'])->first(); 
                if(isset($ProductItems->id) && !empty($ProductItems->id)){ 
                    
                    // ===============================================
                    // Start Conditions for importing selected units 
                    // ===============================================
                    $unitExplode = explode(",", $row['units']);
                    $quantity    = 0;
                    $quantityPerUnit    = $ProductItems->rProducts?->quantity_per_unit;
                    $quantityPerSubunit = $ProductItems->rProducts?->quantity_per_subunit; 
                    
                    if(isset($unitExplode[0]) && $unitExplode[0]==1){ 
                        if($quantityPerUnit === $quantityPerSubunit){
                            $quantity = ($row['quantity'] * $quantityPerUnit); 
                        } else {
                            $quantity = ($row['quantity'] * $quantityPerSubunit);
                        }
                    } else if(isset($unitExplode[0]) && $unitExplode[0]==2){
                        $quantity = $row['quantity']; 
                    } 

                    $total = ($ProductItems->unit_in_stock + $quantity); 
                    // ===============================================
                    // End Conditions for importing selected units 
                    // ===============================================

                    $input_sub['transaction_id']    = $latestID->id;
                    $input_sub['product_id']        = $row['id'];
                    $input_sub['product_items_id']  = $ProductItems->id;
                    $input_sub['quantity_changed']  = $row['quantity'];  
                    $input_sub['unit_position']     = $unitExplode[0];  
                    $input_sub['unit_id']           = $unitExplode[1];  
                    $input_sub['expires_at']        = $row['expires_at']; 
                    stock_transaction_items::create($input_sub);
                    
                    // =============================
                    // UPDATE ยอดจำนวนคงเหลือ STOCK
                    // =============================
                    $ProductItems->update([
                        "unit_in_stock" => $total
                    ]);
                }
            }
        } 

        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | Import Stock Product created successfully' . json_encode($request->all(), true));
        return redirect()->route('import-stock.index')->with('success', 'Import Stock Product created successfully');
    }

    public function destroy(string $id)
    {
        $stockTransactions     = stock_transactions::find($id);  
        $stockTransactionItems = stock_transaction_items::where('transaction_id', $id)->get();  
        if(isset($stockTransactionItems) && count($stockTransactionItems) > 0){
            foreach($stockTransactionItems as $row){
                $total = 0; $quantity = 0;
                $ProductItems = product_items::where('product_id', $row->product_id)->where('id', $row->product_items_id)->first(); 
                $quantityPerUnit    = $ProductItems->rProducts?->quantity_per_unit;
                $quantityPerSubunit = $ProductItems->rProducts?->quantity_per_subunit; 
                if($row->unit_position==1){
                    if($quantityPerUnit === $quantityPerSubunit){
                        $quantity = ($row->quantity_changed * $quantityPerUnit); 
                    } else {
                        $quantity = ($row->quantity_changed * $quantityPerSubunit);
                    }
                } else if($row->unit_position==2) {
                    $quantity = $row->quantity_changed;
                }

                $total = ($ProductItems->unit_in_stock - $quantity);
                
                // =============================
                // UPDATE ยอดจำนวนคงเหลือ STOCK
                // =============================
                $ProductItems->update([
                    "unit_in_stock" => $total
                ]);
            }
        }

        stock_transaction_items::where('transaction_id', $id)->delete();
        $stockTransactions->delete(); 
        
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | Stock Transactions & Items deleted successfully : ' . json_encode($stockTransactions, true));
        return redirect()->route('import-stock.index')->with('success', 'Stock Transactions & Items deleted successfully');
    }
}
