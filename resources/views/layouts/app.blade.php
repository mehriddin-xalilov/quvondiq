<!doctype html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - Yem Do'koni CRM</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    <!-- CSS Assets -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    
    <script>
        localStorage.getItem("_x_darkMode_on") === "true" && document.documentElement.classList.add("dark");
    </script>
    @stack('styles')
</head>
<body x-data x-init="$store.global.activePanel = {{ json_encode(request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('stock-movements.*')) }} ? 'inventory' : ({{ json_encode(request()->routeIs('settings.*') || request()->routeIs('notes.*')) }} ? 'settings' : null)" class="is-header-blur" x-bind="$store.global.documentBody">
    <!-- App preloader -->
    <div class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-slate-50 dark:bg-navy-900">
        <div class="app-preloader-inner relative inline-block size-48"></div>
    </div>

    <!-- Page Wrapper -->
    <div id="root" class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900" x-cloak>
        <!-- Sidebar -->
        <div class="sidebar print:hidden">
            <!-- Main Sidebar -->
            <div class="main-sidebar">
                <div class="flex h-full w-full flex-col items-center border-r border-slate-150 bg-white dark:border-navy-700 dark:bg-navy-800">
                    <!-- Logo -->
                    <div class="flex pt-4">
                        <a href="{{ route('dashboard') }}" class="flex items-center justify-center">
                            <img class="size-11 transition-transform duration-500 ease-in-out hover:rotate-[360deg] object-contain" 
                                 src="{{ $shopInfo->logo_path ? asset('storage/' . $shopInfo->logo_path) : asset('images/app-logo.png') }}" 
                                 alt="logo">
                        </a>
                    </div>

                    <!-- Main Menu Icons -->
                    <div class="is-scrollbar-hidden flex grow flex-col space-y-4 overflow-y-auto pt-6">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'hover:bg-primary/20 dark:hover:bg-navy-300/20' }} outline-none transition-colors duration-200" x-tooltip.placement.right="'Dashboard'">
                            <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path fill="currentColor" fill-opacity=".3" d="M5 14.059c0-1.01 0-1.514.222-1.945.221-.43.632-.724 1.453-1.31l4.163-2.974c.56-.4.842-.601 1.162-.601.32 0 .601.2 1.162.601l4.163 2.974c.821.586 1.232.88 1.453 1.31.222.43.222.935.222 1.945V19c0 .943 0 1.414-.293 1.707C18.414 21 17.943 21 17 21H7c-.943 0-1.414 0-1.707-.293C5 20.414 5 19.943 5 19v-4.94Z"></path>
                                <path fill="currentColor" d="M3 12.387c0 .267 0 .4.084.441.084.041.19-.04.4-.204l7.288-5.669c.59-.459.885-.688 1.228-.688.343 0 .638.23 1.228.688l7.288 5.669c.21.163.316.245.4.204.084-.04.084-.174.084-.441v-.409c0-.48 0-.72-.102-.928-.101-.208-.291-.355-.67-.65l-7-5.445c-.59-.459-.885-.688-1.228-.688-.343 0-.638.23-1.228.688l-7 5.445c-.379.295-.569.442-.67.65-.102.208-.102.448-.102.928v.409Z"></path>
                            </svg>
                        </a>

                        @can('create-sales')
                        <!-- New Sale -->
                        <a href="{{ route('sales.create') }}" class="flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('sales.create') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'hover:bg-primary/20 dark:hover:bg-navy-300/20' }} outline-none transition-colors duration-200" x-tooltip.placement.right="'Yangi Sotuv'">
                            <i class="fa-solid fa-cart-shopping text-xl"></i>
                        </a>
                        @endcan

                        @can('view-sales')
                        <!-- Sales History -->
                        <a href="{{ route('sales.index') }}" class="flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('sales.index') || request()->routeIs('sales.show') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'hover:bg-primary/20 dark:hover:bg-navy-300/20' }} outline-none transition-colors duration-200" x-tooltip.placement.right="'Sotuvlar Tarixi'">
                            <i class="fa-solid fa-receipt text-xl"></i>
                        </a>
                        @endcan

                        @can('view-customers')
                        <!-- Customers -->
                        <a href="{{ route('customers.index') }}" class="flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('customers.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'hover:bg-primary/20 dark:hover:bg-navy-300/20' }} outline-none transition-colors duration-200" x-tooltip.placement.right="'Mijozlar'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </a>
                        @endcan

                        <!-- Telegram Orders -->
                        <a href="{{ route('telegram-orders.index') }}" class="flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('telegram-orders.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'hover:bg-primary/20 dark:hover:bg-navy-300/20' }} outline-none transition-colors duration-200" x-tooltip.placement.right="'Telegram Buyurtmalar'">
                            <i class="fa-brands fa-telegram text-2xl text-blue-500"></i>
                        </a>


                        <!-- Inventory Menu (Products, Categories, Warehouse) -->
                        <!-- Inventory Menu (Products, Categories, Warehouse) -->
                        <a href="{{ route('categories.index') }}" class="flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('stock-movements.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'hover:bg-primary/20 dark:hover:bg-navy-300/20' }} outline-none transition-colors duration-200" x-tooltip.placement.right="'Inventar'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </a>




                        @canany(['view-users', 'view-roles'])


                        <!-- Boshqaruv (Settings) -->
                        @php
                             // Determine the default route for settings. 
                             // If the user has 'view-users' permission, default to 'settings.users.index'.
                             // Otherwise, fallback to 'settings.roles.index' (assuming they have 'view-roles' if they see this).
                             // If they have neither but see this block due to @canany, we should probably check roles too, 
                             // but 'settings.users.index' is the primary request.
                             $settingsDefaultRoute = auth()->user()->can('view-users') ? route('settings.users.index') : route('settings.roles.index');
                        @endphp
                        <a href="{{ $settingsDefaultRoute }}" class="flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('settings.*') || request()->routeIs('notes.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'hover:bg-primary/20 dark:hover:bg-navy-300/20' }} outline-none transition-colors duration-200" x-tooltip.placement.right="'Boshqaruv'">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-opacity="0.3" fill="currentColor" d="M2 12.947v-1.771c0-1.047.85-1.913 1.899-1.913 1.81 0 2.549-1.288 1.64-2.868a1.919 1.919 0 0 1 .699-2.607l1.729-.996c.79-.474 1.81-.192 2.279.603l.11.192c.9 1.58 2.379 1.58 3.288 0l.11-.192c.47-.795 1.49-1.077 2.279-.603l1.73.996a1.92 1.92 0 0 1 .699 2.607c-.91 1.58-.17 2.868 1.639 2.868 1.04 0 1.899.856 1.899 1.912v1.772c0 1.047-.85 1.912-1.9 1.912-1.808 0-2.548 1.288-1.638 2.869.52.915.21 2.083-.7 2.606l-1.729.997c-.79.473-1.81.191-2.279-.604l-.11-.191c-.9-1.58-2.379-1.58-3.288 0l-.11.19c-.47.796-1.49 1.078-2.279.605l-1.73-.997a1.919 1.919 0 0 1-.699-2.606c.91-1.58.17-2.869-1.639-2.869A1.911 1.911 0 0 1 2 12.947Z"></path>
                                <path fill="currentColor" d="M11.995 15.332c1.794 0 3.248-1.464 3.248-3.27 0-1.807-1.454-3.272-3.248-3.272-1.794 0-3.248 1.465-3.248 3.271 0 1.807 1.454 3.271 3.248 3.271Z"></path>
                            </svg>
                        </a>
                        @endcanany
                    </div>

                    <!-- Bottom: Profile (Moved to Navbar) -->
                    {{-- Profile section moved to navbar --}}
                </div>
            </div>

            <!-- Sidebar Panel (for submenu) -->
            <div class="sidebar-panel">
                <div x-show="$store.global.activePanel === 'inventory'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-4"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-4"
                     class="flex h-full grow flex-col bg-white pl-[var(--main-sidebar-width)] dark:bg-navy-750">
                    <div class="flex h-18 w-full items-center justify-between pl-4 pr-1">
                        <p class="text-base tracking-wider text-slate-800 dark:text-navy-100">
                            Inventar
                        </p>
                    </div>

                    <div x-data="{expandedItem:null}" class="h-[calc(100%-4.5rem)] overflow-x-hidden pb-6" x-init="$el._x_simplebar = new SimpleBar($el);">
                        <div class="mt-2 px-4">
                            <label class="px-2 text-xs+ font-medium text-slate-500 uppercase">BOSHQARUV</label>
                            <ul class="mt-1 space-y-1.5 font-inter">
                                @can('view-categories')
                                <li>
                                    <a href="{{ route('categories.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('categories.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <span>Kategoriyalar</span>
                                    </a>
                                </li>
                                @endcan
                                
                                @can('view-products')
                                <li>
                                    <a href="{{ route('products.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('products.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <span>Mahsulotlar</span>
                                    </a>
                                </li>
                                @endcan

                                @can('view-stock-movements')
                                <li>
                                    <a href="{{ route('stock-movements.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('stock-movements.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                        <span>Ombor Harakatlari</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </div>
                </div>

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

                    <div x-data="{expandedItem:null}" class="h-[calc(100%-4.5rem)] overflow-x-hidden pb-6" x-init="$el._x_simplebar = new SimpleBar($el);">
                        <div class="mt-2 px-4">
                            <label class="px-2 text-xs+ font-medium text-slate-500 uppercase">SOZLAMALAR</label>
                            <ul class="mt-1 space-y-1.5 font-inter">
                                @can('view-users')
                                <li>
                                    <a href="{{ route('settings.users.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('settings.users.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span>Foydalanuvchilar</span>
                                    </a>
                                </li>
                                @endcan
                                
                                @can('view-roles')
                                <li>
                                    <a href="{{ route('settings.roles.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('settings.roles.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span>Rollar</span>
                                    </a>
                                </li>
                                @endcan

                                @can('view-users')
                                <li>
                                    <a href="{{ route('settings.shop.edit') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('settings.shop.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                                        <i class="fa-solid fa-shop text-sm w-4.5 text-center"></i>
                                        <span>Do'kon Sozlamalari</span>
                                    </a>
                                </li>
                                @endcan

                                
                                <li>
                                    <a href="{{ route('notes.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('notes.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                                        <i class="fa-solid fa-note-sticky text-sm w-4.5 text-center"></i>
                                        <span>Eslatmalar</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- App Header Wrapper -->
        <nav class="header before:bg-white dark:before:bg-navy-750 print:hidden">
            <div class="header-container relative flex w-full bg-white dark:bg-navy-750 print:hidden">
                <div class="flex w-full items-center justify-between">
                    <!-- Left: Sidebar Toggle -->
                    <div class="h-7 w-7" x-show="$store.global.activePanel === 'inventory' || $store.global.activePanel === 'settings'">
                        <button class="menu-toggle ml-0.5 flex h-7 w-7 flex-col justify-center space-y-1.5 text-primary outline-none focus:outline-none dark:text-accent-light/80" :class="$store.global.isSidebarExpanded && 'active'" @click="$store.global.isSidebarExpanded = !$store.global.isSidebarExpanded">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>

                    <!-- Search Bar moved here -->
                    <div class="ml-4 flex items-center">
                        <div class="relative flex h-8">
                            <input placeholder="Qidirish..." class="form-input peer h-full w-60 rounded-full bg-slate-150 px-4 pl-9 text-xs+ text-slate-800 ring-primary/50 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:text-navy-100 dark:placeholder-navy-300 dark:ring-accent/50 dark:hover:bg-navy-900 dark:focus:bg-navy-900" type="text">
                            <div class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 transition-colors duration-200" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3.316 13.781l.73-.171-.73.171zm0-5.457l.73.171-.73-.171zm15.473 0l.73-.171-.73.171zm0 5.457l.73.171-.73-.171zm-5.008 5.008l-.171-.73.171.73zm-5.457 0l-.171.73.171-.73zm0-15.473l-.171-.73.171.73zm5.457 0l.171-.73-.171.73zM20.47 21.53a.75.75 0 101.06-1.06l-1.06 1.06zM4.046 13.61a11.198 11.198 0 010-5.115l-1.46-.342a12.698 12.698 0 000 5.8l1.46-.343zm14.013-5.115a11.196 11.196 0 010 5.115l1.46.342a12.698 12.698 0 000-5.8l-1.46.343zm-4.45 9.564a11.196 11.196 0 01-5.114 0l-.342 1.46c1.907.448 3.892.448 5.8 0l-.343-1.46zM8.496 4.046a11.198 11.198 0 015.115 0l.342-1.46a12.698 12.698 0 00-5.8 0l.343 1.46zm0 14.013a5.97 5.97 0 01-4.45-4.45l-1.46.343a7.47 7.47 0 005.568 5.568l.342-1.46zm5.457 1.46a7.47 7.47 0 005.568-5.567l-1.46-.342a5.97 5.97 0 01-4.45 4.45l.342 1.46zM13.61 4.046a5.97 5.97 0 014.45 4.45l1.46-.343a7.47 7.47 0 00-5.568-5.567l-.342 1.46zm-5.457-1.46a7.47 7.47 0 00-5.567 5.567l1.46.342a5.97 5.97 0 014.45-4.45l-.343-1.46zm8.652 15.28l3.665 3.664 1.06-1.06-3.665-3.665-1.06 1.06z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Header buttons -->
                    <div class="ml-auto -mr-1.5 flex items-center space-x-2">


                        <!-- Dark Mode Toggle -->
                        <button @click="$store.global.isDarkModeEnabled = !$store.global.isDarkModeEnabled" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                            <svg x-show="$store.global.isDarkModeEnabled" x-transition:enter="transition-transform duration-200 ease-out absolute origin-top" x-transition:enter-start="scale-75" x-transition:enter-end="scale-100 static" class="size-6 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.75 3.412a.818.818 0 01-.07.917 6.332 6.332 0 00-1.4 3.971c0 3.564 2.98 6.494 6.706 6.494a6.86 6.86 0 002.856-.617.818.818 0 011.1 1.047C19.593 18.614 16.218 21 12.283 21 7.18 21 3 16.973 3 11.956c0-4.563 3.46-8.31 7.925-8.948a.818.818 0 01.826.404z"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" x-show="!$store.global.isDarkModeEnabled" x-transition:enter="transition-transform duration-200 ease-out absolute origin-top" x-transition:enter-start="scale-75" x-transition:enter-end="scale-100 static" class="size-6 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <!-- Monochrome Mode Toggle -->
                        <button @click="$store.global.isMonochromeModeEnabled = !$store.global.isMonochromeModeEnabled" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                            <i class="fa-solid fa-palette bg-gradient-to-r from-sky-400 to-blue-600 bg-clip-text text-lg font-semibold text-transparent"></i>
                        </button>

                        <!-- Notification -->
                        <div x-data="usePopper({placement:'bottom-end',offset:12})" @click.outside="isShowPopper && (isShowPopper = false)" class="flex">
                            <button @click="isShowPopper = !isShowPopper" x-ref="popperRef" class="btn relative size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-slate-500 dark:text-navy-100" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.375 17.556h-6.75m6.75 0H21l-1.58-1.562a2.254 2.254 0 01-.67-1.596v-3.51a6.612 6.612 0 00-1.238-3.85 6.744 6.744 0 00-3.262-2.437v-.379c0-.59-.237-1.154-.659-1.571A2.265 2.265 0 0012 2c-.597 0-1.169.234-1.591.65a2.208 2.208 0 00-.659 1.572v.38c-2.621.915-4.5 3.385-4.5 6.287v3.51c0 .598-.24 1.172-.67 1.595L3 17.556h12.375zm0 0v1.11c0 .885-.356 1.733-.989 2.358A3.397 3.397 0 0112 22a3.397 3.397 0 01-2.386-.976 3.313 3.313 0 01-.989-2.357v-1.111h6.75z"></path>
                                </svg>
                                <span class="absolute -top-px -right-px flex size-3 items-center justify-center">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-secondary opacity-80"></span>
                                    <span class="inline-flex size-2 rounded-full bg-secondary"></span>
                                </span>
                            </button>

                            <div :class="isShowPopper && 'show'" class="popper-root" x-ref="popperRoot">
                                <div class="popper-box mx-4 mt-1 flex max-h-[calc(100vh-6rem)] w-[calc(100vw-2rem)] flex-col rounded-lg border border-slate-150 bg-white shadow-soft dark:border-navy-800 dark:bg-navy-700 dark:shadow-soft-dark sm:m-0 sm:w-80">
                                    <div class="rounded-t-lg bg-slate-100 text-slate-600 dark:bg-navy-800 dark:text-navy-200">
                                        <div class="flex items-center justify-between px-4 pt-2">
                                            <div class="flex items-center space-x-2">
                                                <h3 class="font-medium text-slate-700 dark:text-navy-100">Bildirishnomalar</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col overflow-hidden">
                                        <div class="is-scrollbar-hidden space-y-4 overflow-y-auto px-4 py-4">
                                            <p class="text-center text-xs+ text-slate-400 dark:text-navy-300">Bildirishnomalar yo'q</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Apps Menu -->
                        <!-- <button class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-slate-500 dark:text-navy-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button> -->

                        <!-- Profile Dropdown -->
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
                                            <a href="{{ route('profile.edit') }}" class="text-base font-medium text-slate-700 hover:text-primary focus:text-primary dark:text-navy-100 dark:hover:text-accent-light dark:focus:text-accent-light">
                                                {{ auth()->user()->name }}
                                            </a>
                                            <p class="text-xs text-slate-400 dark:text-navy-300">
                                                {{ $shopInfo->name ?? 'Yem Do\'koni' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col pt-2 pb-5">
                                        <a href="{{ route('profile.edit') }}" class="group flex items-center space-x-3 py-2 px-4 tracking-wide outline-none transition-all hover:bg-slate-100 focus:bg-slate-100 dark:hover:bg-navy-600 dark:focus:bg-navy-600">
                                            <div class="flex size-8 items-center justify-center rounded-lg bg-warning text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h2 class="font-medium text-slate-700 transition-colors group-hover:text-primary group-focus:text-primary dark:text-navy-100 dark:group-hover:text-accent-light dark:group-focus:text-accent-light">
                                                    Profil
                                                </h2>
                                                <div class="text-xs text-slate-400 line-clamp-1 dark:text-navy-300">
                                                    Sozlamalar
                                                </div>
                                            </div>
                                        </a>
                                        @can('view-users')
                                        <a href="{{ route('settings.shop.edit') }}" class="group flex items-center space-x-3 py-2 px-4 tracking-wide outline-none transition-all hover:bg-slate-100 focus:bg-slate-100 dark:hover:bg-navy-600 dark:focus:bg-navy-600">
                                            <div class="flex size-8 items-center justify-center rounded-lg bg-info text-white">
                                                <i class="fa-solid fa-shop text-sm"></i>
                                            </div>
                                            <div>
                                                <h2 class="font-medium text-slate-700 transition-colors group-hover:text-primary group-focus:text-primary dark:text-navy-100 dark:group-hover:text-accent-light dark:group-focus:text-accent-light">
                                                    Do'kon Sozlamalari
                                                </h2>
                                                <div class="text-xs text-slate-400 line-clamp-1 dark:text-navy-300">
                                                    {{ $shopInfo->name ?? 'Yem Do\'koni' }}
                                                </div>
                                            </div>
                                        </a>
                                        @endcan
                                        <div class="mt-3 px-4">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="btn h-9 w-full space-x-2 bg-primary text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
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

        <!-- Main Content Wrapper -->
        <main class="main-content w-full px-[var(--margin-x)] pb-8">
            <!-- Page Title -->
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
    
    <!-- Javascript Assets -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        window.addEventListener("DOMContentLoaded", () => Alpine.start());
    </script>
    
    @stack('scripts')
</body>
</html>
