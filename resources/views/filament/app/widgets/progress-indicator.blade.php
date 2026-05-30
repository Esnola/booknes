<x-filament-widgets::widget>
    <x-filament::section class="h-full">
        <div class="flex flex-col gap-2">
            <h2 class="text-lg font-semibold">Reading Streak to Pro Level</h2>
            <p class="text-sm text-gray-500">
                You have read <strong>{{ $booksRead }}</strong> books so far.
            </p>

            <div class="relative w-full h-4 bg-gray-200 rounded-full overflow-hidden">
                <div
                    class="h-full bg-gradient-to-r from-primary-500 to-primary-600 rounded-full transition-all duration-700"
                    style="width: {{ $progress }}%;"
                ></div>
            </div>

            <div class="flex justify-between text-xs text-gray-500">
                <span>0 books</span>
                <span>{{ $target }} books</span>
            </div>

            <p class="text-sm mt-2">
                @if($progress >= 100)
                    🎉 You’ve reached <strong>Pro Reader</strong> level! Keep it up!
                @else
                    Only <strong>{{ $target - $booksRead }}</strong> more books to reach <strong>Pro Reader</strong> level!
                @endif
            </p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
