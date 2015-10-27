<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener;

use Zend\ModuleManager\Listener\ConfigListener as BaseConfigListener;
use Zend\ModuleManager\ModuleEvent;

class ConfigListener extends BaseConfigListener
{
    /**
     * __construct
     *
     * @param ListenerOptions $options
     * @return void
     */
    public function __construct(ListenerOptions $options = null)
    {
        parent::__construct($options);
        if ($this->hasCachedConfig()) {
            $this->skipConfig = true;
            $this->setMergedConfig($this->getCachedConfig());
        }
    }
    
    /**
     * Pass self to the ModuleEvent object early so everyone has access. 
     * 
     * @param ModuleEvent $e 
     * @return ConfigListener
     */
    public function loadModulesPre(ModuleEvent $e)
    {
    	if($this->getOptions()->getConfigCacheEnabled()) {
            $this->getOptions()->setConfigCacheKey(implode('.',$e->getTarget()->getModules()).'.'.$this->getOptions()->getConfigCacheKey());
            if ($this->hasCachedConfig()) {
                $this->skipConfig = true;
                $this->setMergedConfig($this->getCachedConfig());
            }
    	}
        return parent::loadModulesPre($e);
    }
}