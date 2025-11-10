<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait WithMediaCollection
{
    public function saveFile(Model $model, $file, $collection = 'images', $deleteOlderMedia = true)
    {
        if ($deleteOlderMedia) {
            $model->clearMediaCollection($collection);
        }

        $model->addMedia($file)->toMediaCollection($collection);
    }

    public function deleteFile(Model $model, $collection = 'images')
    {
        $model->clearMediaCollection($collection);
    }
}
