<?php

class CoreClass
{
    private $_db;
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

    protected function logAccess($action, $userId) {
        $sql = "INSERT INTO access_logs (action, user_id, access_date, ip_address) 
                VALUES (:action, :userId, NOW(), :ip)";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([
            'action' => $action,
            'userId' => $userId,
            'ip' => $_SERVER['REMOTE_ADDR']
        ]);
    }
}