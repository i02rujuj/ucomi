<?php

namespace App\Helpers; // Your helpers namespace 

use Cloudinary\Asset\Image;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Delivery;

class Helper{
  
    static function subirImagenCloudinary($image, $folder)
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

    static function subirPDFCloudinary($pdf, $folder)
    {
        $url_pdf=null;

        if ($pdf) {
            $publicId_pdf = cloudinary()->upload($pdf->getRealPath(), [
                'folder' => $folder
            ])->getPublicId();
            
            $url_pdf = cloudinary()->getUrl($publicId_pdf); 
        }
        
        return $url_pdf;
    }
}