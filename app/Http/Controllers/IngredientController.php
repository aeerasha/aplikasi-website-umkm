<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredients = Ingredient::latest()->get();

        return view('ingredients.index', compact('ingredients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ingredients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'stock' => 'required|integer',
            'unit' => 'required',
        ]);

        Ingredient::create($validated);

        return redirect()
            ->route('ingredients.index')
            ->with('success', 'Bahan baku berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingredient $ingredient)
    {
        return view('ingredients.edit', compact('ingredient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required',
            'stock' => 'required|integer',
            'unit' => 'required',
        ]);

        $ingredient->update($validated);

        return redirect()
            ->route('ingredients.index')
            ->with('success', 'Stok berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return redirect()
            ->route('ingredients.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function increaseStock(Ingredient $ingredient)
    {
        $ingredient->increment('stock');

        return back()->with('success', 'Stok berhasil ditambah');
    }

    public function decreaseStock(Ingredient $ingredient)
    {
        if ($ingredient->stock > 0) {
            $ingredient->decrement('stock');
        }

        return back()->with('success', 'Stok berhasil dikurangi');
    }
}