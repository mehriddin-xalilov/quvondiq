@section('sidebar-title', 'Ombor')

@section('sidebar-menu')
    <div class="mt-2">
        <label class="px-2 text-xs+ font-medium text-slate-500 uppercase">Katalog</label>
        <ul class="mt-1 space-y-1.5">
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
@endsection
