<?php

/*
 * This file is part of the ZFMLL package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace ZFMLL\ModuleManager\Listener\Environment;

use ZFMLL\ModuleManager\Listener\AbstractListener;
use Zend\Console\Getopt;

class GetoptListener extends AbstractListener
{
    /**
     * @var Getopt 
     */
    protected $getopt;
    
    /**
     * get return of parse
     * @var type 
     */
    protected $isBad = false;

    /**
     * Authorize a module loading
     * @return boolean 
     */
    public function authorizeModule()
    {
    	if(strtolower(ini_get('register_argc_argv'))!='on' && ini_get('register_argc_argv')!='1') {
            return false;
    	}
        
        $numOpt = 0;
        foreach($this->config as $config => $comment) {
            if(preg_match('#^[^=]+=#', $config)) {
                $numOpt++;    
            }
        }
        
        return count($this->getGetopt()->getOptions()) >= $numOpt; // let use of more argument
    }
    
    /**
     * Get argument on command line
     * @return Getopt 
     */
    public function getGetopt()
    {
        if(!$this->getopt) {
            $this->getopt = @new Getopt($this->config, null, array(Getopt::CONFIG_FREEFORM_FLAGS => true)); // let use of more argument
            $this->getopt->parse();
        }
        return $this->getopt;
    }
}
