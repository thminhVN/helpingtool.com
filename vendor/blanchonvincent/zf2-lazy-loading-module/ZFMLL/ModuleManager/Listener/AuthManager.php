<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener;

use ZFMLL\ModuleManager\ModuleEvent;

class AuthManager
{
    /**
     * Listener broker
     * @var ListenerManager
     */
    protected $listenerManager;
	
	/**
     * Lazy loading config
     * @var Config\LazyLoading
     */
    protected $lazyLoading;

    /**
     *
     * @param array $lazyLoading 
     */
    public function __construct($lazyLoading = null)
    {
        if($lazyLoading) {
            $this->setLazyLoading($lazyLoading);
        }
    }
	
    /**
     * Authorize loading module listener
     * @param ModuleEvent $e
     * @return boolean 
     */
    public function authorize(ModuleEvent $e)
    {   
        $moduleName = $e->getModuleName();
        $moduleName = strtolower($moduleName);
        $listeners = $this->getLazyLoading()->getListenersModule($moduleName);
        foreach($listeners as $listener => $value) {
            $listenerObject = $this->load($listener);
            $listenerObject->setConfig($value);
            if(!$listenerObject->authorizeModule($moduleName)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * 
     * @param string $listenerName
     */
    public function load($listenerName, array $options = null)
    {
    	return $this->getListenerManager()->get($listenerName, $options);
    }
    
	/**
     * Set plugin broker instance
     * 
     * @param  string|ListenerManager $broker
     * @return 
     * @throws Exception\InvalidArgumentException
     */
    public function setListenerManager($manager)
    {
    	if (is_string($manager)) {
            if (!class_exists($manager)) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Invalid helper broker class provided (%s)',
                    $manager
                ));
            }
            $manager = new $manager();
        }
        if (!$manager instanceof ListenerManager) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Listener broker must extend ZFMLL\ModuleManager\Listener\ListenerManager; got type "%s" instead',
                (is_object($manager) ? get_class($manager) : gettype($manager))
            ));
        }
        $this->listenerManager = $manager;
    }

    /**
     * Get listeners broker instance
     * 
     * @return ListenerManager
     */
    public function getListenerManager()
    {
        if (null === $this->listenerManager) {
            $this->setListenerManager(new ListenerManager());
        }
        return $this->listenerManager;
    }
    
    /**
     * Get lazy loading config
     * @return Config\LazyLoading 
     */
    public function getLazyLoading()
    {
        if(null === $this->lazyLoading) {
            $this->setLazyLoading(array());
        }
    	return $this->lazyLoading;
    }
    
    /**
     * Set lazy loading config
     * @param LazyLoading $lazyLoading 
     */
    public function setLazyLoading($lazyLoading)
    {
    	if(!$lazyLoading instanceof Config\LazyLoading) {
            $this->lazyLoading = new Config\LazyLoading($lazyLoading);
    	}
    	else {
            $this->lazyLoading = $lazyLoading;
    	}
    }
}