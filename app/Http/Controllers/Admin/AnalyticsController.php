<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Reports\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
 public function index(Request $request)
 {
 [$from, $to] = $this->range($request);

 $overview = AnalyticsService::overview($from, $to);

 return view("admin.analytics.index", compact("from","to","overview"));
 }

 public function academic(Request $request)
 {
 $data = AnalyticsService::academic();
 return view("admin.analytics.academic", compact("data"));
 }

 public function attendance(Request $request)
 {
 [$from, $to] = $this->range($request);
 $data = AnalyticsService::attendance($from, $to);
 return view("admin.analytics.attendance", compact("from","to","data"));
 }

 public function financial(Request $request)
 {
 [$from, $to] = $this->range($request);
 $data = AnalyticsService::financial($from, $to);
 return view("admin.analytics.financial", compact("from","to","data"));
 }

 public function sms(Request $request)
 {
 [$from, $to] = $this->range($request);
 $data = AnalyticsService::sms($from, $to);
 return view("admin.analytics.sms", compact("from","to","data"));
 }

 public function staff()
 {
 $data = AnalyticsService::staff();
 return view("admin.analytics.staff", compact("data"));
 }

 /**
 * Compute from/to range from request.
 */
 protected function range(Request $request): array
 {
 $from = $request->filled("from")
 ? \Carbon\Carbon::parse($request->from)->startOfDay()
 : now()->startOfMonth();

 $to = $request->filled("to")
 ? \Carbon\Carbon::parse($request->to)->endOfDay()
 : now()->endOfMonth();

 return [$from, $to];
 }
}