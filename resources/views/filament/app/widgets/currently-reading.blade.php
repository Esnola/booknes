<x-filament-widgets::widget>
    <x-filament::section class="h-full">
        <h2 class="text-lg font-semibold">Currently Reading</h2>
        <div class="mt-3 grid grid-cols-1 sm:grid-cols-[repeat(auto-fit,minmax(12rem,1fr))] gap-4">
            @forelse ($books as $book)
                <div class="flex items-center gap-3">
                    <div class="shrink-0 w-16 aspect-2/3">
                        @if ($book['image'])
                            <img src="{{ $book['image'] }}" alt="{{ $book['title'] }}" class="w-full object-cover rounded-sm">
                        @else
                            <div class="w-full rounded-sm shrink-0 p-2 bg-slate-200 flex items-center justify-center text-sm text-center text-gray-400">
                                No cover
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 space-y-1">
                        <p class="font-medium">
                            {{ $book['title'] }}
                        </p>
                        <p class="text-xs text-primary-600">
                            {{ $book['author'] }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">You are not currently reading any books
                    .</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
