<!doctype html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/claude-theme.css') }}">

    <style>
        /* ── Tom Select: Premium Dark-mode Override ── */
        .ts-wrapper .ts-control {
            background: transparent !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem 0.75rem !important;
            box-shadow: none !important;
            font-size: 0.875rem !important;
            color: inherit !important;
            min-height: 2.5rem !important;
        }
        .dark .ts-wrapper .ts-control {
            border-color: #3f4e6a !important;
            color: #e2e8f0 !important;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #c96442 !important;
            box-shadow: 0 0 0 3px rgba(201,100,66,0.15) !important;
        }
        .ts-control > input { color: inherit !important; }

        /* Dropdown panel */
        .ts-dropdown {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15), 0 4px 10px -6px rgba(0,0,0,0.1) !important;
            margin-top: 4px !important;
            z-index: 9999 !important;
            overflow: hidden !important;
        }
        .dark .ts-dropdown {
            background: #1e2a3b !important;
            border-color: #3f4e6a !important;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.4) !important;
        }

        /* Options */
        .ts-dropdown .option {
            padding: 0.55rem 0.875rem !important;
            font-size: 0.875rem !important;
            color: #334155 !important;
            white-space: normal !important;
            word-break: break-word !important;
            line-height: 1.5 !important;
            cursor: pointer !important;
            border-bottom: 1px solid transparent !important;
            transition: background 0.15s, color 0.15s !important;
        }
        .dark .ts-dropdown .option { color: #cbd5e1 !important; }

        .ts-dropdown .option:hover,
        .ts-dropdown .option.active {
            background: rgba(201,100,66,0.10) !important;
            color: #b0512f !important;
        }
        .dark .ts-dropdown .option:hover,
        .dark .ts-dropdown .option.active {
            background: rgba(210,118,90,0.20) !important;
            color: #e3a183 !important;
        }

        /* Scroll area */
        .ts-dropdown-content {
            max-height: 220px !important;
            scrollbar-width: thin;
            scrollbar-color: #c96442 transparent;
        }
        .ts-dropdown-content::-webkit-scrollbar { width: 5px; }
        .ts-dropdown-content::-webkit-scrollbar-track { background: transparent; }
        .ts-dropdown-content::-webkit-scrollbar-thumb { background: #c96442; border-radius: 99px; }

        /* Placeholder */
        .ts-wrapper .placeholder,
        .ts-control input::placeholder { color: #94a3b8 !important; }

        /* Caret arrow */
        .ts-wrapper.single .ts-control::after {
            border-color: #94a3b8 transparent transparent !important;
        }
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <script>
        localStorage.getItem("_x_darkMode_on") === "true" && document.documentElement.classList.add("dark");
    </script>
    @stack('styles')
</head>
<body x-data="{ navExpanded: (localStorage.getItem('yd_nav') ?? 'open') !== 'closed' }" x-init="$store.global.activePanel = {{ json_encode(request()->routeIs('settings.*')) }} ? 'settings' : null; $watch('navExpanded', v => localStorage.setItem('yd_nav', v ? 'open' : 'closed'))" class="is-header-blur" x-bind="$store.global.documentBody">
    <div class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-slate-50 dark:bg-navy-900">
        <div class="app-preloader-inner relative inline-block size-48"></div>
    </div>

    <div id="root" class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900" :class="{ 'nav-expanded': navExpanded }" x-cloak>
        <div class="sidebar print:hidden">
            <div class="main-sidebar">
                <div class="flex h-full w-full flex-col items-center border-r border-slate-150 bg-white dark:border-navy-700 dark:bg-navy-800">
                    <div class="flex pt-4">
                        <a href="{{ route('dashboard') }}" class="flex items-center justify-center">
                            <img class="size-11 transition-transform duration-500 ease-in-out hover:rotate-[360deg] object-contain"
                                 src="{{ asset('images/app-logo.png') }}"
                                 alt="logo">
                        </a>
                    </div>

                    <div class="nav-list is-scrollbar-hidden flex grow flex-col space-y-4 overflow-y-auto pt-6">
                        <a href="{{ route('dashboard') }}" class="nav-link flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'hover:bg-primary/20 dark:hover:bg-navy-300/20' }} outline-none transition-colors duration-200" x-tooltip.placement.right="!navExpanded ? 'Bosh sahifa' : ''">
                            <svg class="h-7 w-7 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path fill="currentColor" fill-opacity=".3" d="M5 14.059c0-1.01 0-1.514.222-1.945.221-.43.632-.724 1.453-1.31l4.163-2.974c.56-.4.842-.601 1.162-.601.32 0 .601.2 1.162.601l4.163 2.974c.821.586 1.232.88 1.453 1.31.222.43.222.935.222 1.945V19c0 .943 0 1.414-.293 1.707C18.414 21 17.943 21 17 21H7c-.943 0-1.414 0-1.707-.293C5 20.414 5 19.943 5 19v-4.94Z"></path>
                                <path fill="currentColor" d="M3 12.387c0 .267 0 .4.084.441.084.041.19-.04.4-.204l7.288-5.669c.59-.459.885-.688 1.228-.688.343 0 .638.23 1.228.688l7.288 5.669c.21.163.316.245.4.204.084-.04.084-.174.084-.441v-.409c0-.48 0-.72-.102-.928-.101-.208-.291-.355-.67-.65l-7-5.445c-.59-.459-.885-.688-1.228-.688-.343 0-.638.23-1.228.688l-7 5.445c-.379.295-.569.442-.67.65-.102.208-.102.448-.102.928v.409Z"></path>
                            </svg>
                            <span class="nav-label">Bosh sahifa</span>
                        </a>

                        @can('documents.generate')
                        <a href="{{ route('sertifikatlar.index') }}" class="nav-link flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('sertifikatlar.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'hover:bg-primary/20 dark:hover:bg-navy-300/20' }} outline-none transition-colors duration-200" x-tooltip.placement.right="!navExpanded ? 'Sertifikatlar' : ''">
                            <i class="fa-solid fa-certificate text-xl w-7 text-center shrink-0"></i>
                            <span class="nav-label">Sertifikatlar</span>
                        </a>

                        <a href="{{ route('guvohnomalar.index') }}" class="nav-link flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('guvohnomalar.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'hover:bg-primary/20 dark:hover:bg-navy-300/20' }} outline-none transition-colors duration-200" x-tooltip.placement.right="!navExpanded ? 'Guvohnomalar' : ''">
                            <i class="fa-solid fa-award text-xl w-7 text-center shrink-0"></i>
                            <span class="nav-label">Guvohnomalar</span>
                        </a>
                        @endcan

                        @can('templates.view')
                        <a href="{{ route('templates.index') }}" class="nav-link flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('templates.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'hover:bg-primary/20 dark:hover:bg-navy-300/20' }} outline-none transition-colors duration-200" x-tooltip.placement.right="!navExpanded ? 'Shablonlar' : ''">
                            <i class="fa-solid fa-file-word text-xl w-7 text-center shrink-0"></i>
                            <span class="nav-label">Shablonlar</span>
                        </a>
                        @endcan

                        @can('lookups.manage')
                        <a href="{{ route('professions.index') }}" class="nav-link flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('professions.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'hover:bg-primary/20 dark:hover:bg-navy-300/20' }} outline-none transition-colors duration-200" x-tooltip.placement.right="!navExpanded ? 'Mutaxassisliklar' : ''">
                            <i class="fa-solid fa-briefcase text-xl w-7 text-center shrink-0"></i>
                            <span class="nav-label">Mutaxassisliklar</span>
                        </a>
                        @endcan

                        @canany(['users.view', 'roles.view'])
                        @php
                             $settingsDefaultRoute = auth()->user()->can('users.view') ? route('settings.users.index') : route('settings.roles.index');
                        @endphp
                        <a href="{{ $settingsDefaultRoute }}" class="nav-link flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('settings.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'hover:bg-primary/20 dark:hover:bg-navy-300/20' }} outline-none transition-colors duration-200" x-tooltip.placement.right="!navExpanded ? 'Boshqaruv' : ''">
                            <svg class="h-7 w-7 shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-opacity="0.3" fill="currentColor" d="M2 12.947v-1.771c0-1.047.85-1.913 1.899-1.913 1.81 0 2.549-1.288 1.64-2.868a1.919 1.919 0 0 1 .699-2.607l1.729-.996c.79-.474 1.81-.192 2.279.603l.11.192c.9 1.58 2.379 1.58 3.288 0l.11-.192c.47-.795 1.49-1.077 2.279-.603l1.73.996a1.92 1.92 0 0 1 .699 2.607c-.91 1.58-.17 2.868 1.639 2.868 1.04 0 1.899.856 1.899 1.912v1.772c0 1.047-.85 1.912-1.9 1.912-1.808 0-2.548 1.288-1.638 2.869.52.915.21 2.083-.7 2.606l-1.729.997c-.79.473-1.81.191-2.279-.604l-.11-.191c-.9-1.58-2.379-1.58-3.288 0l-.11.19c-.47.796-1.49 1.078-2.279.605l-1.73-.997a1.919 1.919 0 0 1-.699-2.606c.91-1.58.17-2.869-1.639-2.869A1.911 1.911 0 0 1 2 12.947Z"></path>
                                <path fill="currentColor" d="M11.995 15.332c1.794 0 3.248-1.464 3.248-3.27 0-1.807-1.454-3.272-3.248-3.272-1.794 0-3.248 1.465-3.248 3.271 0 1.807 1.454 3.271 3.248 3.271Z"></path>
                            </svg>
                            <span class="nav-label">Boshqaruv</span>
                        </a>
                        @endcanany
                    </div>
                </div>
            </div>

            <div class="sidebar-panel">
                <div x-show="$store.global.activePanel === 'settings'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-4"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-4"
                     class="flex h-full grow flex-col bg-white pl-[var(--main-sidebar-width)] dark:bg-navy-750">
                    <div class="flex h-18 w-full items-center justify-between pl-4 pr-1">
                        <p class="text-base tracking-wider text-slate-800 dark:text-navy-100">
                            Boshqaruv
                        </p>
                    </div>

                    <div class="h-[calc(100%-4.5rem)] overflow-x-hidden pb-6" x-init="$el._x_simplebar = new SimpleBar($el);">
                        <div class="mt-2 px-4">
                            <label class="px-2 text-xs+ font-medium text-slate-500 uppercase">SOZLAMALAR</label>
                            <ul class="mt-1 space-y-1.5 font-inter">
                                @can('users.view')
                                <li>
                                    <a href="{{ route('settings.users.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('settings.users.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span>Foydalanuvchilar</span>
                                    </a>
                                </li>
                                @endcan

                                @can('roles.view')
                                <li>
                                    <a href="{{ route('settings.roles.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('settings.roles.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span>Rollar</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <nav class="header before:bg-white dark:before:bg-navy-750 print:hidden">
            <div class="header-container relative flex w-full bg-white dark:bg-navy-750 print:hidden">
                <div class="flex w-full items-center justify-between">
                    <button @click="navExpanded = !navExpanded"
                            class="btn size-9 rounded-full p-0 text-slate-500 hover:bg-slate-300/20 focus:bg-slate-300/20 dark:text-navy-200 dark:hover:bg-navy-300/20"
                            x-tooltip="navExpanded ? 'Menyuni yopish' : 'Menyuni ochish'"
                            aria-label="Menyuni ochish/yopish">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <div class="h-7 w-7 ml-1" x-show="$store.global.activePanel === 'settings'">
                        <button class="menu-toggle ml-0.5 flex h-7 w-7 flex-col justify-center space-y-1.5 text-primary outline-none focus:outline-none dark:text-accent-light/80" :class="$store.global.isSidebarExpanded && 'active'" @click="$store.global.isSidebarExpanded = !$store.global.isSidebarExpanded">
                            <span></span><span></span><span></span>
                        </button>
                    </div>

                    <div class="ml-auto -mr-1.5 flex items-center space-x-2">
                        <button @click="$store.global.isDarkModeEnabled = !$store.global.isDarkModeEnabled" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20">
                            <svg x-show="$store.global.isDarkModeEnabled" class="size-6 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.75 3.412a.818.818 0 01-.07.917 6.332 6.332 0 00-1.4 3.971c0 3.564 2.98 6.494 6.706 6.494a6.86 6.86 0 002.856-.617.818.818 0 011.1 1.047C19.593 18.614 16.218 21 12.283 21 7.18 21 3 16.973 3 11.956c0-4.563 3.46-8.31 7.925-8.948a.818.818 0 01.826.404z"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" x-show="!$store.global.isDarkModeEnabled" class="size-6 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <div x-data="usePopper({placement:'bottom-end',offset:12})" @click.outside="isShowPopper && (isShowPopper = false)" class="flex">
                            <button @click="isShowPopper = !isShowPopper" x-ref="popperRef" class="avatar size-10">
                                <img class="rounded-full" src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/avatar/avatar-12.jpg') }}" alt="avatar">
                                <span class="absolute right-0 size-3.5 rounded-full border-2 border-white bg-success dark:border-navy-700"></span>
                            </button>

                            <div :class="isShowPopper && 'show'" class="popper-root fixed" x-ref="popperRoot">
                                <div class="popper-box w-64 rounded-lg border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
                                    <div class="flex items-center space-x-4 rounded-t-lg bg-slate-100 py-5 px-4 dark:bg-navy-800">
                                        <div class="avatar size-14">
                                            <img class="rounded-full" src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/avatar/avatar-12.jpg') }}" alt="avatar">
                                        </div>
                                        <div>
                                            <a href="{{ route('profile.edit') }}" class="text-base font-medium text-slate-700 hover:text-primary dark:text-navy-100">
                                                {{ auth()->user()->name }}
                                            </a>
                                            <p class="text-xs text-slate-400 dark:text-navy-300">
                                                {{ auth()->user()->email }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col pt-2 pb-5">
                                        <a href="{{ route('profile.edit') }}" class="group flex items-center space-x-3 py-2 px-4 tracking-wide outline-none transition-all hover:bg-slate-100 dark:hover:bg-navy-600">
                                            <div class="flex size-8 items-center justify-center rounded-lg bg-warning text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h2 class="font-medium text-slate-700 dark:text-navy-100">Profil</h2>
                                                <div class="text-xs text-slate-400">Sozlamalar</div>
                                            </div>
                                        </a>
                                        <div class="mt-3 px-4">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="btn h-9 w-full space-x-2 bg-primary text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                    </svg>
                                                    <span>Chiqish</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="main-content w-full px-[var(--margin-x)] pb-8">
            <div class="flex items-center space-x-4 py-5 lg:py-6">
                <h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">
                    @yield('page-title', 'Dashboard')
                </h2>
            </div>

            @if(session('success'))
                <div x-data x-init="$notification({text: '{{ session('success') }}', variant: 'success', position: 'right-top', duration: 4000})"></div>
            @endif
            @if(session('error'))
                <div x-data x-init="$notification({text: '{{ session('error') }}', variant: 'error', position: 'right-top', duration: 4000})"></div>
            @endif
            @if(session('warning'))
                <div x-data x-init="$notification({text: '{{ session('warning') }}', variant: 'warning', position: 'right-top', duration: 4000})"></div>
            @endif
            @if(session('info'))
                <div x-data x-init="$notification({text: '{{ session('info') }}', variant: 'info', position: 'right-top', duration: 4000})"></div>
            @endif

            @yield('content')
        </main>
    </div>

    <div id="x-teleport-target"></div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        window.addEventListener("DOMContentLoaded", () => Alpine.start());
    </script>

    @stack('scripts')
</body>
</html>