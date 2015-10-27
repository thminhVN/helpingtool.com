<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener\Server;

use ZFMLL\ModuleManager\Listener\AbstractListener;

class DateTime extends AbstractListener
{
    /**
     * Authorize a module loading
     * @return boolean 
     */
    public function authorizeModule()
    {
        $date = date('Y-m-d H:i:s');
        if($date >= $this->config['start'] && $date <= $this->config['stop']) {
            return true;
        }
        return false;
    }
}
