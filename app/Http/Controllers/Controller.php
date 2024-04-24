<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function image_type_to_extension($imagetype) {
        if (empty($imagetype))
            return false;
        switch ($imagetype) {
            case IMAGETYPE_GIF : return 'gif';
            case IMAGETYPE_JPEG : return 'jpg';
            case IMAGETYPE_PNG : return 'png';
            case IMAGETYPE_SWF : return 'swf';
            case IMAGETYPE_PSD : return 'psd';
            case IMAGETYPE_BMP : return 'bmp';
            case IMAGETYPE_TIFF_II : return 'tiff';
            case IMAGETYPE_TIFF_MM : return 'tiff';
            case IMAGETYPE_JPC : return 'jpc';
            case IMAGETYPE_JP2 : return 'jp2';
            case IMAGETYPE_JPX : return 'jpf';
            case IMAGETYPE_JB2 : return 'jb2';
            case IMAGETYPE_SWC : return 'swc';
            case IMAGETYPE_IFF : return 'aiff';
            case IMAGETYPE_WBMP : return 'wbmp';
            case IMAGETYPE_XBM : return 'xbm';
            default : return false;
        }
    }
    
    public function resizeImage($uploadedFileName, $imgFolder, $thumbfolder, $newWidth = false, $newHeight = false, $quality = 75, $bgcolor = false) {
        $img = $imgFolder . $uploadedFileName; 
        $newName = $uploadedFileName;
        $dest = $thumbfolder . $newName;
        list($oldWidth, $oldHeight, $type) = getimagesize($img);
        $ext = $this->image_type_to_extension($type);
        if ($newWidth OR $newHeight) {
            $widthScale = 2;
            $heightScale = 2;

            if ($newWidth) {
                $widthScale = $newWidth / $oldWidth;
            }
            if ($newHeight) {
                $heightScale = $newHeight / $oldHeight;
            }
            if ($widthScale < $heightScale) {
                $maxWidth = $newWidth;
                $maxHeight = false;
            } elseif ($widthScale > $heightScale) {
                $maxHeight = $newHeight;
                $maxWidth = false;
            } else {
                $maxHeight = $newHeight;
                $maxWidth = $newWidth;
            }

            if ($maxWidth > $maxHeight) {
                $applyWidth = $maxWidth;
                $applyHeight = ($oldHeight * $applyWidth) / $oldWidth;
            } elseif ($maxHeight > $maxWidth) {
                $applyHeight = $maxHeight;
                $applyWidth = ($applyHeight * $oldWidth) / $oldHeight;
            } else {
                $applyWidth = $maxWidth;
                $applyHeight = $maxHeight;
            }

            $startX = 0;
            $startY = 0;

            switch ($ext) {
                case 'gif' :
                    $oldImage = imagecreatefromgif($img);
                    break;
                case 'png' :
                    $oldImage = imagecreatefrompng($img);
                    break;
                case 'jpg' :
                    $oldImage = imagecreatefromjpeg($img);
                    break;
                case 'jpeg' :
                    $oldImage = imagecreatefromjpeg($img);
                    break;
                default :
                    return false;
                    break;
            }
            //create new image
            $newImage = imagecreatetruecolor($applyWidth, $applyHeight);
            imagecopyresampled($newImage, $oldImage, 0, 0, $startX, $startY, $applyWidth, $applyHeight, $oldWidth, $oldHeight);
            switch ($ext) {
                case 'gif' :
                    imagegif($newImage, $dest, $quality);
                    break;
                case 'png' :
                    imagepng($newImage, $dest, 8);
                    break;
                case 'jpg' :
                    imagejpeg($newImage, $dest, $quality);
                    break;
                case 'jpeg' :
                    imagejpeg($newImage, $dest, $quality);
                    break;
                default :
                    return false;
                    break;
            }
            imagedestroy($newImage);
            imagedestroy($oldImage);
            if (!$newName) {
                unlink($img);
                rename($dest, $img);
            }
            return true;
        }
    }

}
