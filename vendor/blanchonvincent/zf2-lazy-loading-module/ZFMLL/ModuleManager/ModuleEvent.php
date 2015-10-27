<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager;

use Zend\ModuleManager\ModuleEvent as BaseModuleEvent;

class ModuleEvent extends BaseModuleEvent
{
    CONST EVENT_LOAD_MODULE_AUTH = 'loadModuleAuth';
    CONST EVENT_LOAD_MODULES_AUTH = 'loadModulesAuth';
}
