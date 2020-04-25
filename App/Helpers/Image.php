<?php

/**
 * Image helper class.
 *
 * @package    YFW
 * @category   Helpers
 * @author     borodatych@demka.org
 * @copyright  (c) 2017 Yulsun Team
 * @license    http://yulsun.ru/license
 */

namespace App\Helpers;

use Core\Core;

class Image extends Core
{
    public function load($filename)
    {
        $imgInfo = getimagesize($filename);

        $type = $imgInfo[2];
        if( $type == IMAGETYPE_JPEG )
        {
            $this->image = ImageCreateFromJPEG($filename);
        }
        elseif( $type == IMAGETYPE_GIF )
        {
            $this->image = ImageCreateFromGIF($filename);
        }
        elseif( $type == IMAGETYPE_PNG )
        {
            $this->image = ImageCreateFromPNG($filename);
        }
        else $this->image = ImageCreateFromGIF($filename);

        return $this->image;
    }
    public function save($filename, $imgType=IMAGETYPE_JPEG, $compression=100, $permissions=0644)
    {
        if( $imgType == IMAGETYPE_JPEG ){
            imagejpeg($this->image,$filename,$compression);
        }
        elseif( $imgType == IMAGETYPE_GIF ){
            imagegif($this->image,$filename);
        }
        elseif( $imgType == IMAGETYPE_PNG ){
            imagepng($this->image,$filename);
        }
        if( $permissions != null) {
            chmod($filename,$permissions);
        }
    }
    public function output($imgType=IMAGETYPE_JPEG)
    {
        if( $imgType == IMAGETYPE_JPEG ){
            imagejpeg($this->image);
        }
        elseif( $imgType == IMAGETYPE_GIF ){
            imagegif($this->image);
        }
        elseif( $imgType == IMAGETYPE_PNG ){
            imagepng($this->image);
        }
    }
    public function getWidth()
    {
        return imagesx($this->image);
    }
    public function getHeight()
    {
        return imagesy($this->image);
    }
    public function resizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width,$height);
    }
    public function resizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width,$height);
    }
    public function scale($scale)
    {
        $width = $this->getWidth() * $scale/100;
        $height = $this->getheight() * $scale/100;
        $this->resize($width,$height);
    }
    public function resize($width,$height)
    {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
        return $this;
    }
    public function zoom($zoom)
    {
        $width  = $this->getWidth();
        $height = $this->getHeight();
        $zoomW  = ceil($width*$zoom);
        $zoomH  = ceil($height*$zoom);
        $newImg = imagecreatetruecolor($zoomW, $zoomH);
        ///imagecopyresized($new_image, $this->image, 0, 0, 0, 0, $zoomW, $zoomH, $width, $height );
        imagecopyresampled($newImg, $this->image, 0, 0, 0, 0, $zoomW, $zoomH, $width, $height );
        $this->image = $newImg;
        return $this;
        /**
         * А вот и он - финт ушами для очистки белого фона от шумов и артефактов
         * Действует в лоб - пробегает картинку и заменяет на ней почти белые цвета на белый
         * Добавляется в код ресайзинга после imagecopyresampled
         */
        /// Это - цвет на который будем заменять (белый)
        $colorWhite = imagecolorallocate($newImg, 255, 255, 255);
        /// Пробегаем все пиксели на изображении по вертикали и горизонтали
        for( $y=0; $y<($zoomH); ++$y ){
            for( $x=0; $x<($zoomW); ++$x ){
                $colorat = imagecolorat($newImg, $x, $y);
                $r = ($colorat >> 16) & 0xFF;
                $g = ($colorat >> 8) & 0xFF;
                $b = $colorat & 0xFF;

                // Если цвет пикселя нас не устраивает, заменяем его на белый
                if(($r == 253 && $g == 253 && $b == 253) || ($r == 254 && $g == 254 && $b ==254)) {
                    imagesetpixel($newImg, $x, $y, $colorWhite);
                }
            }
        }


    }
    public function watermark($font,$txt)
    {
        ### Размер картинки
        $px      = imagesx($this->image);
        $py      = imagesy($this->image);

        ### Цвет текста
        $black   = ImageColorAllocate($this->image, 0, 0, 0);

        ### Вычисляем максимально допустимы по длине шрифт
        $rotate = rand(-45,45);
        $box    = ImageTTFBBox(100, $rotate, $font, $txt);
        if( $rotate==0 )
        {
            $fontsize = intval( (100*($px-100)) / ($box[2]-$box[0]) );
        }
        elseif( $rotate>0 )
        {
            $dx  = abs($box[2]-$box[4]);
            $dy  = abs($box[3]-$box[5]);
            $pxt = $box[2]-$box[6];
            $pyt = $box[1]-$box[5];
            $kx  = $pxt/$px;
            $ky  = $pyt/$py;
            if($ky>$kx){
                $kh = abs(($py-100/2)/($pyt)); //$ds = 'Не вмещается больше по Y';
            }else{
                $kh = abs(($px-100/2)/($pxt)); //$ds = 'Не вмещается больше по X';
            }
            $fontsize = intval( sqrt( pow($dx*$kh,2) + pow($dy*$kh,2) ) );
        }
        else
        {
            $dx  = abs($box[4]-$box[2]);
            $dy  = abs($box[3]-$box[5]);
            $pxt = $box[4]-$box[0];
            $pyt = $box[3]-$box[7];
            $kx  = $pxt/$px;
            $ky  = $pyt/$py;
            if($ky>$kx)
            {
                $kh = abs(($py-100/2)/($pyt)); /// $ds = 'Не вмещается больше по Y';
            }
            else
            {
                $kh = abs(($px-100/2)/($pxt)); /// $ds = 'Не вмещается больше по X';
            }
            $fontsize = intval( sqrt( pow($dx*$kh,2) + pow($dy*$kh,2) ) );
        }
        $txtbox   = ImageTTFBBox($fontsize, $rotate, $font, $txt);

        ### от 0 до 45 градусов, хотя подходит я для все. Не совсем по центру лишь
        $boxw     = $txtbox[2]-$txtbox[6]; /// Ширина текстовой коробки
        $boxh     = $txtbox[7]-$txtbox[5]; /// Высота текстовой коробки

        ###
        $left     = intval( ($px-$boxw) /2 );
        $top      = intval( ($py-$boxh) /2 + $boxh );//Строится с нижнего левого угла
        imagettftext($this->image,$fontsize,$rotate,$left,$top,$black,$font,$txt);

        $percent  = 1;
        $width    = round( $px * $percent ); /// Это если нужно масштабирование
        $height   = round( $py * $percent ); /// Это если нужно масштабирование
        $out      = imagecreatetruecolor($width, $height);
        imagecopyresampled($out, $this->image, 0, 0, 0, 0, $width, $height, $px, $py);
        ImageAlphaBlending($out, true);
        ImageDestroy($this->image);
        $this->image = $out;
    }
}
