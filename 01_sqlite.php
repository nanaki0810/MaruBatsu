<?php

const NEW_LINE = "\n";

final class CannotCreateTableException
extends Exception
{
  protected $message = 'cannot create table.';
}

final class game_t
{
  const data_sqlite = 'data.sqlite.db';
  
  private $conf_ = [];
  private $db_;

  public function __construct()
  {
    try
    {
      $this->db_ = new PDO('sqlite:'.self::data_sqlite);
      if( ! $this->is_exists_table() )
        $this->create_table();
    }
    catch(CannotCreateTableException $e)
    {
      echo('CannotCreateTableException: '.$e->getMessage());
      throw $e;
    }
    catch(Exception $e)
    {
      echo('PDO exception: '.$e->getMessage());
      throw $e;
    }
  }

  private function is_exists_table()
  {
    echo
    ( '---'.NEW_LINE
    . 'is_exists_table'.NEW_LINE
    );
    
    $k = 'count(name)';
    
    $q = 'select '.$k
       . ' from sqlite_master'
       . ' where name = \'data\''
       ;
    
    $res = $this->db_->query($q);
    
    if($res === false)
      throw new Exception('query failed: '.$q);
    
    $ret = (bool)$res->fetch()[0];
    
    var_dump($ret);
    
    return $ret;
  }

  private function create_table()
  {
    echo
    ( '---'.NEW_LINE
    . 'create_table'.NEW_LINE
    );
    
    $this->db_->query('create table data(key primary key,value)');
    if( ! $this->is_exists_table() )
      throw new CannotCreateTableException();

    echo('table is created.'.NEW_LINE);
  }
  
  public function __get($k)
  {
    switch($k)
    {
    case 'data':
      return $this->conf_;
    default:
      throw new LogicException();
    }
  }

  public function __set($k, $v)
  {
    switch($k)
    {
    case 'data':
      $this->conf_ = $v;
      break;
    default:
      throw new LogicException();
    }
  }
  
  public function conf($a = [])
  {
    echo
    ( '----'.NEW_LINE
    . 'conf'.NEW_LINE
    );

    var_dump($a);
    
    $change = function($t, $a)
    {
      if(!is_array($a))
        throw new InvalidArgumentException();
      if($a)
      {
        $t->conf_ = $a + $t->conf_;
        return true;
      }
    };

    $r = $change($this, $a) ? '' : 'no ';
    echo('conf is '.$r.'changed.'.NEW_LINE);

    var_dump($this->conf_);
  }

  public function load()
  {
    echo
    ( '----'.NEW_LINE
    . 'load'.NEW_LINE)
    ;
    
    $q = 'select * from data';
    $r = $this->db_->query($q);

    if($r === false)
    {
      echo('query failed: '.$q.NEW_LINE);
      return;
    }
    
    $r->bindColumn('key'  , $k);
    $r->bindColumn('value', $v);

    $this->conf_ = [];
    while($r->fetch(PDO::FETCH_BOUND))
      $this->conf_[unserialize($k)] = unserialize($v);
    
    var_dump($this->conf_);
  }

  public function save()
  {
    echo
    ( '----'.NEW_LINE
    . 'save'.NEW_LINE
    );
    
    foreach($this->data as $k => $v)
    {
      $q = 'insert or replace into data values('
         . '\''.SQLite3::escapeString(serialize($k)).'\''
         . ','
         . '\''.SQLite3::escapeString(serialize($v)).'\''
         . ')'
         ;
      var_dump($q);
      $r = $this->db_->query($q);
      
      if($r === false)
        echo('query failed: '.$q.NEW_LINE);
    }
    
    var_dump($this->conf_);
  }
}

$main = function()
{
  try
  {
    header('content-type: text/plain; charset=utf-8');
  
    $game = new game_t();
    $game->load();
    $game->conf($_REQUEST);
    $game->save();
  }
  catch(Exception $e)
  {
    echo
    ( NEW_LINE
    . '=== EXCEPTION; caught in main ==='.NEW_LINE
    . 'FILE   : '.$e->getFile().NEW_LINE
    . 'LINE   : '.$e->getLine().NEW_LINE
    . 'CODE   : '.$e->getCode().NEW_LINE
    . 'MESSAGE: '.$e->getMessage().NEW_LINE
    . 'TRACE  : '.$e->getTraceAsString().NEW_LINE
    );
    exit(1);
  }
};

$main();
