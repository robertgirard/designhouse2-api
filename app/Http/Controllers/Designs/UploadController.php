<?php

namespace App\Http\Controllers\Designs;

use Image;
use File;
use App\Jobs\UploadImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    //

    public function upload(Request $request)
    {
     // validate the request
     $this->validate($request, [
        'image' => ['required', 'mimes:jpeg,gif,bmp,png', 'max:2048']
    ]);

    // get the image
    $image = $request->file('image');
    $image_path = $image->getPathName();

    // get the original file name and replace any spaces with _
    // Business Cards.png = timestamp()_business_cards.png
    $filename = time()."_". preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));

    // move the image to the temporary location (tmp)
    $tmp = $image->storeAs('uploads/original', $filename, 'tmp');

    // create the database record for the design
    $design = auth()->user()->designs()->create([
        'image' => $filename,
        'disk'  => config('site.upload_disk')
    ]);

    // dispatch a job to handle the image manipulation
    // $this->dispatch(new UploadImage($design));

    $disk = $design->disk;
    $filename = $design->image;
    $original_file = storage_path() . '/uploads/original/'. $filename;

    try{
        // create the Large Image and save to tmp disk
        Image::make($original_file)
            ->fit(800, 600, function($constraint){
                $constraint->aspectRatio();
            })
            ->save($large = storage_path('uploads/large/'. $filename));

        // Create the thumbnail image
        Image::make($original_file)
            ->fit(250, 200, function($constraint){
                $constraint->aspectRatio();
            })
            ->save($thumbnail = storage_path('uploads/thumbnail/'. $filename));

        // store images to permanent disk
        // original image
        if(Storage::disk($disk)
            ->put('uploads/designs/original/'.$filename, fopen($original_file, 'r+'))){
                File::delete($original_file);
            }

        // large images
        if(Storage::disk($disk)
            ->put('uploads/designs/large/'.$filename, fopen($large, 'r+'))){
                File::delete($large);
            }

        // thumbnail images
        if(Storage::disk($disk)
            ->put('uploads/designs/thumbnail/'.$filename, fopen($thumbnail, 'r+'))){
                File::delete($thumbnail);
            }

        // Update the database record with success flag
        $this->design->update([
            'upload_successful' => true
        ]);

    } catch(\Exception $e){
        \Log::error($e->getMessage());
    }

    return response()->json($design, 200);

    }
}
