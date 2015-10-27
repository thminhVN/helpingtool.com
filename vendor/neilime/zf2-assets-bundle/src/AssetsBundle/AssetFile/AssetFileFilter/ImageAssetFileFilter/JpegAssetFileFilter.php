<?php

namespace AssetsBundle\AssetFile\AssetFileFilter\ImageAssetFileFilter;

class JpegImageAssetFileFilter extends \AssetsBundle\AssetFile\AssetFileFilter\ImageAssetFileFilter\AbstractImageAssetFileFilter {

    /**
     * @var string
     */
    protected $assetFileFilterName = 'Jpeg';

    /**
     * Compression level: from 0 (worst quality, smaller file) to 100 (best quality, biggest file)
     * @var int
     */
    protected $imageQuality = 30;

    /**
     * @param int $iImageQuality
     * @throws \InvalidArgumentException
     * @return \AssetsBundle\Service\Filter\JpegFilter
     */
    public function setImageQuality($iImageQuality) {
        if (!is_numeric($iImageQuality) || $iImageQuality < 0 || $iImageQuality > 100) {
            throw new \InvalidArgumentException(sprintf(
                    '$iImageQuality expects int from 0 to 100 "%s" given', is_scalar($iImageQuality) ? $iImageQuality : (is_object($iImageQuality) ? get_class($iImageQuality) : gettype($iImageQuality))
            ));
        }
        $this->imageQuality = (int) $iImageQuality;
        return $this;
    }

    /**
     * @return int
     */
    public function getImageQuality() {
        return $this->imageQuality;
    }

    /**
     * @param ressource $oImage
     * @return string
     * @throws \InvalidArgumentException
     */
    public function optimizeImage($oImage) {
        if (is_resource($oImage)) {
            ob_start();
            imagejpeg($oImage, null, $this->getImageQuality());
            return ob_get_clean();
        }
        throw new \InvalidArgumentException('Image expects a ressource, "' . gettype($oImage) . '" given');
    }

}
