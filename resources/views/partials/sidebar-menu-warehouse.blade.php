@section('sidebar-title', 'Ombor')

@section('sidebar-menu')
    <div class="mt-2">
        <label class="px-2 text-xs+ font-medium text-slate-500 uppercase">Boshqaruv</label>
        <ul class="mt-1 space-y-1.5">
            @can('view-warehouse-stocks')
            <li>
                <a href="{{ route('warehouse-stocks.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('warehouse-stocks.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Qoldiqlar</span>
                </a>
            </li>
            @endcan

            @can('view-stock-movements')
            <li>
                <a href="{{ route('stock-movements.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('stock-movements.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                    <span>Harakatlar</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
@endsection
