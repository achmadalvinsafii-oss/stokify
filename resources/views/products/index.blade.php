<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7f6">
    <title>Stokify</title>
    <style>
        :root {
            --ink: #17231e;
            --muted: #78847e;
            --line: #e8ede9;
            --surface: #fff;
            --canvas: #f5f7f6;
            --green: #17845b;
            --green-dark: #116b49;
            --green-soft: #e8f5ee;
            --red: #c94d45;
            --red-soft: #fff0ee;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--canvas); color: var(--ink); }
        a { color: inherit; text-decoration: none; }
        button, input { font: inherit; }
        .sidebar {
            position: fixed; inset: 0 auto 0 0; z-index: 2; display: flex; width: 248px; flex-direction: column;
            padding: 28px 18px 20px; border-right: 1px solid var(--line); background: var(--surface);
        }
        .brand { display: flex; align-items: center; gap: 11px; padding: 0 10px 34px; }
        .brand-mark { display: grid; width: 39px; height: 39px; place-items: center; border-radius: 13px; background: var(--green); color: #fff; }
        .brand-name { font-size: 19px; font-weight: 750; letter-spacing: -.6px; }
        .brand-name span { color: var(--green); }
        .brand-copy { display: grid; gap: 2px; }
        .brand-tagline { color: var(--muted); font-size: 9px; font-weight: 600; }
        .nav-label { margin: 0 10px 10px; color: #a0aaa4; font-size: 10px; font-weight: 750; letter-spacing: 1.2px; text-transform: uppercase; }
        .nav-list { display: grid; gap: 5px; }
        .menu-toggle { display: none; }
        .nav-link { display: flex; min-height: 44px; align-items: center; gap: 12px; padding: 0 12px; border-radius: 10px; color: #68756e; font-size: 13px; font-weight: 600; }
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
        .topbar { display: flex; min-height: 76px; align-items: center; justify-content: space-between; padding: 0 42px; border-bottom: 1px solid var(--line); background: rgba(255,255,255,.82); }
        .breadcrumb { color: var(--muted); font-size: 12px; }
        .breadcrumb strong { color: var(--ink); font-weight: 650; }
        .top-link { color: var(--green); font-size: 12px; font-weight: 700; }
        .content { width: min(100%, 1440px); margin: 0 auto; padding: 34px 42px 44px; }
        .heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; margin-bottom: 23px; }
        .eyebrow { margin: 0 0 7px; color: var(--green); font-size: 11px; font-weight: 750; letter-spacing: .8px; text-transform: uppercase; }
        h1 { margin: 0; font-size: clamp(24px, 3vw, 30px); letter-spacing: -1px; }
        .subtitle { margin: 7px 0 0; color: var(--muted); font-size: 13px; }
        .button { display: inline-flex; min-height: 41px; align-items: center; justify-content: center; gap: 8px; padding: 0 14px; border: 0; border-radius: 9px; background: var(--green); color: white; font-size: 12px; font-weight: 700; cursor: pointer; }
        .button:hover { background: var(--green-dark); }
        .button svg { width: 16px; height: 16px; }
        .toolbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 19px; border-bottom: 1px solid var(--line); }
        .toolbar-title { font-size: 13px; font-weight: 700; }
        .toolbar-count { margin-top: 4px; color: var(--muted); font-size: 10px; }
        .filters-form { display: flex; align-items: center; gap: 8px; }
        .search-input, .category-select { height: 38px; padding: 0 11px; border: 1px solid var(--line); border-radius: 8px; outline: none; background: white; font-size: 11px; }
        .search-input { width: min(28vw, 230px); }
        .category-select { min-width: 150px; color: var(--ink); }
        .search-input:focus, .field:focus { border-color: #9acbb2; box-shadow: 0 0 0 3px rgba(23,132,91,.08); }
        .category-select:focus { border-color: #9acbb2; box-shadow: 0 0 0 3px rgba(23,132,91,.08); }
        .sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; clip-path: inset(50%); }
        .search-button { min-height: 38px; padding: 0 12px; }
        .panel { overflow: hidden; border: 1px solid var(--line); border-radius: 13px; background: var(--surface); }
        .notice { margin-bottom: 15px; padding: 12px 15px; border: 1px solid #cce8d7; border-radius: 9px; background: var(--green-soft); color: var(--green-dark); font-size: 12px; }
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table-scroll-hint { display: none; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        thead { background: #fafbfa; }
        th { padding: 12px 18px; color: #8a9690; font-size: 9px; font-weight: 750; letter-spacing: .7px; text-transform: uppercase; white-space: nowrap; }
        td { padding: 13px 18px; border-top: 1px solid #f0f2f0; color: #536159; font-size: 11px; white-space: nowrap; }
        .product-name { color: var(--ink); font-size: 11px; font-weight: 700; }
        .category { display: inline-flex; padding: 5px 8px; border-radius: 6px; background: #f2f5f3; color: #68756e; font-size: 10px; }
        .price, .stock { color: var(--ink); font-weight: 650; }
        .stock-detail { display: block; margin-top: 4px; color: #9aa49e; font-size: 9px; }
        .stock-status { display: inline-flex; align-items: center; gap: 6px; padding: 5px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; }
        .stock-status::before { display: grid; width: 14px; height: 14px; place-items: center; border-radius: 50%; font-size: 9px; font-weight: 800; }
        .stock-status.low { background: #fff3df; color: #a56a16; }
        .stock-status.low::before { background: #f6dfb8; color: #8d5b10; content: "!"; }
        .stock-status.safe { background: var(--green-soft); color: var(--green-dark); }
        .stock-status.safe::before { background: #cdebd9; color: var(--green-dark); content: "✓"; }
        .actions { display: flex; align-items: center; gap: 7px; }
        .small-button { display: inline-flex; min-height: 29px; align-items: center; justify-content: center; padding: 0 9px; border: 1px solid var(--line); border-radius: 7px; background: white; color: #56645c; font-size: 10px; font-weight: 650; cursor: pointer; }
        .small-button:hover { border-color: #b7c8bd; background: #f8faf8; }
        .small-button.delete { border-color: #f4d4d1; color: var(--red); }
        .small-button.delete:hover { background: var(--red-soft); }
        .empty { padding: 45px 20px; text-align: center; }
        .empty strong { display: block; font-size: 13px; }
        .empty p { margin: 7px 0 15px; color: var(--muted); font-size: 11px; }
        .table-footer { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 13px 18px; border-top: 1px solid var(--line); color: var(--muted); font-size: 10px; }
        .pagination nav { display: flex; gap: 5px; }
        .pagination a, .pagination span { display: inline-grid; min-width: 28px; height: 28px; place-items: center; padding: 0 7px; border: 1px solid var(--line); border-radius: 7px; color: #647169; font-size: 10px; }
        .pagination span[aria-current="page"] { border-color: var(--green); background: var(--green); color: white; }
        .pagination span[aria-disabled="true"] { opacity: .45; }
        @media (max-width: 1050px) {
            .sidebar { width: 220px; }
            .main { margin-left: 220px; }
            .topbar, .content { padding-right: 26px; padding-left: 26px; }
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
            .nav-link svg { width: 18px; height: 18px; }
            .main { min-height: calc(100vh - 105px); margin-left: 0; }
            .topbar { min-height: 55px; padding: 0 17px; }
            .breadcrumb { font-size: 11px; }
            .content { padding: 25px 17px 32px; }
            .heading { align-items: flex-start; margin-bottom: 18px; }
            .subtitle { max-width: 255px; line-height: 1.5; }
            .button { min-height: 39px; padding: 0 11px; font-size: 11px; }
            .toolbar { align-items: flex-start; flex-direction: column; padding: 15px; }
            .filters-form { display: grid; width: 100%; grid-template-columns: minmax(0, 1fr) auto; }
            .search-input { width: 100%; height: 44px; grid-column: 1 / -1; }
            .category-select { min-width: 0; height: 44px; }
            .search-button { min-height: 44px; }
            .filters-form .small-button { min-height: 44px; grid-column: 1 / -1; }
            .table-scroll-hint { display: block; margin: 0; padding: 9px 14px; border-bottom: 1px solid var(--line); color: var(--muted); font-size: 10px; }
            table { min-width: 780px; }
            th, td { padding: 13px 12px; }
            th:first-child, td:first-child { position: sticky; left: 0; padding-left: 15px; background: white; box-shadow: 1px 0 0 var(--line); }
            th:first-child { z-index: 1; background: #fafbfa; }
            th:last-child, td:last-child { padding-right: 15px; }
            .small-button { min-height: 36px; }
            .table-footer { align-items: flex-start; flex-direction: column; }
            .pagination { max-width: 100%; overflow-x: auto; }
        }
        @media (max-width: 390px) {
            .content { padding-right: 12px; padding-left: 12px; }
            .heading { gap: 8px; }
            .button { gap: 5px; padding: 0 9px; }
        }
    </style>
</head>
<body>
<aside class="sidebar" aria-label="Navigasi utama">
    <a class="brand" href="{{ route('home') }}" aria-label="Stokify beranda">
        <span class="brand-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" width="22" height="22"><path d="M4 8.5 12 4l8 4.5v8L12 21l-8-4.5v-8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m4.5 8.7 7.5 4.2 7.5-4.2M12 13v7.5M8 6.3l8 4.6" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
        </span>
        <span class="brand-copy"><span class="brand-name">Stoki<span>fy</span></span><span class="brand-tagline">Smart Inventory Management</span></span>
    </a>
    <button class="menu-toggle" type="button" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="primary-navigation">
        <svg class="icon-menu" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        <svg class="icon-close" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
    </button>
    <p class="nav-label">Menu utama</p>
    <nav class="nav-list" id="primary-navigation">
        <a class="nav-link" href="{{ route('home') }}">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.7"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.7"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.7"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.7"/></svg>
            Dashboard
        </a>
        <a class="nav-link active" href="{{ route('products.index') }}" aria-current="page">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m4 7 8-4 8 4v10l-8 4-8-4V7Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m4.5 7.2 7.5 4 7.5-4M12 11.5V21" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
            Data Produk
        </a>
        <a class="nav-link" href="{{ route('stock-in.index') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 16V4m0 0L7.5 8.5M12 4l4.5 4.5M5 14v5a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>Barang Masuk</a>
        <a class="nav-link" href="{{ route('stock-out.index') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 4v12m0 0 4.5-4.5M12 16l-4.5-4.5M5 14v5a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>Barang Keluar</a>
        <a class="nav-link" href="{{ route('stock-report.index') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 20V10m7 10V4m7 16v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M3 20.5h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>Laporan Stok</a>
    </nav>
    <form class="logout-form" method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="nav-link logout-button" type="submit"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>Keluar</button>
    </form>
    <div class="sidebar-bottom">
        <div class="help-card"><strong>Butuh bantuan?</strong><p>Kelola stok kedai jadi lebih mudah dengan Stokify.</p></div>
        <div class="profile"><div class="avatar" aria-hidden="true">AS</div><div><div class="profile-name">Admin Kedai</div><div class="profile-role">Administrator</div></div></div>
    </div>
</aside>

<main class="main">
    <header class="topbar">
        <div class="breadcrumb">Halaman <span aria-hidden="true">/</span> <strong>Data Produk</strong></div>
        <a class="top-link" href="{{ route('home') }}">Kembali ke Dashboard</a>
    </header>
    <section class="content" aria-labelledby="page-title">
        <div class="heading">
            <div>
                <p class="eyebrow">Inventaris kedai</p>
                <h1 id="page-title">Data Produk</h1>
                <p class="subtitle">Kelola nama, kategori, harga, dan jumlah stok produk.</p>
            </div>
            <a class="button" href="{{ route('products.create') }}">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Tambah Produk
            </a>
        </div>

        @if (session('status'))
            <div class="notice" role="status">{{ session('status') }}</div>
        @endif

        <section class="panel" aria-label="Daftar produk">
            <div class="toolbar">
                <div>
                    <div class="toolbar-title">Semua Produk</div>
                    <div class="toolbar-count">{{ $products->total() }} produk terdaftar</div>
                </div>
                <form class="filters-form" method="GET" action="{{ route('products.index') }}" id="product-filters">
                    <label class="sr-only" for="search-product">Cari berdasarkan nama produk</label>
                    <input class="search-input" id="search-product" type="search" name="q" value="{{ $search }}" maxlength="100" placeholder="Cari nama produk...">
                    <label class="sr-only" for="filter-category">Filter kategori</label>
                    <select class="category-select" id="filter-category" name="category">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $categoryOption)
                            <option value="{{ $categoryOption }}" @selected($category === $categoryOption)>{{ $categoryOption }}</option>
                        @endforeach
                    </select>
                    <button class="button search-button" type="submit">Cari</button>
                    @if ($search !== '' || $category !== '')
                        <a class="small-button" href="{{ route('products.index') }}">Reset</a>
                    @endif
                </form>
            </div>
            @if ($products->isNotEmpty())
                <div class="table-wrap">
                    <p class="table-scroll-hint">Geser tabel ke samping untuk melihat kolom lainnya.</p>
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Jumlah Stok</th>
                                <th scope="col">Status Stok</th>
                                <th scope="col">Satuan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td><span class="product-name">{{ $product->name }}</span></td>
                                    <td><span class="category">{{ $product->category }}</span></td>
                                    <td class="price">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="stock">{{ number_format($product->stock, 0, ',', '.') }}</span>
                                        <span class="stock-detail">Batas minimum: {{ number_format($product->min_stock, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        @if ($product->isLowStock())
                                            <span class="stock-status low">Stok menipis</span>
                                        @else
                                            <span class="stock-status safe">Aman</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->unit }}</td>
                                    <td>
                                        <div class="actions">
                                            <a class="small-button" href="{{ route('products.edit', $product) }}">Edit</a>
                                            <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="small-button delete" type="submit">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <span>Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }} dari {{ $products->total() }} produk</span>
                    <div class="pagination">{{ $products->onEachSide(1)->links() }}</div>
                </div>
            @else
                <div class="empty">
                    <strong>{{ $search !== '' || $category !== '' ? 'Produk tidak ditemukan' : 'Belum ada produk' }}</strong>
                    <p>{{ $search !== '' || $category !== '' ? 'Coba kata kunci atau kategori lain.' : 'Tambahkan produk pertama agar inventaris kedai mulai tercatat.' }}</p>
                    @if ($search !== '' || $category !== '')
                        <a class="small-button" href="{{ route('products.index') }}">Reset filter</a>
                    @else
                        <a class="button" href="{{ route('products.create') }}">Tambah Produk</a>
                    @endif
                </div>
            @endif
        </section>
    </section>
</main>
<script>
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = menuToggle.closest('.sidebar');

    menuToggle.addEventListener('click', () => {
        const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', String(!isExpanded));
        menuToggle.setAttribute('aria-label', isExpanded ? 'Buka menu navigasi' : 'Tutup menu navigasi');
        sidebar.classList.toggle('nav-open', !isExpanded);
    });

    const filterForm = document.getElementById('product-filters');
    const searchField = document.getElementById('search-product');
    const categoryField = document.getElementById('filter-category');
    let searchTimer;

    searchField.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => filterForm.requestSubmit(), 350);
    });
    categoryField.addEventListener('change', () => filterForm.requestSubmit());
</script>
</body>
</html>
