@extends('layouts.template')

@section('content')

    <div class="container" style="margin-left: 12rem">

        <div class="mt-4">
            @if (Session::get('failed'))
            <div class="alert alert-danger" >{{ Session::get('failed') }}</div>
            @endif
            
            <h3>Dashboard</h3>
        </div>

        @if (Auth::user()->role == 'staff')
            <div class="container d-flex">
                <div class="card p-4 m-3" style="width: 700px; box-shadow: 0 0 10px ">    
                    <h5>Surat Keluar</h5><br>
                    <h2>{{ $allLetters }}</h2>
                </div>
                <div class="card p-4 m-3" style="width: 700px; box-shadow: 0 0 10px ">
                    <h5>Klasifikasi Surat</h5><br>
                    <h2>{{ $allClassificate }}</h2>
                </div><br>
            </div>
            <div class="container d-flex">
                <div class="card p-4 m-3" style="width: 700px; box-shadow: 0 0 10px ">
                    <h5>Staff Tata Usaha</h5><br>
                    <h2>{{ $usersStaff }}</h2>
                </div>
                <div class="card p-4 m-3" style="width: 700px; box-shadow: 0 0 10px ">
                    <h5>Guru</h5><br>
                    <h2>{{ $usersGuru }}</h2>
                </div>
            </div>
        @endif
        @if (Auth::user()->role == 'guru')
            <div class="card p-4 m-3" style="width: 700px; box-shadow: 0 0 10px ">    
                <h5>Surat Masuk</h5><br>
                <h2>{{ $allLetters }}</h2>
            </div>
        @endif
    </div>

        @endsection