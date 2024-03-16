<?php

namespace App\Http\Controllers;

use App\Models\categcts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CategctsController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:categcts-list|categcts-create|categcts-edit|categcts-delete', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:categcts-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:categcts-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:categcts-delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    {
        $query = categcts::query(); 
        if (isset($request->keyword) && !empty($request->keyword)) {
            $query->where('category_name', 'like', "%$request->keyword%");
        }

        if (isset($request->status['id']) && !empty($request->status['id'])) {
            $status = ($request->status['id'] == -1) ? '0' : $request->status['id'];
            $query->where('status', $status);
        }

        $categcts = $query->orderBy('id', 'DESC')->get();
        return Inertia::render('BasicInfo/Categcts/Index', [
            'categcts' => $categcts
        ]);
    }

    public function create()
    {
        return Inertia::render('BasicInfo/Categcts/Create');
    }

    public function store(Request $request)
    {
        $validate = [ 
            'category_name' => 'required|string|max:255', 
            'status'        => 'required'
        ];
        request()->validate($validate);
 
        $input = $request->all(); 
        categcts::create($input); 
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | Categcts created successfully' . json_encode($request->all(), true));
        return redirect()->route('categcts.index')->with('success', 'Categcts created successfully');  
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $categcts = categcts::find($id);
        return Inertia::render('BasicInfo/Categcts/Edit', [
            'categcts' => $categcts
        ]); 
    }

    public function update(Request $request, string $id)
    {
        $validate = [ 
            'category_name' => 'required|string|max:255', 
            'status'        => 'required'
        ];
        request()->validate($validate);
        
        $categcts = categcts::find($id); 

        $input = $request->all(); 
        $categcts->update($input);
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | Categcts updated successfully' . json_encode($request->all(), true));
        return redirect()->route('categcts.index')->with('success', 'Categcts updated successfully'); 
    }

    public function destroy(string $id)
    {
        $categcts = categcts::find($id);
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | Categcts deleted successfully : ' . json_encode($categcts, true)); 
        $categcts->delete(); 
        return redirect()->route('categcts.index')->with('success', 'Categcts deleted successfully');
    }
}
