<x-filament::page>
    <!-- Upload form -->
    <div class="mb-8 p-6 bg-white border rounded-xl shadow-sm">
        <h2 class="font-semibold text-lg mb-4 text-gray-700"> Add New Image</h2>

        <form wire:submit.prevent="addImage" class="space-y-4">
            <div>
                <input type="text" wire:model.defer="titleInput" placeholder="Image Title"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-primary-500 focus:border-primary-500" required>
                @error('titleInput') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <textarea wire:model.defer="descriptionInput" placeholder="Short Description"
                          class="w-full px-3 py-2 border rounded-lg focus:ring-primary-500 focus:border-primary-500" required></textarea>
                @error('descriptionInput') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <input type="file" wire:model="file" accept="image/*"
                       class="block w-full text-sm text-gray-600" />
                @error('file') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror

                <!-- Live preview -->
                @if ($file)
                    <div class="mt-3">
                        <p class="text-sm text-gray-500 mb-1">Preview:</p>
                        <img src="{{ $file->temporaryUrl() }}" class="w-48 h-48 object-cover rounded-lg shadow-md">
                    </div>
                @endif
            </div>

            <button type="submit"
                    class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                Upload Image
            </button>
        </form>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <input wire:model.debounce.500ms="search"
               type="text"
               placeholder=" Search images..."
               class="px-4 py-2 border rounded-lg w-full focus:ring-primary-500 focus:border-primary-500" />
    </div>

    <!-- Images grid -->
    @if($this->images->count())
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($this->images as $img)
                <div x-data="{ enlarged: false }"
                     class="border rounded-xl bg-white shadow-sm overflow-hidden hover:shadow-md transition">

                    <!-- Image -->
                    <div class="relative">
                        @if($img->file_path)
                            <img
                                :class="enlarged ? 'scale-105' : 'scale-100'"
                                @click="enlarged = !enlarged"
                                src="{{ asset('storage/' . $img->file_path) }}"
                                alt="{{ $img->title }}"
                                class="w-full h-56 object-cover transition-transform duration-300 cursor-pointer"
                            />
                        @else
                            <div class="w-full h-56 flex items-center justify-center bg-gray-100">
                                <span class="text-gray-400">No image</span>
                            </div>
                        @endif
                    </div>

                    <!-- Card body -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-1">{{ $img->title }}</h3>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $img->description }}</p>

                        <div class="flex justify-between items-center text-sm">
                            <div class="flex gap-3 items-center">
                                <!-- Favorite (old version restored) -->
                                <button wire:click="toggleFavorite({{ $img->id }})" title="Toggle Favorite">
                                    @if($img->favorite)
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
                                <a href="{{ route('images.download', $img->id) }}"
                                   class="text-primary-600 hover:underline">Download</a>
                            </div>

                            <!-- Delete -->
                            <button wire:click="removeImage({{ $img->id }})"
                                    class="text-red-600 hover:underline">
                                Remove
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
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 7v10c0 2 2 4 4 4h10c2 0 4-2 4-4V7c0-2-2-4-4-4H7c-2 0-4 2-4 4z"/>
            </svg>
            <p class="mt-4">No images found. Start by uploading one</p>
        </div>
    @endif
</x-filament::page>
