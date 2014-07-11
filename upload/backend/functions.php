<?php
  
   /**
   * functions.php
   * 
   * @author Lee Howarth
   */
   
   /* Load Config */
   include( 'config.php' );
   
   /**
   * Attempt to establish a database connection
   * 
   * @param 
   *        stdclass $config
   * 
   * @return 
   *        PDO object or null on failure
   */
   function dbconn( stdclass $config )
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
 
       return null;
   }
   
   /**
   * Multipurpose Bencode
   * 
   * This function maps variable types to child functions to
   * build a bencoded string.
   * 
   * If an integer is greater than PHP_INT_MAX then PHP will
   * automatically change the var to be a float and this
   * will be recongized by getType as a double.
   * 
   * Sometimes integers will be formatted as a string, ensure
   * you take necessary measures to change this or you may
   * end up having the integer encoded as a string.
   * 
   * @param
   *       string|integer|float|array: $var
   * 
   * @return
   *       string|null
   */
   function bencode( $var )
   {
       $varType = getType( $var );
       
       switch ( $varType )
       {
           case 'string':
           
                 return bencStr( $var );

           case 'double': 
           
           case 'integer':
            
                 return bencInt( $var );
                 
           case 'array':
           
                 $key = key( $var );
           
                 return is_int( $key )
                
              ?  bencList( $var )
              
              :  bencDict( $var );
                 
           default:
            
                 break;
       }
       
       return null;
   }
   
   /**
   * Bencode string
   * 
   * @param
   *       string: $str
   * 
   * @return
   *       string
   */
   function bencStr( $str )
   {
       return strlen( $str ) . ':' . $str;
   }
   
   /**
   * Bencode integer
   * 
   * @param
   *       integer|float: $int
   * 
   * @return
   *       string 
   */
   function bencInt( $int )
   {
       return 'i' . round( $int ) . 'e';
   }
   
   /**
   * Bencode list
   * 
   * @param 
   *       array: $arr An numeric array
   * 
   * @return 
   *       string 
   */
   function bencList( array $arr )
   {
       ksort( $arr, SORT_NUMERIC );
       
       $list = 'l';
       
       foreach ( $arr As $val )
       {
           $list .= bencode( $val );
       }
       
       $list .= 'e';
       
       return $list;
   }
   
   /**
   * Bencode dictionary
   * 
   * @param 
   *       array: $arr An associative array
   * 
   * @return
   *       string
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
