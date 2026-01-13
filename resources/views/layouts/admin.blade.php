<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard')</title>

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
            overflow: hidden;
            background: rgba(255,255,255,.06);
        }
        .sidebar .avatar img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        /* =========================
           MENU + HIGHLIGHT GESER
           ========================= */
        .sidebar .menu{
            position: relative;
            display:flex;
            flex-direction:column;
            gap: 14px;
            padding: 6px 10px;
        }

        /* kotak putih yang geser (smooth) */
        .sidebar .menu-highlight{
            position:absolute;
            left: 10px;
            right: 10px;
            top: 0;
            height: 54px;
            background: #fff;
            border-radius: 12px;
            z-index: 1;
            box-shadow: 0 8px 20px rgba(0,0,0,.12);

            /* bikin super smooth */
            will-change: transform, height;
            transform: translate3d(0,0,0);
            transition:
                transform 420ms cubic-bezier(.2,.9,.2,1),
                height 420ms cubic-bezier(.2,.9,.2,1),
                opacity 200ms ease;
        }

        .sidebar .menu a{
            position: relative;
            z-index: 2;
            display:flex;
            align-items:center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 12px;
            color:#e9eeff;
            text-decoration:none;
            font-weight: 800;
            transition: color .18s ease, background .18s ease;
        }

        .sidebar .menu a:hover{
            background: rgba(255,255,255,.10);
        }

        .sidebar .menu a.active{
            color: var(--navy);
            background: transparent;
        }

        /* icon image */
        .menu-icon{
            width: 22px;
            height: 22px;
            object-fit: contain;
            flex: 0 0 auto;
            display:block;
        }

        /* ========================= */

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
            {{-- photo profile --}}
            <div class="avatar">
                <img src="{{ asset('images/profile.png') }}" alt="Admin">
            </div>
            <div>
                <div style="font-weight:800; line-height:1;">Admin User</div>
                <div style="opacity:.8; font-size:13px;">Administrator</div>
            </div>
        </div>

        <nav class="menu js-sidebar-menu">
            <span class="menu-highlight js-menu-highlight" aria-hidden="true"></span>

            {{-- Dashboard --}}
            @php $isDash = request()->routeIs('admin.dashboard'); @endphp
            <a class="menu-item {{ $isDash ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}"
               data-icon-blue="{{ asset('images/dashboard-biru.png') }}"
               data-icon-white="{{ asset('images/dashboard-putih.png') }}">
                <img class="menu-icon"
                    src="{{ $isDash ? asset('images/dashboard-biru.png') : asset('images/dashboard-putih.png') }}"
                    alt="">
                <span>Dashboard Overview</span>
            </a>

            {{-- Manage Innovations --}}
            @php $isManage = request()->routeIs('admin.innovations.*'); @endphp
            <a class="menu-item {{ $isManage ? 'active' : '' }}"
               href="{{ route('admin.innovations.index') }}"
               data-icon-blue="{{ asset('images/manage-biru.png') }}"
               data-icon-white="{{ asset('images/manage-putih.png') }}">
                <img class="menu-icon"
                     src="{{ $isManage ? asset('images/manage-biru.png') : asset('images/manage-putih.png') }}"
                     alt="">
                <span>Manage Innovations</span>
            </a>

            {{-- Permission Innovations --}}
            @php $isPermission = request()->routeIs('admin.permissions.*'); @endphp
            <a class="menu-item {{ $isPermission ? 'active' : '' }}"
            href="{{ route('admin.permissions.index') }}"
            data-icon-blue="{{ asset('images/accept-biru.png') }}"
            data-icon-white="{{ asset('images/accept-putih.png') }}">
                <img class="menu-icon"
                    src="{{ $isPermission ? asset('images/accept-biru.png') : asset('images/accept-putih.png') }}"
                    alt="">
                <span>Permission Innovations</span>
            </a>



            {{-- Innovator of The Month --}}
            <a class="menu-item"
               href="#"
               data-icon-blue="{{ asset('images/innovatorofthemonth-biru.png') }}"
               data-icon-white="{{ asset('images/innovatorofthemonth-putih.png') }}">
                <img class="menu-icon"
                     src="{{ asset('images/innovatorofthemonth-biru.png') }}"
                     alt="">
                <span>Innovator of The Month</span>
            </a>

            {{-- Innovation ranking --}}
            <a class="menu-item"
               href="#"
               data-icon-blue="{{ asset('images/ranking-biru.png') }}"
               data-icon-white="{{ asset('images/ranking-putih.png') }}">
                <img class="menu-icon"
                     src="{{ asset('images/ranking-biru.png') }}"
                     alt="">
                <span>Innovation Ranking</span>
            </a>

        </nav>

    </aside>

    <main class="content">
        @yield('content')
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function(){
  const menu = document.querySelector('.js-sidebar-menu');
  if(!menu) return;

  const highlight = menu.querySelector('.js-menu-highlight');
  const items = Array.from(menu.querySelectorAll('.menu-item'));

  function syncIcons(){
    items.forEach(a => {
      const img = a.querySelector('.menu-icon');
      if(!img) return;

      const biru = a.getAttribute('data-icon-blue');
      const putih = a.getAttribute('data-icon-white');

      img.src = a.classList.contains('active') ? biru : putih;
    });
  }

  function moveHighlightTo(el){
    if(!highlight || !el) return;
    const menuRect = menu.getBoundingClientRect();
    const elRect = el.getBoundingClientRect();
    const top = elRect.top - menuRect.top;

    highlight.style.height = elRect.height + 'px';
    highlight.style.transform = `translateY(${top}px)`;
  }

  function setActive(el){
    items.forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    syncIcons();
    moveHighlightTo(el);
  }

  const current = items.find(i => i.classList.contains('active')) || items[0];
  syncIcons();
  moveHighlightTo(current);

  items.forEach(a => {
    a.addEventListener('click', (e) => {
      const href = a.getAttribute('href') || '#';
      if(href === '#'){
        e.preventDefault();
        setActive(a);
        return;
      }
      e.preventDefault();
      setActive(a);
      setTimeout(() => { window.location.href = href; }, 180);
    });
  });

  window.addEventListener('resize', () => {
    const active = items.find(i => i.classList.contains('active')) || items[0];
    moveHighlightTo(active);
  });
})();
</script>


@stack('scripts')
</body>
</html>
