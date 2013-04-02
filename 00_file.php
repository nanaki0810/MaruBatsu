<?php

const NEW_LINE = "\n";

final class game_t
{
  const data_file = 'data.txt';
  
  private $conf_;

  // getter/setter pattern with php-getter/setter
  private function __get($k)
  {
    switch($k)
    {
    case 'data':
      return $this->conf_;
    }
  }

  private function __set($k, $v)
  {
    switch($k)
    {
    case 'data':
      $this->conf_ = $v;
      break;
    }
  }
  
  /* // getter/setter pattern with method
  private function data($a)
  { return ($a === NULL) ? get_data() : set_data$a(); }

  private function get_data()
  { return $conf_; }

  private function set_data($a)
  { $conf_ = $a; }
  */
  
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
        throw new Exception('invalid argument');
      if($a)
      {
        $t->conf_ = $a;
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
    
    $c = file_get_contents(self::data_file);
    
    if($c === false)
    {
      echo('cannot open the file: '.self::data_file.NEW_LINE);
      return;
    }
    
    $this->data = unserialize($c);

    var_dump($this->conf_);
  }

  public function save()
  {
    echo
    ( '----'.NEW_LINE
    . 'save'.NEW_LINE
    );

    $r = file_put_contents(self::data_file, serialize($this->data));

    if($r === false)
      throw new Exception
        ( 'cannot save the configuration file: '
        . self::data_file
        );
    
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