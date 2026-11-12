<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7f6">
    <title>Stokify</title>
    <style>
        :root {
            --ink: #17231e; --muted: #78847e; --line: #e8ede9; --surface: #fff; --canvas: #f5f7f6;
            --green: #17845b; --green-dark: #116b49; --green-soft: #e8f5ee; --red: #c94d45;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--canvas); color: var(--ink); }
        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }
        .sidebar { position: fixed; inset: 0 auto 0 0; z-index: 2; display: flex; width: 248px; flex-direction: column; padding: 28px 18px 20px; border-right: 1px solid var(--line); background: var(--surface); }
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
        .heading { margin-bottom: 22px; }
        .eyebrow { margin: 0 0 7px; color: var(--green); font-size: 11px; font-weight: 750; letter-spacing: .8px; text-transform: uppercase; }
        h1 { margin: 0; font-size: clamp(24px, 3vw, 30px); letter-spacing: -1px; }
        .subtitle { margin: 7px 0 0; color: var(--muted); font-size: 13px; }
        .layout { display: grid; grid-template-columns: minmax(270px, .75fr) minmax(0, 1.5fr); align-items: start; gap: 18px; }
        .panel { overflow: hidden; border: 1px solid var(--line); border-radius: 13px; background: var(--surface); }
        .panel-heading { padding: 18px 19px 15px; border-bottom: 1px solid var(--line); }
        .panel-heading h2 { margin: 0; font-size: 14px; letter-spacing: -.2px; }
        .panel-heading p { margin: 5px 0 0; color: var(--muted); font-size: 10px; line-height: 1.5; }
        .entry-form { display: grid; gap: 15px; padding: 18px 19px 20px; }
        .field-group { display: grid; gap: 7px; }
        .field-group label { color: #536159; font-size: 11px; font-weight: 700; }
        .field { width: 100%; min-height: 41px; padding: 0 11px; border: 1px solid var(--line); border-radius: 8px; outline: none; background: white; color: var(--ink); font-size: 11px; }
        textarea.field { min-height: 85px; padding: 10px 11px; resize: vertical; line-height: 1.5; }
        .field:focus { border-color: #9acbb2; box-shadow: 0 0 0 3px rgba(23,132,91,.08); }
        .field.invalid { border-color: #e59a94; }
        .field::placeholder { color: #a4ada8; }
        .error { color: var(--red); font-size: 10px; }
        .product-hint { color: var(--muted); font-size: 10px; line-height: 1.45; }
        .button { display: inline-flex; min-height: 41px; align-items: center; justify-content: center; gap: 8px; padding: 0 14px; border: 0; border-radius: 9px; background: var(--green); color: #fff; font-size: 11px; font-weight: 700; cursor: pointer; }
        .button:hover { background: var(--green-dark); }
        .button:disabled { background: #a8b7ae; cursor: not-allowed; }
        .button svg { width: 16px; height: 16px; }
        .alert { padding: 12px 14px; border: 1px solid #f1d0cd; border-radius: 9px; background: #fff3f1; color: var(--red); font-size: 11px; }
        .alert ul { margin: 6px 0 0; padding-left: 18px; }
        .notice { margin-bottom: 15px; padding: 12px 15px; border: 1px solid #cce8d7; border-radius: 9px; background: var(--green-soft); color: var(--green-dark); font-size: 12px; }
        .history-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; padding: 18px 19px 15px; }
        .history-heading h2 { margin: 0; font-size: 14px; }
        .history-heading p { margin: 5px 0 0; color: var(--muted); font-size: 10px; }
        .count { flex: 0 0 auto; padding: 6px 8px; border-radius: 7px; background: #f2f5f3; color: #68756e; font-size: 10px; font-weight: 650; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        thead { background: #fafbfa; }
        th { padding: 11px 14px; color: #8a9690; font-size: 9px; font-weight: 750; letter-spacing: .65px; text-transform: uppercase; white-space: nowrap; }
        td { padding: 12px 14px; border-top: 1px solid #f0f2f0; color: #536159; font-size: 10px; vertical-align: top; }
        th:first-child, td:first-child { padding-left: 19px; }
        th:last-child, td:last-child { padding-right: 19px; }
        .product-name { color: var(--ink); font-size: 10px; font-weight: 700; }
        .category { display: block; margin-top: 3px; color: #9aa49e; font-size: 9px; }
        .quantity { color: var(--green); font-size: 11px; font-weight: 750; white-space: nowrap; }
        .notes { max-width: 190px; color: var(--muted); white-space: normal; line-height: 1.5; overflow-wrap: anywhere; }
        .empty { padding: 40px 18px; text-align: center; }
        .empty strong { display: block; font-size: 12px; }
        .empty p { margin: 7px 0 0; color: var(--muted); font-size: 10px; }
        .table-footer { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 18px; border-top: 1px solid var(--line); color: var(--muted); font-size: 10px; }
        .pagination nav { display: flex; gap: 5px; }
        .pagination a, .pagination span { display: inline-grid; min-width: 27px; height: 27px; place-items: center; padding: 0 7px; border: 1px solid var(--line); border-radius: 7px; color: #647169; font-size: 10px; }
        .pagination span[aria-current="page"] { border-color: var(--green); background: var(--green); color: white; }
        .pagination span[aria-disabled="true"] { opacity: .45; }
        .no-products { padding: 12px; border: 1px solid #f2dfbd; border-radius: 8px; background: #fff8eb; color: #93651e; font-size: 10px; line-height: 1.5; }
        .no-products a { color: var(--green-dark); font-weight: 700; text-decoration: underline; }
        @media (max-width: 1100px) {
            .sidebar { width: 220px; } .main { margin-left: 220px; }
            .topbar, .content { padding-right: 25px; padding-left: 25px; }
            .layout { grid-template-columns: minmax(245px, .8fr) minmax(0, 1.3fr); gap: 12px; }
            .entry-form { padding-right: 15px; padding-left: 15px; }
        }
        @media (max-width: 820px) {
            .layout { grid-template-columns: 1fr; }
            .entry-form { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .entry-form .field-group.notes, .entry-form .alert, .entry-form .button { grid-column: 1 / -1; }
        }
        @media (max-width: 760px) {
            .sidebar { position: relative; width: 100%; height: auto; padding: 14px 16px 10px; border-right: 0; border-bottom: 1px solid var(--line); }
            .brand { padding: 0 48px 13px 2px; } .brand-mark { width: 35px; height: 35px; border-radius: 11px; } .brand-name { font-size: 17px; }
            .menu-toggle { position: absolute; top: 12px; right: 16px; display: grid; width: 40px; height: 40px; place-items: center; border: 1px solid var(--line); border-radius: 9px; background: white; color: var(--ink); }
            .menu-toggle svg { width: 20px; height: 20px; } .menu-toggle .icon-close, .sidebar.nav-open .menu-toggle .icon-menu { display: none; } .sidebar.nav-open .menu-toggle .icon-close { display: block; }
            .sidebar-bottom, .nav-label { display: none; } .sidebar.nav-open .nav-label { display: block; }
            .nav-list { display: none; gap: 4px; padding: 0 0 4px; } .sidebar.nav-open .nav-list { display: grid; }
            .nav-link { min-height: 44px; gap: 10px; padding: 0 12px; font-size: 13px; } .nav-link svg { width: 18px; height: 18px; }
            .logout-form { display: none; } .sidebar.nav-open .logout-form { display: block; }
            .main { min-height: calc(100vh - 105px); margin-left: 0; }
            .topbar { min-height: 55px; padding: 0 17px; } .breadcrumb { font-size: 11px; }
            .content { padding: 25px 17px 32px; } .entry-form { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 520px) {
            .content { padding-right: 12px; padding-left: 12px; }
            .topbar { padding-right: 12px; padding-left: 12px; }
            .entry-form { grid-template-columns: 1fr; }
            .entry-form .field-group.notes, .entry-form .alert, .entry-form .button { grid-column: auto; }
            .history-heading { padding-right: 14px; padding-left: 14px; }
            th, td { padding-right: 10px; padding-left: 10px; }
            th:first-child, td:first-child { padding-left: 14px; }
            th:last-child, td:last-child { padding-right: 14px; }
        }
    </style>
</head>
<body>
<aside class="sidebar" aria-label="Navigasi utama">
    <a class="brand" href="{{ route('home') }}" aria-label="Stokify beranda">
        <span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" width="22" height="22"><path d="M4 8.5 12 4l8 4.5v8L12 21l-8-4.5v-8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m4.5 8.7 7.5 4.2 7.5-4.2M12 13v7.5M8 6.3l8 4.6" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></span>
        <span class="brand-copy"><span class="brand-name">Stoki<span>fy</span></span><span class="brand-tagline">Smart Inventory Management</span></span>
    </a>
    <button class="menu-toggle" type="button" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="primary-navigation">
        <svg class="icon-menu" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        <svg class="icon-close" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
    </button>
    <p class="nav-label">Menu utama</p>
    <nav class="nav-list" id="primary-navigation">
        <a class="nav-link" href="{{ route('home') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.7"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.7"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.7"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.7"/></svg>Dashboard</a>
        <a class="nav-link" href="{{ route('products.index') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m4 7 8-4 8 4v10l-8 4-8-4V7Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m4.5 7.2 7.5 4 7.5-4M12 11.5V21" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>Data Produk</a>
        <a class="nav-link active" href="{{ route('stock-in.index') }}" aria-current="page"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 16V4m0 0L7.5 8.5M12 4l4.5 4.5M5 14v5a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>Barang Masuk</a>
        <a class="nav-link" href="{{ route('stock-out.index') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 4v12m0 0 4.5-4.5M12 16l-4.5-4.5M5 14v5a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>Barang Keluar</a>
        <a class="nav-link" href="{{ route('stock-report.index') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 20V10m7 10V4m7 16v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M3 20.5h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>Laporan Stok</a>
    </nav>
    <form class="logout-form" method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="nav-link logout-button" type="submit"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>Keluar</button>
    </form>
    <div class="sidebar-bottom"><div class="help-card"><strong>Butuh bantuan?</strong><p>Kelola stok kedai jadi lebih mudah dengan Stokify.</p></div><div class="profile"><div class="avatar" aria-hidden="true">AS</div><div><div class="profile-name">Admin Kedai</div><div class="profile-role">Administrator</div></div></div></div>
</aside>
<main class="main">
    <header class="topbar">
        <div class="breadcrumb">Halaman <span aria-hidden="true">/</span> <strong>Barang Masuk</strong></div>
        <a class="top-link" href="{{ route('home') }}">Kembali ke Dashboard</a>
    </header>
    <section class="content" aria-labelledby="page-title">
        <div class="heading">
            <p class="eyebrow">Pergerakan inventaris</p>
            <h1 id="page-title">Barang Masuk</h1>
            <p class="subtitle">Catat produk yang diterima dan perbarui stok secara otomatis.</p>
        </div>

        @if (session('status'))
            <div class="notice" role="status">{{ session('status') }}</div>
        @endif

        <div class="layout">
            <section class="panel" aria-labelledby="entry-heading">
                <div class="panel-heading">
                    <h2 id="entry-heading">Catat Barang Masuk</h2>
                    <p>Pilih produk dan masukkan jumlah barang yang diterima.</p>
                </div>
                <form class="entry-form" method="POST" action="{{ route('stock-in.store') }}">
                    @csrf
                    @if ($errors->any())
                        <div class="alert" role="alert">
                            Periksa kembali data yang dimasukkan.
                            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <div class="field-group">
                        <label for="product_id">Produk</label>
                        <select class="field @error('product_id') invalid @enderror" id="product_id" name="product_id" required @disabled($products->isEmpty())>
                            <option value="">Pilih produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                    {{ $product->name }} — stok {{ number_format($product->stock, 0, ',', '.') }} {{ $product->unit }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field-group">
                        <label for="quantity">Jumlah Barang Masuk</label>
                        <input class="field @error('quantity') invalid @enderror" id="quantity" name="quantity" type="number" value="{{ old('quantity') }}" min="1" max="2147483647" step="1" placeholder="Contoh: 10" required>
                        @error('quantity')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field-group">
                        <label for="entry_date">Tanggal Barang Masuk</label>
                        <input class="field @error('entry_date') invalid @enderror" id="entry_date" name="entry_date" type="date" value="{{ old('entry_date', now()->toDateString()) }}" required>
                        @error('entry_date')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field-group notes">
                        <label for="notes">Keterangan / Catatan <span style="font-weight:400;color:#9aa49e">(opsional)</span></label>
                        <textarea class="field @error('notes') invalid @enderror" id="notes" name="notes" maxlength="1000" placeholder="Contoh: Pembelian dari supplier">{{ old('notes') }}</textarea>
                        @error('notes')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    @if ($products->isEmpty())
                        <div class="no-products">Belum ada produk untuk dipilih. <a href="{{ route('products.create') }}">Tambah produk terlebih dahulu.</a></div>
                    @endif
                    <button class="button" type="submit" @disabled($products->isEmpty())>
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 16V4m0 0L7.5 8.5M12 4l4.5 4.5M5 14v5a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Simpan Barang Masuk
                    </button>
                </form>
            </section>

            <section class="panel" aria-labelledby="history-heading">
                <div class="history-heading">
                    <div>
                        <h2 id="history-heading">Riwayat Barang Masuk</h2>
                        <p>Transaksi yang sudah dicatat.</p>
                    </div>
                    <span class="count">{{ $entries->total() }} transaksi</span>
                </div>
                @if ($entries->isNotEmpty())
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Produk</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($entries as $entry)
                                    <tr>
                                        <td>{{ $entry->entry_date->format('d M Y') }}</td>
                                        <td>
                                            <span class="product-name">{{ $entry->product_name }}</span>
                                            @if ($entry->product)
                                                <span class="category">{{ $entry->product->category }}</span>
                                            @endif
                                        </td>
                                        <td class="quantity">+{{ number_format($entry->quantity, 0, ',', '.') }}</td>
                                        <td class="notes">{{ $entry->notes ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table-footer">
                        <span>Menampilkan {{ $entries->firstItem() }}–{{ $entries->lastItem() }} dari {{ $entries->total() }}</span>
                        <div class="pagination">{{ $entries->onEachSide(1)->links() }}</div>
                    </div>
                @else
                    <div class="empty">
                        <strong>Belum ada riwayat barang masuk</strong>
                        <p>Transaksi yang disimpan akan muncul di sini.</p>
                    </div>
                @endif
            </section>
        </div>
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
</script>
</body>
</html>
