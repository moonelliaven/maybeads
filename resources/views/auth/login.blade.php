<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Maybeads</title>
    <meta name="description" content="Login Maybeads.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
</head>
<body data-page="login">
    <div class="page-shell">
        <header class="topbar">
            <div class="brand">MAYBEADS</div>

            <nav class="main-nav" aria-label="Main navigation">
                <a href="/">Home</a>
                <a href="#">Products</a>
                <a href="#">Testimonial</a>
                <a href="#">Contact</a>
            </nav>

            <div class="top-actions">
                <a href="/" class="icon-btn" aria-label="Back home">↩</a>
                <a href="/auth/register" class="btn btn-secondary">Register</a>
            </div>
        </header>

        <main class="auth-layout auth-shell">
            <section class="auth-visual" aria-label="Maybeads promo panel">
                <div class="hero-product">
                    <div class="product-figure"></div>
                    <div class="badge">Drop 04</div>
                    <div class="hero-copy">
                        <p class="eyebrow">Y2K streetwear</p>
                        <h2>Built for bold identities.</h2>
                        <p>Modern silhouettes with attitude, crafted for the next generation of street culture.</p>
                    </div>
                </div>
            </section>

            <section class="auth-panel">
                <div class="auth-card">
                    <p class="section-kicker">Welcome back</p>
                    <h1>Login</h1>
                    <p class="subtitle">Continue your story with curated essentials and exclusive drops.</p>

                    <form class="auth-form" method="POST" action="/auth/login">
                        @csrf

                        <div class="field-group">
                            <label for="identifier">Username or Email</label>
                            <input id="identifier" type="text" name="identifier" placeholder="username or email@example.com" value="{{ old('identifier') ?: old('email') }}" required>
                        </div>

                        <div class="field-group">
                            <label for="password">Password</label>
                            <div class="password-field">
                                <input id="password" type="password" name="password" placeholder="Enter your password" required>
                                <button type="button" class="password-toggle" aria-label="Show password" aria-pressed="false">
                                    <svg class="eye-icon eye-open" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                                        <circle cx="12" cy="12" r="2.5"></circle>
                                    </svg>
                                    <svg class="eye-icon eye-closed" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m3 3 18 18"></path>
                                        <path d="M10.6 6.2A10.7 10.7 0 0 1 12 6c6.5 0 10 6 10 6a17.2 17.2 0 0 1-3.1 3.7"></path>
                                        <path d="M6.5 6.8C3.7 8.5 2 12 2 12s3.5 6 10 6a10.7 10.7 0 0 0 3.4-.6"></path>
                                        <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="form-row">
                            <label class="check-wrap">
                                <input type="checkbox" name="remember">
                                <span>Remember me</span>
                            </label>
                            <a href="#" class="forgot-link">Forgot password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>

                    <div class="divider">or</div>

                    <p class="helper-text">
                        New here? <a href="/auth/register" class="inline-link">Create an account</a>
                    </p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
