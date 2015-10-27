<?php
/**
 * St Navigation Helper 
 */

namespace Application\View\Helper;

use Zend\View\Helper\Navigation as ZendNavigation;

/**
 * Navigation
 */
class Navigation extends ZendNavigation
{
    /**
     * Default proxy to use in {@link render()}
     *
     * @var string
     */
    protected $defaultProxy = 'stmenu';

    /**
     * Default set of helpers to inject into the plugin manager
     *
     * @var array
     */
    protected $defaultPluginManagerHelpers = array(
        //'stbreadcrumbs' => 'Admin\View\Helper\Navigation\Breadcrumbs',
        'stmenu'        => 'Application\View\Helper\Navigation\Menu',
    );

    /**
     * Retrieve plugin loader for navigation helpers
     *
     * Lazy-loads an instance of Navigation\HelperLoader if none currently
     * registered.
     *
     * @return \Zend\View\Helper\Navigation\PluginManager
     */
    public function getPluginManager()
    {
        $pm = parent::getPluginManager();
        foreach ($this->defaultPluginManagerHelpers as $name => $invokableClass) {
            $pm->setInvokableClass($name, $invokableClass);
        }
        return $pm;
    }
}
