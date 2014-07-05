Bittorrent Tracker
=================

This is my implementation of a Open torrent tracker in PHP, the reason for me creating this is because i strongly believe
there isn't a great deal of good trackers out there that follow a up to date specification so my objective is to create exactly that whilst ensuring the quality of code is optimized as much as possible due to running a tracker can be quite heavy
when we are talking about dealing with 100,000 peers +

This implementation will be heavily based on everything discussed in this link:

https://wiki.theory.org/BitTorrentSpecification

<b>Requirements</b>

PHP 5.4 + with PDO

MySQL

Webserver 

<b>Apache</b>

For users whom use apache i have supplied a .htaccess which will provide nice urls providing mod_rewrite is enabled, forexample:

http://domain.tld/announce

http://domain.tld/scrape

<b>Scrape</b>

Scraping supports the following dictionaries / keys.
 
 + files
   + infohash
     - complete
     - incomplete
     - downloaded
     - name
 - failure reason
 + flags
     - min_request_interval
    
<b>Failure reason</b> 

will be available if no results are found.

<b>Announce</b>

Failure reasons:

 #1 - One or more fields were missing from the request.
 
 #2 - The info_hash was not the correct length.
 
 #3 - The peer_id was not the correct length.
 
 #4 - A invalid port was provided.
 
 #5 - The torrent is not registered at the tracker.
 
 #6 - The torrent is banned.
