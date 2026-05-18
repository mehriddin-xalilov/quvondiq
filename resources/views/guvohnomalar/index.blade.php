@extends('layouts.app')

@section('title', 'Guvohnomalar')
@section('page-title', 'Guvohnomalar ro\'yxati')

@section('content')
<div class="flex items-center justify-between mt-4">
    <h3 class="text-lg font-medium text-slate-700 dark:text-navy-100">Guvohnomalar</h3>
    <a href="{{ route('guvohnomalar.create') }}" class="btn bg-primary text-white hover:bg-primary-focus dark:bg-accent">
        <i class="fa-solid fa-plus mr-2"></i> Yangi guvohnoma
    </a>
</div>

<div class="card mt-5">
    <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
        <table class="is-hoverable w-full text-left">
            <thead>
                <tr>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase rounded-tl-lg">#</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">Raqam</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">F.I.O.</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">Mutaxassislik</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">Sanalar</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase">Yaratdi</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase rounded-tr-lg">Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guvohnomalar as $g)
                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                    <td class="px-4 py-3">{{ $g->id }}</td>
                    <td class="px-4 py-3 font-mono">
                        <a href="{{ route('guvohnomalar.show', $g) }}" class="text-primary hover:underline">
                            № {{ $g->raqam }}
                        </a>
                    </td>
                    <td class="px-4 py-3">{{ $g->familiya_oz }} {{ $g->ism_oz }} {{ $g->otasi_ismi_oz }}</td>
                    <td class="px-4 py-3 text-sm">
                        {{ $g->mutaxassislik_oz }}
                        @if($g->razryad)<span class="text-slate-400"> ({{ $g->razryad }}-разряд)</span>@endif
                    </td>
                    <td class="px-4 py-3 text-xs+">
                        {{ $g->boshlanish_sanasi?->format('d.m.Y') }} — {{ $g->tugash_sanasi?->format('d.m.Y') }}
                    </td>
                    <td class="px-4 py-3 text-xs+ text-slate-500">
                        {{ $g->creator?->name ?? '—' }}<br>
                        <span class="text-slate-400">{{ $g->created_at->format('d.m.Y') }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-1">
                            @if($g->guvohnoma_path)
                            <a href="{{ route('guvohnomalar.download', $g) }}" class="btn size-8 p-0 hover:bg-slate-300/20" title="PDF yuklab olish">
                                <i class="fa-solid fa-file-pdf text-success"></i>
                            </a>
                            @endif
                            <a href="{{ route('guvohnomalar.edit', $g) }}" class="btn size-8 p-0 hover:bg-slate-300/20" title="Tahrirlash">
                                <i class="fa-solid fa-pen text-warning"></i>
                            </a>
                            <form action="{{ route('guvohnomalar.destroy', $g) }}" method="POST" onsubmit="return confirm('O\'chirilsinmi?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="btn size-8 p-0 hover:bg-slate-300/20" title="O'chirish">
                                    <i class="fa-solid fa-trash text-error"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-8 text-slate-400">Guvohnomalar yo'q.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($guvohnomalar->hasPages())
    <div class="px-4 py-3">{{ $guvohnomalar->links() }}</div>
    @endif
</div>
@endsection