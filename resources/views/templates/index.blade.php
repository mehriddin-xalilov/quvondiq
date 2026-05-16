@extends('layouts.app')

@section('title', 'Shablonlar')
@section('page-title', 'Hujjat shablonlari')

@section('content')
<div class="flex items-center justify-between mt-4">
    <h3 class="text-lg font-medium text-slate-700 dark:text-navy-100">
        Barcha shablonlar
    </h3>

    @can('templates.create')
    <a href="{{ route('templates.create') }}" class="btn bg-primary font-medium text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
        <i class="fa-solid fa-upload mr-2"></i> Yangi shablon yuklash
    </a>
    @endcan
</div>

<div class="card mt-5">
    <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
        <table class="is-hoverable w-full text-left">
            <thead>
                <tr>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 rounded-tl-lg">#</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Nomi</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Turi</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Fayl</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Holat</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Yaratdi</th>
                    <th class="bg-slate-200 dark:bg-navy-800 px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 rounded-tr-lg">Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $template)
                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                    <td class="px-4 py-3 sm:px-5">{{ $template->id }}</td>
                    <td class="px-4 py-3 sm:px-5">
                        <a href="{{ route('templates.show', $template) }}" class="font-medium text-primary hover:underline">
                            {{ $template->name }}
                        </a>
                        @if($template->description)
                            <p class="text-xs text-slate-400 line-clamp-1">{{ $template->description }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 sm:px-5">
                        @if($template->type === 'certificate')
                            <span class="badge rounded-full bg-success/10 text-success">Sertifikat</span>
                        @else
                            <span class="badge rounded-full bg-warning/10 text-warning">Guvohnoma</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 sm:px-5 text-xs text-slate-500">
                        {{ $template->original_filename }}
                    </td>
                    <td class="px-4 py-3 sm:px-5">
                        @if($template->is_active)
                            <span class="badge rounded-full bg-success/10 text-success">Faol</span>
                        @else
                            <span class="badge rounded-full bg-slate-200 text-slate-600 dark:bg-navy-500 dark:text-navy-200">Nofaol</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 sm:px-5 text-xs+ text-slate-500">
                        {{ $template->creator?->name ?? '—' }}<br>
                        <span class="text-slate-400">{{ $template->created_at->format('d.m.Y H:i') }}</span>
                    </td>
                    <td class="px-4 py-3 sm:px-5">
                        <div class="flex items-center space-x-1">
                            <a href="{{ route('templates.download', $template) }}" class="btn size-8 p-0 hover:bg-slate-300/20" title="Yuklab olish">
                                <i class="fa-solid fa-download text-info"></i>
                            </a>
                            @can('templates.edit')
                            <a href="{{ route('templates.edit', $template) }}" class="btn size-8 p-0 hover:bg-slate-300/20" title="Tahrirlash">
                                <i class="fa-solid fa-pen text-warning"></i>
                            </a>
                            @endcan
                            @can('templates.delete')
                            <form action="{{ route('templates.destroy', $template) }}" method="POST" onsubmit="return confirm('O\'chirilsinmi?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="btn size-8 p-0 hover:bg-slate-300/20" title="O'chirish">
                                    <i class="fa-solid fa-trash text-error"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-8 text-slate-400">Shablonlar yo'q</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($templates->hasPages())
    <div class="px-4 py-3">{{ $templates->links() }}</div>
    @endif
</div>
@endsection