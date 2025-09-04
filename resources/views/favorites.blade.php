<x-filament::page>
    <!-- Search -->
    <div class="mb-6">
        <input wire:model.debounce.500ms="search"
               type="text"
               placeholder=" Search favorites..."
               class="px-4 py-2 border rounded-lg w-full focus:ring-primary-500 focus:border-primary-500" />
    </div>

    @if($this->images->count())
        <!-- Favorites grid -->
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($this->images as $img)
                <div class="border rounded-xl bg-white shadow-sm overflow-hidden hover:shadow-md transition">

                    <div class="relative">
                        @if($img['file_path'])
                            <img src="{{ $img['file_path'] }}"
                                 alt="{{ $img['title'] }}"
                                 class="w-full h-56 object-cover">
                        @else
                            <div class="w-full h-56 flex items-center justify-center bg-gray-100">
                                <span class="text-gray-400">No image</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-500 mb-2 black">{{ $img['title'] }}</h3>

                        <div class="flex justify-between items-center text-sm">
                            <!-- Unfavorite -->
                            <button wire:click="toggleFavorite({{ $img['id'] }})"
                                    class="text-red-500 hover:text-red-600 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5" fill="red" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 
                                    2 8.5c0-2.49 2.01-4.5 4.5-4.5 
                                    1.74 0 3.41.81 4.5 2.09 
                                    C12.09 4.81 13.76 4 15.5 4 
                                    17.99 4 20 6.01 20 8.5c0 
                                    3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            <span class="font-semibold text-gray-800 dark:text-gray-500 mb-2">
                                Unfavorite
                            </span>

                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $this->images->links() }}
        </div>
    @else
        <!-- Empty state -->
        <div class="text-center py-12 text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="grey" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 7v10c0 2 2 4 4 4h10c2 0 4-2 4-4V7c0-2-2-4-4-4H7c-2 0-4 2-4 4z"/>
            </svg>
            <p class="mt-4">No favorites yet. Add some from your gallery!</p>
        </div>
    @endif
</x-filament::page>
