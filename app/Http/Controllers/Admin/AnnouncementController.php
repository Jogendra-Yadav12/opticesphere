<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();

        return view('admin.announcements', compact('announcements'));
    }

    public function create()
    {
        return view('admin.editAnnouncement', ['announcement' => new Announcement]);
    }

    public function store(Request $request)
    {
        $data = $this->validateAnnouncement($request);

        Announcement::create($data);

        return redirect()->route('admin.announcement.index')->with('success', 'Announcement created.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.editAnnouncement', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $this->validateAnnouncement($request);

        $announcement->update($data);

        return redirect()->route('admin.announcement.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcement.index')->with('success', 'Announcement deleted.');
    }

    protected function validateAnnouncement(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'audience' => 'required|in:all,customers,vendors,admins',
            'is_published' => 'nullable|in:1,0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);
    }
}
