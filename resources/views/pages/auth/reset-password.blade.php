@extends('layouts.auth')

@section('title', "$application->name - Setel Ulang Kata Sandi")

@section('description', 'Halaman formulir untuk atur ulang kata sandi.')

@section('content')
    <div class="p-5">
        <div class="text-center">
            <h1 class="h4 text-gray-900 mb-2">Ubah Kata Sandi</h1>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                @if ($errors->count() < 2)
                    <ul class="m-0 list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @else
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <form class="user" method="POST" action="{{ url('reset-password') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <input type="email"
                    class="form-control form-control-user"
                    id="email"
                    placeholder="Ketikkan email..."
                    name="email">
            </div>
            <div class="form-group">
                <input type="password"
                    class="form-control form-control-user"
                    id="password"
                    placeholder="Ketikkan kata sandi baru..."
                    name="password">
            </div>
            <div class="form-group">
                <input type="password"
                    class="form-control form-control-user"
                    id="password_confirmation"
                    placeholder="Ketik kembali kata sandi..."
                    name="password_confirmation">
            </div>
            <button type="submit" class="btn btn-primary btn-user btn-block">
                Simpan
            </button>
        </form>
        <hr>
        <div class="text-center">
            <a class="small" href="{{ url('') }}">Masuk</a>
        </div>
    </div>
@endsection