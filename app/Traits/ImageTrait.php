<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Image;

trait ImageTrait
{

    private $public_path = 'app/public/uploads/';

    /**
     * @param String $image
     * @param Model $model
     * @param String $tagName
     * @param boolean $isOld
     * @return void
     */
    public function saveImage($image, $model, $tagName, $isOld = false)
    {
        try {
            if ($isOld) {
                $imageExist = storage_path($this->public_path . $model->src_foto);
                if ($model->src_foto && file_exists($imageExist)) {
                    unlink($imageExist);
                    unlink(storage_path($this->public_path . 'thumbnail/' . $model->src_foto));
                    unlink(storage_path($this->public_path . 'thumbnail-small/' . $model->src_foto));
                }
            }
            $imageName = $tagName . '_' . $model->id . date('ymdHis').'.jpg';
            $path = storage_path($this->public_path . $imageName);
            Image::make(file_get_contents($image))->save($path); 

            $img = Image::make(storage_path($this->public_path . $imageName))->resize(200, 275);
            $img->save(storage_path($this->public_path . 'thumbnail/' . $imageName));
            $imgSm = Image::make(storage_path($this->public_path . $imageName))->resize(50, 50);
            $imgSm->save(storage_path($this->public_path . 'thumbnail-small/' . $imageName));
            $model->src_foto = $imageName;
            $model->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
