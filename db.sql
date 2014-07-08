CREATE TABLE peers 
(
  pid int(1) unsigned NOT NULL AUTO_INCREMENT,
  tid int(1) unsigned NOT NULL,
  uid varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  peerId varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  ip varchar(39) COLLATE utf8_unicode_ci NOT NULL,
  port smallint(1) unsigned NOT NULL DEFAULT '0',
  crypt bit(1) NOT NULL DEFAULT b'0',
  residual bigint(1) unsigned NOT NULL DEFAULT '0',
  added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (pid),
  UNIQUE KEY uid (uid,tid),
  KEY tid (tid)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE torrents 
(
  tid int(1) unsigned NOT NULL AUTO_INCREMENT,
  infohash binary(20) NOT NULL,
  name varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  downloaded int(1) unsigned NOT NULL DEFAULT '0',
  banned bit(1) NOT NULL DEFAULT b'0',
  added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (tid),
  UNIQUE KEY infohash (infohash),
  KEY banned (banned),
  FULLTEXT KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE peers ADD CONSTRAINT peers_ibfk_1 FOREIGN KEY (tid) REFERENCES torrents (tid) ON DELETE CASCADE ON UPDATE NO ACTION;