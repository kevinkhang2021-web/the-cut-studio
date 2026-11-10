<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with('user')
            ->when($request->type, fn ($q, $t) => $q->where('type', $t))
            ->latest()->paginate(15)->withQueryString();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function update(Request $request, Review $review)
    {
        $review->update($request->validate(['response' => 'nullable|string|max:1000']));
        return back()->with('success', 'Réponse enregistrée.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Message supprimé.');
    }
}
