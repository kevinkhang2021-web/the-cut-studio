<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Product;
use App\Models\Review;
use App\Models\Tournament;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'announcements' => Announcement::where('is_active', true)->latest()->take(3)->get(),
            'consoles'      => Product::active()->where('category', 'console')->take(4)->get(),
            'games'         => Product::active()->where('category', 'jeu')->inRandomOrder()->take(6)->get(),
            'tournaments'   => Tournament::where('status', 'open')->where('starts_at', '>=', now())->orderBy('starts_at')->take(3)->get(),
            'reviews'       => Review::with('user')->where('type', 'review')->latest()->take(3)->get(),
        ]);
    }
}
