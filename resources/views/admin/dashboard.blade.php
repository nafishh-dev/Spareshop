@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h3 class="fw-bold mb-4">Daftar Suku Cadang</h3>
    <div class="container">
        <div style="padding: 20px;" class="d-flex justify-content-center">
            <h2>Halow admin {{Auth::user()->name;}}!</h2>
        </div>
        
    </div>

@endsection

    

