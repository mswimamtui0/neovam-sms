<?php

namespace App\Services\Department;

use App\Models\Staff;

class DepartmentRouter
{
 /**
 * Priority order — highest first.
 * If a staff has multiple roles, the highest priority wins for auto-redirect.
 */
 public const PRIORITY = [
 "administration",
 "academic",
 "finance",
 "health",
 "discipline",
 "security",
 "library",
 "boarding",
 "feeding",
 "environment",
 "guidance",
 "sports",
 "duty",
 "class_teacher",
 ];

 /**
 * Route map — department role => route name.
 */
 public const ROUTES = [
 "health" => "department.health",
 "discipline" => "department.discipline",
 "sports" => "department.sports",
 "boarding" => "department.boarding",
 "feeding" => "department.feeding",
 "library" => "department.library",
 "guidance" => "department.guidance",
 "environment" => "department.environment",
 "security" => "department.security",
 "duty" => "department.duty-redirect",
 "class_teacher"=> "department.class-teacher-redirect",
 "finance" => "department.finance-redirect",
 "academic" => "department.academic-redirect",
 "administration"=> "department.admin-redirect",
 ];

 /**
 * Determine where a staff should land after login.
 * Returns a route name.
 */
 public static function landingRoute(?Staff $staff): string
 {
 if (!$staff) {
 return "dashboard";
 }

 $roles = $staff->roleList();

 if (empty($roles)) {
 return "dashboard";
 }

 // If more than one role, land on the hub
 if (count($roles) > 1) {
 return "department.hub";
 }

 // Single role — go directly to that department
 $role = $roles[0];
 return self::ROUTES[$role] ?? "dashboard";
 }

 /**
 * Get the highest priority role for a staff (used in some flows).
 */
 public static function primaryRole(?Staff $staff): ?string
 {
 if (!$staff) return null;

 $roles = $staff->roleList();
 if (empty($roles)) return null;

 foreach (self::PRIORITY as $p) {
 if (in_array($p, $roles, true)) return $p;
 }

 return $roles[0];
 }

 /**
 * All department cards for the hub page.
 */
 public static function cards(?Staff $staff): array
 {
 if (!$staff) return [];

 $roles = $staff->roleList();
 $cards = [];

 $meta = [
 "health" => ["title" => "Health", "desc" => "Sick students, first aid, hygiene", "color" => "red", "route" => "department.health"],
 "discipline" => ["title" => "Discipline", "desc" => "Behavior, warnings, detentions", "color" => "yellow", "route" => "department.discipline"],
 "sports" => ["title" => "Sports", "desc" => "Teams, matches, training", "color" => "green", "route" => "department.sports"],
 "boarding" => ["title" => "Boarding", "desc" => "Dorms, night duty, roll calls", "color" => "purple", "route" => "department.boarding"],
 "feeding" => ["title" => "Feeding", "desc" => "Meals, hygiene, menu", "color" => "orange", "route" => "department.feeding"],
 "library" => ["" => "", "title" => "Library", "desc" => "Books, loans, returns", "color" => "blue", "route" => "department.library"],
 "guidance" => ["title" => "Guidance", "desc" => "Counseling, career, welfare", "color" => "indigo", "route" => "department.guidance"],
 "environment" => ["title" => "Environment", "desc" => "Cleanliness, tree planting", "color" => "teal", "route" => "department.environment"],
 "security" => ["title" => "Security", "desc" => "Patrol, gate, drills", "color" => "gray", "route" => "department.security"],
 "duty" => ["title" => "Teacher on Duty", "desc" => "Duty roster, supervision", "color" => "yellow", "route" => "duty.dashboard"],
 "class_teacher"=> ["title" => "Class Teacher", "desc" => "My class, discipline, welfare", "color" => "green", "route" => "class-teacher.dashboard"],
 "finance" => ["title" => "Finance", "desc" => "Fees, invoices, payments", "color" => "blue", "route" => "admin.dashboard"],
 "academic" => ["title" => "Academic", "desc" => "Teaching, lesson plans, marks", "color" => "blue", "route" => "teacher.dashboard"],
 "administration"=>["title" => "Administration", "desc" => "School-wide tools", "color" => "blue", "route" => "admin.dashboard"],
 ];

 foreach ($roles as $r) {
 if (isset($meta[$r])) {
 $cards[] = array_merge(["key" => $r], $meta[$r]);
 }
 }

 return $cards;
 }
}