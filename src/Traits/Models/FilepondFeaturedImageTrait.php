<?php

namespace Vesaka\Core\Traits\Models;

use Illuminate\Http\Request;
use Sopamo\LaravelFilepond\Filepond;
use Storage;

/**
 * @author vesak
 */
trait FilepondFeaturedImageTrait {
    public function createFromFilepond(Request $request) {
        $filepond = app(Filepond::class);
        $disk = config('filepond.temporary_files_disk');

        $path = $filepond->getPathFromServerId($request->fileID);
        $fullpath = Storage::disk($disk)->path($path);
        $this->addMedia($fullpath)
            ->toMediaCollection(FEATURED_IMAGE);

        return $this;
    }

    public function updateFromFilepond(Request $request) {
        if ($request->fileID) {
            $this->clearMediaCollection(FEATURED_IMAGE);
            $this->createFromFilepond($request);
        }

        return $this;
    }
}
