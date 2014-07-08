<?php
  
   /**
   * cron.php
   * 
   * @author Lee Howarth
   */

   /* Load Backend */
   include( 'backend/functions.php' );
   
   /* Prepare Db */
   $pdo = dbconn( $config );
   
   /* Remove Old Peers */
   $sql = 'DELETE FROM `peers`
           
           WHERE
                 UNIX_TIMESTAMP(`updated`) < ' . ( time() - $config -> maxInterval + $config -> minInterval );
  
   $pdo -> query( $sql );
   
   /* Remove Dead Torrents */
   $sql = 'DELETE FROM `torrents`
   
           WHERE
                 UNIX_TIMESTAMP(`updated`) < ' . ( time() - 604800 );
                 
   $pdo -> query( $sql );