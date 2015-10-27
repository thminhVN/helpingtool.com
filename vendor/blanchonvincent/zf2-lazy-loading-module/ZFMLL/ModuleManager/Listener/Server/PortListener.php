<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener\Server;

use ZFMLL\ModuleManager\Listener\AbstractListener;

class PortListener extends AbstractListener
{
    /**
     * Set config
     * @param mixed
     */
    public function setConfig($config)
    {   
    	if(!is_array($config)) {
            $config = array($config);
        }
    	return parent::setConfig($config);
    }
    
    /**
     * Authorize a module loading
     * @return boolean 
     */
    public function authorizeModule()
    {
        return in_array(@$_SERVER['SERVER_PORT'], $this->config);
    }
}
