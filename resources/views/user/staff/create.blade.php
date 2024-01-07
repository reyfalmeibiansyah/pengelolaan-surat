@extends('layouts.template')

@section('content')

<div class="container" style="margin-left: 12rem">

<h3 class="mt-4">Tambah Data Staff</h3>
<div class="d-flex mb-3">
</div>
<a href="{{ route('user.staff.data') }}" class="btn btn-warning" style="float: right; width: 100px; margin-top: -2rem;">Kembali</a><br><br>

    <form action="{{ route('user.staff.store') }}" class="container bg-light p-5 " method="post">
        @csrf
        @if ($errors->any())
        <ul class="alert alert-danger p-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        
        @endif
        @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        
            <div class="mb-3 row">
                <label for="name" class="col-sm-2 col-form-label">Nama : </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="email" class="col-sm-2 col-form-label">Email : </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="email" name="email">
                </div>
            </div>
            <input type="hidden" name="password">
            <input type="hidden" name="role" value="staff">
            <button type="submit" class="btn btn-primary" style="width: 20%; float:right;">Tambah Data</button>
        </form>
</div>
</div>

@endsection