<?php
namespace Application\Model;
use Zend\Authentication\Storage\Session;

class MyAuthStorage extends Session
{
    
    public function setRememberMe($rememberMe = 0, $time = 604800)
    {
        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($time);
        }
    }
     
    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
}

?>