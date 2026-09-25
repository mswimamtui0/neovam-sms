<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\School;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
 public function index()
 {
 $announcements = Announcement::with("staff")->latest()->paginate(20);
 return view("admin.announcements.index", compact("announcements"));
 }

 public function create()
 {
 return view("admin.announcements.create");
 }

 public function store(Request $request)
 {
 $data = $request->validate([
 "title" => "required|string|max:200",
 "body" => "required|string",
 "audience" => "required|in:all,teachers,parents,students",
 "is_pinned" => "nullable|boolean",
 "publish_date" => "required|date",
 "expiry_date" => "nullable|date|after_or_equal:publish_date",
 ]);

 $data["school_id"] = School::first()?->id;
 $data["staff_id"] = \App\Models\Staff::where("user_id", auth()->id())->first()?->id;
 $data["is_pinned"] = $request->boolean("is_pinned");

 Announcement::create($data);

 return redirect()->route("admin.announcements.index")->with("success","Announcement published.");
 }

 public function destroy(Announcement $announcement)
 {
 $announcement->delete();
 return back()->with("success","Announcement removed.");
 }
}