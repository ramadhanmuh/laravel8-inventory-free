@extends('layouts.auth')

@section('title', "$application->name - Login")

@section('description', 'Halaman formulir pengisian akun untuk masuk ke aplikasi.')

@section('content')
    <div class="p-5">
        <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">{{ $application->name }}</h1>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="m-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="user" method="POST" action="{{ url('') }}">
            @csrf
            <div class="form-group">
                <input type="text"
                    class="form-control form-control-user"
                    id="exampleInputEmail"
                    placeholder="Username"
                    name="username">
                @error('username')
                    <small class="text-danger ml-4">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <input type="password"
                    class="form-control form-control-user"
                    id="exampleInputPassword"
                    placeholder="Kata sandi"
                    name="password">
                @error('password')
                    <small class="text-danger ml-4">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox small">
                    <input type="checkbox" class="custom-control-input" id="customCheck" name="remember_me">
                    <label class="custom-control-label" for="customCheck">
                        Tetap Masuk
                    </label>
                </div>
                @error('remember_me')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary btn-user btn-block">
                Login
            </button>
        </form>
        <hr>
        <div class="text-center">
            <a class="small" href="{{ url('lupa-kata-sandi') }}">Lupa Kata Sandi ?</a>
        </div>
    </div>
@endsection