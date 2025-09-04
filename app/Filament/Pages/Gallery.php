<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;
use App\Models\Image;

class Gallery extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static string $view = 'gallery';

    public $images = [];
    public $favorites = [];
    public $search = '';
    public $page = 1;
    public $pagination = [];

    public function mount()
    {
        $this->fetchImages();
        $this->loadFavorites();
    }

    public function updatedSearch()
    {
        $this->page = 1;
        $this->fetchImages();
    }

    public function nextPage()
    {
        if ($this->pagination['current_page'] < $this->pagination['total_pages']) {
            $this->page++;
            $this->fetchImages();
        }
    }

    public function prevPage()
    {
        if ($this->page > 1) {
            $this->page--;
            $this->fetchImages();
        }
    }

    public function fetchImages()
    {
        $endpoint = "https://api.artic.edu/api/v1/artworks";

        $response = Http::get($endpoint, [
            'page' => $this->page,
            'limit' => 9,
            'fields' => 'id,title,image_id',
            'q' => $this->search,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            $this->images = $data['data'];
            $this->pagination = [
                'current_page' => $data['pagination']['current_page'],
                'total_pages' => $data['pagination']['total_pages'],
            ];
        }
    }

    public function loadFavorites()
    {
        $this->favorites = Image::where('favorite', true)->pluck('artic_id')->toArray();
    }

    public function toggleFavorite($articId)
    {
        $image = Image::firstOrCreate(['artic_id' => $articId]);
        $image->favorite = !$image->favorite;
        $image->save();

        $this->loadFavorites();
    }
}
