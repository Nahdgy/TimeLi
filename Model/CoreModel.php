<?php
abstract class CoreModel 
{

  private $_engine = DB_ENGINE;
  private $_host = DB_HOST;
  private $_dbname = DB_NAME;
  private $_charset = DB_CHARSET;
  private $_dbuser = DB_USER;
  private $_dbpwd = DB_PWD;

  private $_db;

  public function __construct()
  {
    $this->connect();
  }


  private function connect()
  {
    try
    {
      $dsn = $this->_engine . ':host=' . $this->_host .  ';dbname='.$this->_dbname .';charset='.$this->_charset;
      $this->_db = new PDO($dsn, $this->_dbuser, $this->_dbpwd, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    } catch(PDOException $e)
    {
      die($e->getMessage());
    }
  }


  protected function getDb()
  {
    return $this->_db;
  }

  protected function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map([$this, 'sanitizeInput'], $input);
    }
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
  }
}
