@extends('layouts.dashboard')

@section('title', "$application->name - Merek - $item->part_number")

@section('description', 'Halaman yang berisi informasi detail barang.')

@section('route_name', 'Detail Barang')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="m-0 text-dark">
                {{ $item->part_number }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4 col-lg-3">
                    <b>Kategori <span class="d-md-none">:</span></b>
                </div>
                <div class="col-md-8 col-lg-9">
                    <b class="d-none d-md-inline">:</b> {{ $item->category->name }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 col-lg-3">
                    <b>Merek <span class="d-md-none">:</span></b>
                </div>
                <div class="col-md-8 col-lg-9">
                    <b class="d-none d-md-inline">:</b> {{ $item->brand->name }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 col-lg-3">
                    <b>Satuan Barang <span class="d-md-none">:</span></b>
                </div>
                <div class="col-md-8 col-lg-9">
                    <b class="d-none d-md-inline">:</b> {{ $item->unitOfMeasurement->short_name }}
                    ({{ $item->unitOfMeasurement->full_name }})
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 col-lg-3">
                    <b>Deskripsi <span class="d-md-none">:</span></b>
                </div>
                <div class="col-md-8 col-lg-9">
                    <b class="d-none d-md-inline">:</b> {{ $item->description }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 col-lg-3">
                    <b>Harga <span class="d-md-none">:</span></b>
                </div>
                <div class="col-md-8 col-lg-9">
                    <b class="d-none d-md-inline">:</b>
                    {{ number_format($item->price, 0, ',', '.') }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-3">
                    <b>Gambar <span class="d-md-none">:</span></b>
                </div>
                <div class="col-md-8 col-lg-9">
                    <b class="d-none d-md-inline">:</b>
                    @if (empty($item->image))
                        -
                    @else
                        <a href="{{ url("items/$item->id/image") }}">
                            <img src="{{ url("items/$item->id/image")  }}" alt="Gambar Barang" width="200" class="img-thumbnail">
                        </a>
                    @endif
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