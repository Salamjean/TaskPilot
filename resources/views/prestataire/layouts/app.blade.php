<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Prestataire') — TaskPilot</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #7E22CE;
            --secondary: #9333EA;
            --accent: #A855F7;
            --bg: #FAF8FF;
            --text: #1F2937;
            --border: #E5E7EB;
            --sidebar-w: 260px;
            --navbar-h: 64px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .app-wrapper {
            display: flex;
            flex: 1;
            min-height: 100vh;
        }

        .app-body {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            overflow-x: auto;
            min-width: 0;
        }

        .page-content {
            padding: 32px;
            flex: 1;
        }

        /* ── Mobile Overlay ── */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
            z-index: 150;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* ── Responsive ── */
        @media (max-width: 1024px) {
            .app-body {
                margin-left: 0;
            }

            .page-content {
                padding: 20px;
            }

            /* Global Table Responsiveness */
            .pi-table-wrap,
            .dl-table-wrap,
            [style*="overflow-x: auto"] {
                -webkit-overflow-scrolling: touch;
            }
        }

        @stack('styles')
    </style>
    @stack('head')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="app-wrapper">
        {{-- Overlay pour mobile --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        @include('prestataire.layouts.sidebar')
        <div class="app-body">
            @include('prestataire.layouts.navbar')
            <main class="page-content">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            }
        }

        document.getElementById('sidebarOverlay')?.addEventListener('click', toggleSidebar);
    </script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({ icon: 'success', title: 'Succès', text: "{{ session('success') }}", showConfirmButton: false, timer: 2500, position: 'center' });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({ icon: 'error', title: 'Erreur', text: "{{ session('error') }}", showConfirmButton: true, confirmButtonColor: 'var(--primary)', position: 'center' });
            });
        </script>
    @endif
</body>

</html>