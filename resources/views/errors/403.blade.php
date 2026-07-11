<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ruxsat berilmagan - 403</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/claude-theme.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    
    <script>
        localStorage.getItem("_x_darkMode_on") === "true" && document.documentElement.classList.add("dark");
    </script>
</head>
<body class="bg-slate-50 dark:bg-navy-900">
    <div id="root" class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900">
        <main class="grid w-full grow grid-cols-1 place-items-center">
            <div class="max-w-md p-6 text-center">
                <div class="w-full">
                    <img class="w-full" src="{{ asset('images/illustrations/security.svg') }}" onerror="this.style.display='none'" alt="403 image" />
                </div>
                <p class="pt-4 text-7xl font-bold text-error">403</p>
                <p class="pt-4 text-xl font-semibold text-slate-800 dark:text-navy-50">
                    Ruxsat berilmagan
                </p>
                <p class="pt-2 text-slate-500 dark:text-navy-200">
                    Sizda ushbu sahifani ko'rish uchun yetarli huquqlar mavjud emas.
                </p>
                <a href="{{ route('dashboard') }}" class="btn mt-8 h-11 bg-primary text-base font-medium text-white hover:bg-primary-focus hover:shadow-lg hover:shadow-primary/50 focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:hover:shadow-accent/50 dark:focus:bg-accent-focus dark:active:bg-accent/90">
                    Bosh sahifaga qaytish
                </a>
            </div>
        </main>
    </div>
    <script>window.addEventListener("DOMContentLoaded", () => Alpine.start());</script>
</body>
</html>
