<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScoreCategoryRequest;
use App\Models\ScoreCategory;
use Illuminate\Http\Request;

class ScoreCategoriesController extends Controller
{
    private $scoreCategories;

    public function __construct()
    {
        $this->middleware('auth');

        $this->scoreCategories = resolve(ScoreCategory::class);    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $scoreCategories = $this->scoreCategories->paginate();
        return view('pages.score-categories', compact('scoreCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.score-categories_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScoreCategoryRequest $request)
    {
        ScoreCategory::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('score-categories.index');
    }
}