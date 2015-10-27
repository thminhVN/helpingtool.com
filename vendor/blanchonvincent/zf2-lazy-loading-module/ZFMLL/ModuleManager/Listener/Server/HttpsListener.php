<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener\Server;

use ZFMLL\ModuleManager\Listener\AbstractListener;

class HttpsListener extends AbstractListener
{
    /**
     * Authorize a module loading
     * @return boolean 
     */
    public function authorizeModule()
    {
        return @$_SERVER['HTTPS'] == $this->config;
    }
}
