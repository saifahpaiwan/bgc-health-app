<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DispensingMedicineController extends Controller
{
    public function create()
    {
        return Inertia::render('DispensingMedicine/Create');
    }
}
