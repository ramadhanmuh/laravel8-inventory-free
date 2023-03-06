@extends('layouts.dashboard')

@section('title', "$application->name - Pengguna - $item->name")

@section('description', 'Halaman yang berisi informasi detail Pengguna.')

@section('route_name', 'Detail Pengguna')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="m-0 text-dark">
                {{ $item->name }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4 col-lg-3">
                    <b>Username <span class="d-md-none">:</span></b>
                </div>
                <div class="col-md-8 col-lg-9">
                    <b class="d-none d-md-inline">:</b> {{ $item->username }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 col-lg-3">
                    <b>Email <span class="d-md-none">:</span></b>
                </div>
                <div class="col-md-8 col-lg-9">
                    <b class="d-none d-md-inline">:</b> {{ $item->email }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 col-lg-3">
                    <b>Jenis <span class="d-md-none">:</span></b>
                </div>
                <div class="col-md-8 col-lg-9">
                    <b class="d-none d-md-inline">:</b> {{ $item->role }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 col-lg-3">
                    <b>Tanggal Dibuat <span class="d-md-none">:</span></b>
                </div>
                <div class="col-md-8 col-lg-9">
                    <b class="d-none d-md-inline">:</b>
                    <span class="unix-column">
                        {{ $item->created_at }}
                    </span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 col-lg-3">
                    <b>Tanggal Diubah <span class="d-md-none">:</span></b>
                </div>
                <div class="col-md-8 col-lg-9">
                    <b class="d-none d-md-inline">:</b>
                    <span class="unix-column">
                        {{ $item->created_at === $item->updated_at ? '-' : $item->updated_at }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </div>
@endsection