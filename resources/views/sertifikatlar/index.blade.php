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

<div class="card mt-5">
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
                    <td class="px-4 py-3">{{ $s->familiya_uz }} {{ $s->ism_uz }} {{ $s->otasi_ismi_uz }}</td>
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
                            <a href="{{ route('sertifikatlar.download', $s) }}" class="btn size-8 p-0 hover:bg-slate-300/20" title=".docx yuklab olish">
                                <i class="fa-solid fa-download text-success"></i>
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
                <tr><td colspan="7" class="text-center py-8 text-slate-400">Sertifikatlar yo'q. Birinchisini yarating.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sertifikatlar->hasPages())
    <div class="px-4 py-3">{{ $sertifikatlar->links() }}</div>
    @endif
</div>
@endsection