<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Services\Department\DepartmentRouter;

class DepartmentHubController extends Controller
{
    public function index()
    {
        $staff = Staff::where("user_id", auth()->id())->first();
        $cards = DepartmentRouter::cards($staff);

        return view("department.hub", compact("staff","cards"));
    }
}