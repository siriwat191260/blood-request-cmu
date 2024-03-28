CREATE DATABASE  IF NOT EXISTS `blood_request` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `blood_request`;
-- MySQL dump 10.13  Distrib 8.0.33, for macos13 (arm64)
--
-- Host: 127.0.0.1    Database: blood_request
-- ------------------------------------------------------
-- Server version	8.0.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `BloodBagCharacteristic`
--

DROP TABLE IF EXISTS `BloodBagCharacteristic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `BloodBagCharacteristic` (
  `idBloodBagCharacter` int NOT NULL AUTO_INCREMENT,
  `isTransfusionSet` tinyint DEFAULT NULL,
  `needleStatus` tinyint DEFAULT NULL,
  `plasmaCharacteristicStatus` tinyint DEFAULT NULL,
  `isLeakagePosition` tinyint DEFAULT NULL,
  `leakagePosition` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `volumeOfBag` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `TransfusionVolume` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `idTR_Report` int DEFAULT NULL,
  PRIMARY KEY (`idBloodBagCharacter`),
  KEY `fk_BloodBagCharacteristic_TR_Report_idx` (`idTR_Report`),
  KEY `fk_BloodBagCharacteristic_NeedleStatus_idx` (`needleStatus`),
  KEY `fk_BloodBagCharacteristic_PlasmaCharacteristicStatus_idx` (`plasmaCharacteristicStatus`),
  CONSTRAINT `fk_BloodBagCharacteristic_NeedleStatus` FOREIGN KEY (`needleStatus`) REFERENCES `NeedleStatus` (`idNeedleStatus`),
  CONSTRAINT `fk_BloodBagCharacteristic_PlasmaCharacteristicStatus` FOREIGN KEY (`plasmaCharacteristicStatus`) REFERENCES `PlasmaCharacteristicStatus` (`idPlasmaCharacteristicStatus`),
  CONSTRAINT `fk_BloodBagCharacteristic_TR_Report` FOREIGN KEY (`idTR_Report`) REFERENCES `TR_Report` (`idTR_Report`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BloodBagCharacteristic`
--

