@extends('layouts.app')

@section('title', 'Mahsulotlar')
@section('header-title', 'Mahsulotlar')

@section('content')
<div x-data="productTable()" x-init="init()" class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    
    <!-- Header & Controls -->
    <div class="flex flex-col items-center justify-between space-y-4 sm:flex-row sm:space-y-0 lg:py-2">
        <div class="flex items-center space-x-1">
            <h2 class="text-xl font-medium text-slate-700 line-clamp-1 dark:text-navy-50">
                Mahsulotlar
            </h2>
            <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">
                <span id="total-count">{{ $products->total() }}</span>
            </div>
        </div>
        <div class="flex justify-center space-x-2">
            <button @click="exportToExcel()" 
                    class="btn min-w-[7rem] bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90 dark:bg-success dark:hover:bg-success-focus dark:focus:bg-success-focus dark:active:bg-success/90">
                <i class="fa-solid fa-file-excel mr-2"></i> Excel
            </button>
            <a href="{{ route('products.create') }}" 
               class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                <i class="fa-solid fa-plus mr-2"></i> Yangi
            </a>
        </div>
    </div>

    <!-- Advanced Filter Section -->
    <div x-data="{isFilterExpanded:false}">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100">
                Filtrlash
            </h2>
            <div class="flex">
                <div class="flex items-center" x-data="{isInputActive:false}">
                    <label class="block">
                        <input x-model="search" x-effect="isInputActive === true && $nextTick(() => { $el.focus()});" :class="isInputActive ? 'w-32 lg:w-48' : 'w-0'" class="form-input bg-transparent px-1 text-right transition-all duration-100 placeholder:text-slate-500 dark:placeholder:text-navy-200" placeholder="Qidiruv..." type="text">
                    </label>
                    <button @click="isInputActive = !isInputActive" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewbox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>

                <button @click="isFilterExpanded = !isFilterExpanded" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewbox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M18 11.5H6M21 4H3m6 15h6"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div x-show="isFilterExpanded" x-collapse>
            <div class="max-w-2xl py-3">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:gap-6">
                    <label class="block">
                        <span>Nomi yoki Kod:</span>
                        <div class="relative mt-1.5 flex">
                            <input x-model="search" class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Mahsulot nomi" type="text">
                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 transition-colors duration-200" fill="none" viewbox="0 0 24 24">
                                    <path stroke="currentColor" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                        </div>
                    </label>
                    <label class="block">
                        <span>Kategoriya:</span>
                        <div class="relative mt-1.5 flex">
                            <select x-model="category_id" class="form-select w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">Bararchasi</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </label>
                    <div class="sm:col-span-2">
                        <span>Status:</span>
                        <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <label class="inline-flex items-center space-x-2">
                                <input x-model="status" value="" class="form-radio is-basic size-5 rounded-full border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent" type="radio" name="status">
                                <span>Barchasi</span>
                            </label>
                            <label class="inline-flex items-center space-x-2">
                                <input x-model="status" value="active" class="form-radio is-basic size-5 rounded-full border-slate-400/70 checked:border-success checked:bg-success hover:border-success focus:border-success dark:border-navy-400 dark:checked:border-success dark:checked:bg-success" type="radio" name="status">
                                <span>Faol</span>
                            </label>
                            <label class="inline-flex items-center space-x-2">
                                <input x-model="status" value="inactive" class="form-radio is-basic size-5 rounded-full border-slate-400/70 checked:border-error checked:bg-error hover:border-error focus:border-error dark:border-navy-400 dark:checked:border-error dark:checked:bg-error" type="radio" name="status">
                                <span>Nofaol</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-4 space-x-1 text-right">
                    <button @click="isFilterExpanded = false" class="btn font-medium text-slate-700 hover:bg-slate-300/20 active:bg-slate-300/25 dark:text-navy-100 dark:hover:bg-navy-300/20 dark:active:bg-navy-300/25">
                        Yopish
                    </button>
                    <button @click="resetFilter()" class="btn bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90">
                        Tozalash
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card mt-3">
        <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
            <table class="is-hoverable w-full text-left">
                <thead>
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Mahsulot
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Kategoriya
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Narx
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Qoldiq
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Status
                        </th>
                        <th class="whitespace-nowrap rounded-tr-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Amallar
                        </th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @include('products.partials.table-body')
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex flex-col justify-between space-y-4 px-4 py-4 sm:flex-row sm:items-center sm:space-y-0 sm:px-5" id="pagination-container">
            {{ $products->links() }}
        </div>
    </div>
</div>

<script>
    function productTable() {
        return {
            search: "{{ request('search') }}",
            category_id: "{{ request('category_id') }}",
            status: "{{ request('status') }}",
            debounce: null,
            
            init() {
                this.$watch('search', () => { clearTimeout(this.debounce); this.debounce = setTimeout(() => this.fetchResults(), 500) });
                this.$watch('category_id', () => this.fetchResults());
                this.$watch('status', () => this.fetchResults());
                this.bindPaginationLinks();
            },
            
            resetFilter() {
                this.search = '';
                this.category_id = '';
                this.status = '';
            },

            async fetchResults(url = '{{ route('products.index') }}') {
                const params = new URLSearchParams();
                if(this.search) params.append('search', this.search);
                if(this.category_id) params.append('category_id', this.category_id);
                if(this.status) params.append('status', this.status);
                
                // If url is from pagination, it might already contain params.
                // We should append our current filter state to it.
                // But Laravel pagination links include current GET params IF we used appends().
                // However, since we are doing AJAX, the initial links rendered by Blade might have initial params.
                // The cleaner way: If url is just route('products.index'), append query.
                // If url has page=X, also append query params. 
                // Wait, if I use `url` from `href`, it might have old search params.
                // I should strip them and re-append current `this.search`.
                
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
                    document.getElementById('total-count').innerText = data.total;
                    
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

            exportToExcel() {
                const params = new URLSearchParams();
                if(this.search) params.append('search', this.search);
                if(this.category_id) params.append('category_id', this.category_id);
                if(this.status) params.append('status', this.status);
                
                window.location.href = "{{ route('products.export') }}?" + params.toString();
            }
        }
    }
</script>
@endsection
