<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happy Wife - Happy Life</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- HTMX -->
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>

    <!-- Custom Styles -->
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .main-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .setup-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 2rem;
            margin: 2rem 0;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: 600;
            color: #6c757d;
        }

        .step.active {
            background-color: #0d6efd;
            color: white;
        }

        .step.completed {
            background-color: #198754;
            color: white;
        }

        .step-line {
            width: 50px;
            height: 2px;
            background-color: #e9ecef;
            margin-top: 19px;
        }

        .step-line.completed {
            background-color: #198754;
        }

        .app-header {
            text-align: center;
            margin-bottom: 2rem;
            color: #495057;
        }

        .app-title {
            font-size: 2.5rem;
            font-weight: 300;
            margin-bottom: 0.5rem;
            color: #212529;
        }

        .app-subtitle {
            font-size: 1.1rem;
            color: #6c757d;
        }

        .btn-next {
            min-width: 120px;
        }

        .form-floating label {
            color: #6c757d;
        }

        .navbar-brand {
            font-weight: 300;
            font-size: 1.5rem;
        }

        .sidebar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1.5rem;
            height: fit-content;
        }

        .content-area {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 2rem;
            min-height: 500px;
        }

        .calendar-day {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 2px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .calendar-day:hover {
            background-color: #e9ecef;
        }

        .mood-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 2px;
        }

        .mood-great { background-color: #198754; }
        .mood-good { background-color: #20c997; }
        .mood-neutral { background-color: #ffc107; }
        .mood-bad { background-color: #fd7e14; }
        .mood-terrible { background-color: #dc3545; }

        .htmx-indicator {
            opacity: 0;
            transition: opacity 500ms ease-in;
        }

        .htmx-request .htmx-indicator {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="main-container vh-100">
        <div id="app-content" hx-get="/index" hx-trigger="load" class="h-100">
            <!-- Контент будет загружен через HTMX -->
            <div class="d-flex justify-content-center align-items-center" style="height: 60vh;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Загрузка...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // HTMX конфигурация
        htmx.config.defaultSwapStyle = 'innerHTML';
        htmx.config.defaultSwapDelay = 100;

        // Автоматическое обновление индикаторов загрузки
        document.body.addEventListener('htmx:beforeRequest', function(evt) {
            const indicator = evt.detail.elt.querySelector('.htmx-indicator');
            if (indicator) {
                indicator.style.opacity = '1';
            }
        });

        document.body.addEventListener('htmx:afterRequest', function(evt) {
            const indicator = evt.detail.elt.querySelector('.htmx-indicator');
            if (indicator) {
                indicator.style.opacity = '0';
            }
        });
    </script>
</body>
</html>
