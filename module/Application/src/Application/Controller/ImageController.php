<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
/**
 * Return image reponse with crop or scale option with original image file
 *
 * @version 1.0
 * @author Minh Tran <minh@starseed.fr>
 */
class ImageController extends AbstractActionController{

    public function indexAction(){
        $targetUrl = $this->params()->fromQuery('p', '');
        $targetUrl = urldecode($targetUrl);
        $thumbWidth = $this->params()->fromQuery('w', 0);
        $thumbHeight = $this->params()->fromQuery('h', 0);
        return $this->_getThumbImage($targetUrl, $thumbWidth, $thumbHeight);
    }
    
    private function _getThumbImage($targetUrl, $thumbWidth = 0, $thumbHeight = 0)
    {
        $thumbPath = $this->_createThumbImage($targetUrl, $thumbWidth, $thumbHeight);
        $file_parts = pathinfo($thumbPath);
        $seconds_to_cache = 2592000;
        $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
        $response = $this->getResponse();
        $response->getHeaders()
            ->addHeaderLine('Content-Transfer-Encoding', 'binary')
            ->addHeaderLine('Cache-Control', "max-age=$seconds_to_cache, public") //30days (60sec * 60min * 24hours * 30days)
            ->addHeaderLine('Content-Type', 'image/' . $file_parts['extension'])
            ->addHeaderLine('Connection', 'Keep-Alive')
            ->addHeaderLine('Expires', $ts)
            ->addHeaderLine('Pragma', 'cache')
            ->addHeaderLine('Keep-Alive', 'timeout=15, max=99')
        ;
        $image_content = @file_get_contents($thumbPath);
        $response->getHeaders()->addHeaderLine('Content-Length', mb_strlen($image_content, '8bit'));
        $response->setStatusCode(200);
        $response->setContent($image_content);
        return $response;
    }
    
    private function _getThumbPath($targetPath, $thumbWidth = 0, $thumbHeight = 0)
    {
        $thumbPath = '';
        if($thumbWidth == 0 && $thumbHeight == 0) {
            return $targetPath;
        }
        $extension = pathinfo($targetPath, PATHINFO_EXTENSION);
        $thumbName = pathinfo($targetPath, PATHINFO_FILENAME)."s=$thumbWidth" . "x$thumbHeight.$extension";
        $thumbDir = pathinfo($targetPath, PATHINFO_DIRNAME) . '/thumb';
        if(!is_dir($thumbDir)){
            mkdir($thumbDir);
        }
        $thumbPath = "$thumbDir/$thumbName";
        return $thumbPath;
    }
    
    private function _createThumbImage($targetUrl, $thumbWidth = 0, $thumbHeight = 0)
    {
        try {
            $targetPath = getcwd().'/public'.$targetUrl;
            if(!is_file($targetPath)) {
                $targetPath = getcwd().'/public/img/default/noimage.jpg';
            }
            $thumbPath = $this->_getThumbPath($targetPath, $thumbWidth, $thumbHeight);
            if(!is_file($thumbPath)) {
                $extension = pathinfo($targetPath, PATHINFO_EXTENSION);
                if($extension == "jpg" || $extension == "jpeg")
                {
                    $image = imagecreatefromjpeg($targetPath);
                }
                else if($extension == "png")
                {
                    $image = imagecreatefrompng($targetPath);
                }
                $width = imagesx($image);
                $height = imagesy($image);
                
                $originalAspect = $width / $height;
        
                $thumbHeight = $thumbHeight > 0 ? $thumbHeight : (int) $thumbWidth / $originalAspect;
                $thumbWidth = $thumbWidth > 0 ? $thumbWidth : (int) $thumbHeight * $originalAspect;
        
                $thumbAspect = $thumbWidth / $thumbHeight;
                
                if ($originalAspect >= $thumbAspect) {
                
                    // If image is wider than thumbnail (in aspect ratio sense)
                    $newHeight = $thumbHeight;
                    $newWidth = $width / ($height / $thumbHeight);
                
                } else {
                    // If the thumbnail is wider than the image
                    $newWidth = $thumbWidth;
                    $newHeight = $height / ($width / $thumbWidth);
                }
                
                $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
                if($extension == "png") {
                    @imagecolortransparent($thumb, @imagecolorallocate($thumb, 0, 0, 0));
                    @imagealphablending($thumb, false);
                    @imagesavealpha($thumb, true);
                }
                // Resize and crop
                imagecopyresampled(
                $thumb,
                $image,
                0 - ($newWidth - $thumbWidth) / 2, // Center the image horizontally
                0 - ($newHeight - $thumbHeight) / 2, // Center the image vertically
                0, 0,
                $newWidth, $newHeight,
                $width, $height);
                if($extension == "jpg" || $extension == "jpeg")
                {
                    imagejpeg($thumb, $thumbPath, 100);
                }
                else if($extension == "png")
                {
                    imagepng($thumb, $thumbPath, 9);
                }
                @imagedestroy($thumb);
                @imagedestroy($image);
            }
            return $thumbPath;
        } catch (\Exception $e) {
            echo $e->getMessage();die;
        }
    }
}