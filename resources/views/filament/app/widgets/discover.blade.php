@use('App\Filament\App\Resources\Books\BookResource')
<x-filament-widgets::widget>
    <x-filament::section class="h-full">
        <h3 class="text-lg font-semibold">Discover a new book</h3>
        <div class="mt-3 flex gap-3 items-center">
            <div class="shrink-0 w-16">
                @if ($book['image'])
                    <img src="{{ $book['image'] }}" alt="{{ $book['title'] }}" class="w-full aspect-2/3 object-cover rounded-sm">
                @else
                    <div class="w-full aspect-2/3 rounded-sm shrink-0 p-2 bg-slate-200 flex items-center justify-center text-sm text-center text-gray-400">
                        No cover
                    </div>
                @endif
            </div>

            <div class="space-y-1">
                <p class="text-base/snug font-medium">
                    {{ $book['title']}}
                </p>
                <p class="text-xs text-primary-600 font-medium">
                    {{ $book->author }}
                </p>
            </div>
        </div>
        <div class="mt-3 line-clamp-6 text-sm text-slate-700">
            {{ $book->description }}
        </div>
        <div class="mt-2">
            <a href="{{ BookResource::getUrl('view', ['record' => $book['id']]) ?? '#' }}" class="text-sm text-primary-600 font-medium hover:underline">
                Read more →
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
