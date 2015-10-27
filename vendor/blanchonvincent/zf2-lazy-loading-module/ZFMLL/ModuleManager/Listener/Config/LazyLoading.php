<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener\Config;

class LazyLoading
{
    /**
     * Authorize Listener by module name
     * @var array
     */
    protected $listeners = array();
	
	/**
     * @param  array|Traversable|null $options
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($options = null)
    {
        if (null !== $options) {
            $this->setFromArray($options);
        }
    }
	
	/**
     * @param  array|Traversable $options
     * @return void
     */
    public function setFromArray($options)
    {
        if (!is_array($options) && !$options instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Parameter provided to %s must be an array or Traversable',
                __METHOD__
            ));
        }

        foreach ($options as $moduleName => $value) {
            $moduleName = strtolower($moduleName);
            if(!isset($this->listeners[$moduleName])) {
            	$this->listeners[$moduleName] = array();
            }
            $this->listeners[$moduleName] = array_merge($this->listeners[$moduleName], $value);
        }
    }
        
    /**
     * Get listener name by module name
     * @param string $moduleName
     */
    public function getListenersModule($moduleName)
    {
    	 $moduleName = strtolower($moduleName);
    	 if(!isset($this->listeners[$moduleName])) {
            return array();
    	 }
    	 
    	 return $this->listeners[$moduleName];
    }
}