-- MySQL dump 10.13  Distrib 5.7.31, for Win64 (x86_64)
--
-- Host: localhost    Database: gsb_frais
-- ------------------------------------------------------
-- Server version	5.7.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `etat`
--

DROP TABLE IF EXISTS `etat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etat` (
  `id` char(2) NOT NULL,
  `libelle` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etat`
--

LOCK TABLES `etat` WRITE;
/*!40000 ALTER TABLE `etat` DISABLE KEYS */;
INSERT INTO `etat` VALUES ('CL','Saisie clôturée'),('CR','Fiche créée, saisie en cours'),('RB','Remboursée'),('VA','Validée et mise en paiement');
/*!40000 ALTER TABLE `etat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fichefrais`
--

DROP TABLE IF EXISTS `fichefrais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fichefrais` (
  `idvisiteur` char(4) NOT NULL,
  `mois` char(6) NOT NULL,
  `nbjustificatifs` int(11) DEFAULT NULL,
  `montantvalide` decimal(10,2) DEFAULT NULL,
  `datemodif` date DEFAULT NULL,
  `idetat` char(2) DEFAULT 'CR',
  PRIMARY KEY (`idvisiteur`,`mois`),
  KEY `idetat` (`idetat`),
  CONSTRAINT `fichefrais_ibfk_1` FOREIGN KEY (`idetat`) REFERENCES `etat` (`id`),
  CONSTRAINT `fichefrais_ibfk_2` FOREIGN KEY (`idvisiteur`) REFERENCES `visiteur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fichefrais`
--

LOCK TABLES `fichefrais` WRITE;
/*!40000 ALTER TABLE `fichefrais` DISABLE KEYS */;
INSERT INTO `fichefrais` VALUES ('a131','202110',0,0.00,'2021-11-17','CR'),('a131','202111',0,0.00,'2021-11-17','CL'),('a131','202112',0,0.00,'2021-11-17','CR');
/*!40000 ALTER TABLE `fichefrais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fraisforfait`
--

DROP TABLE IF EXISTS `fraisforfait`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fraisforfait` (
  `id` char(3) NOT NULL,
  `libelle` char(20) DEFAULT NULL,
  `montant` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fraisforfait`
--

LOCK TABLES `fraisforfait` WRITE;
/*!40000 ALTER TABLE `fraisforfait` DISABLE KEYS */;
INSERT INTO `fraisforfait` VALUES ('ETP','Forfait Etape',110.00),('KM','Frais Kilométrique',0.62),('NUI','Nuitée Hôtel',80.00),('REP','Repas Restaurant',25.00);
/*!40000 ALTER TABLE `fraisforfait` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lignefraisforfait`
--

DROP TABLE IF EXISTS `lignefraisforfait`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lignefraisforfait` (
  `idvisiteur` char(4) NOT NULL,
  `mois` char(6) NOT NULL,
  `idfraisforfait` char(3) NOT NULL,
  `quantite` int(11) DEFAULT NULL,
  PRIMARY KEY (`idvisiteur`,`mois`,`idfraisforfait`),
  KEY `idfraisforfait` (`idfraisforfait`),
  CONSTRAINT `lignefraisforfait_ibfk_1` FOREIGN KEY (`idvisiteur`, `mois`) REFERENCES `fichefrais` (`idvisiteur`, `mois`),
  CONSTRAINT `lignefraisforfait_ibfk_2` FOREIGN KEY (`idfraisforfait`) REFERENCES `fraisforfait` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lignefraisforfait`
--

LOCK TABLES `lignefraisforfait` WRITE;
/*!40000 ALTER TABLE `lignefraisforfait` DISABLE KEYS */;
INSERT INTO `lignefraisforfait` VALUES ('a131','202110','ETP',0),('a131','202110','KM',0),('a131','202110','NUI',0),('a131','202110','REP',0),('a131','202111','ETP',1),('a131','202111','KM',5),('a131','202111','NUI',1),('a131','202111','REP',5),('a131','202112','ETP',5),('a131','202112','KM',0),('a131','202112','NUI',2),('a131','202112','REP',0);
/*!40000 ALTER TABLE `lignefraisforfait` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lignefraishorsforfait`
--

DROP TABLE IF EXISTS `lignefraishorsforfait`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lignefraishorsforfait` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idvisiteur` char(4) NOT NULL,
  `mois` char(6) NOT NULL,
  `libelle` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idvisiteur` (`idvisiteur`,`mois`),
  CONSTRAINT `lignefraishorsforfait_ibfk_1` FOREIGN KEY (`idvisiteur`, `mois`) REFERENCES `fichefrais` (`idvisiteur`, `mois`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lignefraishorsforfait`
--

LOCK TABLES `lignefraishorsforfait` WRITE;
/*!40000 ALTER TABLE `lignefraishorsforfait` DISABLE KEYS */;
INSERT INTO `lignefraishorsforfait` VALUES (8,'a131','202111','dtyuju','2022-11-08',50.00),(15,'a131','202112','jhfkjsfhskfjhkfj','2021-11-08',50.00);
/*!40000 ALTER TABLE `lignefraishorsforfait` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visiteur`
--

DROP TABLE IF EXISTS `visiteur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visiteur` (
  `id` char(4) NOT NULL,
  `nom` char(30) DEFAULT NULL,
  `prenom` char(30) DEFAULT NULL,
  `login` char(20) DEFAULT NULL,
  `mdp` char(255) DEFAULT NULL,
  `adresse` char(30) DEFAULT NULL,
  `cp` char(5) DEFAULT NULL,
  `ville` char(30) DEFAULT NULL,
  `dateembauche` date DEFAULT NULL,
  `email` text,
  `profil` char(10) DEFAULT 'comptable',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visiteur`
--

LOCK TABLES `visiteur` WRITE;
/*!40000 ALTER TABLE `visiteur` DISABLE KEYS */;
INSERT INTO `visiteur` VALUES ('999','collette','basil','basil','$2y$10$k/hzcxk.dHyoxoAoGNV59eBIKj4sDpdIMu5Lcwyx6dwneIS31G7YO','280 boulevard michelet','13008','marseille',NULL,'basil@monde.fr','comptable'),('a131','Villechalane','Louis','lvillachane','$2y$10$k/hzcxk.dHyoxoAoGNV59eBIKj4sDpdIMu5Lcwyx6dwneIS31G7YO','8 rue des Charmes','46000','Cahors','2005-12-21','lvillachane@swiss-galaxy.com','visiteur'),('a17','Andre','David','dandre','$2y$10$.azqaAmLG/jaAWhpcAgZuOfjXlQMwaC2Xz03a8egLxdTXEXq4FA9W','1 rue Petit','46200','Lalbenque','1998-11-23','dandre@swiss-galaxy.com','visiteur'),('a55','Bedos','Christian','cbedos','$2y$10$DQ5Cb034PZ2eSBVXLsRzvOlhjlV9jintF/B4XGdMiOZsmOPPr0i/m','1 rue Peranud','46250','Montcuq','1995-01-12','cbedos@swiss-galaxy.com','visiteur'),('a93','Tusseau','Louis','ltusseau','$2y$10$ETjIMPclIMmP07VHowbByexFyhpvc0Oeo1e.85ns0kAMYiFwkkTDO','22 rue des Ternes','46123','Gramat','2000-05-01','ltusseau@swiss-galaxy.com','visiteur'),('b13','Bentot','Pascal','pbentot','$2y$10$ri2bPA8xfMRhCUSAbiv4UuBN/YApsSGxGusgrjGGiEQ65kbHnz9IW','11 allée des Cerises','46512','Bessines','1992-07-09','pbentot@swiss-galaxy.com','visiteur'),('b16','Bioret','Luc','lbioret','$2y$10$6Jf910oCtC5mps3KdQIfSOXVm0qpx7jV9i1ZqPjjU2lxLtJdC.V2W','1 Avenue gambetta','46000','Cahors','1998-05-11','lbioret@swiss-galaxy.com','visiteur'),('b19','Bunisset','Francis','fbunisset','$2y$10$A42rYWxDyJlsnF.qwVk0kOoGYcooO7RJtZF.I1I83soqRi.qas8DK','10 rue des Perles','93100','Montreuil','1987-10-21','fbunisset@swiss-galaxy.com','visiteur'),('b25','Bunisset','Denise','dbunisset','$2y$10$Su45zG3goC2KtWpTfRh1uuIP69IdyUe/7BudNS6balZTDhvMhQWD2','23 rue Manin','75019','paris','2010-12-05','dbunisset@swiss-galaxy.com','visiteur'),('b28','Cacheux','Bernard','bcacheux','$2y$10$Hp05RZkk.gAilj5Bwrnhz.ETzcygCC.yZhUCt3WE.MW6dd/LsfUhW','114 rue Blanche','75017','Paris','2009-11-12','bcacheux@swiss-galaxy.com','visiteur'),('b34','Cadic','Eric','ecadic','$2y$10$BMMjUTvphVrupYxADIbifOkhDXoipNPAWsfwxKsYFQ9A5SaUYz6I6','123 avenue de la République','75011','Paris','2008-09-23','ecadic@swiss-galaxy.com','visiteur'),('b4','Charoze','Catherine','ccharoze','$2y$10$jheES3IZ64c9.b48Isq9MeYJqcqXEzx4Scx4NBUZKlwdJ1DyKY70y','100 rue Petit','75019','Paris','2005-11-12','ccharoze@swiss-galaxy.com','visiteur'),('b50','Clepkens','Christophe','cclepkens','$2y$10$.clKa.8RoMPTwNqzC4TIHuMqSlqlDmUmQrvmwoWFqFEmZg8sWwBZ6','12 allée des Anges','93230','Romainville','2003-08-11','cclepkens@swiss-galaxy.com','visiteur'),('b59','Cottin','Vincenne','vcottin','$2y$10$8EklMlwtljFjfiavH9B9RehfYpS.H.14cGCGoMn4c/XwhfGeQnT52','36 rue Des Roches','93100','Monteuil','2001-11-18','vcottin@swiss-galaxy.com','visiteur'),('c14','Daburon','François','fdaburon','$2y$10$mRG6.viKPYehhlWSjaT0VOaEC7Sby9ENXREN4UmqizoHxXuKKir6a','13 rue de Chanzy','94000','Créteil','2002-02-11','fdaburon@swiss-galaxy.com','visiteur'),('c3','De','Philippe','pde','$2y$10$LJTw3RZg0rMLtbuC.rLBVe8qlAz2KuiVUC57pmyKV1ifKsnBIfLiy','13 rue Barthes','94000','Créteil','2010-12-14','pde@swiss-galaxy.com','visiteur'),('c54','Debelle','Michel','mdebelle','$2y$10$UtYNONRF1bp4P4UurjGD.O9kbopjfpEpC9P98ayIq7R/Dx3uY8Oi6','181 avenue Barbusse','93210','Rosny','2006-11-23','mdebelle@swiss-galaxy.com','visiteur'),('d13','Debelle','Jeanne','jdebelle','$2y$10$hmiA6Htyvl/j0ctL7014fuSo3DVPGv60/rm4T4or8dnC.8OxedbZa','134 allée des Joncs','44000','Nantes','2000-05-11','jdebelle@swiss-galaxy.com','visiteur'),('d51','Debroise','Michel','mdebroise','$2y$10$G8zUXed2xCppijEsjpdo9OzBJVNc5Qx3u2ce7CResw8WA6wD6jneS','2 Bld Jourdain','44000','Nantes','2001-04-17','mdebroise@swiss-galaxy.com','visiteur'),('e22','Desmarquest','Nathalie','ndesmarquest','$2y$10$RkXX3Ob4vguH.o9WtM/PrO8je5MFNLZEV8vjteuk/5a5ayv6AU2bm','14 Place d Arc','45000','Orléans','2005-11-12','ndesmarquest@swiss-galaxy.com','visiteur'),('e24','Desnost','Pierre','pdesnost','$2y$10$1UbMo.nCYnxw5TxPhqvrWOwqtT0H1pRgYjYFormmjckXpFayk6gCy','16 avenue des Cèdres','23200','Guéret','2001-02-05','pdesnost@swiss-galaxy.com','visiteur'),('e39','Dudouit','Frédéric','fdudouit','$2y$10$qy6BJHtELN5lvaKOmaL4AOKlWLsecV.q4gYK7g639513UWm4weZGy','18 rue de l église','23120','GrandBourg','2000-08-01','fdudouit@swiss-galaxy.com','visiteur'),('e49','Duncombe','Claude','cduncombe','$2y$10$I281mwljMxV3txN8ZSggXuAunxG1aRJyA.OfIloxqmnbskqSCCkIO','19 rue de la tour','23100','La souteraine','1987-10-10','cduncombe@swiss-galaxy.com','visiteur'),('e5','Enault-Pascreau','Céline','cenault','$2y$10$Idx.kxYKk/w0tZVqZca75OBL5V10GjG/02Z7Ao9X7XVCFhK.432zG','25 place de la gare','23200','Gueret','1995-09-01','cenault@swiss-galaxy.com','visiteur'),('e52','Eynde','Valérie','veynde','$2y$10$hXcNb5dOCBQv/ruP43q2HuiwixfROCyBlbBlMYo5gM4pYg3jGU33a','3 Grand Place','13015','Marseille','1999-11-01','veynde@swiss-galaxy.com','visiteur'),('f21','Finck','Jacques','jfinck','$2y$10$SoGm8diPUgKTZGtSNiWDeeh510m1q3xNpd7EAMwimzVyNYxCbWGyy','10 avenue du Prado','13002','Marseille','2001-11-10','jfinck@swiss-galaxy.com','visiteur'),('f39','Frémont','Fernande','ffremont','$2y$10$ch01frAgCYYFdTzgfcgjHeBmIZKE8gwH1uSMCdxhybz9VVfE9J2Wm','4 route de la mer','13012','Allauh','1998-10-01','ffremont@swiss-galaxy.com','visiteur'),('f4','Gest','Alain','agest','$2y$10$Uc2pJX1aEWuVh7hbh4Q.zev24DVzt.zN3MpaGnkrL9ETK2.FHQglK','30 avenue de la mer','13025','Berre','1985-11-01','agest@swiss-galaxy.com','visiteur');
/*!40000 ALTER TABLE `visiteur` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-12-21  1:00:01
