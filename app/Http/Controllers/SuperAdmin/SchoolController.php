<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\School;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SchoolController extends Controller
{
 /**
 * Overview of all schools in the network.
 */
 public function index()
 {
 $schools = School::with("parent")->withCount([
 "students","staff","users","classrooms"
 ])->orderByDesc("is_active")->orderBy("name")->paginate(30);

 $stats = [
 "total_schools" => School::count(),
 "active_schools" => School::where("is_active", true)->count(),
 "main_schools" => School::whereNull("parent_school_id")->count(),
 "branches" => School::whereNotNull("parent_school_id")->count(),
 "total_students" => Student::withoutGlobalScopes()->count(),
 "total_staff" => Staff::withoutGlobalScopes()->count(),
 "total_users" => User::count(),
 ];

 return view("super-admin.schools.index", compact("schools","stats"));
 }

 /**
 * Dashboard for the super admin.
 */
 public function dashboard()
 {
 $schools = School::withCount(["students","staff"])->orderBy("name")->get();

 $totalStudents = Student::withoutGlobalScopes()->count();
 $totalStaff = Staff::withoutGlobalScopes()->count();
 $totalRevenue = Invoice::withoutGlobalScopes()->sum("amount_paid");
 $totalSmsUnits = School::sum("sms_balance_units");

 return view("super-admin.dashboard", compact(
 "schools","totalStudents","totalStaff","totalRevenue","totalSmsUnits"
 ));
 }

 public function create()
 {
 $parents = School::whereNull("parent_school_id")->orderBy("name")->get();
 return view("super-admin.schools.create", compact("parents"));
 }

 public function store(Request $request)
 {
 $data = $request->validate([
 "name" => "required|string|max:150",
 "group_name" => "nullable|string|max:150",
 "code" => "required|string|max:50|unique:schools,code",
 "branch_code" => "nullable|string|max:50",
 "subdomain" => "nullable|string|max:50|unique:schools,subdomain|regex:/^[a-z0-9\-]+$/",
 "phone" => "required|string|max:30",
 "email" => "nullable|email",
 "address" => "nullable|string|max:255",
 "has_primary" => "nullable|boolean",
 "has_secondary" => "nullable|boolean",
 "has_alevel" => "nullable|boolean",
 "parent_school_id" => "nullable|exists:schools,id",
 "subscription_plan" => "required|in:basic,standard,premium,enterprise",
 "subscription_expires_at" => "nullable|date",
 "sms_balance_units" => "nullable|integer|min:0",
 "admin_name" => "required|string|max:150",
 "admin_email" => "required|email|unique:users,email",
 "admin_password" => "required|string|min:8",
 ]);

 // Create school
 $school = School::create([
 "name" => $data["name"],
 "group_name" => $data["group_name"] ?? null,
 "code" => $data["code"],
 "branch_code" => $data["branch_code"] ?? null,
 "subdomain" => $data["subdomain"] ?? null,
 "phone" => $data["phone"],
 "email" => $data["email"] ?? null,
 "address" => $data["address"] ?? null,
 "has_primary" => $request->boolean("has_primary"),
 "has_secondary" => $request->boolean("has_secondary"),
 "has_alevel" => $request->boolean("has_alevel"),
 "parent_school_id" => $data["parent_school_id"] ?? null,
 "subscription_plan" => $data["subscription_plan"],
 "subscription_expires_at" => $data["subscription_expires_at"] ?? null,
 "sms_balance_units" => $data["sms_balance_units"] ?? 1000,
 "is_active" => true,
 ]);

 // Create initial admin user for the school
 $admin = User::create([
 "name" => $data["admin_name"],
 "email" => $data["admin_email"],
 "password" => Hash::make($data["admin_password"]),
 "school_id" => $school->id,
 ]);
 $admin->assignRole("admin");

 return redirect()->route("super-admin.schools.index")
 ->with("success", "School created: {$school->name}. Admin: {$admin->email}");
 }

 public function show(School $school)
 {
 $school->loadCount(["students","staff","users","classrooms"]);

 $stats = [
 "students" => Student::withoutGlobalScopes()->where("school_id", $school->id)->count(),
 "staff" => Staff::withoutGlobalScopes()->where("school_id", $school->id)->count(),
 "users" => User::where("school_id", $school->id)->count(),
 "classrooms" => $school->classrooms()->count(),
 "invoiced" => (float) Invoice::withoutGlobalScopes()->where("school_id", $school->id)->sum("amount"),
 "collected" => (float) Invoice::withoutGlobalScopes()->where("school_id", $school->id)->sum("amount_paid"),
 ];

 $admins = User::where("school_id", $school->id)
 ->whereHas("roles", fn($q) => $q->whereIn("name", ["admin","head_of_school"]))
 ->get();

 return view("super-admin.schools.show", compact("school","stats","admins"));
 }

 public function edit(School $school)
 {
 $parents = School::whereNull("parent_school_id")
 ->where("id", "!=", $school->id)
 ->orderBy("name")->get();

 return view("super-admin.schools.edit", compact("school","parents"));
 }

 public function update(Request $request, School $school)
 {
 $data = $request->validate([
 "name" => "required|string|max:150",
 "group_name" => "nullable|string|max:150",
 "code" => "required|string|max:50|unique:schools,code," . $school->id,
 "branch_code" => "nullable|string|max:50",
 "subdomain" => "nullable|string|max:50|unique:schools,subdomain," . $school->id . "|regex:/^[a-z0-9\-]+$/",
 "phone" => "required|string|max:30",
 "email" => "nullable|email",
 "address" => "nullable|string|max:255",
 "has_primary" => "nullable|boolean",
 "has_secondary" => "nullable|boolean",
 "has_alevel" => "nullable|boolean",
 "is_active" => "nullable|boolean",
 "parent_school_id" => "nullable|exists:schools,id",
 "subscription_plan" => "required|in:basic,standard,premium,enterprise",
 "subscription_expires_at" => "nullable|date",
 "sms_balance_units" => "nullable|integer|min:0",
 ]);

 $data["has_primary"] = $request->boolean("has_primary");
 $data["has_secondary"] = $request->boolean("has_secondary");
 $data["has_alevel"] = $request->boolean("has_alevel");
 $data["is_active"] = $request->boolean("is_active");

 $school->update($data);

 return redirect()->route("super-admin.schools.show", $school)
 ->with("success", "School updated.");
 }

 public function destroy(School $school)
 {
 // Only allow deactivate — never hard delete
 $school->update(["is_active" => false]);

 return redirect()->route("super-admin.schools.index")
 ->with("success", "School deactivated: {$school->name}");
 }

 /**
 * Top up SMS balance for a school.
 */
 public function topupSms(Request $request, School $school)
 {
 $data = $request->validate([
 "units" => "required|integer|min:1",
 ]);

 $school->increment("sms_balance_units", $data["units"]);

 return back()->with("success", "Added {$data["units"]} SMS units to {$school->name}.");
 }

 /**
 * Reset admin password for a school.
 */
 public function resetAdmin(Request $request, School $school, User $user)
 {
 if ($user->school_id !== $school->id) {
 return back()->with("error", "User does not belong to this school.");
 }

 $data = $request->validate([
 "new_password" => "required|string|min:8",
 ]);

 $user->update(["password" => Hash::make($data["new_password"])]);

 return back()->with("success", "Password reset for {$user->email}.");
 }

 /**
 * Compare schools side by side.
 */
 public function compare()
 {
 $schools = School::withCount(["students","staff","users","classrooms"])
 ->orderBy("name")->get();

 return view("super-admin.schools.compare", compact("schools"));
 }
}