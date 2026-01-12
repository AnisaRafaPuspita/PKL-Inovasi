<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <style>
        :root{
            --navy:#061a4d;
            --navy-2:#0a2b7a;
            --border:#0b1f5a;
            --bg:#f6f8ff;
        }

        body { background: var(--bg); }

        .app {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 280px 1fr;
        }

        .sidebar {
            background: var(--navy);
            color: #fff;
            padding: 18px 14px;
        }

        .sidebar .profile {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 10px 10px 18px;
            border-bottom: 1px solid rgba(255,255,255,.15);
            margin-bottom: 14px;
        }

        .sidebar .avatar {
            width: 44px;
            height: 44px;
            border-radius: 999px;
            border: 2px solid rgba(255,255,255,.35);
            display: grid;
            place-items: center;
            font-weight: 700;
        }

        .sidebar .menu a{
            display:flex;
            align-items:center;
            gap:10px;
            padding: 10px 12px;
            border-radius: 10px;
            color:#e9eeff;
            text-decoration:none;
            margin-bottom: 6px;
        }

        .sidebar .menu a.active,
        .sidebar .menu a:hover{
            background: rgba(255,255,255,.12);
        }

        .content {
            padding: 26px 28px;
        }

        .panel {
            background: #fff;
            border: 2px solid var(--border);
            border-radius: 18px;
            padding: 18px;
        }

        .stat-card{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            padding: 18px 20px;
            border-radius: 18px;
            border: 2px solid var(--border);
            background: #fff;
            min-height: 92px;
        }

        .stat-title{
            font-size: 14px;
            font-weight: 600;
            color: #14255f;
            margin-bottom: 2px;
        }

        .stat-value{
            font-size: 28px;
            font-weight: 800;
            color: #061a4d;
            line-height: 1.1;
        }

        .icon-badge{
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 2px solid var(--border);
            display:grid;
            place-items:center;
            color: var(--navy);
        }

        .section-title{
            font-weight: 800;
            color:#061a4d;
            margin-bottom: 12px;
        }

        .btn-navy{
            background: var(--navy);
            border-color: var(--navy);
            color:#fff;
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 700;
        }
        .btn-navy:hover{ background: #04133a; border-color:#04133a; color:#fff; }

        .iom-wrap{
            display:grid;
            grid-template-columns: 140px 1fr;
            gap: 18px;
            align-items: center;
        }

        .iom-photo{
            width: 120px;
            height: 120px;
            border-radius: 16px;
            border: 2px solid var(--border);
            background: #fff;
            overflow:hidden;
            display:grid;
            place-items:center;
            color:#6b7280;
            font-weight:700;
        }

        .iom-photo img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .iom-label{
            font-weight:800;
            color:#061a4d;
            margin: 0 0 8px;
        }

        .iom-text{
            font-weight:700;
            color:#111827;
            margin: 0 0 6px;
        }
    </style>
</head>
<body>
<div class="app">

    <aside class="sidebar">
        <div class="profile">
            <div class="avatar">👤</div>
            <div>
                <div style="font-weight:800; line-height:1;">Admin User</div>
                <div style="opacity:.8; font-size:13px;">Administrator</div>
            </div>
        </div>

        <nav class="menu">
            <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                🧩 Dashboard Overview
            </a>
            <a href="#">💡 Manage Innovations</a>
            <a href="#">✅ Accept Innovations</a>
            <a href="#">🏆 Innovator of The Month</a>
            <a href="#">📈 Innovation Ranking</a>
        </nav>
    </aside>

    <main class="content">
        @yield('content')
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
