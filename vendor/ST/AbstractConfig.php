<?php
namespace ST;

abstract class AbstractConfig
{

    protected $data;

    public function getConfig($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        return null;
    }

    public function getConfigFlip($name)
    {
        return array_flip($this->getConfig($name));
    }

    public function getValue($name, $key, $is_reverse = false)
    {
        if ($is_reverse)
        {
            $array = $this->getConfigFlip($name);
        }
        else
        {
            $array = $this->getConfig($name);
        }

        if(isset($array[$key]))
        {
            return $array[$key];
        }
        return null;
    }
}