<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener\Server;

use ZFMLL\ModuleManager\Listener\AbstractListener;

class UserAgent extends AbstractListener
{
    /**
     * Authorize a module loading
     * @return boolean 
     */
    public function authorizeModule()
    {
    	if(isset($this->config['static'])) {
            return @$_SERVER['HTTP_USER_AGENT'] == $this->config['static'];
    	}
    	else if(isset($this->config['regex'])) {
            return preg_match('(^' . $this->config['regex'] . '$)', @$_SERVER['HTTP_USER_AGENT']);
    	}
        else if(isset($this->config['is_robot'])) {
            // must to improve robot detection
            return (boolean)$this->config['is_robot'] === (boolean)preg_match('#(bot|spider|crawler|yahoo)#', @$_SERVER['HTTP_USER_AGENT']);
    	}
        return false;
    }
}
