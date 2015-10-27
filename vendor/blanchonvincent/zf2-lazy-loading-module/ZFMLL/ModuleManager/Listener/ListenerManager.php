<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener;

use Zend\ServiceManager\AbstractPluginManager;
use ZFMLL\ModuleManager\Listener\Exception\InvalidListenerException;

class ListenerManager extends AbstractPluginManager
{
    protected $invokableClasses = array(
        'datetime'          => 'ZFMLL\ModuleManager\Listener\Server\DateTime',
        'hostname'          => 'ZFMLL\ModuleManager\Listener\Server\DomainListener',
       	'getopt'            => 'ZFMLL\ModuleManager\Listener\Environment\GetoptListener',
        'http_method'       => 'ZFMLL\ModuleManager\Listener\Server\HttpMethod',
    	'https'             => 'ZFMLL\ModuleManager\Listener\Server\HttpsListener',
        'port'              => 'ZFMLL\ModuleManager\Listener\Server\PortListener',
    	'remoteaddr'        => 'ZFMLL\ModuleManager\Listener\Server\RemoteAddrListener',
        'sapi'              => 'ZFMLL\ModuleManager\Listener\Environment\SapiListener',
        'url'               => 'ZFMLL\ModuleManager\Listener\Server\UrlListener',
        'user_agent'        => 'ZFMLL\ModuleManager\Listener\Server\UserAgent',
    );

    protected $aliases = array(
        'php_sapi'          => 'sapi',
        'domain'            => 'hostname',
        'uri'               => 'url',
        'remote_addr'       => 'remoteaddr',
        'ip'                => 'remoteaddr',
        'http_user_agent'   => 'user_agent',
    );
    
    /**
     * Determine if we have a valid helper
     * 
     * @param  mixed $plugin 
     * @return true
     * @throws Exception\InvalidListenerException
     */
    public function validatePlugin($plugin)
    {
    	if ($plugin instanceof AuthorizeHandlerInterface) {
            return;
        }
    	throw new InvalidListenerException(
            'Auth listeners must implement AuthorizeHandlerInterface'
        );
    }
}
