<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Maybeads</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/admin-dashboard.css'])
</head>
<body>
    <div class="admin-page">
        @include('admin.partials.sidebar')

        <main class="admin-main">
            <header class="topbar">
                <div>
                    <p style="margin:0 0 8px; color:#6d7280; font-size:0.7rem; letter-spacing:0.18em; text-transform:uppercase; font-weight:700;">Overview</p>
                    <h1>Dashboard</h1>
                </div>
                <div class="topbar-meta">
                    <span class="date-chip">{{ now()->format('d M Y') }}</span>
                </div>
            </header>

            <section class="stats-grid">
                <article class="stat-card">
                    <div class="stat-head">
                        <span>Users</span>
                        <span class="stat-icon">U</span>
                    </div>
                    <p class="stat-value">{{ App\Models\User::count() }}</p>
                    <div class="stat-trend">+12.5% from last month</div>
                </article>

                <article class="stat-card">
                    <div class="stat-head">
                        <span>Products</span>
                        <span class="stat-icon">P</span>
                    </div>
                    <p class="stat-value">{{ App\Models\Product::count() }}</p>
                    <div class="stat-trend">+4 new releases</div>
                </article>

                <article class="stat-card">
                    <div class="stat-head">
                        <span>Orders</span>
                        <span class="stat-icon">O</span>
                    </div>
                    <p class="stat-value">{{ App\Models\Order::count() }}</p>
                    <div class="stat-trend">+8.1% conversion</div>
                </article>

                <article class="stat-card">
                    <div class="stat-head">
                        <span>Revenue</span>
                        <span class="stat-icon">R</span>
                    </div>
                    <p class="stat-value">${{ number_format(App\Models\Order::sum('subtotal') ?? 0, 0, '.', ',') }}</p>
                    <div class="stat-trend">+15.3% this week</div>
                </article>
            </section>

            <section class="content-grid">
                <article class="panel">
                    <div class="panel-header">
                        <h3>Sales overview</h3>
                        <span class="mini-tag">This year</span>
                    </div>
                    <div class="chart-bars" aria-label="Sales chart">
                        <div class="bar-group"><div class="bar" style="height: 42%;"></div><span>Jan</span></div>
                        <div class="bar-group"><div class="bar" style="height: 58%;"></div><span>Feb</span></div>
                        <div class="bar-group"><div class="bar" style="height: 50%;"></div><span>Mar</span></div>
                        <div class="bar-group"><div class="bar" style="height: 72%;"></div><span>Apr</span></div>
                        <div class="bar-group"><div class="bar" style="height: 64%;"></div><span>May</span></div>
                        <div class="bar-group"><div class="bar" style="height: 88%;"></div><span>Jun</span></div>
                    </div>
                </article>

                <article class="panel">
                    <div class="panel-header">
                        <h3>Recent activity</h3>
                        <span class="mini-tag">Live</span>
                    </div>
                    <ul class="activity-list">
                        <li>
                            <div class="activity-left">
                                <span class="activity-dot"></span>
                                <span class="activity-text">New order received</span>
                            </div>
                            <span class="activity-time">2 min ago</span>
                        </li>
                        <li>
                            <div class="activity-left">
                                <span class="activity-dot" style="background:#29b76d; box-shadow:0 0 0 4px rgba(41,183,109,0.12);"></span>
                                <span class="activity-text">Inventory updated</span>
                            </div>
                            <span class="activity-time">18 min ago</span>
                        </li>
                        <li>
                            <div class="activity-left">
                                <span class="activity-dot" style="background:#ff9d5c; box-shadow:0 0 0 4px rgba(255,157,92,0.12);"></span>
                                <span class="activity-text">Customer feedback</span>
                            </div>
                            <span class="activity-time">1 hour ago</span>
                        </li>
                    </ul>
                </article>
            </section>
        </main>
    </div>
    @vite(['resources/js/admin-dashboard.js'])
</body>
</html>
