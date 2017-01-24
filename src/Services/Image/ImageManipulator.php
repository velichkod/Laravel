<?php
namespace Optimait\Laravel\Services\Image;

use \Image;


class ImageManipulator {


    public function resize($filename, $uploadFolder){

        $sizes = array_values(config('resize.sizes'));

        foreach($sizes as $size){
            $img = Image::make($uploadFolder.$filename);
            if(isset($size[2])){
                if($size[2] == 'width'){
                    $img->resize(null, $size[1], function ($constraint) {
                        $constraint->aspectRatio();
                        //$constraint->upsize();
                    });
                }
                else{
                    $img->resize($size[0], null, function ($constraint) {
                        $constraint->aspectRatio();
                        //$constraint->upsize();
                    });
                }
            }
            else{
                $img->resize($size[0], $size[1]);
            }

            $img->save($uploadFolder.$size[0].'X'.$size[1].$filename);
        }

    }
} 