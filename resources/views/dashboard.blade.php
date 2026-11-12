<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7f6">
    <title>Stokify</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #17231e;
            --muted: #78847e;
            --line: #e8ede9;
            --surface: #ffffff;
            --canvas: #f5f7f6;
            --green: #17845b;
            --green-dark: #116b49;
            --green-soft: #e8f5ee;
            --orange: #d98b2b;
            --orange-soft: #fff3df;
            --red: #d95d54;
            --red-soft: #fff0ee;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        * { box-sizing: border-box; }
        body { margin: 0; background: var(--canvas); color: var(--ink); }
        button, input { font: inherit; }
        a { color: inherit; text-decoration: none; }
        .app-shell { min-height: 100vh; }
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 5;
            display: flex;
            width: 248px;
            flex-direction: column;
            padding: 28px 18px 20px;
            border-right: 1px solid var(--line);
            background: var(--surface);
        }
        .brand { display: flex; align-items: center; gap: 11px; padding: 0 10px 34px; }
        .brand-mark {
            display: grid;
            width: 39px;
            height: 39px;
            place-items: center;
            border-radius: 13px;
            background: var(--green);
            color: white;
        }
        .brand-name { font-size: 19px; font-weight: 750; letter-spacing: -.6px; }
        .brand-name span { color: var(--green); }
        .brand-copy { display: grid; gap: 2px; }
        .brand-tagline { color: var(--muted); font-size: 9px; font-weight: 600; }
        .nav-label { margin: 0 10px 10px; color: #a0aaa4; font-size: 10px; font-weight: 750; letter-spacing: 1.2px; text-transform: uppercase; }
        .nav-list { display: grid; gap: 5px; }
        .menu-toggle { display: none; }
        .nav-link {
            display: flex;
            min-height: 44px;
            align-items: center;
            gap: 12px;
            padding: 0 12px;
            border-radius: 10px;
            color: #68756e;
            font-size: 13px;
            font-weight: 600;
            transition: background .18s ease, color .18s ease;
        }
        .nav-link:hover, .nav-link.active { background: var(--green-soft); color: var(--green-dark); }
        .nav-link svg { width: 18px; height: 18px; flex: 0 0 auto; }
        .logout-form { margin-top: 5px; }
        .logout-button { width: 100%; border: 0; background: transparent; text-align: left; cursor: pointer; font: inherit; }
        .sidebar-bottom { margin-top: auto; }
        .help-card { padding: 15px; border: 1px solid var(--line); border-radius: 13px; background: #fbfcfb; }
        .help-card strong { display: block; margin-bottom: 5px; font-size: 12px; }
        .help-card p { margin: 0; color: var(--muted); font-size: 11px; line-height: 1.55; }
        .profile { display: flex; align-items: center; gap: 10px; margin: 18px 4px 0; padding-top: 16px; border-top: 1px solid var(--line); }
        .avatar { display: grid; width: 36px; height: 36px; flex: 0 0 auto; place-items: center; border-radius: 50%; background: #e9eee9; color: #52635a; font-size: 12px; font-weight: 750; }
        .profile-name { font-size: 12px; font-weight: 700; }
        .profile-role { margin-top: 3px; color: var(--muted); font-size: 10px; }
        .main { min-height: 100vh; margin-left: 248px; }
        .topbar {
            display: flex;
            min-height: 76px;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 0 42px;
            border-bottom: 1px solid var(--line);
            background: rgba(255,255,255,.82);
        }
        .breadcrumb { color: var(--muted); font-size: 12px; }
        .breadcrumb strong { color: var(--ink); font-weight: 650; }
        .top-actions { display: flex; align-items: center; gap: 12px; }
        .search { position: relative; }
        .search svg { position: absolute; top: 50%; left: 12px; width: 16px; height: 16px; color: #9aa59f; transform: translateY(-50%); }
        .search input { width: 220px; height: 38px; padding: 0 12px 0 37px; border: 1px solid var(--line); border-radius: 9px; outline: none; background: white; color: var(--ink); font-size: 12px; }
        .search input:focus { border-color: #9acbb2; box-shadow: 0 0 0 3px rgba(23,132,91,.08); }
        .icon-button { display: grid; width: 38px; height: 38px; place-items: center; border: 1px solid var(--line); border-radius: 9px; background: white; color: #69766f; cursor: pointer; }
        .icon-button svg { width: 17px; height: 17px; }
        .content { width: min(100%, 1440px); margin: 0 auto; padding: 34px 42px 44px; }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 25px; }
        .eyebrow { margin: 0 0 7px; color: var(--green); font-size: 11px; font-weight: 750; letter-spacing: .8px; text-transform: uppercase; }
        h1 { margin: 0; font-size: clamp(24px, 3vw, 30px); letter-spacing: -1px; }
        .subtitle { margin: 7px 0 0; color: var(--muted); font-size: 13px; }
        .primary-button {
            display: inline-flex;
            min-height: 42px;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 15px;
            border: 0;
            border-radius: 9px;
            background: var(--green);
            color: white;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 3px 8px rgba(23,132,91,.16);
            cursor: pointer;
            transition: background .18s ease, transform .18s ease;
        }
        .primary-button:hover { background: var(--green-dark); transform: translateY(-1px); }
        .primary-button svg { width: 16px; height: 16px; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-bottom: 22px; }
        .stat-card { min-height: 132px; padding: 19px 20px; border: 1px solid var(--line); border-radius: 13px; background: var(--surface); }
        .stat-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .stat-label { color: var(--muted); font-size: 12px; font-weight: 600; }
        .stat-icon { display: grid; width: 34px; height: 34px; place-items: center; border-radius: 10px; }
        .stat-icon svg { width: 17px; height: 17px; }
        .stat-icon.green { background: var(--green-soft); color: var(--green); }
        .stat-icon.orange { background: var(--orange-soft); color: var(--orange); }
        .stat-icon.blue { background: #eaf2ff; color: #5482cd; }
        .stat-value { margin-top: 12px; font-size: 27px; font-weight: 750; letter-spacing: -.9px; }
        .stat-note { margin-top: 5px; color: var(--muted); font-size: 10px; }
        .stat-note .positive { color: var(--green); font-weight: 700; }
        .stat-note .warning { color: #b77720; font-weight: 700; }
        .panel { overflow: hidden; border: 1px solid var(--line); border-radius: 13px; background: var(--surface); }
        .panel-heading { display: flex; align-items: center; justify-content: space-between; gap: 15px; padding: 19px 21px; }
        .panel-heading h2 { margin: 0; font-size: 15px; letter-spacing: -.2px; }
        .panel-heading p { margin: 5px 0 0; color: var(--muted); font-size: 11px; }
        .text-link { color: var(--green); font-size: 11px; font-weight: 700; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        thead { background: #fafbfa; }
        th { padding: 11px 20px; color: #8a9690; font-size: 9px; font-weight: 750; letter-spacing: .7px; text-transform: uppercase; white-space: nowrap; }
        td { padding: 13px 20px; border-top: 1px solid #f0f2f0; color: #536159; font-size: 11px; white-space: nowrap; }
        .product-name { color: var(--ink); font-size: 11px; font-weight: 700; }
        .product-code { margin-top: 4px; color: #9aa49e; font-size: 10px; }
        .category { display: inline-flex; padding: 5px 8px; border-radius: 6px; background: #f2f5f3; color: #68756e; font-size: 10px; }
        .stock { color: var(--ink); font-weight: 700; }
        .stock.low { color: #c17a21; }
        .stock.out { color: var(--red); }
        .status { display: inline-flex; align-items: center; gap: 6px; font-size: 10px; font-weight: 650; }
        .status::before { width: 6px; height: 6px; border-radius: 50%; background: currentColor; content: ""; }
        .status.available { color: var(--green); }
        .status.low { color: #c17a21; }
        .status.empty { color: var(--red); }
        .table-footer { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 20px; border-top: 1px solid var(--line); color: var(--muted); font-size: 10px; }
        .page-controls { display: flex; align-items: center; gap: 6px; }
        .page-controls button { display: grid; width: 27px; height: 27px; place-items: center; border: 1px solid var(--line); border-radius: 7px; background: white; color: #647169; font-size: 10px; }
        .page-controls button.current { border-color: var(--green); background: var(--green); color: white; }
        .mobile-brand { display: none; }

        @media (max-width: 1050px) {
            .sidebar { width: 220px; }
            .main { margin-left: 220px; }
            .topbar { padding-right: 26px; padding-left: 26px; }
            .content { padding-right: 26px; padding-left: 26px; }
            .stats-grid { gap: 11px; }
            .stat-card { padding: 16px; }
        }
        @media (max-width: 760px) {
            .sidebar { position: relative; width: 100%; height: auto; padding: 14px 16px 10px; border-right: 0; border-bottom: 1px solid var(--line); }
            .brand { padding: 0 48px 13px 2px; }
            .brand-mark { width: 35px; height: 35px; border-radius: 11px; }
            .brand-name { font-size: 17px; }
            .menu-toggle { position: absolute; top: 12px; right: 16px; display: grid; width: 40px; height: 40px; place-items: center; border: 1px solid var(--line); border-radius: 9px; background: white; color: var(--ink); }
            .menu-toggle svg { width: 20px; height: 20px; }
            .menu-toggle .icon-close, .sidebar.nav-open .menu-toggle .icon-menu { display: none; }
            .sidebar.nav-open .menu-toggle .icon-close { display: block; }
            .sidebar-bottom, .nav-label { display: none; }
            .sidebar.nav-open .nav-label { display: block; }
            .nav-list { display: none; gap: 4px; padding: 0 0 4px; }
            .sidebar.nav-open .nav-list { display: grid; }
            .logout-form { display: none; }
            .sidebar.nav-open .logout-form { display: block; }
            .nav-link { min-height: 44px; gap: 10px; padding: 0 12px; font-size: 13px; }
            .nav-link svg { width: 15px; height: 15px; }
            .main { min-height: calc(100vh - 105px); margin-left: 0; }
            .topbar { min-height: 55px; padding: 0 17px; }
            .breadcrumb { font-size: 11px; }
            .top-actions { gap: 8px; }
            .search input { width: min(42vw, 180px); height: 35px; font-size: 11px; }
            .icon-button { width: 35px; height: 35px; }
            .content { padding: 25px 17px 32px; }
            .page-heading { align-items: flex-start; margin-bottom: 19px; }
            .page-heading .primary-button { min-height: 39px; padding: 0 11px; font-size: 11px; }
            .subtitle { max-width: 250px; line-height: 1.5; }
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; margin-bottom: 16px; }
            .stat-card { min-height: 117px; padding: 14px; }
            .stat-label { font-size: 10px; }
            .stat-icon { width: 30px; height: 30px; }
            .stat-value { margin-top: 10px; font-size: 24px; }
            .stat-note { font-size: 9px; }
            .panel-heading { padding: 16px; }
            th, td { padding-right: 14px; padding-left: 14px; }
            th:first-child, td:first-child { padding-left: 16px; }
            th:last-child, td:last-child { padding-right: 16px; }
        }
        @media (max-width: 390px) {
            .nav-link { gap: 6px; padding: 0 8px; font-size: 10px; }
            .topbar { padding-right: 12px; padding-left: 12px; }
            .content { padding-right: 12px; padding-left: 12px; }
            .page-heading { gap: 8px; }
            .page-heading .primary-button { gap: 5px; padding: 0 9px; }
            .stat-card { padding: 12px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" aria-label="Navigasi utama">
        <a class="brand" href="#dashboard" aria-label="Stokify beranda">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" width="22" height="22">
                    <path d="M4 8.5 12 4l8 4.5v8L12 21l-8-4.5v-8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                    <path d="m4.5 8.7 7.5 4.2 7.5-4.2M12 13v7.5M8 6.3l8 4.6" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                </svg>
            </span>
            <span class="brand-copy"><span class="brand-name">Stoki<span>fy</span></span><span class="brand-tagline">Smart Inventory Management</span></span>
        </a>

        <button class="menu-toggle" type="button" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="primary-navigation">
            <svg class="icon-menu" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
            <svg class="icon-close" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>
        <p class="nav-label">Menu utama</p>
        <nav class="nav-list" id="primary-navigation">
            <a class="nav-link active" href="#dashboard" aria-current="page">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.7"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.7"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.7"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.7"/></svg>
                Dashboard
            </a>
            <a class="nav-link" href="{{ route('products.index') }}">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m4 7 8-4 8 4v10l-8 4-8-4V7Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m4.5 7.2 7.5 4 7.5-4M12 11.5V21" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                Data Produk
            </a>
            <a class="nav-link" href="{{ route('stock-in.index') }}">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 16V4m0 0L7.5 8.5M12 4l4.5 4.5M5 14v5a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Barang Masuk
            </a>
            <a class="nav-link" href="{{ route('stock-out.index') }}">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 4v12m0 0 4.5-4.5M12 16l-4.5-4.5M5 14v5a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Barang Keluar
            </a>
            <a class="nav-link" href="{{ route('stock-report.index') }}">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 20V10m7 10V4m7 16v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M3 20.5h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Laporan Stok
            </a>
        </nav>

        <form class="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="nav-link logout-button" type="submit">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Keluar
            </button>
        </form>

        <div class="sidebar-bottom">
            <div class="help-card">
                <strong>Butuh bantuan?</strong>
                <p>Kelola stok kedai jadi lebih mudah dengan Stokify.</p>
            </div>
            <div class="profile">
                <div class="avatar" aria-hidden="true">AS</div>
                <div>
                    <div class="profile-name">Admin Kedai</div>
                    <div class="profile-role">Administrator</div>
                </div>
            </div>
        </div>
    </aside>

    <main class="main" id="dashboard">
        <header class="topbar">
            <div class="breadcrumb">Halaman <span aria-hidden="true">/</span> <strong>Dashboard</strong></div>
            <div class="top-actions">
                <label class="search">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8" stroke="currentColor" stroke-width="1.7"/><path d="m16 16 4.5 4.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                    <input type="search" aria-label="Cari produk" placeholder="Cari produk...">
                </label>
                <button class="icon-button" type="button" aria-label="Notifikasi">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9ZM10 21h4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
        </header>

        <section class="content" aria-labelledby="page-title">
            <div class="page-heading">
                <div>
                    <p class="eyebrow">Ringkasan inventaris</p>
                    <h1 id="page-title">Dashboard</h1>
                    <p class="subtitle">Pantau kondisi stok dan aktivitas produk kedai Anda.</p>
                </div>
                <a class="primary-button" href="{{ route('products.create') }}">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Tambah Produk
                </a>
            </div>

            <div class="stats-grid" aria-label="Ringkasan stok">
                <article class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Total Produk</span>
                        <span class="stat-icon green" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"><path d="m4 7 8-4 8 4v10l-8 4-8-4V7Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m4.5 7.2 7.5 4 7.5-4M12 11.5V21" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                        </span>
                    </div>
                    <div class="stat-value">{{ number_format($totalProducts, 0, ',', '.') }}</div>
                    <div class="stat-note">Produk terdaftar di inventaris</div>
                </article>
                <article class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Stok Menipis</span>
                        <span class="stat-icon orange" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M12 3 2.8 19a1.4 1.4 0 0 0 1.2 2h16a1.4 1.4 0 0 0 1.2-2L12 3Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M12 9v5m0 3h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        </span>
                    </div>
                    <div class="stat-value">{{ number_format($lowStockCount, 0, ',', '.') }}</div>
                    <div class="stat-note"><span class="warning">Perlu diperiksa</span> — stok ≤ batas minimum</div>
                </article>
                <article class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Total Stok Barang</span>
                        <span class="stat-icon blue" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M4 19V9m5 10V5m5 14v-7m5 7V3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M2.5 21h19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </span>
                    </div>
                    <div class="stat-value">{{ number_format($totalStock, 0, ',', '.') }}</div>
                    <div class="stat-note">Unit tersedia di inventaris</div>
                </article>
            </div>

            <section class="panel" id="produk" aria-labelledby="product-heading">
                <div class="panel-heading">
                    <div>
                        <h2 id="product-heading">Daftar Produk</h2>
                        <p>Produk yang terakhir diperbarui di inventaris Anda.</p>
                    </div>
                    <a class="text-link" href="{{ route('products.index') }}">Lihat semua <span aria-hidden="true">→</span></a>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Stok</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td><div class="product-name">{{ $product->name }}</div></td>
                                    <td><span class="category">{{ $product->category }}</span></td>
                                    <td>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                                    <td><span class="stock {{ $product->isLowStock() ? 'low' : '' }}">{{ number_format($product->stock, 0, ',', '.') }} {{ $product->unit }}</span></td>
                                    <td>
                                        @if ($product->isLowStock())
                                            <span class="status low">Stok menipis · min {{ number_format($product->min_stock, 0, ',', '.') }}</span>
                                        @else
                                            <span class="status available">Aman</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding: 28px 20px; color: var(--muted); text-align: center;">Belum ada produk di database.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <span>Menampilkan {{ $products->count() }} produk terbaru</span>
                    <a class="text-link" href="{{ route('products.index') }}">Kelola produk →</a>
                </div>
            </section>
        </section>
    </main>
</div>
<script>
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = menuToggle.closest('.sidebar');

    menuToggle.addEventListener('click', () => {
        const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', String(!isExpanded));
        menuToggle.setAttribute('aria-label', isExpanded ? 'Buka menu navigasi' : 'Tutup menu navigasi');
        sidebar.classList.toggle('nav-open', !isExpanded);
    });
</script>
</body>
</html>