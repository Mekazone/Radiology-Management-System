<?php

/**
 * @author 
 * @copyright 2013
 */

class ImageCompress {
var $image;

function getWidth() {
 		$width = @imagesx($this->image);
      return $width;
   }
   function getHeight() {
 		$height = @imagesy($this->image);
      return $height;
   }
   function resizeToHeight() {
 
      $height = $this->getHeight() / 2;
      $width = $this->getWidth() / 2;
      $this->resize($width,$height);
   }
 
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
 
   function resize($width,$height) {
      $new_image = @imagecreatetruecolor($width, $height);
      @imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());

   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         @imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         @imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         @imagepng($this->image,$filename);
      }
      if( $permissions != null) {
 
         @chmod($filename,$permissions);
      }
   }   
 }   
 
?>