@forelse($customers as $customer)
<tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
        <div class="flex items-center space-x-3">
            <div class="avatar size-10">
                <div class="is-initial rounded-full bg-primary/10 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                    {{ substr($customer->name, 0, 2) }}
                </div>
            </div>
            <div>
                <a href="{{ route('customers.show', $customer->id) }}" class="font-medium text-slate-700 hover:text-primary dark:text-navy-100 dark:hover:text-accent-light transition-colors">{{ $customer->name }}</a>
                @if($customer->telegram_username)
                    <p class="text-xs text-slate-400">{{ $customer->telegram_username }}</p>
                @endif
            </div>
        </div>
    </td>
    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
        <a href="tel:{{ $customer->phone }}" class="text-primary hover:underline">{{ $customer->phone }}</a>
    </td>
    <td class="px-4 py-3 sm:px-5">
        <p class="text-sm text-slate-600 dark:text-navy-200 line-clamp-2">{{ $customer->address ?? '-' }}</p>
    </td>
    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
        @if($customer->is_regular)
            <div class="badge rounded-full bg-success/10 text-success dark:bg-success/15">
                <i class="fa-solid fa-star mr-1"></i> Doimiy
            </div>
        @else
            <div class="badge rounded-full bg-slate-150 text-slate-800 dark:bg-navy-500 dark:text-navy-100">
                Oddiy
            </div>
        @endif
    </td>
    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
        @if($customer->total_debt > 0)
            <span class="font-medium text-error">{{ number_format($customer->total_debt, 0, ',', ' ') }} so'm</span>
        @else
            <span class="text-slate-400">-</span>
        @endif
    </td>
    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
        <div class="text-sm">
            <p class="font-medium">{{ $customer->total_orders }} ta</p>
            <p class="text-xs text-slate-400">{{ number_format($customer->total_purchases, 0, ',', ' ') }} so'm</p>
        </div>
    </td>
    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
        <div class="flex space-x-2">
            <a href="{{ route('customers.show', $customer->id) }}" class="btn size-8 p-0 text-primary hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25">
                <i class="fa-solid fa-eye"></i>
            </a>
            @can('edit-customers')
            <a href="{{ route('customers.edit', $customer->id) }}" class="btn size-8 p-0 text-info hover:bg-info/20 focus:bg-info/20 active:bg-info/25">
                <i class="fa-solid fa-pen"></i>
            </a>
            @endcan
            @can('delete-customers')
            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn size-8 p-0 text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
            @endcan
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="px-4 py-10 sm:px-5 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-16 mx-auto text-slate-300 dark:text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <p class="mt-4 text-slate-400 dark:text-navy-300">Hozircha mijozlar yo'q</p>
    </td>
</tr>
@endforelse
