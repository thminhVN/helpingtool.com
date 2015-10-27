<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener;

use ZFMLL\ModuleManager\ModuleEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\Listener\DefaultListenerAggregate;
use Zend\EventManager\EventCollection;

class AuthListenerAggregate extends DefaultListenerAggregate
{
    /**
     * Attach one or more listeners
     *
     * @param EventCollection $events
     * @return DefaultListenerAggregate
     */
    public function attach(EventManagerInterface $events)
    {	
        $options = $this->getOptions();
        $lazyLoading = $options->getLazyLoading();
        
        $listenerManager = new AuthManager($lazyLoading);
        $this->listeners[] = $events->attach(ModuleEvent::EVENT_LOAD_MODULE_AUTH, array($listenerManager, 'authorize'));
        
        return parent::attach($events);
    }
}