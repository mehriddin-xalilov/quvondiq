@extends('layouts.app')

@section('title', $template->name)
@section('page-title', 'Shablon: ' . $template->name)

@section('content')
<div class="flex items-center justify-between mt-4">
    <a href="{{ route('templates.index') }}" class="text-primary text-sm hover:underline">← Ro'yxatga qaytish</a>
    <div class="space-x-2">
        <a href="{{ route('templates.download', $template) }}" class="btn bg-info text-white hover:bg-info-focus">
            <i class="fa-solid fa-download mr-2"></i> Yuklab olish
        </a>
        @can('templates.edit')
        <a href="{{ route('templates.edit', $template) }}" class="btn bg-warning text-white hover:bg-warning-focus">
            Tahrirlash
        </a>
        @endcan
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-5">
    <div class="card p-5 lg:col-span-1">
        <h4 class="text-base font-medium text-slate-700 dark:text-navy-100">Ma'lumot</h4>
        <dl class="mt-4 space-y-3 text-sm">
            <div><dt class="text-slate-400">Nomi</dt><dd class="font-medium">{{ $template->name }}</dd></div>
            <div><dt class="text-slate-400">Slug</dt><dd class="font-mono text-xs">{{ $template->slug }}</dd></div>
            <div>
                <dt class="text-slate-400">Turi</dt>
                <dd>
                    @if($template->type === 'certificate')
                        <span class="badge rounded-full bg-success/10 text-success">Sertifikat</span>
                    @else
                        <span class="badge rounded-full bg-warning/10 text-warning">Guvohnoma</span>
                    @endif
                </dd>
            </div>
            <div><dt class="text-slate-400">Fayl</dt><dd class="text-xs">{{ $template->original_filename }}</dd></div>
            <div><dt class="text-slate-400">Holat</dt>
                <dd>
                    @if($template->is_active)
                        <span class="badge rounded-full bg-success/10 text-success">Faol</span>
                    @else
                        <span class="badge rounded-full bg-slate-200 text-slate-600 dark:bg-navy-500">Nofaol</span>
                    @endif
                </dd>
            </div>
            @if($template->description)
            <div><dt class="text-slate-400">Tavsif</dt><dd class="text-xs">{{ $template->description }}</dd></div>
            @endif
            <div><dt class="text-slate-400">Yaratdi</dt><dd>{{ $template->creator?->name ?? '—' }}<br><span class="text-xs text-slate-400">{{ $template->created_at->format('d.m.Y H:i') }}</span></dd></div>
        </dl>
    </div>

    <div class="card p-5 lg:col-span-2">
        <h4 class="text-base font-medium text-slate-700 dark:text-navy-100">
            Aniqlangan placeholder'lar
            <span class="text-sm text-slate-400">({{ count($placeholders) }} ta)</span>
        </h4>
        <p class="text-xs text-slate-400 mt-1">Shablon ichida shu maydonlar topildi. Forma'da ularning qiymatlari kiritiladi.</p>

        @if(count($placeholders))
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($placeholders as $ph)
                    @php $tag = '{{'.$ph.'}}'; @endphp
                    <code class="px-2 py-1 bg-slate-100 dark:bg-navy-700 rounded text-xs+ font-mono text-primary">{{ $tag }}</code>
                @endforeach
            </div>
        @else
            <p class="mt-4 text-center text-slate-400 text-sm">Hech qanday <code>@verbatim{{...}}@endverbatim</code> placeholder topilmadi. Shablonni qayta yuklang.</p>
        @endif
    </div>
</div>
@endsection