<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolSettingsController extends Controller
{
 public function edit()
 {
 $school = School::first() ?? new School();
 return view('admin.settings.school', compact('school'));
 }

 public function update(Request $request)
 {
 $data = $request->validate([
 'name' => 'required|string|max:150',
 'code' => 'required|string|max:50',
 'phone' => 'required|string|max:30',
 'email' => 'nullable|email',
 'address' => 'nullable|string|max:255',
 'has_primary' => 'nullable|boolean',
 'has_secondary' => 'nullable|boolean',
 'has_alevel' => 'nullable|boolean',
 'has_nursery' => 'nullable|boolean',
 'has_kg' => 'nullable|boolean',
 'has_pre_unit' => 'nullable|boolean',
 ]);

 $data['has_primary'] = $request->boolean('has_primary');
 $data['has_secondary'] = $request->boolean('has_secondary');
 $data['has_alevel'] = $request->boolean('has_alevel');
 $data['has_nursery'] = $request->boolean('has_nursery');
 $data['has_kg'] = $request->boolean('has_kg');
 $data['has_pre_unit'] = $request->boolean('has_pre_unit');

 $school = School::first();
 if ($school) {
 $school->update($data);
 } else {
 School::create($data);
 }

 return back()->with('success', 'School settings updated.');
 }
}