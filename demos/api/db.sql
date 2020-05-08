-- create items table
CREATE TABLE `items` (
	`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` varchar(255) NOT NULL,
	`surname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- insert default admin credentials
INSERT INTO `items` (`name`, `surname`) VALUES 
	('a', 'b'),
	('c', 'd'),
	('e', 'f'),
	('g', 'h'),
	('i', 'j'),
	('k', 'l'),
	('m', 'n'),
	('o', 'p'),
	('q', 'r'),
	('s', 't'),
	('u', 'v'),
	('w', 'x'),
	('y', 'z');
