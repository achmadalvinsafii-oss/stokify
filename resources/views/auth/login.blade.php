<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f2f6f3">
    <title>Stokify</title>
    <style>
        :root {
            --ink: #17231e;
            --muted: #78847e;
            --line: #e2e9e4;
            --green: #17845b;
            --green-dark: #116b49;
            --green-soft: #e8f5ee;
            --red: #b94139;
            --canvas: #f2f6f3;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        * { box-sizing: border-box; }
        body { display: grid; min-height: 100vh; margin: 0; place-items: center; padding: 24px; background: radial-gradient(circle at 15% 15%, #e2f1e8 0, transparent 34%), var(--canvas); color: var(--ink); }
        .login-panel { width: min(100%, 420px); padding: 34px; border: 1px solid var(--line); border-radius: 12px; background: #fff; box-shadow: 0 18px 50px rgba(35, 67, 48, .08); }
        .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 32px; }
        .brand-mark { display: grid; width: 44px; height: 44px; flex: 0 0 auto; place-items: center; border-radius: 12px; background: var(--green); color: #fff; }
        .brand-mark svg { width: 25px; height: 25px; }
        .brand-name { margin: 0; font-size: 20px; font-weight: 750; }
        .brand-name span { color: var(--green); }
        .tagline { margin: 3px 0 0; color: var(--muted); font-size: 11px; }
        h1 { margin: 0; font-size: 25px; font-weight: 750; }
        .intro { margin: 8px 0 25px; color: var(--muted); font-size: 13px; line-height: 1.5; }
        .field-group { display: grid; gap: 7px; margin-top: 17px; }
        label { color: #46564c; font-size: 12px; font-weight: 700; }
        input { width: 100%; min-height: 46px; padding: 0 13px; border: 1px solid var(--line); border-radius: 8px; outline: none; background: #fff; color: var(--ink); font: inherit; font-size: 14px; }
        input::placeholder { color: #a4ada8; }
        input:focus { border-color: #8fc5a7; box-shadow: 0 0 0 3px rgba(23, 132, 91, .1); }
        input[aria-invalid="true"] { border-color: #d77870; }
        .error { color: var(--red); font-size: 11px; }
        .submit { display: flex; width: 100%; min-height: 46px; align-items: center; justify-content: center; gap: 9px; margin-top: 24px; border: 0; border-radius: 8px; background: var(--green); color: #fff; font: inherit; font-size: 13px; font-weight: 700; cursor: pointer; }
        .submit:hover { background: var(--green-dark); }
        .submit:focus-visible { outline: 3px solid rgba(23, 132, 91, .28); outline-offset: 2px; }
        .submit svg { width: 17px; height: 17px; }
        .security-note { display: flex; align-items: center; justify-content: center; gap: 7px; margin: 20px 0 0; color: #8b9690; font-size: 10px; }
        .security-note svg { width: 14px; height: 14px; color: var(--green); }
        @media (max-width: 480px) {
            body { padding: 16px; }
            .login-panel { padding: 26px 21px; }
            .brand { margin-bottom: 27px; }
        }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; } }
    </style>
</head>
<body>
    <main class="login-panel">
        <div class="brand">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none"><path d="M4 8.5 12 4l8 4.5v8L12 21l-8-4.5v-8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m4.5 8.7 7.5 4.2 7.5-4.2M12 13v7.5M8 6.3l8 4.6" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
            </span>
            <div>
                <p class="brand-name">Stoki<span>fy</span></p>
                <p class="tagline">Smart Inventory Management</p>
            </div>
        </div>

        <h1>Masuk ke Stokify</h1>
        <p class="intro">Masukkan username dan password untuk melanjutkan.</p>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="field-group">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" autocomplete="username" required autofocus maxlength="255" placeholder="Username Anda" @if ($errors->has('username')) aria-invalid="true" aria-describedby="username-error" @endif>
                @error('username')
                    <span class="error" id="username-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="field-group">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Password Anda" @if ($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif>
                @error('password')
                    <span class="error" id="password-error">{{ $message }}</span>
                @enderror
            </div>

            <button class="submit" type="submit">
                Masuk
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </form>

        <p class="security-note">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3 19 6v5c0 4.8-2.9 8.2-7 10-4.1-1.8-7-5.2-7-10V6l7-3Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Sesi login dilindungi
        </p>
    </main>
</body>
</html>