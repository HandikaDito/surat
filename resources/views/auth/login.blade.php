<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login E-Disposisi</title>

    {{-- 🔥 WAJIB MOBILE --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- BOOTSTRAP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            .card {
                border-radius: 12px;
            }

            .container {
                padding-left: 16px;
                padding-right: 16px;
            }
        }
    </style>
</head>

<body class="bg-light d-flex align-items-center">

<div class="container">

    <div class="row justify-content-center">

        {{-- 🔥 FULL WIDTH DI HP --}}
        <div class="col-12 col-md-4">

            <div class="card shadow-sm">

                <div class="card-body p-4">

                    <h4 class="text-center mb-4">
                        Login E-Disposisi
                    </h4>

                    {{-- ERROR --}}
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="/login">
                        @csrf

                        <input type="email"
                               name="email"
                               class="form-control mb-3"
                               placeholder="Email"
                               required>

                        <input type="password"
                               name="password"
                               class="form-control mb-3"
                               placeholder="Password"
                               required>

                        {{-- 🔥 BUTTON BESAR --}}
                        <button class="btn btn-primary w-100 py-2">
                            Login
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>