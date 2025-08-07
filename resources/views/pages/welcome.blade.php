@extends('layouts.app')

@section('nav')
    @include('components.nav')
@endsection

@section(section: 'content')
    {{-- Hero Section --}}
    <div class="container-fluid my-0 py-0">
        <div class="row hero">
            <div class="col-8 offset-1 d-flex align-items-center">
                <div>
                    <h1 class="text-white font-weight-bold text-capitalize">Warkop Laris Manis</h1>
                    <h4 class="text-white">Semanis senyumnya!</h4>
                </div>
            </div>
        </div>
    </div>
    {{-- End of Hero Section --}}

    <div class="container pb-5" id="recruitments">
        {{-- Job Recruitments --}}
        @if (count($recruitments) > 0)
            <div class="row mt-5">
                <div class="col-12 text-center mt-5">
                    <h3>Recruitments</h3>
                    <div class="line text-center"> &nbsp;</div>
                </div>
            </div>
            <div class="row">
                @foreach ($recruitments as $recruitment)
                    <div class="col-lg-4 col-sm-12 mb-2">
                        <div class="card mx-3 h-100">
                            @if ($recruitment->attachment !== null)
                                <img src="{{ $recruitment->attachment }}" class="card-img-top" alt="recruitment">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $recruitment->title }}</h5>
                                <p class="card-text">{{ $recruitment->description }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('welcome.recruitments.show', ['recruitment' => $recruitment->id]) }}"
                                    class="btn btn-primary">Read more</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row mt-2 float-right mr-1">
                <div class="col-12">
                    <a href="{{ route('welcome.recruitments') }}" class="btn btn-primary">See older job vacancies</a>
                </div>
            </div>
        @endif
        {{-- End of Job Recruitments --}}
    </div>
@endsection
