



CREATE TABLE `data` (

`id` int(11) NOT NULL AUTO_INCREMENT,
`id_capteur` int(11) NOT NULL,
`temp` int(11) NOT NULL,
`hum` int(11) NOT NULL,
`heure` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

primary key (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `capteur` (

`id` int(11) NOT NULL AUTO_INCREMENT,
`nom_capteur` varchar(256) NOT NULL,
`id_capteur` int(11) NOT NULL,

primary key (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;












