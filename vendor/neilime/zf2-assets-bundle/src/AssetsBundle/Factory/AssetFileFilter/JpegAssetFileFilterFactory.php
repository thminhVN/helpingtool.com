<?php

namespace AssetsBundle\Factory\AssetFileFilter;

class JpegAssetFileFilterFactory implements \Zend\ServiceManager\FactoryInterface {

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
     * @return \AssetsBundle\AssetFile\AssetFileFilter\ImageAssetFileFilter\JpegImageAssetFileFilter
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator) {
        return new \AssetsBundle\AssetFile\AssetFileFilter\ImageAssetFileFilter\JpegImageAssetFileFilter();
    }

}
