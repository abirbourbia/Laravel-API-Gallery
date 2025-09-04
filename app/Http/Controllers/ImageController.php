<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function download($id)
{
    $image = Image::where('id', $id)->orWhere('artic_id', $id)->first();

    // Case 1: User-uploaded
    if ($image && $image->file_path && Storage::disk('public')->exists($image->file_path)) {
        return Storage::disk('public')->download(
            $image->file_path,
            $image->title . '.' . pathinfo($image->file_path, PATHINFO_EXTENSION)
        );
    }

    // Case 2: API-fetched image (IIIF)
    if ($image && $image->artic_id && $image->image_id) {
        $url = "https://www.artic.edu/iiif/2/{$image->image_id}/full/843,/0/default.jpg";

        $contents = file_get_contents($url);
        $extension = 'jpg';

        return response($contents)
            ->header('Content-Type', 'image/jpeg')
            ->header('Content-Disposition', 'attachment; filename="' . $image->title . '.' . $extension . '"');
    }

    abort(404);
}

}
