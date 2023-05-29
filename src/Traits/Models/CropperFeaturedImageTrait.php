<?php

namespace Vesaka\Core\Traits\Models;

use Illuminate\Http\Request;
use File;
/**
 *
 * @author vesak
 */
trait CropperFeaturedImageTrait {
    
   public function createFromCropper(Request $request) {
        $this->addMedia($request->file('file'))
                    ->withCustomProperties(['crop' => $request->crop])
                    ->toMediaCollection(FEATURED_IMAGE);
        return $this;
    }
    
    public function updateFromCropper(Request $request) {
        $crop = $request->crop ?? [];
        $media = $this->getFirstMedia(FEATURED_IMAGE);
        
        $cropChanged = false;
        if ($media) {
            $cropData = $media->getCustomProperty('crop');
            foreach ($crop as $key => $value) {
                if (isset($cropData[$key])) {
                    if (intval($cropData[$key]) === intval($value)) {
                        $cropChanged = true;
                    break;
                    }
                } else {
                    $cropChanged = true;
                    break;
                }
            }
        }
        
        
        $newFile = null;
        if ($request->hasFile('file')) {
            $newFile = $request->file('file');
            
        } else if ($cropChanged) {
            $oldFile = $media->getPath();
            $newFile = storage_path('media-library/temp/' . basename($oldFile));
            File::copy($oldFile, $newFile);
        }

        if ($newFile) {
            $this->clearMediaCollection(FEATURED_IMAGE);
            $this->addMedia($newFile)
                    ->withCustomProperties(['crop' => $crop])
                    ->toMediaCollection(FEATURED_IMAGE);
            if (file_exists($newFile)) {
                File::delete($newFile);
            }
        }
    }
}
