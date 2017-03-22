@extends('layout')

@section('title', 'Wellcome to the Auth Service')

@section('content')

    <div class="title m-b-md">
        Profile
    </div>

    <div>
        <div>
            <img src="{{ $user->picture_url }}" alt="avatar"/>
        </div>

        <div style="margin-top: 40px; margin-bottom: 20px;">

                <span>{{ $user->surname }}, {{ $user->firstname }}</span><br/>
                <span>{{ $user->email }}</span><br/>
                <span>{{ $user->birthday }}</span><br/>

        </div>
    </div>

@endsection