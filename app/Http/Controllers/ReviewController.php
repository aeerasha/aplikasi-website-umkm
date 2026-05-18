<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CUSTOMER
    |--------------------------------------------------------------------------
    */

    public function customerIndex()
    {
        $reviews = Review::latest()->get();

        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('reviews.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required',
        ]);

        Review::create([
            'customer_name' => $request->is_anonymous
                ? null
                : $request->customer_name,

            'is_anonymous' => $request->has('is_anonymous'),

            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()
            ->route('reviews.index')
            ->with('success', 'Review berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | OWNER
    |--------------------------------------------------------------------------
    */

    public function ownerIndex()
    {
        $reviews = Review::latest()->get();

        return view('owner.reviews.index', compact('reviews'));
    }
}