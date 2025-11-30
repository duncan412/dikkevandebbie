<div
    wire:poll.4000ms="next"
    x-data="{ startX: 0, endX: 0 }"
    x-on:touchstart="startX = $event.touches[0].clientX"
    x-on:touchend="
        endX = $event.changedTouches[0].clientX;
        if (startX - endX > 50) { $wire.next(); }
        if (endX - startX > 50) { $wire.prev(); }
    "
>
    <div class="w-full max-w-5xl mx-auto px-4">
        <div class="relative bg-gray-900 rounded-xl overflow-hidden shadow-lg">
            <div class="relative w-full h-64 sm:h-72 md:h-80 lg:h-96 xl:h-[28rem] overflow-hidden">

                @foreach ($computedSlides as $slide)
                    <div
                        wire:key="slide-{{ $slide['index'] }}"
                        class="absolute inset-0 w-full h-full {{ $slide['gradient'] }}
                        transition-opacity duration-700 ease-in-out
                        {{ $slide['is_current'] ? 'opacity-100 z-30' : ($slide['is_previous'] ? 'opacity-0 z-20' : 'opacity-0 z-10') }}"
                    >
                        <div class="text-white p-6 sm:p-8 md:p-12 text-center
                                    flex items-center justify-center h-full">
                            <p class="text-lg sm:text-xl md:text-2xl font-semibold leading-snug">
                                {{ $slide['title'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <button wire:click="prev"
                class="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-60
                        hover:bg-opacity-90 text-black rounded-full w-10 h-10 items-center justify-center
                        transition z-40">
                &#10094;
            </button>

            <button wire:click="next"
                class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-60
                        hover:bg-opacity-90 text-black rounded-full w-10 h-10 items-center justify-center
                        transition z-40">
                &#10095;
            </button>

            {{-- Dots --}}
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-40">
                @foreach ($computedSlides as $slide)
                    <button
                        wire:click="goTo({{ $slide['index'] }})"
                        wire:key="dot-{{ $slide['index'] }}"
                        class="w-3 h-3 rounded-full transition
                                {{ $slide['is_current'] ? 'bg-white' : 'bg-white/40 hover:bg-white/60' }}"
                    ></button>
                @endforeach
            </div>

        </div>
    </div>
</div>
