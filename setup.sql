USE buskruit;

DROP TABLE IF EXISTS `user_type`;
CREATE TABLE `user_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `pwd_hash` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `user_type` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_num` int NOT NULL,
  `description` varchar(200) COLLATE utf8_bin NOT NULL,
  `supplier` int COLLATE utf8_bin NOT NULL,
  `product_group` int COLLATE utf8_bin NOT NULL,
  `unit` varchar(25) COLLATE utf8_bin NOT NULL,
  `price` decimal(2,2) NOT NULL,
  `storage` int NOT NULL,
  `ordered` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `product_group`;
CREATE TABLE `product_group` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

LOCK TABLES `product` WRITE;
INSERT INTO `product`
VALUES 
(1,123456,'Broccoli',1,1,'stuk',1.99,15,0),
(2,123457,'Bloemkool',1,1,'stuk',2.99,50,0),
(3,123458,'Aubergine',1,1,'stuk',0.89,75,0),
(4,123459,'Salade ui',1,1,'bosje',0.59,50,0),
(5,123460,'Snoepgroente tomaat',1,1,'500g',2.99,75,0),
(6,123461,'Kruimige aardappel',1,1,'1kg',1.09,25,0),
(7,123462,'Kruimige aardappel',1,1,'5kg',2.75,25,0),
(8,123463,'Kaas geraspt mid 45+',2,2,'200g',1.79,45,0),
(9,123464,'Kaas Pittig 45+ geraspt',2,2,'200g',1.89,45,0),
(10,123465,'Kaas Jong 48+',2,2,'400g',2.89,45,0),
(11,123466,'Kipfilet',3,2,'150g',1.69,40,0),
(12,123467,'Gerookte spekreepjes',3,2,'300g',2.69,40,0),
(13,123468,'Gerookte schouderham',3,2,'150g',1.09,40,0),
(14,123469,'Boterhamworst',3,2,'150g',0.99,45,0),
(15,123470,'Pindakaas',4,3,'350g',2.69,65,0),
(16,123471,'Appelstroop',5,3,'450g',0.69,65,0),
(17,123472,'Hazelnootpasta',6,3,'630g',4.99,65,0),
(18,123473,'Vruchtenhagel',7,3,'400g',1.35,65,0),
(19,123474,'Chocoladehagel puur',7,3,'390g',2.49,56,0),
(20,123475,'Hagelslag melk',8,3,'400g',1.69,57,0),
(21,123476,'Rimboe kroko vlokken puur/vanille',8,3,'200g',1.99,35,0),
(22,123477,'Vlokken puur',7,3,'300g',1.99,55,0),
(23,123478,'Cola Zero sugar',9,4,'1l',1.85,100,0),
(24,123479,'Cola Regular',9,4,'1l',1.85,150,0),
(25,123480,'Fanta Orange',10,4,'1,5l',1.95,125,0),
(26,123481,'Aroma rood filter koffie',11,5,'250g',3.29,250,0),
(27,123482,'Aroma rood filter koffie',11,5,'500g',6.15,125,0),
(28,123483,'Koffiemelk Halvamel',12,5,'455ml',1.25,122,0),
(29,123484,'Senseo Classic Koffiepads',11,5,'36st',3.69,100,0),
(30,123485,'Opschuimmelk voor cappucino',12,5,'1l',1.35,50,0),
(31,123486,'Huisblends Aroma snelfiltermaling',13,5,'250g',2.89,75,0),
(32,123487,'Chips Naturel',14,6,'225g',1.49,75,0),
(33,123488,'Chips Paprika',14,6,'225g',1.49,85,0),
(34,123489,'Superchips paprika',14,6,'200g',1.59,10,0),
(35,123490,'Nibb-it sticks',15,6,'110g',1.35,5,0),
(36,123491,'Ontbijtkoek naturel gesneden',16,7,'485g',1.75,15,0);
UNLOCK TABLES;

LOCK TABLES `product_group` WRITE;
INSERT INTO `product_group`
VALUES 
(1,"Aardappels, groente en fruit1"),
(2,"Kaas,vleeswaren"),
(3,"Broodbeleg"),
(4,"Frisdrank"),
(5,"Koffie"),
(6,"Chips"),
(7,"Koek");
UNLOCK TABLES;

LOCK TABLES `supplier` WRITE;
INSERT INTO `supplier`
VALUES 
("1","Boer Harms"),
("2","De Zaanse Hoeve"),
("3","Meester & Zn."),
("4","Calvé"),
("5","Rinse"),
("6","Nutella"),
("7","De Ruijter"),
("8","Venz"),
("9","Coca-Cola"),
("10","Fanta"),
("11","Douwe Egberts"),
("12","Friesche vlag"),
("13","Perla"),
("14","Lay's"),
("15","Cheetos"),
("16","Peijnenburg");
UNLOCK TABLES;

LOCK TABLES `user_type` WRITE;
INSERT INTO `user_type`
VALUES
(1, "Beheerder"),
(2, "Kassamedewerker"),
(3, "Magazijnbeheer");
UNLOCK TABLES;