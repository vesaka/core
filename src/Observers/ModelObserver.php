<?php
namespace Vesaka\Core\Observers;

use Vesaka\Core\Abstracts\BaseObserver;
use Vesaka\Core\Models\Model;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
/**
 * Description of ModelObserver
 *
 * @author Vesaka
 */
class ModelObserver extends BaseObserver {
    
    const GROUP = 'gallery';
    const WEBP_GROUP = 'webp_gallery';
    
    
    public function saved(Model $model) {
        if (request('id')) {
            $model->clearMediaCollection();
            $model->clearMediaGroup(self::GROUP);
            $model->clearMediaGroup(self::WEBP_GROUP);
        }

        $model->syncCategories(request('category', []));
        $this->storeMeta($model, request('meta', []));
        $model->addMedia(request()->file('file'))->toMediaCollection();
//        if ($fileId) {
//            $file = app(Filepond::class)->getPathFromServerId($fileId);
//
//            if (File::exists($file)) {
//                $filename = Str::slug($model->title) . '_' . time() . '.' . pathinfo($file, PATHINFO_EXTENSION);
//
//                $uploaded_file = new UploadedFile($file, $filename);
////                $media = MediaUploader::fromFile($uploaded_file)
////                        ->useFileName($filename)
////                        ->useName(Str::slug($model->title))
////                        ->upload();
////                //dd($media); 
////                $model->attachMedia($media, MEDIA_GROUP_GALLERY);
//
//            }
//        }
    }
    
    public function deleting(Model $model) {
        $model->meta()->delete();
    }
    
    public function deleted(Model $model) {
        $model->tags()->delete();
        $model->meta()->delete();
        $model->clearMediaGroup(self::GROUP);
        $model->clearMediaGroup(self::WEBP_GROUP);
    }
    
    private function storeTags($model) {
        try {
            $request_tags = request('tags', []);
        } catch (\JsonException) { 
            $request_tags = [];
        }
        $model->tags()->delete();
        $newTags = [];
        
        $class = get_class($model);

        foreach ($request_tags as $tag) {
            $newTags[] = [
                'model_id' => $model->id,
                'type' => $class,
                'key' => 'tag',
                'value' => $tag
            ];
        }
        
        $model->tags()->insert($newTags);
        //dump($request_tags, $newTags, $image->tags->toArray());
        
    }
    
    private function storeFile($model) {
        $request = request();
        $file = $request->file('file');
        
        if ($file instanceof UploadedFile) {
            $filename = Str::slug($model->title) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filename_webp = Str::slug($model->title) . '_' . time() . '.webp';
            $media = MediaUploader::fromFile($file)
                   ->useFileName($filename)
                    ->useName(Str::slug($model->title))
                    ->upload();
            $webp_media = MediaUploader::fromFile($file)
                   ->useFileName($filename_webp)
                    ->useName(Str::slug($model->title))
                    ->upload();
            
        } else {
            $media = $model->getLastMedia(self::GROUP);
            $webp_media = $model->getLastMedia(self::WEBP_GROUP);
        }
        
        if ($request->id) {
            $model->clearMediaGroup(self::GROUP);
            $model->clearMediaGroup(self::WEBP_GROUP);
        }

        $model->attachMedia($media, self::GROUP);    
        $model->attachMedia($webp_media, self::WEBP_GROUP);
        $model->meta()->where('key', 'crop')->updateOrCreate([
            'type' => get_class($model),
            'key' => 'crop',
            'value' => request('meta.crop')
        ]);
    }
    
    
}

