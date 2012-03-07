CREATE DATABASE IF NOT EXISTS `login_system`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
`id` int(20) NOT NULL AUTO_INCREMENT,
`username` varchar(40) NOT NULL,
`firstname` varchar(40) NOT NULL,
`lastname` varchar(40) NOT NULL,
`email` varchar(40) NOT NULL,
`phone` varchar(40) default '',
`website` varchar(40) default '',
`passhash` varchar(40) NOT NULL,
`geripass` varchar(40) NOT NULL,
`created` datetime NOT NULL,
`ip` varchar(20) NOT NULL,
`lastlogin` datetime default '2012-03-07 12:21:59',
`lastip` varchar(20) NOT NULL,
`approved` tinyint(1) NOT NULL default '0',
`blocked` tinyint(1) NOT NULL default '0',
`ulevel` tinyint(10) NOT NULL default '1',
PRIMARY KEY (`id`)
) DEFAULT charset=utf8 AUTO_INCREMENT=3;

INSERT INTO `users` (
`id`,`username`,`firstname`,`lastname`,`email`,`phone`,`website`,
`passhash`,`geripass`,`created`,`ip`,`lastlogin`,`lastip`,`approved`,
`blocked`,`ulevel`
) VALUES (
0,'admin','admin','atlocalhost','admin@localhost','1-800-tollfree',
'127.0.0.1','fdfa02ecf86feac3801254da57c1c9bar2ad','da2rekoms',
'2012-03-05 19:51:00', '68.12.92.1', '2012-03-05 19:51:00','68.12.92.1',
1,0,9)