<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — TaskPilot</title>
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
            --primary: #1E3A8A;
            --secondary: #2563EB;
            --accent: #3B82F6;
            --bg: #F8FAFC;
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

        /* ── Wrapper global ── */
        .app-wrapper {
            display: flex;
            flex: 1;
            min-height: 100vh;
        }

        /* ── Zone de contenu (à droite de la sidebar) ── */
        .app-body {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Contenu de la page ── */
        .page-content {
            padding: 32px;
            flex: 1;
        }

        @stack('styles')
    </style>
    @stack('head')
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="app-wrapper">

        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

        <div class="app-body">

            {{-- Navbar --}}
            @include('admin.layouts.navbar')

            {{-- Contenu principal --}}
            <main class="page-content">
                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')

    {{-- Alertes Flash Globales via SweetAlert2 --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2500,
                    position: 'center'
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: "{{ session('error') }}",
                    showConfirmButton: true,
                    confirmButtonColor: 'var(--primary)',
                    position: 'center'
                });
            });
        </script>
    @endif
</body>

</html>