<?php
namespace Admin\Config;

use ST\AbstractConfig;

class Config extends AbstractConfig
{

    function __construct()
    {
        $config = array(
            'admin_roles' => array(
                'admin' => 'Admin',
                'moderator' => 'Moderator',
                'member' => 'Member'
            ),
            'status' => array(
                \Application\Config\Config::STATUS_ACTIVE => 'Active',
                \Application\Config\Config::STATUS_INACTIVE => 'Inactive'
            ),
            'language_status' => array(
                \Application\Config\Config::STATUS_DEFAULT => 'Default',
                \Application\Config\Config::STATUS_ACTIVE => 'Active',
                \Application\Config\Config::STATUS_INACTIVE => 'Inactive'
            ),
            'priority' => array(
                \Application\Config\Config::PRIORITY_NORMAL => 'Normal',
                \Application\Config\Config::PRIORITY_HOME => 'Home',
                \Application\Config\Config::PRIORITY_TOP => 'Top',
                \Application\Config\Config::PRIORITY_FEATURE => 'Feature'
            ),
            'layout' => array(
                'layout/layout' => 'layout/layout'
            )
        );
        $this->data = $config;
    }
}

?>