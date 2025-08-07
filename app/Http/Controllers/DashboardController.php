<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\RecruitmentCandidate;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $employees;
    private $transactions;

    public function __construct()
    {
        $this->middleware('auth');

        $this->employees = resolve(Employee::class);
        $this->transactions = resolve(Transaction::class);
    }

    public function index()
    {
        $employeesCount = $this->employees->count();
        $recruitmentCandidatesCount = RecruitmentCandidate::count();
        $endingEmployees = $this->employees->where('end_of_contract', '<=', Carbon::now()->addDays(30))->get();
        $checkForAttendance = $this->employees->where('is_active', 1)->get();

        // Get recent transactions instead of announcements
        $reports = $this->transactions->with(['cashier', 'paymentMethod', 'transactionDetails.product'])->orderBy('date', 'desc')->take(5)->get();

        return view('pages.dashboard', compact('reports', 'employeesCount', 'recruitmentCandidatesCount', 'endingEmployees', 'checkForAttendance'));
    }
}
