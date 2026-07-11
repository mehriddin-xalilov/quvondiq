@extends('layouts.app')

@section('title', 'Sertifikatlar')
@section('page-title', 'Sertifikatlar ro\'yxati')

@section('content')
<div class="flex items-center justify-between mt-4">
    <h3 class="text-lg font-medium text-slate-700 dark:text-navy-100">Sertifikatlar</h3>
    <a href="{{ route('sertifikatlar.create') }}" class="btn bg-primary text-white hover:bg-primary-focus dark:bg-accent">
        <i class="fa-solid fa-plus mr-2"></i> Yangi sertifikat
    </a>
</div>

{{-- Filtrlar --}}
@php $hasFilters = request()->filled('q') || request()->filled('date_from') || request()->filled('date_to'); @endphp
<form method="GET" action="{{ route('sertifikatlar.index') }}" class="card mt-5 p-4">
    <div class="flex flex-wrap items-end gap-3">
        <div class="min-w-[14rem] flex-1">
            <span class="text-xs text-slate-500 dark:text-navy-300">Qidirish</span>
            <label class="relative mt-1 flex">
                <input name="q" value="{{ request('q') }}" placeholder="Raqam, seriya, F.I.O., kasb"
                       class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent py-2 pl-9 pr-3 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                <div class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:peer-focus:text-accent">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </div>
            </label>
        </div>
        <div class="w-40">
            <span class="text-xs text-slate-500 dark:text-navy-300">Sanadan</span>
            <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="this.form.submit()"
                   class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-2.5 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:focus:border-accent">
        </div>
        <div class="w-40">
            <span class="text-xs text-slate-500 dark:text-navy-300">Sanagacha</span>
            <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="this.form.submit()"
                   class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-2.5 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:focus:border-accent">
        </div>
        <button type="submit" class="btn bg-primary text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
            <i class="fa-solid fa-filter mr-2"></i> Filtrlash
        </button>
        @if($hasFilters)
        <a href="{{ route('sertifikatlar.index') }}" class="btn border border-slate-300 text-slate-600 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-200 dark:hover:bg-navy-500">
            <i class="fa-solid fa-xmark mr-2"></i> Tozalash
        </a>
        @endif
    </div>
</form>

<p class="mt-3 text-xs text-slate-400 dark:text-navy-300">
    Jami: <span class="font-medium text-slate-600 dark:text-navy-100">{{ $sertifikatlar->total() }}</span> ta sertifikat
</p>

<div class="card mt-3">
    <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
        <table class="is-hoverable w-full text-left">
            <thead>
                <tr>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase rounded-tl-lg">#</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">Raqam</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">F.I.O.</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">Kasb</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">Sanalar</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">Yaratdi</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase rounded-tr-lg">Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sertifikatlar as $s)
                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                    <td class="px-4 py-3">{{ $s->id }}</td>
                    <td class="px-4 py-3 font-mono">
                        <a href="{{ route('sertifikatlar.show', $s) }}" class="text-primary hover:underline">
                            {{ $s->seria }}{{ $s->raqam }}
                        </a>
                    </td>
                    <td class="px-4 py-3">{{ $s->fio_uz }}</td>
                    <td class="px-4 py-3 text-sm">{{ $s->kasb_uz }}</td>
                    <td class="px-4 py-3 text-xs+">
                        {{ $s->boshlanish_sanasi?->format('d.m.Y') }} — {{ $s->tugash_sanasi?->format('d.m.Y') }}<br>
                        <span class="text-slate-400">{{ $s->soat }} soat</span>
                    </td>
                    <td class="px-4 py-3 text-xs+ text-slate-500">
                        {{ $s->creator?->name ?? '—' }}<br>
                        <span class="text-slate-400">{{ $s->created_at->format('d.m.Y') }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-1">
                            @if($s->certificate_path)
                            @php $ext = strtolower(pathinfo($s->certificate_path, PATHINFO_EXTENSION)); @endphp
                            <a href="{{ route('sertifikatlar.download', $s) }}" class="btn size-8 p-0 hover:bg-slate-300/20"
                               title="{{ $ext === 'pdf' ? 'PDF yuklab olish' : '.docx yuklab olish' }}">
                                <i class="fa-solid {{ $ext === 'pdf' ? 'fa-file-pdf text-error' : 'fa-file-word text-primary' }}"></i>
                            </a>
                            @endif
                            <a href="{{ route('sertifikatlar.edit', $s) }}" class="btn size-8 p-0 hover:bg-slate-300/20" title="Tahrirlash">
                                <i class="fa-solid fa-pen text-warning"></i>
                            </a>
                            <form action="{{ route('sertifikatlar.destroy', $s) }}" method="POST" onsubmit="return confirm('O\'chirilsinmi?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="btn size-8 p-0 hover:bg-slate-300/20" title="O'chirish">
                                    <i class="fa-solid fa-trash text-error"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-8 text-slate-400">
                    {{ $hasFilters ? 'Filtrga mos sertifikat topilmadi.' : 'Sertifikatlar yo\'q. Birinchisini yarating.' }}
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sertifikatlar->hasPages())
    <div class="px-4 py-3">{{ $sertifikatlar->links() }}</div>
    @endif
</div>
@endsection