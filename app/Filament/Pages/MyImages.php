<?php

namespace App\Filament\Pages;

use App\Models\Image;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MyImages extends Page
{
    use WithFileUploads;
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string $view = 'my-images';
    protected static ?string $title = 'My Images';
    protected static ?string $navigationLabel = 'My Images';

    // Livewire state
    public string $search = '';
    public $file = null;                // uploaded file (temporary Livewire file)
    public string $titleInput = '';
    public string $descriptionInput = '';
    public int $perPage = 6;

    protected $rules = [
        'titleInput' => 'required|string|max:255',
        'descriptionInput' => 'required|string',
        'file' => 'required|image|max:6144', // 6 MB
    ];

    // resets pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // computed property used in Blade as $this->images
    public function getImagesProperty()
    {
        return Image::query()
            ->whereNotNull('file_path') // show only uploaded images
            ->when($this->search, function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderByDesc('id')
            ->paginate($this->perPage);
    }

    public function addImage()
    {
        $this->validate();

        // store file in storage/app/public/uploads/images
        $path = $this->file->store('uploads/images', 'public');

        Image::create([
            'title' => $this->titleInput,
            'description' => $this->descriptionInput,
            'file_path' => $path,
            'favorite' => false,
        ]);

        // cleanup
        $this->reset(['file', 'titleInput', 'descriptionInput']);
        session()->flash('success', 'Image uploaded successfully!');
        $this->resetPage(); // go to first page so user sees newest upload
    }

    public function toggleFavorite(int $id)
    {
        $img = Image::findOrFail($id);
        $img->favorite = !$img->favorite;
        $img->save();
    }

    public function removeImage(int $id)
    {
        $img = Image::findOrFail($id);

        if ($img->file_path && Storage::disk('public')->exists($img->file_path)) {
            Storage::disk('public')->delete($img->file_path);
        }

        $img->delete();
    }
}
