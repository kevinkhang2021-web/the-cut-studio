<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('user')->where('type', 'review')->latest()->paginate(10);
        $avg = Review::where('type', 'review')->avg('rating');
        return view('reviews.index', compact('reviews', 'avg'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'    => 'required|in:review,complaint',
            'rating'  => 'required_if:type,review|nullable|integer|between:1,5',
            'message' => 'required|string|min:5|max:1000',
        ]);
        $request->user()->reviews()->create($data);
        return back()->with('success', $data['type'] === 'review'
            ? 'Merci pour votre avis !'
            : 'Votre réclamation a été transmise au gérant. Vous verrez sa réponse dans votre compte.');
    }
}
