<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceTime;
use App\Models\AttendanceType;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendancesController extends Controller
{
    private $attendances;
    private $attendanceTimes;
    private $attendanceTypes;

    public function __construct()
    {
        $this->middleware('auth');

        $this->attendances = resolve(Attendance::class);
        $this->attendanceTimes = resolve(AttendanceTime::class)->get();
        $this->attendanceTypes = resolve(AttendanceType::class)->get();
    }

    public function index()
    {
        $attendances = $this->attendances->paginate();
        return view('pages.attendances', compact('attendances'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $inId = $this->getId($this->attendanceTimes, "IN");
        $outId = $this->getId($this->attendanceTimes, "OUT");

        $now = Carbon::now('Asia/Jakarta');
        $checkInTime = Carbon::createFromTime(17, 45, 0, 'Asia/Jakarta');
        $checkOutTime = Carbon::createFromTime(19, 0, 0, 'Asia/Jakarta');

        $type = "";
        $time = "";
        $logStatus = "";
        $logDetails = "";

        // Sakit
        if ($request->sick == "1") {
            $type = "SICK";
            $time = "OTHER";
            $logStatus = "sick";
            $logDetails = "Employee marked as sick";
        } else {
            // Cek apakah sudah absen hari ini
            $checkForAttendance = Attendance::whereDate('created_at', Carbon::today('Asia/Jakarta'))
                ->where('employee_id', auth()->user()->employee->id)
                ->whereIn('attendance_time_id', [$inId, $outId])
                ->get();

            $hasCheckedIn = $checkForAttendance->where('attendance_time_id', $inId)->first();
            $hasCheckedOut = $checkForAttendance->where('attendance_time_id', $outId)->first();

            if (!$hasCheckedIn) {
                // Proses Check-in
                $time = "IN";

                if ($now < $checkInTime) {
                    return redirect()->route('attendances')->with('status', 'Belum waktunya check-in. Tunggu sampai jam 07:00.');
                }

                // Toleransi keterlambatan 15 menit
                $lateLimit = $checkInTime->copy()->addMinutes(15);
                $type = $now <= $lateLimit ? "ONTIME" : "LATE";
                $logStatus = "present";
                $logDetails = "Check-in at " . $now->format('H:i:s') . " - Status: " . $type;
            } elseif (!$hasCheckedOut) {
                // Proses Check-out
                $time = "OUT";

                if ($now < $checkOutTime) {
                    return redirect()->route('attendances')->with('status', 'Belum waktunya check-out. Tunggu sampai jam 17:00.');
                }

                // Toleransi lembur 30 menit
                $overtimeStart = $checkOutTime->copy()->addMinutes(30);
                $type = $now <= $overtimeStart ? "ONTIME" : "OVERTIME";
                $logStatus = "present";
                $logDetails = "Check-out at " . $now->format('H:i:s') . " - Status: " . $type;
            } else {
                return redirect()->route('attendances')->with('status', 'Anda sudah check-in dan check-out hari ini.');
            }
        }

        $attendance = $this->attendances->create([
            'employee_id' => auth()->user()->employee->id,
            'attendance_time_id' => $this->getId($this->attendanceTimes, $time),
            'attendance_type_id' => $this->getId($this->attendanceTypes, $type),
            'message' => $request->input('message'),
        ]);

        // Log the attendance event
        Log::create([
            'employee_id' => auth()->user()->employee->id,
            'event_type' => 'attendance',
            'status' => $logStatus,
            'description' => auth()->user()->employee->name . " - " . ucfirst($logStatus) . " (" . $type . ")",
            'details' => $logDetails
        ]);

        return redirect()->route('attendances')->with('status', 'Absensi berhasil disimpan.');
    }

    public function show(Attendance $attendance)
    { /* optional */
    }

    public function edit(Attendance $attendance)
    { /* optional */
    }

    public function update(Request $request, Attendance $attendance)
    { /* optional */
    }

    public function destroy(Attendance $attendance)
    { /* optional */
    }

    public function print()
    {
        $attendances = Attendance::all();
        return view('pages.attendances_print', compact('attendances'));
    }

    public function getId($collection, $type)
    {
        return $collection->firstWhere('name', $type)?->id;
    }
}