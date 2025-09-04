<x-filament::page>
    <!-- Search -->
    <div class="mb-6">
        <input wire:model.debounce.500ms="search"
               type="text"
               placeholder="Search artworks..."
               class="px-3 py-2 border rounded w-full" />
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($images as $img)
            @php
                $imageUrl = $img['image_id']
                    ? "https://www.artic.edu/iiif/2/{$img['image_id']}/full/843,/0/default.jpg"
                    : null;
                $isFav = in_array($img['id'], $favorites);
            @endphp

            <div x-data="{ enlarged: false }" class="border rounded bg-white shadow-sm overflow-hidden">
                <div class="relative">
                    @if($imageUrl)
                        <img
                            :class="enlarged ? 'scale-110' : 'scale-100'"
                            @click="enlarged = !enlarged"
                            src="{{ $imageUrl }}"
                            alt="{{ $img['title'] }}"
                            class="w-full h-64 object-cover transition-transform duration-300 transform cursor-pointer"
                        />
                    @else
                        <div class="w-full h-64 flex items-center justify-center bg-gray-100">
                            <span class="text-gray-500">No image</span>
                        </div>
                    @endif
                </div>

                <div class="p-3">
                    <h3 class="font-semibold text-sm mb-2">{{ $img['title'] }}</h3>

                    <div class="flex justify-between items-center">
                        <div class="flex gap-3 items-center">
                            <!-- Favorite -->
                            <button wire:click="toggleFavorite({{ $img['id'] }})" title="Toggle Favorite">
                                @if($isFav)
                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 24 24" fill="red">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5c0-2.49 2.01-4.5 4.5-4.5
                                            1.74 0 3.41.81 4.5 2.09C12.09 4.81 13.76 4 15.5 4
                                            17.99 4 20 6.01 20 8.5c0 3.78-3.4 6.86-8.55
                                            11.54L12 21.35z"/>
                                        </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="grey">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4.318 6.318a4.5 4.5 0 0 1 6.364 0L12
                                                  7.636l1.318-1.318a4.5 4.5 0 1 1
                                                  6.364 6.364L12 21.364l-7.682-7.682a4.5
                                                  4.5 0 0 1 0-6.364z"/>
                                        </svg>
                                @endif
                            </button>

                            <!-- Download -->
<a href="{{ route('images.download', $img['id']) }}" class="text-primary-600 hover:underline"> Download </a>                            

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination controls -->
    @if(!empty($pagination))
        <div class="mt-6 flex items-center justify-between">
            <div class="space-x-2">
                <button wire:click="prevPage"
                        class="px-3 py-1 bg-gray-100 rounded disabled:opacity-50"
                        @disabled(!($pagination['current_page'] > 1))>
                    Previous
                </button>

                <button wire:click="nextPage"
                        class="px-3 py-1 bg-gray-100 rounded disabled:opacity-50"
                        @disabled($pagination['current_page'] >= $pagination['total_pages'])>
                    Next
                </button>
            </div>

            <div class="text-sm text-gray-600">
                Page {{ $pagination['current_page'] }} of {{ $pagination['total_pages'] }}
            </div>
        </div>
    @endif
</x-filament::page>
