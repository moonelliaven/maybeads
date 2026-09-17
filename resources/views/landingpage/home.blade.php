<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maybeads | Landing page</title>
    <meta name="description" content="MaybeAds landing page inspired by vintage Y2K streetwear aesthetic.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/landingpage/home.css', 'resources/js/landingpage/home.js'])
</head>
<body>
    <div class="page-shell">
        <header class="topbar reveal-up">
            <div class="brand">MAYBEADS</div>

            <nav class="main-nav" aria-label="Main navigation">
                <a href="#">Home</a>
                <a href="#" class="nav-link-with-arrow">
                    <span>Products</span>
                    <span class="nav-arrow" aria-hidden="true">↗</span>
                </a>
                <a href="#">Testimonial</a>
                <a href="#">Contact</a>
            </nav>

            <div class="top-actions">
                <a href="/auth/login" class="login-link" aria-label="Login">
                    <span class="login-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M20 21a8 8 0 0 0-16 0" />
                            <circle cx="12" cy="8" r="4" />
                        </svg>
                    </span>
                    <span>Login</span>
                </a>
                <button class="icon-btn" aria-label="Cart">
                    <span>🛒</span>
                </button>
                <button class="order-btn" btn-onclick="">ORDER NOW</button>
            </div>
        </header>

        <main class="landing-main">
            <section class="hero-section">
                <div class="hero-copy reveal-up">
                    <p class="eyebrow">DROP 04 / LIVE</p>
                    <h1>
                        REVIVE THE<br>
                        <span>2000S AESTHETIC</span><br>
                        MODERNIZED.
                    </h1>
                    <p class="lead">
                        Curated high-grade Y2K streetwear meets precision contemporary silhouettes.
                        Bold graphic cuts, baby tees, chrome hardware, and signature street culture.
                    </p>

                    <div class="hero-actions">
                        <a href="#" class="btn btn-primary">EXPLORE COLLECTION</a>
                        <a href="#" class="btn btn-secondary">LATEST DROP</a>
                    </div>

                    <div class="hero-meta">
                        <span>☑ 6K+ GLOBAL BOUTIQUE</span>
                        <span>☑ 4.8/5 RATING (2,200+ REVIEW)</span>
                        <span>☑ 100% VERIFIED</span>
                    </div>
                </div>

                <div class="hero-visual reveal-up" aria-label="Featured product image">
                    <div class="hero-product-photo">
                        <div class="badge badge-top">PARK.</div>
                    </div>

                    <div class="floating-card card-left">
                        <div class="mini-thumb thumb-one"></div>
                        <div class="mini-content">
                            <span class="mini-label">AERO-09 RAVE</span>
                            <strong>LIMITED 240 PCS</strong>
                        </div>
                    </div>

                    <div class="floating-card card-right">
                        <div class="mini-thumb thumb-two"></div>
                        <div class="mini-content">
                            <span class="mini-label">ZERO FAST</span>
                            <strong>AUTHENTIC CUT</strong>
                        </div>
                    </div>
                </div>
            </section>

            <div class="brand-strip reveal-up" aria-label="Fashion brands">
                <span>WORLDWIDE SHIPPING</span>
                <span>AUTHENTIC Y2K FITS</span>
                <span>WEEKLY DROPS</span>
                <span>SUSTAINABLE PACKAGING</span>
                <span>ZERO FAST-FASHION</span>
            </div>

            <section class="product-section reveal-up">
                <div class="section-header">
                    <p class="section-kicker">THE Y2K VAULT</p>
                    <h2>Curated pieces to define the era.</h2>
                    <div class="section-tabs">
                        @foreach($categories as $category)
                            <button class="tab {{ $loop->first ? 'active' : '' }}">{{ strtoupper($category->category_name) }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="product-grid">
                    @forelse($products as $product)
                        <article class="product-card reveal-up">
                            <div
                                class="product-image"
                                style="background-image: url('{{ $product->image ?: 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=900&q=80' }}');"
                            >
                                <button class="tag">{{ strtoupper($product->category->category_name ?? 'FEATURED') }}</button>
                            </div>
                            <div class="product-info">
                                <span class="product-type">{{ $product->category->category_name ?? 'Featured' }} / Drop 04</span>
                                <h3>{{ $product->product_name }}</h3>
                                <div class="product-meta">
                                    <span class="price">${{ number_format($product->price, 2) }}</span>
                                    <span class="stock">{{ $product->stock > 0 ? ($product->stock <= 5 ? 'LOW STOCK' : 'IN STOCK') : 'OUT OF STOCK' }}</span>
                                </div>
                            </div>
                        </article>
                    @empty
                        <article class="product-card reveal-up">
                            <div class="product-image image-one">
                                <button class="tag">BEST SELLER</button>
                            </div>
                            <div class="product-info">
                                <span class="product-type">Tops / Drop 04</span>
                                <h3>Cyber Star Rhinestone Baby Tee</h3>
                                <div class="product-meta">
                                    <span class="price">$48.00</span>
                                    <span class="stock">LOW STOCK</span>
                                </div>
                            </div>
                        </article>

                        <article class="product-card reveal-up">
                            <div class="product-image image-two">
                                <button class="tag">LOOK TOOK</button>
                            </div>
                            <div class="product-info">
                                <span class="product-type">Bottoms / Hype</span>
                                <h3>Parachute Wide-Leg Cargo Pants</h3>
                                <div class="product-meta">
                                    <span class="price">$89.00</span>
                                    <span class="stock">NEW</span>
                                </div>
                            </div>
                        </article>

                        <article class="product-card reveal-up">
                            <div class="product-image image-three">
                                <button class="tag">LIMITED</button>
                            </div>
                            <div class="product-info">
                                <span class="product-type">Accessories</span>
                                <h3>Liquid Metal Zip-Up Track Jacket</h3>
                                <div class="product-meta">
                                    <span class="price">$115.00</span>
                                    <span class="stock">HOT</span>
                                </div>
                            </div>
                        </article>

                        <article class="product-card reveal-up">
                            <div class="product-image image-four">
                                <button class="tag">RISING</button>
                            </div>
                            <div class="product-info">
                                <span class="product-type">Jewelry / Custom</span>
                                <h3>Beaded Chunky Y2K Choker</h3>
                                <div class="product-meta">
                                    <span class="price">$34.00</span>
                                    <span class="stock">2 LEFT</span>
                                </div>
                            </div>
                        </article>
                    @endforelse
                </div>

                <div class="see-more-wrap">
                    <a href="#" class="see-more">VIEW ALL PRODUCTS <span>→</span></a>
                </div>
            </section>

            <section class="story-section reveal-up">
                <div class="story-media">
                    <div class="story-photo"></div>
                    <div class="story-note">MANIFESTO<br>THE MAYBEADS PROTOCOL</div>
                </div>

                <div class="story-copy">
                    <p class="section-kicker">EST. 2024 / ARCHIVE AUSTRALIA</p>
                    <h2>BRIDGING 2000S NOSTALGIA WITH HIGH-STREET TAILORING</h2>
                    <p>
                        We grow up in the era of futuristic CD player shines, neon arcs,
                        and unapologetic self-expression. MaybeAds reimagines that golden era
                        through premium streetwear and elevated silhouettes that celebrate an era of attitude.
                    </p>
                    <a href="#" class="btn btn-primary">ORDER NOW</a>
                </div>
            </section>

            <section class="testimonials reveal-up">
                <div class="section-header simple">
                    <p class="section-kicker">SPOTTED IN MAYBEADS</p>
                    <h2>Real styling from our global collective.</h2>
                </div>

                <div class="testimonial-grid">
                    <article class="quote-card">
                        <div class="quote-person">
                            <div class="avatar avatar-one"></div>
                            <div>
                                <strong>Mia K.</strong>
                                <span>Verified Buyer</span>
                            </div>
                        </div>
                        <p>
                            “The Parachute Cargo is unreal. Heavyweight drape, perfectly baggy without feeling oversized.”
                        </p>
                    </article>

                    <article class="quote-card">
                        <div class="quote-person">
                            <div class="avatar avatar-two"></div>
                            <div>
                                <strong>Leo R.</strong>
                                <span>Verified Buyer</span>
                            </div>
                        </div>
                        <p>
                            “Ordered the Rhinestone Tee and the fit is immaculate. Washed and styled in the right way.”
                        </p>
                    </article>

                    <article class="quote-card">
                        <div class="quote-person">
                            <div class="avatar avatar-three"></div>
                            <div>
                                <strong>Sasha V.</strong>
                                <span>Verified Buyer</span>
                            </div>
                        </div>
                        <p>
                            “Packaging is 10/10 with chrome stickers and the fit is so cool. It feels premium in every detail.”
                        </p>
                    </article>
                </div>
            </section>

            <section class="newsletter reveal-up">
                <div class="newsletter-box left reveal-up">
                    <p class="section-kicker">PRIVATE DROP</p>
                    <h2>JOIN THE MAYBEADS SYNDICATE</h2>
                    <p>Be the first to unlock secret drop passwords, private access, and exclusive edits.</p>

                    <form class="signup-form">
                        <label class="sr-only" for="email">Email address</label>
                        <input id="email" type="email" placeholder="Enter your email address...">
                        <button type="submit" class="btn btn-primary">NEXT DROP: CODE 05</button>
                    </form>
                </div>

                <div class="newsletter-box right reveal-up">
                    <p class="section-kicker">OUR COMMUNITY</p>
                    <h3>GET IN TOUCH</h3>

                    <div class="contact-form">
                        <div class="field-row">
                            <label>Email</label>
                            <input type="email" placeholder="support@maybeads.com">
                        </div>
                        <div class="field-row">
                            <label>Your Name</label>
                            <input type="text" placeholder="Your name">
                        </div>
                        <div class="field-row">
                            <label>Your Email</label>
                            <input type="text" placeholder="Your email">
                        </div>
                        <div class="field-row">
                            <label>How can we assist with your order, or custom sizing?</label>
                            <textarea rows="4" placeholder="Write your message..."></textarea>
                        </div>
                        <button class="btn btn-primary full">SEND MESSAGE</button>
                    </div>
                </div>
            </section>
        </main>

        <footer class="site-footer">
            <div class="footer-top">
                <div class="footer-brand">
                    <div class="brand">MAYBEADS</div>
                    <p>Curating authentic 2000s nostalgic fashion with modern streetwear and futuristic energy.</p>
                    <span>• maybeads@gmail.com</span>
                </div>

                <div class="footer-links">
                    <div>
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Products</a></li>
                            <li><a href="#">Testimonial</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4>Policies</h4>
                        <ul>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms of Service</a></li>
                            <li><a href="#">Return & Exchange</a></li>
                            <li><a href="#">Shipping Info</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4>Socials</h4>
                        <ul>
                            <li><a href="#">Instagram</a></li>
                            <li><a href="#">TikTok</a></li>
                            <li><a href="#">Pinterest</a></li>
                            <li><a href="#">Discord</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2024 MaybeAds. All rights reserved. Built for digital cyber nomads.</p>
                <div class="footer-badges">
                    <span>SSL 256-BIT</span>
                    <span>VERIFIED CHECKOUT</span>
                    <span>EXPRESS CARRIER</span>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
