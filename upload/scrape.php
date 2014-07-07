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
                 infohash, downloaded, name,
                 
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
   $response[ 'flags' ][ 'min_request_interval' ] = 3600;
   
   $response[ 'files' ] = [];
                  
   foreach ( $res As $row )
   {
       $response[ 'files' ][ $row[ 'infohash' ] ] = [ 'complete'   => ( int ) $row[ 'complete' ], 
       
                                                      'incomplete' => ( int ) $row[ 'incomplete' ],
          
                                                      'downloaded' => ( int ) $row[ 'downloaded' ], 
                                        
                                                      'name' => $row[ 'name' ] ];
   }

   if ( ! $response[ 'files' ] )
   {
        $response[ 'failure reason' ] = 'No results were found.';
   }
   
   echo bencode( $response );