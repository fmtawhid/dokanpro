<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Repositories\AttendanceRepository;
use App\Repositories\EmployeeRepository;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = AttendanceRepository::getAll();
        $employees = EmployeeRepository::getAll();
        return view('attendance.index', compact('attendances', 'employees'));
    }

    public function store(AttendanceRequest $request)
    {
        AttendanceRepository::storeByRequest($request);
        return back()->with('success', 'Attendance created successfully');
    }

    public function delete(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Attendance deleted successfully');
    }
}
