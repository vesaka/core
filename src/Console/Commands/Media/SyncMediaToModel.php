<?php

namespace Vesaka\Core\Console\Commands\Media;

use Illuminate\Console\Command;
use Storage;

/**
 * Description of SyncMediaToModel
 *
 * @author vesak
 */
class SyncMediaToModel extends Command {
    protected $signature = 'media:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test media sync to model';

    public function handle() {
        $model = app('model')->first();

        $img = Storage::disk('public')->path('misc/img-1.jpg');

        $model->clearMediaCollection();
        $model->addMedia($img)->toMediaCollection();
    }
}
