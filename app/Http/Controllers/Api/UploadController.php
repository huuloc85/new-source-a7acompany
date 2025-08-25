<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HandleError;
use App\Http\Controllers\Api\Admin\BaseController;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;

class UploadController extends BaseController
{
    public function getImages(Request $request)
    {
        try {
            $images = QueryBuilder::for(Image::class)
                ->allowedFilters(['title', 'type', 'description'])
                ->paginate($request->input('limit', 10));

            return response()->json($images);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    public function getImage($id)
    {
        try {
            $image = Image::findOrFail($id);

            return response()->json($image);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    public function uploadImage(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'description' => 'nullable|string|max:255',
            ]);

            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $title = 'image_'.time().'.'.$extension;
            $path = $image->storeAs('uploads/images', $title, 'public');

            $upload = Image::create([
                'title' => $title,
                'path' => $path,
                'employee_id' => auth()->id(),
                'type' => $image->getClientMimeType(),
                'description' => $validated['description'] ?? null,
                'size' => $image->getSize(),
                'extension' => $extension,
            ]);

            DB::commit();

            return response()->json($upload, 201);
        } catch (\Throwable $th) {
            DB::rollBack();

            return HandleError::handle($th);
        }
    }

    public function deleteImage($id)
    {
        DB::beginTransaction();
        try {
            $image = Image::findOrFail($id);
            $image->delete();
            Storage::delete(['public/'.$image->path]);
            DB::commit();

            return response()->json(['message' => 'Image deleted successfully']);
        } catch (\Throwable $th) {
            DB::rollBack();

            return HandleError::handle($th);
        }
    }

    public function updateImage(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $image = Image::findOrFail($id);
            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:255',
                'is_show' => 'nullable|boolean',
            ]);

            $image->update($validated);
            DB::commit();

            return response()->json($image);
        } catch (\Throwable $th) {
            DB::rollBack();

            return HandleError::handle($th);
        }
    }
}
