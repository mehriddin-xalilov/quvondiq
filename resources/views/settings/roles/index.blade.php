@extends('layouts.app')

@section('title', 'Rollar')
@section('page-title', 'Rollar Boshqaruvi')
@section('sidebar-title', 'Boshqaruv')

@section('sidebar-menu')
    <div class="mt-2">
        <label class="px-2 text-xs+ font-medium text-slate-500 uppercase">Foydalanuvchilar</label>
        <ul class="mt-1 space-y-1.5">
            <li>
                <a href="{{ route('settings.users.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('settings.users.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Foydalanuvchilar</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.roles.index') }}" class="group flex space-x-2 rounded-lg px-2 py-2 tracking-wide outline-none transition-all {{ request()->routeIs('settings.roles.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 dark:text-navy-200 dark:hover:bg-navy-600 dark:hover:text-navy-50 dark:focus:bg-navy-600 dark:focus:text-navy-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Rollar</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
<div class="flex items-center justify-between mt-4">
    <h3 class="text-lg font-medium text-slate-700 dark:text-navy-100">
        Barcha Rollar
    </h3>
    
    @can('roles.create')
    <div class="flex space-x-2">
        <a href="{{ route('settings.roles.export') }}" class="btn bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90">
            <i class="fa-solid fa-file-excel mr-2"></i> Excel
        </a>
        <a href="{{ route('settings.roles.create') }}" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Yangi Rol</span>
        </a>
    </div>
    @endcan
</div>

<div class="card mt-5">
    <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
        <table class="is-hoverable w-full text-left">
            <thead>
                <tr>
                    <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                        #
                    </th>
                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                        Rol Nomi
                    </th>
                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                        Ruxsatlar Soni
                    </th>
                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                        Foydalanuvchilar
                    </th>
                    <th class="whitespace-nowrap rounded-tr-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                        Amallar
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">{{ $loop->iteration }}</td>
                    <td class="whitespace-nowrap px-3 py-3 font-medium text-slate-700 dark:text-navy-100 lg:px-5">
                        {{ $role->name }}
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                        <div class="badge rounded-full bg-info/10 text-info dark:bg-info/15">
                            {{ $role->permissions->count() }} ta
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                        {{ $role->users->count() }} ta
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                        <div x-data="usePopper({placement:'bottom-end',offset:4})" @click.outside="isShowPopper && (isShowPopper = false)" class="inline-flex">
                            <button x-ref="popperRef" @click="isShowPopper = !isShowPopper" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                </svg>
                            </button>

                            <div x-ref="popperRoot" class="popper-root" :class="isShowPopper && 'show'">
                                <div class="popper-box rounded-md border border-slate-150 bg-white py-1.5 font-inter dark:border-navy-500 dark:bg-navy-700">
                                    <ul>
                                        <li>
                                            <a href="{{ route('settings.roles.show', $role) }}" class="flex h-8 items-center space-x-3 px-3 pr-8 font-medium tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 text-slate-400 dark:text-navy-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <span>Ko'rish</span>
                                            </a>
                                        </li>
                                        @can('roles.edit')
                                        <li>
                                            <a href="{{ route('settings.roles.edit', $role) }}" class="flex h-8 items-center space-x-3 px-3 pr-8 font-medium tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 text-slate-400 dark:text-navy-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                <span>Tahrirlash</span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('roles.delete')
                                        <li>
                                            <div class="my-1 h-px bg-slate-150 dark:bg-navy-500"></div>
                                        </li>
                                        <li>
                                            <button @click="document.querySelector('#delete-modal-{{ $role->id }}').showModal()" type="button" class="flex h-8 w-full items-center space-x-3 px-3 pr-8 font-medium tracking-wide text-error outline-none transition-all hover:bg-error/20 focus:bg-error/20 dark:hover:bg-error/20 dark:focus:bg-error/20">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span>O'chirish</span>
                                            </button>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                        Hozircha rollar yo'q
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($roles->hasPages())
    <div class="flex flex-col justify-between space-y-4 px-4 py-4 sm:flex-row sm:items-center sm:space-y-0 sm:px-5">
        {{ $roles->links() }}
    </div>
    @endif
</div>

<!-- Delete Confirmation Modals -->
@foreach($roles as $role)
<dialog id="delete-modal-{{ $role->id }}" class="dialog rounded-lg bg-white dark:bg-navy-700">
    <div class="p-6">
        <div class="flex items-center justify-center">
            <div class="flex size-16 items-center justify-center rounded-full bg-error/10 dark:bg-error/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <h3 class="text-lg font-medium text-slate-700 dark:text-navy-100">
                Rolni o'chirish
            </h3>
            <p class="mt-2 text-slate-500 dark:text-navy-300">
                Rostdan ham <strong class="text-slate-700 dark:text-navy-100">{{ $role->name }}</strong> rolini o'chirmoqchimisiz?
            </p>
            @if($role->users->count() > 0)
            <div class="mt-3 rounded-lg bg-warning/10 p-3 dark:bg-warning/15">
                <p class="text-sm text-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Bu rolga {{ $role->users->count() }} ta foydalanuvchi tayinlangan!
                </p>
            </div>
            @endif
        </div>

        <div class="mt-6 flex space-x-2">
            <button @click="$el.closest('dialog').close()" class="btn flex-1 border border-slate-300 font-medium text-slate-700 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
                Bekor qilish
            </button>
            <form method="POST" action="{{ route('settings.roles.destroy', $role) }}" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn w-full bg-error font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90">
                    O'chirish
                </button>
            </form>
        </div>
    </div>
</dialog>
@endforeach
@endsection
