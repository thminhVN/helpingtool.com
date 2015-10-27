<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener;

use Zend\ModuleManager\Listener\ListenerOptions as BaseListener;

class ListenerOptions extends BaseListener
{
    /**
     * @var array
     */
    protected $lazyLoading = array();
    
    /**
     * Get array of module loadinf with condition
     *
     * @return bool
     */
    public function getLazyLoading()
    {
        return $this->lazyLoading;
    }

    /**
     * Set array of module loadinf with condition
     *
     * @param array $lazyLoading
     * @return ListenerOptions
     */
    public function setLazyLoading(array $lazyLoading)
    {
        $this->lazyLoading = $lazyLoading;
        return $this;
    }
}