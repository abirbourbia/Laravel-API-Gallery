<?php

namespace App\Filament\Pages;

use App\Models\Image;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;
use Livewire\WithPagination;

class Favorites extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static string $view = 'favorites';
    protected static ?string $title = 'Favorites';
    protected static ?string $navigationLabel = 'Favorites';

    public string $search = '';
    public int $perPage = 6;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     * All favorited images (local DB + API-fetched).
     */
    public function getImagesProperty()
    {
        // Local DB favorites
        $local = Image::query()
            ->where('favorite', true)
            ->when($this->search, function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->get()
            ->map(function ($img) {
                return [
                    'id' => $img->id,
                    'title' => $img->title,
                    'file_path' => asset('storage/' . $img->file_path),
                    'from_api' => false,
                ];
            });

        // API favorites stored in DB
        $api = Image::query()
            ->whereNull('file_path') // API images don’t have file_path
            ->where('favorite', true)
            ->when($this->search, function ($q) {
                $q->where('title', 'like', "%{$this->search}%");
            })
            ->get()
            ->map(function ($img) {
                $imageUrl = $img->artic_id
                    ? "https://www.artic.edu/iiif/2/{$img->image_id}/full/843,/0/default.jpg"
                    : null;

                return [
                    'id' => $img->id,
                    'title' => $img->title,
                    'file_path' => $imageUrl,
                    'from_api' => true,
                ];
            });

        // Merge both collections
        $all = $local->merge($api);

        // Paginate manually
        $page = $this->page ?? 1;
        $perPage = $this->perPage;
        $items = $all->forPage($page, $perPage);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function toggleFavorite(int $id)
    {
        $img = Image::findOrFail($id);
        $img->favorite = !$img->favorite;
        $img->save();
    }
}
