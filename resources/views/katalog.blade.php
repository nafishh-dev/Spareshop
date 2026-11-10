@extends('layouts.app')

@section('title', 'Katalog Suku Cadang')

@section('content')
    <h3 class="fw-bold mb-4">Daftar Suku Cadang</h3>
    <div class="container">
        <div style="padding: 20px;" class="d-flex justify-content-center">
            <h2>Ini katalog ya {{Auth::user()->name;}} ~</h2>
        </div>
        
    </div>

@endsection