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
                <a href="/register" class="btn btn-secondary">Register</a>
            </div>
        </header>

        <main class="auth-layout">
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

                    <form class="auth-form" method="POST" action="#">
                        @csrf

                        <div class="field-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" placeholder="email@example.com" required>
                        </div>

                        <div class="field-group">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" placeholder="Enter your password" required>
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
                        New here? <a href="/register" class="inline-link">Create an account</a>
                    </p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
