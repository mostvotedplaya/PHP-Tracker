<?php
  
   /**
   * functions.php
   * 
   * @author Lee Howarth
   */
   
   /* Load Config */
   include( 'config.php' );

   /**
   * Attempt to establish database connection, the
   * script will exit upon failure.
   * 
   * @param stdClass $config
   * @return PDO
   */
   function dbconn( stdClass $config )
   {
       try
       {
           $pdo = new PDO 
           (
              $config -> dbDsn,
              
              $config -> dbUser,
              
              $config -> dbPass 
           );
           
           return $pdo;
       }
       catch ( PDOException $e )
       {
       
       }
      
       exit( 'Failed to establish db connection.' );
   }

   /**
   * Multipurpose bencode
   * 
   * @param  mixed $var
   * @return mixed
   */
   function bencode( $var )
   {                                  
       $varType = getType( $var );
                   
       switch ( $varType )
       {
           case 'string':
           
                 return bencStr( $var );
                 
           case 'double': case 'integer':
           
                 return bencInt( $var );
                 
           case 'array':
                 
                 $key = key( $var );
                 
                 return is_int( $key )
                   
               ? bencList( $var )
                       
               : bencDict( $var );
       }
       
       return null;
   }                    
   
   /**
   * Bencode strings
   * 
   * @param  string $str
   * @return string
   */
   function bencStr( $str )
   {
       return strlen( $str ) . ':' . $str;
   }
   
   /**
   * Bencode integers
   * 
   * @param  integer $int
   * @return string
   */
   function bencInt( $int )
   {
       return 'i' . $int . 'e';
   }
   
   /**
   * Bencode list
   * 
   * @param  array $list
   * @return string
   */
   function bencList( array $arr )
   {
       ksort( $arr, SORT_NUMERIC );
       
       $list = 'l';
       
       foreach ( $arr As $var )
       {
           $list .= bencode( $var );
       }
       
       $list .= 'e';
       
       return $list;
   }
   
   /**
   * Bencode dictionary
   * 
   * @param  array $dict
   * @return string
   */
   function bencDict( array $arr )
   {
       ksort( $arr, SORT_STRING );
       
       $dict = 'd';
       
       foreach ( $arr As $key => $val )
       {
           $dict .= bencStr( $key );
           
           $dict .= bencode( $val );
       }
       
       $dict .= 'e';
       
       return $dict;
   }
