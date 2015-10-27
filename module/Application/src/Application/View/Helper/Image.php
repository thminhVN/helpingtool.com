<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Return thumbnail url form original image
 * 
 * @version 1.0
 * @author Minh Tran <minh@starseed.fr>
 */
class Image extends AbstractHelper
{

    /**
     * return thumbnail url with width and height
     * 
     * @param string $url
     * @param number $width
     * @param number $height
     * 
     * @return string
     */
    public function thumb($url, $width = 0, $height = 0)
    {
        return $this->_getUrl($url, $width, $height);
    }

    public function getAvatar($url, $width = 0, $height = 0)
    {
        $targetUrl = $this->_getUrl($url, $width, $height);
        return "$targetUrl&time=" . _TIME_NOW_;
    }

    private function _getUrl($url, $width = 0, $height = 0)
    {
        $imageThumbUrl = $this->getView()->url('application/default', array(
            'controller' => 'image'
        ), array(
            'query' => array(
                'p' => urlencode($url),
                'w' => $width,
                'h' => $height
            )
        ));
        $url = $this->getView()->serverurl($imageThumbUrl);
        return $url;
    }
}
