<?php

class CoreClass
{
    public function __construct($data)
    {
        $this->hydrate($data);
    }

    private function hydrate($data)
    {
        foreach($data as $key => $value)
        {
            $methodName = 'set'.ucfirst($key);
            if(method_exists($this, $methodName))
            {
                $this->$methodName($value);
            }
        }
    }
}