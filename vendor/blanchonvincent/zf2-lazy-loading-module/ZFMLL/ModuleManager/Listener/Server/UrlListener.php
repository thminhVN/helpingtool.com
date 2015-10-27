<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener\Server;

use ZFMLL\ModuleManager\Listener\AbstractListener;

class UrlListener extends AbstractListener
{
    /**
     * Authorize a module loading
     * @return boolean 
     */
    public function authorizeModule()
    {
    	if(isset($this->config['static'])) {
            return @$_SERVER['REQUEST_URI'] == $this->config['static'];
    	}
    	else if(isset($this->config['regex'])) {
            return preg_match('(^' . $this->config['regex'] . '$)', @$_SERVER['REQUEST_URI']);
    	}
        return false;
    }
}
