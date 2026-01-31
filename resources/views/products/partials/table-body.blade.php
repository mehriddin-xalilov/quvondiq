                @forelse($products as $product)
                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                        <div class="flex items-center space-x-4">
                            <div class="avatar size-9">
                                @if($product->image)
                                    <img class="rounded-lg object-cover" src="{{ asset('storage/'.$product->image) }}" alt="image"/>
                                @else
                                    <div class="is-initial rounded-lg bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-100 text-xs">
                                        {{ substr($product->name, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-slate-700 dark:text-navy-100">{{ $product->name }}</p>
                                <p class="text-xs text-slate-400">{{ $product->code }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                        <div class="badge space-x-2.5 px-0 text-slate-800 dark:text-navy-100">
                            {{ $product->category->name ?? 'Kategoriyasiz' }}
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                        <p class="font-medium">{{ number_format($product->price, 0, '.', ' ') }} so'm</p>
                        @if($product->cost_price)
                            <p class="text-xs text-slate-400">Tan: {{ number_format($product->cost_price, 0, '.', ' ') }}</p>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                        <div class="badge space-x-2.5 px-0 text-slate-800 dark:text-navy-100">
                            <div class="size-2 rounded-full {{ $product->is_low_stock ? 'bg-error' : 'bg-success' }}"></div>
                            <span>{{ $product->stock->quantity ?? 0 }} {{ $product->unit }}</span>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                        <div class="badge rounded-full {{ $product->is_active ? 'bg-success/10 text-success dark:bg-success/15' : 'bg-error/10 text-error dark:bg-error/15' }}">
                            {{ $product->is_active ? 'Faol' : 'Nofaol' }}
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                        <div class="flex space-x-2">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn size-8 p-0 text-info hover:bg-info/20 focus:bg-info/20 active:bg-info/25">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn size-8 p-0 text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-3 sm:px-5 text-center text-slate-400">
                        Mahsulotlar topilmadi
                    </td>
                </tr>
                @endforelse
