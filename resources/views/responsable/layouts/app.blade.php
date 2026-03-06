<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Responsable') — TaskPilot</title>
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
            --primary: #B45309;
            --secondary: #D97706;
            --accent: #F59E0B;
            --bg: #FFFBF5;
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
        }

        .page-content {
            padding: 32px;
            flex: 1;
        }

        @stack('styles')
    </style>
    @stack('head')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="app-wrapper">
        @include('responsable.layouts.sidebar')
        <div class="app-body">
            @include('responsable.layouts.navbar')
            <main class="page-content">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

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