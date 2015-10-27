<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager;

use Zend\ModuleManager\ModuleManager as BaseModuleManager;

class ModuleManager extends BaseModuleManager
{
    /**
     * Load the provided modules.
     *
     * @triggers loadModules.pre
     * @triggers loadModules.post
     * @return Manager
     */
    public function loadModules()
    {
        $this->getEventManager()->trigger(ModuleEvent::EVENT_LOAD_MODULES_AUTH, $this, $this->getEvent());
        return parent::loadModules();
    }

    /**
     * Listener auth modules
     */
    public function onLoadModulesAuth()
    {
        if (true === $this->modulesAreLoaded) {
            return $this;
        }

        $modules = array();
    	foreach ($this->getModules() as $moduleName) {
            $auth = $this->loadModuleAuth($moduleName);
            if($auth) {
                $modules[] = $moduleName;
            }
        }
        $this->setModules($modules);
    }
	
    /**
     * Get auth to load a specific module by name.
     *
     * @param string $moduleName
     * @triggers loadModule.resolve
     * @triggers loadModule
     * @return mixed Module's Module class
     */
    public function loadModuleAuth($moduleName)
    {
        $event = $this->getEvent();
        $event->setModuleName($moduleName);
        
        $result = $this->getEventManager()->trigger(ModuleEvent::EVENT_LOAD_MODULE_AUTH, $this, $event, function($r) {
            return !$r;
        });
        
        if(!$result->last()) {
            return false;
        }
        
        return true;
    }

    /**
     * Register the default event listeners
     *
     * @return ModuleManager
     */
    protected function attachDefaultListeners()
    {
        $events = $this->getEventManager();
        $events->attach(ModuleEvent::EVENT_LOAD_MODULES_AUTH, array($this, 'onLoadModulesAuth'));
        parent::attachDefaultListeners();
    }

    /**
     * Get the module event
     *
     * @return ModuleEvent
     */
    public function getEvent()
    {
        if (!$this->event instanceof ModuleEvent) {
            $this->setEvent(new ModuleEvent);
        }
        return $this->event;
    }
}