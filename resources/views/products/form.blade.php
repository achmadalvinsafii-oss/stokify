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
        button, input { font: inherit; }
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
        .content { width: min(100%, 1050px); margin: 0 auto; padding: 34px 42px 44px; }
        .heading { margin-bottom: 22px; }
        .eyebrow { margin: 0 0 7px; color: var(--green); font-size: 11px; font-weight: 750; letter-spacing: .8px; text-transform: uppercase; }
        h1 { margin: 0; font-size: clamp(24px, 3vw, 30px); letter-spacing: -1px; }
        .subtitle { margin: 7px 0 0; color: var(--muted); font-size: 13px; }
        .panel { padding: 23px; border: 1px solid var(--line); border-radius: 13px; background: var(--surface); }
        .fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 19px 16px; }
        .field-group { display: grid; align-content: start; gap: 7px; }
        .field-group.full { grid-column: 1 / -1; }
        .field-group label { color: #536159; font-size: 11px; font-weight: 700; }
        .field { width: 100%; min-height: 42px; padding: 0 12px; border: 1px solid var(--line); border-radius: 8px; outline: none; background: white; color: var(--ink); font-size: 12px; }
        .field::placeholder { color: #a4ada8; }
        .field-group small { color: var(--muted); font-size: 10px; }
        .error { color: var(--red); font-size: 10px; }
        .field.invalid { border-color: #e59a94; }
        .form-actions { display: flex; justify-content: flex-end; gap: 9px; margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--line); }
        .button { display: inline-flex; min-height: 40px; align-items: center; justify-content: center; padding: 0 15px; border: 1px solid var(--line); border-radius: 8px; background: white; color: #56645c; font-size: 11px; font-weight: 700; cursor: pointer; }
        .button.primary { border-color: var(--green); background: var(--green); color: white; }
        .button.primary:hover { border-color: var(--green-dark); background: var(--green-dark); }
        .alert { margin-bottom: 16px; padding: 12px 15px; border: 1px solid #f1d0cd; border-radius: 9px; background: #fff3f1; color: var(--red); font-size: 11px; }
        .alert ul { margin: 6px 0 0; padding-left: 18px; }
        @media (max-width: 1050px) { .sidebar { width: 220px; } .main { margin-left: 220px; } .topbar, .content { padding-right: 26px; padding-left: 26px; } }
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
            .content { padding: 25px 17px 32px; } .panel { padding: 18px; }
        }
        @media (max-width: 520px) { .fields { grid-template-columns: 1fr; gap: 15px; } .field-group.full { grid-column: auto; } .content { padding-right: 12px; padding-left: 12px; } .panel { padding: 15px; } }
        @media (max-width: 390px) { .form-actions .button { flex: 1; } }
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
        <a class="nav-link active" href="{{ route('products.index') }}" aria-current="page"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m4 7 8-4 8 4v10l-8 4-8-4V7Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m4.5 7.2 7.5 4 7.5-4M12 11.5V21" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>Data Produk</a>
        <a class="nav-link" href="{{ route('stock-in.index') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 16V4m0 0L7.5 8.5M12 4l4.5 4.5M5 14v5a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>Barang Masuk</a>
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
        <div class="breadcrumb">Halaman <span aria-hidden="true">/</span> <a href="{{ route('products.index') }}">Data Produk</a> <span aria-hidden="true">/</span> <strong>{{ $pageTitle }}</strong></div>
        <a class="top-link" href="{{ route('products.index') }}">Kembali ke Data Produk</a>
    </header>
    <section class="content" aria-labelledby="page-title">
        <div class="heading">
            <p class="eyebrow">Inventaris kedai</p>
            <h1 id="page-title">{{ $pageTitle }}</h1>
            <p class="subtitle">Isi informasi produk dengan lengkap untuk mencatat stok.</p>
        </div>

        @if ($errors->any())
            <div class="alert" role="alert">
                Periksa kembali data yang dimasukkan.
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="panel" method="POST" action="{{ $formAction }}">
            @csrf
            @if ($formMethod !== 'POST')
                @method($formMethod)
            @endif
            <div class="fields">
                <div class="field-group">
                    <label for="name">Nama Produk</label>
                    <input class="field @error('name') invalid @enderror" id="name" name="name" type="text" value="{{ old('name', $product->name) }}" maxlength="150" placeholder="Contoh: Kopi Arabika 250g" required autofocus>
                    @error('name')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="field-group">
                    <label for="category">Kategori</label>
                    <input class="field @error('category') invalid @enderror" id="category" name="category" type="text" value="{{ old('category', $product->category) }}" maxlength="100" placeholder="Contoh: Kopi" required>
                    @error('category')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="field-group">
                    <label for="price">Harga (Rp)</label>
                    <input class="field @error('price') invalid @enderror" id="price" name="price" type="number" value="{{ old('price', $product->price) }}" min="0" max="9999999999.99" step="0.01" placeholder="0" required>
                    @error('price')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="field-group">
                    <label for="stock">Jumlah Stok</label>
                    <input class="field @error('stock') invalid @enderror" id="stock" name="stock" type="number" value="{{ old('stock', $product->stock) }}" min="0" max="2147483647" step="1" placeholder="0" required>
                    @error('stock')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="field-group">
                    <label for="min_stock">Batas Minimum Stok</label>
                    <input class="field @error('min_stock') invalid @enderror" id="min_stock" name="min_stock" type="number" value="{{ old('min_stock', $product->exists ? $product->min_stock : 10) }}" min="0" max="2147483647" step="1" required>
                    <small>Stok pada atau di bawah batas ini akan ditandai menipis. Default 10.</small>
                    @error('min_stock')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="field-group full">
                    <label for="unit">Satuan</label>
                    <input class="field @error('unit') invalid @enderror" id="unit" name="unit" type="text" value="{{ old('unit', $product->unit) }}" maxlength="30" placeholder="Contoh: pcs, kg, botol, pak" required>
                    <small>Satuan stok, misalnya pcs, kg, botol, atau pak.</small>
                    @error('unit')<span class="error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-actions">
                <a class="button" href="{{ route('products.index') }}">Batal</a>
                <button class="button primary" type="submit">{{ $formMethod === 'POST' ? 'Simpan Produk' : 'Simpan Perubahan' }}</button>
            </div>
        </form>
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
