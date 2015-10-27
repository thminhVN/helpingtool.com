<?php

namespace ZFMLLTest\ModuleManager;

use PHPUnit_Framework_TestCase as TestCase;
use ZFMLL\ModuleManager\ModuleManager;
use ZFMLL\ModuleManager\Listener\ListenerOptions;
use ZFMLL\ModuleManager\Listener\AuthListenerAggregate;
use Zend\EventManager\EventManager;
use Zend\EventManager\SharedEventManager;
use Zend\Loader\ModuleAutoloader;
use Zend\Loader\AutoloaderFactory;
use InvalidArgumentException;

class ModuleManagerTest extends TestCase
{
    protected $tmpdir;
    protected $configCache;
    protected $loaders;
    protected $includePath;
    protected $defaultListeners;
    protected $eventManager;

    public function setUp()
    {
        $this->tmpdir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'zend_module_cache_dir';
        @mkdir($this->tmpdir);
        $this->configCache = $this->tmpdir . DIRECTORY_SEPARATOR . 'config.cache.php';
        // Store original autoloaders
        $this->loaders = spl_autoload_functions();
        if (!is_array($this->loaders)) {
            // spl_autoload_functions does not return empty array when no
            // autoloaders registered...
            $this->loaders = array();
        }

        // Store original include_path
        $this->includePath = get_include_path();

        $this->defaultListeners = new AuthListenerAggregate(
            new ListenerOptions(array( 
                'module_paths'         => array(
                    realpath(__DIR__ . '/TestAsset'),
                ),
            ))
        );
        $this->eventManager = new EventManager;
        $this->eventManager->setSharedManager(new SharedEventManager);
    }

    public function tearDown()
    {
        $file = glob($this->tmpdir . DIRECTORY_SEPARATOR . '*');
        @unlink($file[0]); // change this if there's ever > 1 file 
        @rmdir($this->tmpdir);
        // Restore original autoloaders
        AutoloaderFactory::unregisterAutoloaders();
        $loaders = spl_autoload_functions();
        if (is_array($loaders)) {
            foreach ($loaders as $loader) {
                spl_autoload_unregister($loader);
            }
        }

        foreach ($this->loaders as $loader) {
            spl_autoload_register($loader);
        }

        // Restore original include_path
        set_include_path($this->includePath);
    }
    
    public function testCanLoadSomeModule()
    {
        $configListener = $this->defaultListeners->getConfigListener();
        $moduleManager  = new ModuleManager(array('SomeModule'), $this->eventManager);
        $moduleManager->getEventManager()->attachAggregate($this->defaultListeners);
        $moduleManager->loadModules();
        $loadedModules = $moduleManager->getLoadedModules();
        $this->assertEquals('SomeModule\Module', get_class($loadedModules['SomeModule']));
        $config = $configListener->getMergedConfig();
        $this->assertSame($config->some, 'thing');
    }
    
    public function testCanLoadSomeModuleWithLazyLoading()
    {
        $this->defaultListeners->getOptions()->setLazyLoading(array('SomeModule'=>array('sapi'=>php_sapi_name())));
        $configListener = $this->defaultListeners->getConfigListener();
        $moduleManager  = new ModuleManager(array('SomeModule'), $this->eventManager);
        $moduleManager->getEventManager()->attachAggregate($this->defaultListeners);
        $moduleManager->loadModules();
        $loadedModules = $moduleManager->getLoadedModules();
        $this->assertEquals('SomeModule\Module', get_class($loadedModules['SomeModule']));
        $config = $configListener->getMergedConfig();
        $this->assertSame($config->some, 'thing');
    }
    
    public function testCanLoadSomeModuleWithLazyLoadingRestricted()
    {
        $this->defaultListeners->getOptions()->setLazyLoading(array('SomeModule'=>array('sapi'=>'fail')));
        $configListener = $this->defaultListeners->getConfigListener();
        $moduleManager  = new ModuleManager(array('SomeModule'), $this->eventManager);
        $moduleManager->getEventManager()->attachAggregate($this->defaultListeners);
        $moduleManager->loadModules();
        $loadedModules = $moduleManager->getLoadedModules();
        $this->assertEquals(0, count($loadedModules));
    }
    
    public function testCanLoadMultipleModules()
    {
        $configListener = $this->defaultListeners->getConfigListener();
        $moduleManager  = new ModuleManager(array('BarModule', 'BazModule'));
        $moduleManager->getEventManager()->attachAggregate($this->defaultListeners);
        $moduleManager->loadModules();
        $loadedModules = $moduleManager->getLoadedModules();
        $this->assertEquals('BarModule\Module', get_class($loadedModules['BarModule']));
        $this->assertEquals('BazModule\Module', get_class($loadedModules['BazModule']));
        $this->assertEquals('BarModule\Module', get_class($moduleManager->getModule('BarModule')));
        $this->assertEquals('BazModule\Module', get_class($moduleManager->getModule('BazModule')));
        $this->assertNull($moduleManager->getModule('NotLoaded'));
        $config = $configListener->getMergedConfig();
        $this->assertSame('foo', $config->bar);
        $this->assertSame('bar', $config->baz);
    }
    
