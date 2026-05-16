@extends('layouts.app')

@section('title', 'Mutaxassisliklar')
@section('page-title', 'Mutaxassisliklar (Kasblar)')

@section('content')
<div class="flex flex-col gap-3 mt-4 sm:flex-row sm:items-center sm:justify-between">
    <h3 class="text-lg font-medium text-slate-700 dark:text-navy-100">
        Barcha mutaxassisliklar
        <span class="text-sm text-slate-400">({{ $professions->total() }})</span>
    </h3>

    <div class="flex items-center gap-2">
        <form method="GET" class="flex">
            <div class="relative">
                <input type="text" name="q" value="{{ $search }}" placeholder="Qidirish..."
                       class="form-input h-9 w-64 rounded-lg border border-slate-300 bg-transparent px-3 pl-9 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-9 items-center justify-center text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
            </div>
        </form>

        <a href="{{ route('professions.create') }}" class="btn bg-primary font-medium text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
            <i class="fa-solid fa-plus mr-2"></i> Yangi
        </a>
    </div>
</div>

<div class="card mt-5">
    <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
        <table class="is-hoverable w-full text-left">
            <thead>
                <tr>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase rounded-tl-lg">#</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">Kod</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">Lotin (UZ)</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">Kirill (UZ)</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">Русский</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">English</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase rounded-tr-lg">Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($professions as $p)
                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                    <td class="px-4 py-3">{{ $p->id }}</td>
                    <td class="px-4 py-3 font-mono text-xs+ text-slate-500">{{ $p->code ?? '—' }}</td>
                    <td class="px-4 py-3 font-medium">{{ $p->name_uz }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $p->name_oz ?? '—' }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $p->name_ru ?? '—' }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $p->name_en ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-1">
                            <a href="{{ route('professions.edit', $p) }}" class="btn size-8 p-0 hover:bg-slate-300/20" title="Tahrirlash">
                                <i class="fa-solid fa-pen text-warning"></i>
                            </a>
                            <form action="{{ route('professions.destroy', $p) }}" method="POST" onsubmit="return confirm('O\'chirilsinmi?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="btn size-8 p-0 hover:bg-slate-300/20" title="O'chirish">
                                    <i class="fa-solid fa-trash text-error"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-10 text-slate-400">
                        @if($search)
                            "{{ $search }}" bo'yicha mutaxassislik topilmadi.
                        @else
                            Mutaxassisliklar yo'q. Birinchisini qo'shing.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($professions->hasPages())
    <div class="px-4 py-3">{{ $professions->links() }}</div>
    @endif
</div>
@endsection