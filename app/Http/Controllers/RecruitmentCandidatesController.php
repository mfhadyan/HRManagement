<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecruitmentCandidateRequest;
use App\Models\Recruitment;
use App\Models\RecruitmentCandidate;
use Illuminate\Http\Request;

class RecruitmentCandidatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRecruitmentCandidateRequest $request)
    {
        RecruitmentCandidate::create([
            'recruitment_id' => $request->input('recruitment_id'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'message' => $request->input('message'),
            'address' => $request->input('address'),
            'photo' => $request->file('photo')->store('photos', 'public'),
            'cv' => $request->file('cv')->store('cvs', 'public')
        ]);

        $name = Recruitment::whereId($request->input('recruitment_id'))->first()->position->name;

        return redirect()->back()->with('status', 'Application submitted successfully for ' . $name . ' position.');
    }
}

