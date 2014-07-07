<?php
  
   /**
   * config.php
   * 
   * @author Lee Howarth
   */
   
   $config = ( object ) Array 
   (
      /* Db Settings */
      'dbDsn'  => 'mysql:host=localhost;dbname=open;charset=utf8',
       
      'dbUser' => 'root',
       
      'dbPass' => '',
      
      /* Tracker Settings */
      'minInterval' => 300,
      
      'maxInterval' => 900
   );