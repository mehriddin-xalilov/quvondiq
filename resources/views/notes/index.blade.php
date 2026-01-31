@extends('layouts.app')

@section('title', 'Eslatmalar')
@section('page-title', 'Eslatmalar')

@section('content')
<div class="space-y-4 sm:space-y-5 lg:space-y-6">
    <div class="flex justify-end">
        <a href="{{ route('notes.export') }}" class="btn bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90">
            <i class="fa-solid fa-file-excel mr-2"></i> Excel
        </a>
    </div>
    <!-- Quick Add Form -->
    <div class="card p-4 sm:p-5" x-data="{ expanded: false }">
        <form action="{{ route('notes.store') }}" method="POST">
            @csrf
            <div class="relative">
                <input @click="expanded = true" type="text" name="content" class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Eslatma yozing..." required />
                <div x-show="expanded" @click.outside="expanded = false" class="mt-3 space-y-3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <label class="inline-flex items-center space-x-2">
                                <input class="form-checkbox is-basic size-4 rounded border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent" type="checkbox" name="is_important" />
                                <span class="text-sm">Muhim</span>
                            </label>
                            <input type="date" name="reminder_date" class="form-input h-8 rounded-lg border border-slate-300 bg-transparent px-2 py-1 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" />
                        </div>
                        <button type="submit" class="btn h-8 rounded-full bg-primary px-4 text-xs+ font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                            Qo'shish
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Notes Grid -->
    <div class="columns-1 gap-4 sm:columns-2 lg:columns-3 space-y-4 pb-12">
        @forelse($notes as $note)
        <div class="break-inside-avoid relative group rounded-lg p-4 sm:p-5 transition-shadow hover:shadow-lg dark:hover:shadow-navy-450/50 
            {{ $note->is_completed ? 'bg-slate-100 dark:bg-navy-600 opacity-75' : ($note->is_important ? 'bg-warning/10 border border-warning/30' : 'bg-white dark:bg-navy-700 border border-slate-150 dark:border-navy-500') }}
            " x-data="{ editing: false }">
            
            <!-- View Mode -->
            <div x-show="!editing">
                <div class="flex items-start justify-between">
                    <p class="text-slate-700 dark:text-navy-100 whitespace-pre-wrap {{ $note->is_completed ? 'line-through text-slate-400' : '' }}">{{ $note->content }}</p>
                    <div class="flex flex-col space-y-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button @click="editing = true" class="btn size-6 rounded-full p-0 text-slate-500 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:text-navy-200 dark:hover:bg-navy-300/20">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </button>
                        <form action="{{ route('notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn size-6 rounded-full p-0 text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="mt-3 flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-xs text-slate-400">
                        <span>{{ $note->created_at->format('d.m.Y H:i') }}</span>
                        @if($note->reminder_date)
                            <span class="flex items-center text-info">
                                <i class="fa-regular fa-clock mr-1"></i> {{ $note->reminder_date->format('d.m.Y') }}
                            </span>
                        @endif
                    </div>
                    
                    <form action="{{ route('notes.update', $note->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="content" value="{{ $note->content }}">
                        <input type="hidden" name="is_completed" value="{{ $note->is_completed ? '0' : '1' }}">
                        <button type="submit" class="btn size-6 rounded-full p-0 {{ $note->is_completed ? 'text-success hover:bg-success/20' : 'text-slate-400 hover:bg-slate-300/20' }}" title="{{ $note->is_completed ? 'Bajarilmadi deb belgilash' : 'Bajarildi deb belgilash' }}">
                            <i class="fa-solid fa-check text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Edit Mode -->
            <div x-show="editing" style="display: none;">
                <form action="{{ route('notes.update', $note->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <textarea name="content" rows="3" class="form-textarea w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ $note->content }}</textarea>
                    
                    <div class="mt-2 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                             <label class="inline-flex items-center space-x-2">
                                <input class="form-checkbox is-basic size-4 rounded border-slate-400/70 checked:border-primary checked:bg-primary" type="checkbox" name="is_important" value="1" {{ $note->is_important ? 'checked' : '' }} />
                                <span class="text-xs">Muhim</span>
                            </label>
                            <input type="date" name="reminder_date" value="{{ $note->reminder_date ? $note->reminder_date->format('Y-m-d') : '' }}" class="form-input h-7 rounded border border-slate-300 bg-transparent px-2 py-1 text-xs" />
                        </div>
                        <div class="flex space-x-1">
                            <button @click="editing = false" type="button" class="btn h-7 rounded bg-slate-150 px-2 text-xs font-medium text-slate-800 hover:bg-slate-200 dark:bg-navy-500 dark:text-navy-50">Bekor</button>
                            <button type="submit" class="btn h-7 rounded bg-primary px-2 text-xs font-medium text-white hover:bg-primary-focus dark:bg-accent">Saqlash</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full py-10 text-center">
            <div class="inline-flex justify-center items-center rounded-full bg-slate-100 p-4 dark:bg-navy-600 mb-3">
                <i class="fa-regular fa-note-sticky text-3xl text-slate-400"></i>
            </div>
            <p class="text-slate-500">Hozircha eslatmalar yo'q</p>
        </div>
        @endforelse
    </div>

    @if($notes->hasPages())
    <div class="mt-4">
        {{ $notes->links() }}
    </div>
    @endif
</div>
@endsection
