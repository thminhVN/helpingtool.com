<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener;

interface AuthorizeHandlerInterface
{
    /**
     * Authorize a module loading
     * @return boolean 
     */
    public function authorizeModule();
}