LOCK TABLES `BloodBagCharacteristic` WRITE;
/*!40000 ALTER TABLE `BloodBagCharacteristic` DISABLE KEYS */;
/*!40000 ALTER TABLE `BloodBagCharacteristic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BloodTransfusionTest`
--

DROP TABLE IF EXISTS `BloodTransfusionTest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `BloodTransfusionTest` (
  `idBloodTransfusion_Test` int NOT NULL AUTO_INCREMENT,
  `isCorrectPatientName` tinyint DEFAULT NULL,
  `isWithin24hrsFever` tinyint DEFAULT NULL,
  `isCorrectBloodComponent` tinyint DEFAULT NULL,
  `isCorrectBloodTransfusionRec` tinyint DEFAULT NULL,
  `isCorrectBloodBagNumber` tinyint DEFAULT NULL,
  `isCorrectBloodGroupDonor` tinyint DEFAULT NULL,
  `isCorrectBloodGroupPatient` tinyint DEFAULT NULL,
  `idTR_Form` int DEFAULT NULL,
  PRIMARY KEY (`idBloodTransfusion_Test`),
  KEY `fk_BloodTransfusionTest_idx` (`idTR_Form`),
  KEY `fk_BloodTransfusionTest_StatusTestCorrect_idx` (`isCorrectPatientName`,`isCorrectBloodComponent`,`isCorrectBloodTransfusionRec`,`isCorrectBloodBagNumber`,`isCorrectBloodGroupDonor`,`isCorrectBloodGroupPatient`),
  KEY `fk_BloodTransfusionTest_BloodComponent_StatusTestCorrect_idx` (`isCorrectBloodComponent`),
  KEY `fk_BloodTransfusionTest_BTRec_StatusTestCorrect_idx` (`isCorrectBloodTransfusionRec`),
  KEY `fk_BloodTransfusionTest_BloodBagNumber_StatusTestCorrect_idx` (`isCorrectBloodBagNumber`),
  KEY `fk_BloodTransfusionTest_GroupDonor_StatusTestCorrect_idx` (`isCorrectBloodGroupDonor`),
  KEY `fk_BloodTransfusionTest_GroupPatient_StatusTestCorrect_idx` (`isCorrectBloodGroupPatient`),
  KEY `fk_BloodTransfusionTest_Within24hrs_StatusTestCorrect_idx` (`isWithin24hrsFever`),
  CONSTRAINT `fk_BloodTransfusionTest_isCorrectBloodBagNumber` FOREIGN KEY (`isCorrectBloodBagNumber`) REFERENCES `StatusTestCorrect` (`idStatusTestCorrect`),
  CONSTRAINT `fk_BloodTransfusionTest_isCorrectBloodComponent` FOREIGN KEY (`isCorrectBloodComponent`) REFERENCES `StatusTestCorrect` (`idStatusTestCorrect`),
  CONSTRAINT `fk_BloodTransfusionTest_isCorrectBloodGroupDonor` FOREIGN KEY (`isCorrectBloodGroupDonor`) REFERENCES `StatusTestCorrect` (`idStatusTestCorrect`),
  CONSTRAINT `fk_BloodTransfusionTest_isCorrectBloodGroupPatient` FOREIGN KEY (`isCorrectBloodGroupPatient`) REFERENCES `StatusTestCorrect` (`idStatusTestCorrect`),
  CONSTRAINT `fk_BloodTransfusionTest_isCorrectBloodTransfusionRec` FOREIGN KEY (`isCorrectBloodTransfusionRec`) REFERENCES `StatusTestCorrect` (`idStatusTestCorrect`),
  CONSTRAINT `fk_BloodTransfusionTest_StatusTestCorrect_isCorrectPatientName` FOREIGN KEY (`isCorrectPatientName`) REFERENCES `StatusTestCorrect` (`idStatusTestCorrect`),
  CONSTRAINT `fk_BloodTransfusionTest_StatusTestCorrect_isWithin24hrsFever` FOREIGN KEY (`isWithin24hrsFever`) REFERENCES `StatusTestFever` (`idStatusTestFever`),
  CONSTRAINT `fk_BloodTransfusionTest_TR_Form` FOREIGN KEY (`idTR_Form`) REFERENCES `TR_Form` (`idTR_Form`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BloodTransfusionTest`
--

LOCK TABLES `BloodTransfusionTest` WRITE;
/*!40000 ALTER TABLE `BloodTransfusionTest` DISABLE KEYS */;
/*!40000 ALTER TABLE `BloodTransfusionTest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DetailRecordIn24Hrs`
--

DROP TABLE IF EXISTS `DetailRecordIn24Hrs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `DetailRecordIn24Hrs` (
  `idDetailRecordIn24Hrs` int NOT NULL AUTO_INCREMENT,
  `bloodBagNumber` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `bloodComponent` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `bloodGroup` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `bloodRH` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `startTransfusion` datetime DEFAULT NULL,
  `endTransfusion` datetime DEFAULT NULL,
  `volume` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `isReaction` tinyint DEFAULT NULL,
  `idTR_Form` int DEFAULT NULL,
  PRIMARY KEY (`idDetailRecordIn24Hrs`),
  KEY `fk_DetailRecordIn24Hrs_TR_Form_idx` (`idTR_Form`),
  KEY `fk_DetailRecordIn24Hrs_StatusReaction_idx` (`isReaction`),
  CONSTRAINT `fk_DetailRecordIn24Hrs_StatusReaction` FOREIGN KEY (`isReaction`) REFERENCES `StatusReaction` (`idStatusReaction`),
  CONSTRAINT `fk_DetailRecordIn24Hrs_TR_Form` FOREIGN KEY (`idTR_Form`) REFERENCES `TR_Form` (`idTR_Form`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DetailRecordIn24Hrs`
--

LOCK TABLES `DetailRecordIn24Hrs` WRITE;
/*!40000 ALTER TABLE `DetailRecordIn24Hrs` DISABLE KEYS */;
/*!40000 ALTER TABLE `DetailRecordIn24Hrs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GramStainAndCulture`
--

DROP TABLE IF EXISTS `GramStainAndCulture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `GramStainAndCulture` (
  `idGramStainAndCulture` int NOT NULL AUTO_INCREMENT,
  `isSubmittingGramStain` tinyint DEFAULT NULL,
  `gramNegativeOrPositive` tinyint DEFAULT NULL,
  `resultGramStain` varchar(100) DEFAULT NULL,
  `toDateGram` datetime DEFAULT NULL,
  `isSubmittingCulture` tinyint DEFAULT NULL,
  `cultureNegativeOrPositive` tinyint DEFAULT NULL,
  `resultCulture` varchar(100) DEFAULT NULL,
  `toDateCulture` datetime DEFAULT NULL,
  `idTR_Report` int DEFAULT NULL,
  PRIMARY KEY (`idGramStainAndCulture`),
  KEY `fk_GramStainAndCulture_TR_Report_idx` (`idTR_Report`),
  CONSTRAINT `fk_GramStainAndCulture_TR_Report` FOREIGN KEY (`idTR_Report`) REFERENCES `TR_Report` (`idTR_Report`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `GramStainAndCulture`
--

LOCK TABLES `GramStainAndCulture` WRITE;
/*!40000 ALTER TABLE `GramStainAndCulture` DISABLE KEYS */;
/*!40000 ALTER TABLE `GramStainAndCulture` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Indicator`
--

DROP TABLE IF EXISTS `Indicator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Indicator` (
  `idIndicator` int NOT NULL AUTO_INCREMENT,
  `idIndicatorName` int DEFAULT NULL,
  `PreTransfusionSample` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `PostTransfusionSample` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `bloodBagNumber` varchar(100) COLLATE utf8mb3_bin DEFAULT NULL,
  `Remarks` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `idTR_Report` int DEFAULT NULL,
  PRIMARY KEY (`idIndicator`),
  KEY `fk_Indicatior_TR_Report_idx` (`idTR_Report`),
  CONSTRAINT `fk_Indicatior_TR_Report` FOREIGN KEY (`idTR_Report`) REFERENCES `TR_Report` (`idTR_Report`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Indicator`
--

LOCK TABLES `Indicator` WRITE;
/*!40000 ALTER TABLE `Indicator` DISABLE KEYS */;
/*!40000 ALTER TABLE `Indicator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NeedleStatus`
--

DROP TABLE IF EXISTS `NeedleStatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `NeedleStatus` (
  `idNeedleStatus` tinyint NOT NULL,
  `name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`idNeedleStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `NeedleStatus`
--

LOCK TABLES `NeedleStatus` WRITE;
/*!40000 ALTER TABLE `NeedleStatus` DISABLE KEYS */;
INSERT INTO `NeedleStatus` VALUES (1,'มี ปืดสนิท'),(2,'มี ปืดไม่สนิท'),(3,'ไม่มี');
/*!40000 ALTER TABLE `NeedleStatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PlasmaCharacteristicStatus`
--

DROP TABLE IF EXISTS `PlasmaCharacteristicStatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `PlasmaCharacteristicStatus` (
  `idPlasmaCharacteristicStatus` tinyint NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idPlasmaCharacteristicStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PlasmaCharacteristicStatus`
--

LOCK TABLES `PlasmaCharacteristicStatus` WRITE;
/*!40000 ALTER TABLE `PlasmaCharacteristicStatus` DISABLE KEYS */;
INSERT INTO `PlasmaCharacteristicStatus` VALUES (1,'มี Fabrin'),(2,'ใส'),(3,'ขุ่น');
/*!40000 ALTER TABLE `PlasmaCharacteristicStatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ReactionCategory`
--

DROP TABLE IF EXISTS `ReactionCategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ReactionCategory` (
  `idReactionCategory` int NOT NULL,
  `nameReactionCategory` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  PRIMARY KEY (`idReactionCategory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ReactionCategory`
--

LOCK TABLES `ReactionCategory` WRITE;
/*!40000 ALTER TABLE `ReactionCategory` DISABLE KEYS */;
INSERT INTO `ReactionCategory` VALUES (1,'Allergic / Anaphylaxis'),(2,'TRALI'),(3,'TACO'),(4,'Septic Transfusion Reaction'),(5,'Hemolytic Transfusion Reaction');
/*!40000 ALTER TABLE `ReactionCategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SignAndSymptomsReactionCategory`
--

DROP TABLE IF EXISTS `SignAndSymptomsReactionCategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `SignAndSymptomsReactionCategory` (
  `idSignAndSymptomsReactionCategory` int NOT NULL AUTO_INCREMENT,
  `idSignsAndSymtomsName` int DEFAULT NULL,
  `idReactionCategory` int DEFAULT NULL,
  PRIMARY KEY (`idSignAndSymptomsReactionCategory`),
  KEY `fk_SignAndSymptomsReactionCategory_SignAndSymptomsName_idx` (`idSignsAndSymtomsName`),
  KEY `fk_SignAndSymptomsReactionCategory_ReactionCategory_idx` (`idReactionCategory`),
  CONSTRAINT `fk_SignAndSymptomsReactionCategory_ReactionCategory` FOREIGN KEY (`idReactionCategory`) REFERENCES `ReactionCategory` (`idReactionCategory`),
  CONSTRAINT `fk_SignAndSymptomsReactionCategory_SignAndSymptomsName` FOREIGN KEY (`idSignsAndSymtomsName`) REFERENCES `SignsAndSymptomsName` (`idSignsAndSymtomsName`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SignAndSymptomsReactionCategory`
--

LOCK TABLES `SignAndSymptomsReactionCategory` WRITE;
/*!40000 ALTER TABLE `SignAndSymptomsReactionCategory` DISABLE KEYS */;
/*!40000 ALTER TABLE `SignAndSymptomsReactionCategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SignsAndSymptoms`
--

DROP TABLE IF EXISTS `SignsAndSymptoms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `SignsAndSymptoms` (
  `idSignsAndSymptoms` int NOT NULL AUTO_INCREMENT,
  `idSignsAndSymptomsName` int DEFAULT NULL,
  `Other` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `idTR_Form` int DEFAULT NULL,
  PRIMARY KEY (`idSignsAndSymptoms`),
  KEY `fk_SignsAndSymptoms_TR_Form_idx` (`idTR_Form`),
  KEY `fk_SignsAndSymptoms_SignsAndSymptomsName_idx` (`idSignsAndSymptomsName`),
  CONSTRAINT `fk_SignsAndSymptoms_SignsAndSymptomsName` FOREIGN KEY (`idSignsAndSymptomsName`) REFERENCES `SignsAndSymptomsName` (`idSignsAndSymtomsName`),
  CONSTRAINT `fk_SignsAndSymptoms_TR_Form` FOREIGN KEY (`idTR_Form`) REFERENCES `TR_Form` (`idTR_Form`)
) ENGINE=InnoDB AUTO_INCREMENT=680 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SignsAndSymptoms`
--

LOCK TABLES `SignsAndSymptoms` WRITE;
/*!40000 ALTER TABLE `SignsAndSymptoms` DISABLE KEYS */;
/*!40000 ALTER TABLE `SignsAndSymptoms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SignsAndSymptomsName`
--

DROP TABLE IF EXISTS `SignsAndSymptomsName`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `SignsAndSymptomsName` (
  `idSignsAndSymtomsName` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idSignsAndSymtomsName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SignsAndSymptomsName`
--

LOCK TABLES `SignsAndSymptomsName` WRITE;
/*!40000 ALTER TABLE `SignsAndSymptomsName` DISABLE KEYS */;
INSERT INTO `SignsAndSymptomsName` VALUES (1,'Abdominal pain / creep [1,4]'),(2,'Dyspnea [1,2,3,4]'),(3,'Nausea / Vomitting [1,4]'),(4,'Angioedema [1]'),(5,'Edema-pulmonary [2,3]'),(6,'Oliguria [4,5]'),(7,'Anxiety [1]'),(8,'Edema-pedal [3]'),(9,'Orthopnea [3]'),(10,'Arrhythmia [1]'),(11,'Erythema [1]'),(12,'Pain at infusion site [4]'),(13,'Back pain [4,5]'),(14,'Fever [2,4,5]'),(15,'Pink,Red,Dark Urine [5]'),(16,'Cardiac arrest [1]'),(17,'Fushing [1]'),(18,'Pruritis [1]'),(19,'Chest pain [4]'),(20,'Headache [3,4]'),(21,'Shock [1,4]'),(22,'Chest tightness [1,3]'),(23,'Hoarseness/Stridor [1]'),(24,'Shortness of breath [1,2,3,4]'),(25,'Chills/Rigors [4,5]'),(26,'Hypertension [2,3]'),(27,'Substernal pain [1]'),(28,'Cough [3,4]'),(29,'Hypotension [1,2,4,5]'),(30,'Tachycardia [1,2,3,4]'),(31,'Cyanosis [1,2,3]'),(32,'Hypoxemia [2,3]'),(33,'Tachypnea [2,3]'),(34,'Diarrhea [1]'),(35,'Impending doom [1]'),(36,'Urticaria [1]'),(37,'DIC [4,5]'),(38,'Jugular venous distension [3]'),(39,'Wheezing [1,4]'),(40,'Loss of consciousness [1]'),(41,'Other');
/*!40000 ALTER TABLE `SignsAndSymptomsName` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `StatusReaction`
--

DROP TABLE IF EXISTS `StatusReaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `StatusReaction` (
  `idStatusReaction` tinyint NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idStatusReaction`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `StatusReaction`
--

LOCK TABLES `StatusReaction` WRITE;
/*!40000 ALTER TABLE `StatusReaction` DISABLE KEYS */;
INSERT INTO `StatusReaction` VALUES (0,'ไม่มีปฏิกิริยา'),(1,'มีปฏิกิริยา');
/*!40000 ALTER TABLE `StatusReaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `StatusTestCorrect`
--

DROP TABLE IF EXISTS `StatusTestCorrect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `StatusTestCorrect` (
  `idStatusTestCorrect` tinyint NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  PRIMARY KEY (`idStatusTestCorrect`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `StatusTestCorrect`
--

LOCK TABLES `StatusTestCorrect` WRITE;
/*!40000 ALTER TABLE `StatusTestCorrect` DISABLE KEYS */;
INSERT INTO `StatusTestCorrect` VALUES (0,'ไม่ถูกต้อง'),(1,'ถูกต้อง');
/*!40000 ALTER TABLE `StatusTestCorrect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `StatusTestFever`
--

DROP TABLE IF EXISTS `StatusTestFever`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `StatusTestFever` (
  `idStatusTestFever` tinyint NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idStatusTestFever`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `StatusTestFever`
--

LOCK TABLES `StatusTestFever` WRITE;
/*!40000 ALTER TABLE `StatusTestFever` DISABLE KEYS */;
INSERT INTO `StatusTestFever` VALUES (0,'ไม่มีไข้'),(1,'มีไข้');
/*!40000 ALTER TABLE `StatusTestFever` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SubmittingTest`
--

DROP TABLE IF EXISTS `SubmittingTest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `SubmittingTest` (
  `idSubmittingTest` int NOT NULL AUTO_INCREMENT,
  `isBloodSample` tinyint DEFAULT NULL,
  `isBloodBagReaction` tinyint DEFAULT NULL,
  `nurseName` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `nurseDateTime` datetime DEFAULT NULL,
  `physicianName` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `physicianDateTime` datetime DEFAULT NULL,
  `idTR_Form` int DEFAULT NULL,
  PRIMARY KEY (`idSubmittingTest`),
  KEY `fk_SendingTest_TR_Form_idx` (`idTR_Form`),
  CONSTRAINT `fk_SubmittingTest_TR_Form` FOREIGN KEY (`idTR_Form`) REFERENCES `TR_Form` (`idTR_Form`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SubmittingTest`
--

LOCK TABLES `SubmittingTest` WRITE;
/*!40000 ALTER TABLE `SubmittingTest` DISABLE KEYS */;
/*!40000 ALTER TABLE `SubmittingTest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TR_Form`
--

DROP TABLE IF EXISTS `TR_Form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `TR_Form` (
  `idTR_Form` int NOT NULL AUTO_INCREMENT,
  `title` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `firstName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `lastName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `HN` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `TXN` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `pt_type` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `createdDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `ward_code` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `ward` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `phoneNumber` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `diagnosis` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `primaryPhysicianName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `bloodGroup_Patient` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Rh_Patient` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `blood_component` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `bloodGroup_Donor` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Rh_Donor` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `bloodBagNumber` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `volume` float DEFAULT NULL,
  `medicationHistory` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `isReactionHistory` tinyint DEFAULT NULL,
  `reactionCategory` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `status` int DEFAULT NULL,
  PRIMARY KEY (`idTR_Form`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TR_Form`
--

LOCK TABLES `TR_Form` WRITE;
/*!40000 ALTER TABLE `TR_Form` DISABLE KEYS */;
/*!40000 ALTER TABLE `TR_Form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TR_Report`
--

DROP TABLE IF EXISTS `TR_Report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `TR_Report` (
  `idTR_Report` int NOT NULL AUTO_INCREMENT,
  `title` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `firstName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `lastName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `HN` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `createdDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `ward` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `bloodGroup_Patient` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `blood_component` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `bloodGroup_Donor` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `bloodBagNumber` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `primaryPhysicianName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `witness` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `interpretation` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `testedBy` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `testedDateTime` datetime DEFAULT NULL,
  `reportedBy` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `reportedDateTime` datetime DEFAULT NULL,
  `nurse` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `status` int DEFAULT NULL,
  PRIMARY KEY (`idTR_Report`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TR_Report`
--

LOCK TABLES `TR_Report` WRITE;
/*!40000 ALTER TABLE `TR_Report` DISABLE KEYS */;
/*!40000 ALTER TABLE `TR_Report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TransfusionMedicalDirectorReview`
--

DROP TABLE IF EXISTS `TransfusionMedicalDirectorReview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `TransfusionMedicalDirectorReview` (
  `idTransfusionMedicalDirectorReview` int NOT NULL AUTO_INCREMENT,
  `reaction` varchar(100) DEFAULT NULL,
  `caseDefinitionCriteria` varchar(100) DEFAULT NULL,
  `severity` varchar(100) DEFAULT NULL,
  `imputability` varchar(100) DEFAULT NULL,
  `approvedBy` varchar(100) DEFAULT NULL,
  `approvedDateTime` datetime DEFAULT NULL,
  `idTR_Report` int DEFAULT NULL,
  `other` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idTransfusionMedicalDirectorReview`),
  KEY `fk_TransfusionMedicalDirectorReview_TR_Report_idx` (`idTR_Report`),
  CONSTRAINT `fk_TransfusionMedicalDirectorReview_TR_Report` FOREIGN KEY (`idTR_Report`) REFERENCES `TR_Report` (`idTR_Report`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TransfusionMedicalDirectorReview`
--

LOCK TABLES `TransfusionMedicalDirectorReview` WRITE;
/*!40000 ALTER TABLE `TransfusionMedicalDirectorReview` DISABLE KEYS */;
/*!40000 ALTER TABLE `TransfusionMedicalDirectorReview` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VitalSigns`
--

DROP TABLE IF EXISTS `VitalSigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `VitalSigns` (
  `idVitalSigns` int NOT NULL AUTO_INCREMENT,
  `beforeReactionTime` datetime DEFAULT NULL,
  `beforeReactionTemp` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `beforeReactionBP` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `beforeReactionPulse` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `afterReactionTime` datetime DEFAULT NULL,
  `afterReactionTemp` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `afterReactionBP` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `afterReactionPulse` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `idTR_Form` int DEFAULT NULL,
  PRIMARY KEY (`idVitalSigns`),
  KEY `fk_VitualSigns_TR_Form_idx` (`idTR_Form`),
  CONSTRAINT `fk_VitualSigns_TR_Form` FOREIGN KEY (`idTR_Form`) REFERENCES `TR_Form` (`idTR_Form`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VitalSigns`
--

LOCK TABLES `VitalSigns` WRITE;
/*!40000 ALTER TABLE `VitalSigns` DISABLE KEYS */;
/*!40000 ALTER TABLE `VitalSigns` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-24 12:24:59
