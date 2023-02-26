@extends('layouts.dashboard')

@section('title', "$application->name - Barang - Tambah")

@section('description', 'Halaman yang berisi formulir untuk membuat data barang.')

@section('route_name', 'Tambah Barang')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="m-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card-header bg-white">
            Isi Formulir
        </div>
        <div class="card-body">
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                @method('POST')
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="part_number">Nomor Unik</label>
                            <input type="text" class="form-control" id="part_number" value="{{ old('part_number') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="brand_id">Merek</label>
                            <select name="brand_id" id="brand_id" class="form-control">
                                <option value="">-- Pilih Merek --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unit_of_measurement_id">Satuan Barang</label>
                            <select name="unit_of_measurement_id" id="unit_of_measurement_id" class="form-control">
                                <option value="">-- Pilih Satuan Barang --</option>
                                @foreach ($unit_of_measurements as $unit_of_measurement)
                                    <option value="{{ $unit_of_measurement->id }}"
                                        {{ old('unit_of_measurement_id') == $unit_of_measurement->id ? 'selected' : '' }}>
                                        {{ $unit_of_measurement->short_name }}
                                        ({{ $unit_of_measurement->full_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="number" class="form-control" id="price" name="price">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image">Gambar</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                        <a href="{{ route('items.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection