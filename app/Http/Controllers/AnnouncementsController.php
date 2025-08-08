<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementsController extends Controller
{
    private $announcements;

    public function __construct()
    {
        $this->middleware('auth');
        $this->announcements = resolve(Announcement::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $announcements = $this->announcements
            ->with(['department', 'createdBy'])
            ->forUser(Auth::user())
            ->latest()
            ->paginate(10);

        return view('pages.announcements', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Only administrators can create announcements
        if (Auth::user()->role_id != 1) {
            return redirect()->route('announcements')->with('error', 'Access denied. Only administrators can create announcements.');
        }

        $departments = Department::all();
        return view('pages.announcements_create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Only administrators can create announcements
        if (Auth::user()->role_id != 1) {
            return redirect()->route('announcements')->with('error', 'Access denied. Only administrators can create announcements.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'department_id' => $request->department_id,
            'created_by' => Auth::user()->employee->id
        ];

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('announcements', 'public');
        }

        $this->announcements->create($data);

        return redirect()->route('announcements')->with('status', 'Announcement created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        // Check if user has access to this announcement
        if (Auth::user()->role_id != 1) {
            $user = Auth::user();
            if ($announcement->department_id && $announcement->department_id != $user->employee->department_id) {
                return redirect()->route('announcements')->with('error', 'Access denied. You can only view announcements for your department.');
            }
        }

        $announcement->load(['department', 'createdBy']);
        return view('pages.announcements_show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        // Only administrators can edit announcements
        if (Auth::user()->role_id != 1) {
            return redirect()->route('announcements')->with('error', 'Access denied. Only administrators can edit announcements.');
        }

        $departments = Department::all();
        return view('pages.announcements_edit', compact('announcement', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {
        // Only administrators can update announcements
        if (Auth::user()->role_id != 1) {
            return redirect()->route('announcements')->with('error', 'Access denied. Only administrators can update announcements.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'department_id' => $request->department_id
        ];

        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($announcement->attachment) {
                \Storage::disk('public')->delete($announcement->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('announcements', 'public');
        }

        $announcement->update($data);

        return redirect()->route('announcements')->with('status', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        // Only administrators can delete announcements
        if (Auth::user()->role_id != 1) {
            return redirect()->route('announcements')->with('error', 'Access denied. Only administrators can delete announcements.');
        }

        // Delete attachment if exists
        if ($announcement->attachment) {
            \Storage::disk('public')->delete($announcement->attachment);
        }

        $announcement->delete();

        return redirect()->route('announcements')->with('status', 'Announcement deleted successfully.');
    }

    /**
     * Print announcements
     *
     * @return \Illuminate\Http\Response
     */
    public function print()
    {
        $announcements = $this->announcements
            ->with(['department', 'createdBy'])
            ->forUser(Auth::user())
            ->latest()
            ->get();

        return view('pages.announcements_print', compact('announcements'));
    }
}
