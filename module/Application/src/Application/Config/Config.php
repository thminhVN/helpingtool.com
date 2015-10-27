<?php
namespace Application\Config;

use ST\AbstractConfig;
class Config extends AbstractConfig
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DEFAULT = 2;
    
    const PRIORITY_NORMAL = 1;
    const PRIORITY_TOP = 2;
    const PRIORITY_HOME = 3;
    const PRIORITY_FEATURE = 4;
    
    public function __construct()
    {
    }
}