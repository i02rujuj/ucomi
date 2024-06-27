<?php

use Cloudinary\Asset\Image;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Delivery;

if (!function_exists('subirImagenCloudinary')) {
    function subirImagenCloudinary($image, $folder)
    {
        $url_image=null;

        if ($image) {
            $publicId_image = cloudinary()->upload($image->getRealPath(), [
                'folder' => $folder
            ])->getPublicId();
            
            $url_image = (new Image($publicId_image))
            ->resize(Resize::scale()->width(250))
            ->delivery(Delivery::quality(35))
            ->delivery(Delivery::format(
            Format::auto())); 
        }
        else{
            $url_image = asset('img/default_image.png');
        } 
        
        return $url_image;
    }
}