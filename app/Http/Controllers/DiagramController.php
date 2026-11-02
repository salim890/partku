<?php

namespace App\Http\Controllers;

use App\Models\Product;

class DiagramController extends Controller
{
    public function index()
    {
        $motors = ['Beat', 'Vario', 'PCX', 'Scoopy', 'Genio', 'ADV'];
        return view('diagram-index', compact('motors'));
    }

    public function show($motor, $category)
    {
        $products = Product::where('category', $category)
            ->where('motor_models', 'like', '%' . $motor . '%')
            ->orderBy('part_number')
            ->get();

        return view('diagram-show', compact('motor', 'category', 'products'));
    }
}