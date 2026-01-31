@extends('layouts.app')

@section('title', 'Mijozlar')
@section('header-title', 'Mijozlar')

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6" x-data="customerFilter" x-init="bindPaginationLinks()">
    <!-- Filters -->
    <div class="card p-4 sm:p-5">
        <form method="GET" action="{{ route('customers.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div class="sm:col-span-2">
                <label class="block">
                    <span class="text-xs+ text-slate-400">Qidiruv</span>
                    <input x-model="filters.search" type="text" placeholder="Ism, telefon yoki manzil..." class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
            </div>

            <div>
                <label class="block">
                    <span class="text-xs+ text-slate-400">Turi</span>
                    <select x-model="filters.is_regular" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                        <option value="">Barchasi</option>
                        <option value="1">Doimiy</option>
                        <option value="0">Oddiy</option>
                    </select>
                </label>
            </div>

            <div>
                <label class="block">
                    <span class="text-xs+ text-slate-400">Qarz</span>
                    <select x-model="filters.has_debt" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                        <option value="">Barchasi</option>
                        <option value="1">Qarzdorlar</option>
                        <option value="0">Qarzsizlar</option>
                    </select>
                </label>
            </div>

            <div class="flex items-end space-x-2 sm:col-span-4">
                <button @click="fetchResults()" type="button" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                    <i class="fa-solid fa-filter mr-2"></i> Filtr
                </button>
                <button @click="resetFilters()" type="button" class="btn bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90">
                    Tozalash
                </button>
            </div>
        </form>
    </div>

    <!-- Header -->
    <div class="flex flex-col items-center justify-between space-y-4 sm:flex-row sm:space-y-0">
        <h2 class="text-xl font-medium text-slate-700 dark:text-navy-50">
            Barcha Mijozlar ({{ $customers->total() }})
        </h2>
        @can('create-customers')
        @can('create-customers')
        <div class="flex space-x-2">
            <button @click="exportExcel()" class="btn bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90">
                <i class="fa-solid fa-file-excel mr-2"></i> Excel
            </button>
            <a href="{{ route('customers.create') }}" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                <i class="fa-solid fa-plus mr-2"></i> Yangi Mijoz
            </a>
        </div>
        @endcan
        @endcan
    </div>

    <!-- Table -->
    <div class="card">
        <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
            <table class="is-hoverable w-full text-left">
                <thead>
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Mijoz
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Telefon
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Manzil
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Turi
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Qarz
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Buyurtmalar
                        </th>
                        <th class="whitespace-nowrap rounded-tr-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Amallar
                        </th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @include('customers.partials.table-body')
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($customers->hasPages())
        <div id="pagination-container" class="flex flex-col justify-between space-y-4 px-4 py-4 sm:flex-row sm:items-center sm:space-y-0 sm:px-5">
            {{ $customers->links() }}
        </div>
        @endif
    </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('customerFilter', () => ({
        filters: {
            search: '{{ request("search") }}',
            is_regular: '{{ request("is_regular") }}',
            has_debt: '{{ request("has_debt") }}'
        },
        
        async fetchResults(url = '{{ route("customers.index") }}') {
            const params = new URLSearchParams();
            if(this.filters.search) params.append('search', this.filters.search);
            if(this.filters.is_regular) params.append('is_regular', this.filters.is_regular);
            if(this.filters.has_debt) params.append('has_debt', this.filters.has_debt);
            
            let baseUrl = url.split('?')[0];
            let urlParams = new URLSearchParams(url.split('?')[1]);
            if (urlParams.has('page')) {
                params.append('page', urlParams.get('page'));
            }
            
            const finalUrl = `${baseUrl}?${params.toString()}`;
            
            try {
                const response = await fetch(finalUrl, {
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                document.getElementById('table-body').innerHTML = data.html;
                document.getElementById('pagination-container').innerHTML = data.pagination;
                
                this.bindPaginationLinks();
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        
        bindPaginationLinks() {
            document.querySelectorAll('#pagination-container a').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.fetchResults(link.href);
                });
            });
        },
        
        resetFilters() {
            this.filters = { search: '', is_regular: '', has_debt: '' };
            this.fetchResults();
        },

        exportExcel() {
            const params = new URLSearchParams();
            if(this.filters.search) params.append('search', this.filters.search);
            if(this.filters.is_regular) params.append('is_regular', this.filters.is_regular);
            if(this.filters.has_debt) params.append('has_debt', this.filters.has_debt);
            
            window.location.href = '{{ route("customers.export") }}?' + params.toString();
        }
    }));
});
</script>
@endsection

@include('partials.sidebar-menu-customers')
