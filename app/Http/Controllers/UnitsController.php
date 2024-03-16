<?php

namespace App\Http\Controllers;

use App\Models\units;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UnitsController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:units-list|units-create|units-edit|units-delete', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:units-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:units-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:units-delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    {
        $query = units::query(); 
        if (isset($request->keyword) && !empty($request->keyword)) {
            $query->where('unit_name', 'like', "%$request->keyword%");
        }

        if (isset($request->status['id']) && !empty($request->status['id'])) {
            $status = ($request->status['id'] == -1) ? '0' : $request->status['id'];
            $query->where('status', $status);
        }

        $units = $query->orderBy('id', 'DESC')->get();
        return Inertia::render('BasicInfo/Units/Index', [
            'units' => $units
        ]);
    }

    public function create()
    {
        return Inertia::render('BasicInfo/Units/Create');
    }

    public function store(Request $request)
    {
        $validate = [ 
            'unit_name' => 'required|string|max:255', 
            'status'        => 'required'
        ];
        request()->validate($validate);
 
        $input = $request->all(); 
        units::create($input); 
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | units created successfully' . json_encode($request->all(), true));
        return redirect()->route('units.index')->with('success', 'Units created successfully');  
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $units = units::find($id);
        return Inertia::render('BasicInfo/Units/Edit', [
            'units' => $units
        ]); 
    }

    public function update(Request $request, string $id)
    {
        $validate = [ 
            'unit_name' => 'required|string|max:255', 
            'status'        => 'required'
        ];
        request()->validate($validate);
        
        $units = units::find($id); 

        $input = $request->all(); 
        $units->update($input);
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | Units updated successfully' . json_encode($request->all(), true));
        return redirect()->route('units.index')->with('success', 'Units updated successfully'); 
    }

    public function destroy(string $id)
    {
        $units = units::find($id);
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | Units deleted successfully : ' . json_encode($units, true)); 
        $units->delete(); 
        return redirect()->route('units.index')->with('success', 'Units deleted successfully');
    }
}