    public function testCanLoadMultipleModulesWithLazyLoading()
    {
        $this->defaultListeners->getOptions()->setLazyLoading(array('BarModule'=>array('sapi' => php_sapi_name())));
        $this->defaultListeners->getOptions()->setLazyLoading(array('BazModule'=>array('sapi' => php_sapi_name())));
        $configListener = $this->defaultListeners->getConfigListener();
        $moduleManager  = new ModuleManager(array('BarModule', 'BazModule'));
        $moduleManager->getEventManager()->attachAggregate($this->defaultListeners);
        $moduleManager->loadModules();
        $loadedModules = $moduleManager->getLoadedModules();
        $this->assertEquals('BarModule\Module', get_class($loadedModules['BarModule']));
        $this->assertEquals('BazModule\Module', get_class($loadedModules['BazModule']));
        $this->assertEquals('BarModule\Module', get_class($moduleManager->getModule('BarModule')));
        $this->assertEquals('BazModule\Module', get_class($moduleManager->getModule('BazModule')));
        $this->assertNull($moduleManager->getModule('NotLoaded'));
        $config = $configListener->getMergedConfig();
        $this->assertSame('foo', $config->bar);
        $this->assertSame('bar', $config->baz);
    }
    
    
    public function testCanLoadMultipleModulesWithLazyLoadingRestricted()
    {
        $this->defaultListeners->getOptions()->setLazyLoading(array('BarModule'=>array('sapi' => php_sapi_name())));
        $this->defaultListeners->getOptions()->setLazyLoading(array('BazModule'=>array('sapi' => 'fail')));
        $configListener = $this->defaultListeners->getConfigListener();
        $moduleManager  = new ModuleManager(array('BarModule', 'BazModule'));
        $moduleManager->getEventManager()->attachAggregate($this->defaultListeners);
        $moduleManager->loadModules();
        $loadedModules = $moduleManager->getLoadedModules();
        $this->assertEquals(1, count($loadedModules));
        $this->assertEquals('BarModule\Module', get_class(array_shift($loadedModules)));
    }
    
    public function testCanLoadMultipleModulesWithMultipleLazyLoadingRestrictedSuccess()
    {
        $_SERVER['SERVER_PORT'] = 443;
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['REMOTE_ADDR'] = '12.14.16.15';
        $_SERVER['REQUEST_URI'] = '/blog/my-article';
        $_SERVER['HTTP_HOST'] = 'lazy-loading.zend-framework-2.fr';
        $this->defaultListeners->getOptions()->setLazyLoading(array(
            'SomeModule' => array('domain' => array('lazy-loading.zend-framework-2.fr', 'lazy-loading.zend-framework-2.com')),
            'BarModule' => array('sapi' => php_sapi_name(), 'port' => 443, 'https' => 'on'),
            'BazModule' => array('remote_addr' => array('12.14.16.15'), 'url' => array('regex' => '/blog/.*')),
        ));
        $configListener = $this->defaultListeners->getConfigListener();
        $moduleManager  = new ModuleManager(array('BarModule', 'BazModule', 'SomeModule'));
        $moduleManager->getEventManager()->attachAggregate($this->defaultListeners);
        $moduleManager->loadModules();
        $loadedModules = $moduleManager->getLoadedModules();
        $this->assertEquals(3, count($loadedModules));
    }
    
    public function testCanLoadMultipleModulesWithMultipleLazyLoadingRestrictedFail()
    {
        $_SERVER['SERVER_PORT'] = 443;
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['REMOTE_ADDR'] = '12.14.16.15';
        $_SERVER['REQUEST_URI'] = '/blog/my-article';
        $_SERVER['HTTP_HOST'] = 'lazy-loading.zend-framework-2.fr';
        $this->defaultListeners->getOptions()->setLazyLoading(array(
            'SomeModule' => array('domain' => 'zend-framework-2.fr'),
            'BarModule' => array('sapi' => 'cgi', 'port' => 80, 'https' => 'off'),
            'BazModule' => array('remote_addr' => '15.15.15.15', 'url' => array('regex' => 'myblog')),
        ));
        $configListener = $this->defaultListeners->getConfigListener();
        $moduleManager  = new ModuleManager(array('BarModule', 'BazModule', 'SomeModule'));
        $moduleManager->getEventManager()->attachAggregate($this->defaultListeners);
        $moduleManager->loadModules();
        $loadedModules = $moduleManager->getLoadedModules();
        $this->assertEquals(0, count($loadedModules));
    }
    
    public function testCanLoadMultipleModulesWithArgumentGetopt()
    {   
        $_SERVER['argv'][] = '--cron=url';
        $this->defaultListeners->getOptions()->setLazyLoading(array(
            'SomeModule' => array('getopt' => array('cron=s' => 'cron url')),
        ));
        $configListener = $this->defaultListeners->getConfigListener();
        $moduleManager  = new ModuleManager(array('SomeModule'));
        $moduleManager->getEventManager()->attachAggregate($this->defaultListeners);
        $moduleManager->loadModules();
        $loadedModules = $moduleManager->getLoadedModules();
        $this->assertEquals(1, count($loadedModules));
    }
}