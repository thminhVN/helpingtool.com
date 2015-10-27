<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener;

abstract class AbstractListener implements AuthorizeHandlerInterface
{
    /**
     * Current config
     * @var mixed
     */
    protected $config;
	
    /**
     * Authorize listener by module name
     * @var array()
     */
    protected $lazyLoading = array();
	
    /**
     * 
     * @param mixed $config
     */
    public function __construct($config = null)
    {
    	if($config) {
            $this->setConfig($config);
    	}
    }
    
    /**
     * Authorize a module loading
     * @return boolean 
     */
    public function authorizeModule()
    {
        return true;
    }
    
    /**
     * Get config
     * @return mixed
     */
    public function getConfig()
    {
    	return $this->config;
    }
    
    /**
     * Set config
     * @param mixed
     */
    public function setConfig($config)
    {   
    	$this->config = $config;
    	return $this;
    }
}