<?php
  
   /**
   * announce.php
   * 
   * @author Lee Howarth
   */
   
   /* Load Backend */
   include( 'backend/functions.php' );
   
   /* Required Vars */
   $valid = true;
  
   foreach ( [ 'info_hash', 'peer_id', 'port', 'uploaded', 'downloaded', 'left' ] As $var )
   {
       if ( ! isset( $_GET[ $var ] ) Or ! is_string( $_GET[ $var ] ) )
       {
            $valid = false;
            
            break;
       }
       
       ${$var} = urldecode( $_GET[ $var ] );
   }
   
   /* Error Helper */
   $error = function( $e )
   {
       return bencode( [ 'failure reason' => $e ] );
   };
   
   /* Missing key ? */
   if ( ! $valid )
   {
        echo $error( 'Tracker error: #1' );
       
        exit;
   }
   
   /* Invalid info_hash */
   if ( strlen( $info_hash ) != 20 )
   {
        echo $error( 'Tracker error: #2' );
       
        exit;
   }
   
   /* Invalid peer_id */
   if ( strlen( $peer_id ) != 20 )
   {
        echo $error( 'Tracker error: #3' );
       
        exit;
   }
   
   /* Invalid Port */
   if ( ! $port Or $port > 0xffff )
   {
        echo $error( 'Tracker error: #4' );
       
        exit;
   }
   
   /* Optional Vars */
   foreach ( [ 'compact', 'no_peer_id', 'event', 'ip', 'numwant', 'key', 'trackerid', 'supportcrypto', 'requirecrypto', 'cryptoport' ] As $opt )
   {
       if ( ! isset( $_GET[ $opt ] ) )
       {
            ${$opt} = null;
           
            continue;
       }
       
       ${$opt} = strval( $_GET[ $opt ] );
   }
   
   /* Prepare Db */
   $pdo = dbconn( $config );
   
   $sql = 'SELECT
                 tid, name, downloaded, banned,
                 
                 (SELECT COUNT(pid) FROM peers WHERE residual = 0 AND tid = torrents.tid) As complete,
                
                 (SELECT COUNT(pid) FROM peers WHERE residual > 0 AND tid = torrents.tid) As incomplete
           FROM
                 torrents
                 
           WHERE
                 infohash = ' . $pdo -> quote( $info_hash );
                 
   /* Unregistered Torrent */
   if ( ! ( $torrent = $pdo -> query( $sql ) -> fetch( PDO::FETCH_ASSOC ) ) )
   {
        echo $error( 'Tracker error: #5' );
       
        exit;
   }

   /* Banned Torrent */
   if ( $torrent[ 'banned' ] )
   {
        echo $error( 'Tracker error: #6' );
       
        exit;
   }
   
   /* Prepare Response */
   $response = [ 'complete' => $torrent[ 'complete' ], 'incomplete' => $torrent[ 'incomplete' ], 'downloaded' => $torrent[ 'downloaded' ], 'interval' => 900, 'min interval' => 300 ];
 
   switch ( $event )
   {
       default: case 'started':
    
             break;
             
       case 'stopped':
   
             break;
             
       case 'completed':
      
             break;
   } 
   
   /* Send Headers */
   header( 'Cache-Control: no-cache, must-revalidate' );
   
   header( 'Expires: Fri, 30 Mar 1990 00:00:00 GMT' );
                 
   header( 'Pragma: no-cache' );
   
   /* Send Response */
   echo bencode( $response );