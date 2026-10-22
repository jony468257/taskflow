<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'TaskFlow - Mini Task Management System')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons 1.11.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --app-bg: #f8fafc;
            --brand-primary: #3b82f6;
            --brand-primary-dark: #1d4ed8;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--app-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-brand-badge {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: white;
            font-weight: 700;
            padding: 0.35rem 0.65rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .stat-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.05);
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        .card-main {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
        }

        .table thead th {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            color: #64748b;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding-top: 0.85rem;
            padding-bottom: 0.85rem;
        }

        .table tbody td {
            vertical-align: middle;
            border-color: #f1f5f9;
            padding: 1rem 0.75rem;
        }

        .badge-priority, .badge-status {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35em 0.65em;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .badge-priority-high {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }

        .badge-priority-medium {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fcd34d;
        }

        .badge-priority-low {
            background-color: #f0fdf4;
            color: #15803d;
            border: 1px solid #86efac;
        }

        .badge-status-pending {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .badge-status-in-progress {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #7dd3fc;
        }

        .badge-status-completed {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .btn-brand {
            background-color: var(--brand-primary);
            border-color: var(--brand-primary);
            color: #ffffff;
            font-weight: 500;
        }

        .btn-brand:hover {
            background-color: var(--brand-primary-dark);
            border-color: var(--brand-primary-dark);
            color: #ffffff;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .footer {
            margin-top: auto;
            border-top: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            font-size: 0.85rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark" href="{{ route('tasks.index') }}">
                <span class="navbar-brand-badge">
                    <i class="bi bi-check2-square"></i>
                </span>
                <span>TaskFlow</span>
            </a>

            <div class="ms-auto d-flex align-items-center gap-2">
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary d-none d-sm-inline-flex align-items-center gap-1">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-brand d-inline-flex align-items-center gap-1">
                    <i class="bi bi-plus-lg"></i>
                    <span>Add Task</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @include('partials.flash')
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer py-3 text-center">
        <div class="container">
            <span>&copy; {{ date('Y') }} TaskFlow &bull; Swift Tech Solution Laravel Developer Skill Test</span>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
