@extends('layouts.auth')

@section('title', "$application->name - Lupa Kata Sandi")

@section('description', 'Halaman formulir pengisian email untuk atur ulang kata sandi.')

@section('content')
    <div class="p-5">
        <div class="text-center">
            <h1 class="h4 text-gray-900 mb-2">Lupa Kata Sandi</h1>
            <p class="mb-4">
                Kami mengerti, banyak hal terjadi. Cukup masukkan alamat email Anda di bawah ini
                dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda!
            </p>
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
                Berhasil mengirim email.
            </div>
        @endif
        <form class="user" method="POST">
            @csrf
            <div class="form-group">
                <input type="email"
                    class="form-control form-control-user"
                    id="exampleInputEmail"
                    aria-describedby="emailHelp"
                    placeholder="Ketikkan alamat email..."
                    name="email">
            </div>
            <button type="submit" class="btn btn-primary btn-user btn-block">
                Reset Password
            </button>
        </form>
        <div class="text-center">
            <a class="small" href="{{ url('') }}">Login</a>
        </div>
    </div>
@endsection