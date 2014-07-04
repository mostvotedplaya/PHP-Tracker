<?php
  
   /**
   * scrape.php
   * 
   * @author Lee Howarth
   */
   
   /* Load Backend */
   include( 'backend/functions.php' );

   /* Parse Infohash */
   $infohash = null;
   
   if ( preg_match_all( '#info_hash=([^&]+)#', $_SERVER[ 'QUERY_STRING' ], $m ) )
   {
        $infohash = array_map( 'urldecode', $m[ 1 ] ); 
   }
   
   /* Prepare Db */
   $pdo = dbconn( $config );
   
   $sql = 'SELECT
                 infohash, downloaded,
                 
                 (SELECT COUNT(pid) FROM peers WHERE residual = 0 AND tid = torrents.tid) As complete,
                
                 (SELECT COUNT(pid) FROM peers WHERE residual > 0 AND tid = torrents.tid) As incomplete
           FROM
                 torrents
                 
           WHERE
                 banned = 0' . ( $infohash ? ' AND infohash IN (' . join( ',', array_map( [ $pdo, 'quote' ], $infohash ) ) . ')' : '' );
   
   $res = $pdo -> query( $sql, PDO::FETCH_ASSOC );
                           
   /* Send Headers */
   header( 'Cache-Control: no-cache, must-revalidate' );
   
   header( 'Expires: Fri, 30 Mar 1990 00:00:00 GMT' );
                 
   header( 'Pragma: no-cache' );
   
   /* Send Response */
   $files = [];
   
   foreach ( $res As $row )
   {
       $files[ array_shift( $row ) ] = $row; 
   }
  
   echo bencode( [ 'files' => $files ] );