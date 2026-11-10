<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('admin.announcements.index', ['announcements' => Announcement::latest()->get()]);
    }

    public function store(Request $request)
    {
        Announcement::create($request->validate(['title' => 'required|string|max:150', 'body' => 'required|string|max:1000']) + ['is_active' => true]);
        return back()->with('success', 'Annonce publiée.');
    }

    public function update(Announcement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);
        return back()->with('success', 'Annonce mise à jour.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Annonce supprimée.');
    }
}
