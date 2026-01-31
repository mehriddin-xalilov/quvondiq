@section('sidebar-title', 'Savdo')

@section('sidebar-menu')
    <div class="mt-2">
        <label class="px-2 text-xs+ font-medium text-slate-500 uppercase">Boshqaruv</label>
        <ul class="mt-1 space-y-1.5">
            @can('view-sales')
            <li>
                <a href="{{ route('sales.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('sales.index') || request()->routeIs('sales.show') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Sotuvlar ro'yxati</span>
                </a>
            </li>
            @endcan

            @can('create-sales')
            <li>
                <a href="{{ route('sales.create') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('sales.create') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Yangi Sotuv</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
@endsection
