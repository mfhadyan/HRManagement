<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecruitmentRequest;
use App\Models\Position;
use App\Models\Recruitment;
use Illuminate\Http\Request;

class RecruitmentsController extends Controller
{
    private $recruitments;

    public function __construct()
    {
        $this->middleware('auth');  
        
        $this->recruitments = resolve(Recruitment::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recruitments = $this->recruitments->paginate();
        return view('pages.recruitments', compact('recruitments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $positions = Position::where('open_for_recruitment', 1)->latest()->get();
        return view('pages.recruitments_create', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRecruitmentRequest $request)
    {
        $createArray = [
            'position_id' => $request->input('position_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ];

        if($request->has('attachment') && $request->attachment !== null) {
            $createArray["attachment"] = $request->file('attachment')->store('attachments', 'public');
        }

        $this->recruitments->create($createArray);

        return redirect()->route('recruitments.index');
    }
}