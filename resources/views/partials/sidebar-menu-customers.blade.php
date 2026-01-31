@section('sidebar-title', 'Mijozlar')

@section('sidebar-menu')
    <div class="mt-2">
        <label class="px-2 text-xs+ font-medium text-slate-500 uppercase">Boshqaruv</label>
        <ul class="mt-1 space-y-1.5">
            @can('view-customers')
            <li>
                <a href="{{ route('customers.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('customers.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Barcha Mijozlar</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
@endsection
