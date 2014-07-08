PHP BT Tracker
=================

This folder is here for the sole purpose of testing.

1.) Download sample.torrent and sample.txt

2.) Go to http://torrenteditor.com/ upload sample.torrent and replace the announce with the location
    of the tracker on your server, save and redownload the torrent.
    
3.) The infohash of the torrent is DE35AAFB0645A667D2789F05E3156581CC653296 and it needs to be imported into
    the database a simple script below will show you how to do this; this file should go with the other php
    files so it can make use of the functions file.
    
```
    <?php
  
       /**
       * import.php
       * 
       * @import Lee Howarth
       */
       
       /* Load Backend */ 
       include( 'backend/functions.php' );
       
       /* Prepare Db */
       $pdo = dbconn( $config );
       
       $hash = hex2bin( 'DE35AAFB0645A667D2789F05E3156581CC653296' );
       
       $name = 'sample.torrent';
       
       /* Insert Query */
       $pdo -> query( 'INSERT INTO `torrents` (`infohash`, `name`) VALUES (' . $pdo -> quote( $hash ) . ', ' . $pdo -> quote( $name ) . ')' );
```
   
After you've created the import.php file temporarily change the .htaccess filename to .htaccess.txt, then run the import script and rename
    the .htaccess.txt back to .htaccess
    
4.) Load your sample.torrent to your client and select the sample.txt location and if everything went ok you should be registered as a seeder.
