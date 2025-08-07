<?php

namespace App\Http\Controllers;

use App\Models\Recruitment;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    private $recruitments;

    public function __construct()
    {
        $this->recruitments = resolve(Recruitment::class);
    }

    public function index () 
    {
        $recruitments = $this->recruitments->get();
        return view('pages.welcome', compact('recruitments'));
    }

    public function recruitments () 
    {
        $recruitments = $this->recruitments->where('is_active', 1)->latest()->paginate(10);
        return view('pages.welcome_recruitments', compact('recruitments'));
    }

    public function recruitmentShow (Recruitment $recruitment) 
    {
        return view('pages.welcome_recruitments_show', compact('recruitment'));
    }
}
