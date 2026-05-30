<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{ state: $wire.$entangle(@js($getStatePath())) }"
        {{ $getExtraAttributeBag() }}
    >
        <div class="flex flex-row-reverse w-fit gap-1">
            @for($i = 5; $i >= 1; $i--)
                <label
                    class="text-slate-200 hover:text-amber-400 hover:scale-110
                has-checked:text-amber-500 cursor-pointer
                peer peer-hover:text-amber-400 peer-has-checked:text-amber-500">
                    <input
                        class="sr-only"
                        type="radio"
                        x-model="state"
                        value="{{ $i }}"
                    />
                    <span>
                    <x-heroicon-s-star
                        class="h-6 w-6"
                    />
                </span>
                </label>
            @endfor
        </div>
    </div>
</x-dynamic-component>
