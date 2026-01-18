<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f5f7fb;">

<div class="min-vh-100 d-flex align-items-center justify-content-center px-3">
    <div class="card shadow-sm" style="max-width:520px;width:100%;border-radius:18px;border:1px solid rgba(6,26,77,.18);">
        <div class="card-body p-4 p-md-5">
            <div class="mb-3">
                <div style="font-weight:900;color:#061a4d;font-size:44px;line-height:1;">Login Admin</div>
                <div style="font-weight:700;color:#061a4d;opacity:.85;">Masuk untuk akses dashboard</div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger mb-3">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.auth.login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn w-100 py-2"
                        style="background:#061a4d;color:#fff;font-weight:800;border-radius:12px;">
                    Login
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('home') }}" class="text-decoration-none" style="color:#061a4d;font-weight:700;">
                        Kembali ke Home
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>
