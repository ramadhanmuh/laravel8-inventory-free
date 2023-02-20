<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('description')">

    <title>@yield('title')</title>

    {{-- Custom fonts for this template --}}
    <link href="{{ url('') }}/template/sb-admin-2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    {{-- Custom styles for this template --}}
    <link href="{{ url('') }}/template/sb-admin-2/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        {{-- Outer Row  --}}
        <div class="row justify-content-center min-vh-100 align-items-center">

            <div class="col-xl-5 col-lg-6 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        {{-- Nested Row within Card Body  --}}
                        <div class="row">
                            <div class="col-lg-12">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- Bootstrap core JavaScript --}}
    <script defer src="{{ url('') }}/template/sb-admin-2/vendor/jquery/jquery.min.js"></script>
    <script defer src="{{ url('') }}/template/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    {{-- Core plugin JavaScript --}}
    <script defer src="{{ url('') }}/template/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js"></script>

    {{-- Custom scripts for all pages --}}
    <script defer src="{{ url('') }}/template/sb-admin-2/js/sb-admin-2.min.js"></script>

</body>

</html>