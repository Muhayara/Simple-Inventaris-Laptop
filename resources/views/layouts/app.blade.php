<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventaris Laptop') | {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            background-color: #f4f6f9;
            color: #1f2937;
            line-height: 1.6;
        }
        a { color: #1d4ed8; }

        /* Header & navigasi */
        header {
            background-color: #0f294a;
            color: #ffffff;
            padding: 14px 24px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        .header-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .brand { font-size: 18px; font-weight: 700; color: #ffffff; text-decoration: none; }
        .brand small { display: block; font-size: 12px; font-weight: 400; color: #94a3b8; }
        nav a {
            color: #cbd5e1;
            text-decoration: none;
            margin-left: 8px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
        }
        nav a:hover, nav a.active { background-color: #1e40af; color: #ffffff; }

        /* Area judul halaman */
        .page-title {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 18px 24px;
        }
        .page-title-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .page-title h1 { margin: 0; font-size: 24px; color: #0f294a; }
        .page-title p { margin: 2px 0 0; color: #6b7280; font-size: 14px; }

        main { max-width: 1100px; margin: 24px auto; padding: 0 20px; min-height: 65vh; }

        .card {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px 24px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            margin-bottom: 18px;
        }

        /* Pesan status */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 18px; font-size: 14px; border: 1px solid; }
        .alert-success { background: #dcfce7; color: #166534; border-color: #86efac; }
        .alert-error { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        .alert-info { background: #e0f2fe; color: #075985; border-color: #7dd3fc; }
        .alert ul { margin: 6px 0 0; padding-left: 20px; }

        /* Badge */
        .badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .badge-cat { background-color: #e0f2fe; color: #075985; }
        .badge-baru { background-color: #dcfce7; color: #166534; }
        .badge-bekas { background-color: #fef3c7; color: #92400e; }
        .badge-rusak { background-color: #fee2e2; color: #991b1b; }

        /* Tombol */
        .btn {
            display: inline-block;
            padding: 7px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #374151;
            cursor: pointer;
            font-family: inherit;
            line-height: 1.4;
        }
        .btn:hover { background: #f3f4f6; }
        .btn-sm { padding: 4px 10px; font-size: 13px; }
        .btn-primary { background-color: #1d4ed8; border-color: #1d4ed8; color: #ffffff; }
        .btn-primary:hover { background-color: #1e40af; }
        .btn-warning { background-color: #f59e0b; border-color: #f59e0b; color: #ffffff; }
        .btn-warning:hover { background-color: #d97706; }
        .btn-danger { background-color: #dc2626; border-color: #dc2626; color: #ffffff; }
        .btn-danger:hover { background-color: #b91c1c; }
        .actions { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
        .actions form { margin: 0; }

        /* Tabel */
        .table-wrap { overflow-x: auto; }
        table.data { width: 100%; border-collapse: collapse; font-size: 14px; }
        table.data th, table.data td { padding: 10px 10px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: middle; }
        table.data th { background: #f8fafc; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.3px; }
        table.data tr:hover td { background: #f9fafb; }
        .text-right { text-align: right !important; }
        .muted { color: #6b7280; }

        /* Form */
        .form-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px 20px; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-group.full { grid-column: 1 / -1; }
        .form-group label { font-weight: 600; font-size: 14px; }
        .form-group input, .form-group select, .form-group textarea {
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            background: #ffffff;
        }
        .form-group input[readonly] { background: #f3f4f6; color: #6b7280; }
        .form-group .is-invalid { border-color: #dc2626; }
        .form-group .error-text { color: #dc2626; font-size: 13px; }
        .form-group .hint { color: #6b7280; font-size: 12px; }
        .radio-group { display: flex; gap: 18px; padding-top: 6px; }
        .radio-group label { font-weight: 400; display: flex; align-items: center; gap: 6px; }
        .form-actions { margin-top: 22px; display: flex; gap: 8px; }

        .search-bar { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
        .search-bar input, .search-bar select { padding: 7px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: inherit; }
        .search-bar input { min-width: 240px; }

        .empty-state { text-align: center; padding: 36px 16px; color: #6b7280; }

        footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 13px;
            border-top: 1px solid #e5e7eb;
            background: #ffffff;
        }
    </style>
</head>
<body>
    {{-- Area header & navigasi --}}
    <header>
        <div class="header-inner">
            <a href="{{ route('laptop.index') }}" class="brand">
                Inventaris Laptop
                <small>Sistem Jual Beli Laptop &bull; UTS PBO</small>
            </a>
            <nav>
                <a href="{{ route('laptop.index') }}" class="{{ request()->routeIs('laptop.index', 'laptop.show', 'laptop.edit') ? 'active' : '' }}">Daftar Laptop</a>
                <a href="{{ route('laptop.create') }}" class="{{ request()->routeIs('laptop.create') ? 'active' : '' }}">Tambah Laptop</a>
                <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Praktikum Buku</a>
            </nav>
        </div>
    </header>

    {{-- Area judul halaman --}}
    <section class="page-title">
        <div class="page-title-inner">
            <div>
                <h1>@yield('title', 'Inventaris Laptop')</h1>
                @hasSection('subtitle')
                    <p>@yield('subtitle')</p>
                @endif
            </div>
            <div>@yield('title-actions')</div>
        </div>
    </section>

    {{-- Area konten --}}
    <main>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @yield('content')
    </main>

    {{-- Area footer --}}
    <footer>
        &copy; 2026 Inventaris Laptop &bull; UTS Pemrograman Berorientasi Objek &bull; Laravel 13
    </footer>
</body>
</html>
