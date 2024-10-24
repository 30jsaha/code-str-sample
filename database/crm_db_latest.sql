-- MariaDB dump 10.19  Distrib 10.4.22-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: crm
-- ------------------------------------------------------
-- Server version	10.4.22-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL DEFAULT 1001,
  `employee_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `attendance_month` int(11) NOT NULL,
  `attendance_year` int(11) NOT NULL,
  `reporting_time` time NOT NULL,
  `log_off_time` time DEFAULT NULL,
  `working_hours` varchar(50) DEFAULT NULL,
  `early_log_off_reason` varchar(250) DEFAULT NULL,
  `early_log_off_mints` varchar(50) DEFAULT NULL,
  `is_late_entry` int(11) NOT NULL DEFAULT 0 COMMENT '0 = NO, 1 = YEs',
  `late_mints` varchar(50) DEFAULT NULL,
  `late_entry_reason` varchar(200) DEFAULT NULL,
  `admin_approval_for_late_entry` int(11) NOT NULL DEFAULT 0 COMMENT '0 = NOT APPROVED, 1 = APPROVED',
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `active` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Cycle Active,\r\n2 = Cycle closed',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance`
--

LOCK TABLES `attendance` WRITE;
/*!40000 ALTER TABLE `attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(20) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `pin_code` varchar(10) DEFAULT NULL,
  `website_name` varchar(50) DEFAULT NULL,
  `company_name` varchar(50) NOT NULL,
  `company_logo` varchar(100) DEFAULT NULL COMMENT 'Document',
  `company_email` varchar(50) NOT NULL,
  `company_email_password` varchar(100) DEFAULT NULL,
  `company_mobile` varchar(15) NOT NULL,
  `company_phone` varchar(15) DEFAULT NULL,
  `company_address` varchar(200) DEFAULT NULL,
  `company_city` varchar(50) DEFAULT NULL,
  `company_district` varchar(50) DEFAULT NULL,
  `company_state` varchar(20) DEFAULT NULL,
  `company_pincode` varchar(10) DEFAULT NULL,
  `gstin_no` varchar(20) DEFAULT NULL,
  `tan` varchar(20) DEFAULT NULL,
  `pan` varchar(20) DEFAULT NULL,
  `joined_date` date NOT NULL,
  `validity_period` int(11) NOT NULL COMMENT 'In months',
  `expiry_date` date NOT NULL,
  `total_charge` double NOT NULL COMMENT 'Regiustration charge',
  `rental_type` int(11) NOT NULL DEFAULT 0 COMMENT '0 - Monthly , 1 - Yearly',
  `rental_charge` double NOT NULL,
  `paid_amount` double NOT NULL,
  `due_amount` double NOT NULL DEFAULT 0,
  `payment_status` int(11) NOT NULL DEFAULT 0 COMMENT '0 - Due, 1 - Paid',
  `sms_enabled` int(11) NOT NULL DEFAULT 0 COMMENT '1 - Enable, 0 - Disable',
  `google_api_enable` int(11) NOT NULL DEFAULT 0 COMMENT '1 - Enable, 0 - Disable',
  `whatsapp_integration_enable` int(11) NOT NULL DEFAULT 0 COMMENT '1 - Enable, 0 - Disable',
  `live_location_tracking_enable` int(11) NOT NULL DEFAULT 0 COMMENT '1 - Enable, 0 - Disable',
  `multi_language_support_enable` int(11) NOT NULL DEFAULT 0 COMMENT '1 - Enable, 0 - Disable',
  `sms_package_code` varchar(50) DEFAULT NULL,
  `registration_charge` double NOT NULL DEFAULT 0,
  `sms_recharge_date` date DEFAULT NULL,
  `sms_validity_period` int(11) NOT NULL DEFAULT 0 COMMENT 'In Month',
  `sms_gateway_type` int(11) NOT NULL DEFAULT 1 COMMENT '1 - Saha CyberTech SMS API, 2 - Personal SMS api',
  `sms_gateway` varchar(10) DEFAULT NULL,
  `sms_endpoint` varchar(250) DEFAULT NULL,
  `sms_sid` varchar(10) DEFAULT NULL,
  `send_auto_sms` int(11) NOT NULL DEFAULT 0 COMMENT '0 - Manual, 1 - Automatic',
  `total_sms` int(11) NOT NULL DEFAULT 0,
  `sms_sent` int(11) NOT NULL DEFAULT 0,
  `sms_balance` int(11) NOT NULL DEFAULT 0,
  `sms_sid_enable` int(11) NOT NULL DEFAULT 0 COMMENT '0 - Disable, 1 - Enable',
  `max_product` int(11) NOT NULL DEFAULT 0,
  `max_user` int(11) NOT NULL DEFAULT 0,
  `max_manager` int(11) NOT NULL DEFAULT 0,
  `max_category` int(11) NOT NULL DEFAULT 0,
  `max_banner_content` int(11) NOT NULL,
  `max_special_menu` int(11) NOT NULL DEFAULT 4,
  `product_added` int(11) NOT NULL DEFAULT 0,
  `user_added` int(11) NOT NULL DEFAULT 0 COMMENT 'General Employee',
  `manager_added` int(11) NOT NULL DEFAULT 0,
  `category_added` int(11) NOT NULL DEFAULT 0,
  `feature_plan` int(11) NOT NULL COMMENT '7 - Basic, 2 - Standard, 1 - Premium, 3 - Custom',
  `project_service_type` int(11) NOT NULL COMMENT '1 - Grocery, 2 - Texttile, 3 - Home Appliances, 4 - All',
  `application_server` int(11) DEFAULT 1 COMMENT '1 - Saha CyberTech Server, 2 - Company Own server, 3 - 3rdf Party server',
  `mac_id` varchar(20) DEFAULT NULL COMMENT 'MAC ID of the server',
  `ip` varchar(15) DEFAULT NULL COMMENT 'ip of the server',
  `site_url` varchar(200) DEFAULT NULL,
  `trade_license` varchar(100) DEFAULT NULL COMMENT 'Document',
  `gstin_certificate` varchar(100) DEFAULT NULL COMMENT 'Document',
  `pan_card` varchar(100) DEFAULT NULL COMMENT 'Document',
  `company_director_list` varchar(100) DEFAULT NULL COMMENT 'Document',
  `company_master_data` varchar(100) DEFAULT NULL COMMENT 'Document',
  `company_type` int(11) NOT NULL DEFAULT 1 COMMENT '1 - PVT, 2 - LTD, 3 - LLP, 4 - Partnership, 5 - Propreitary, 6 - Others',
  `cin_document` varchar(100) DEFAULT NULL COMMENT 'Document',
  `moa_aoa` varchar(100) DEFAULT NULL COMMENT 'Document',
  `partnership_deed` varchar(100) DEFAULT NULL COMMENT 'Document',
  `company_photograph_1` varchar(100) DEFAULT NULL COMMENT 'Document. Outside',
  `company_photograph_2` varchar(100) DEFAULT NULL COMMENT 'Document. Inside',
  `company_photograph_3` varchar(100) DEFAULT NULL COMMENT 'Document. Inside',
  `corporate_mail_id` varchar(100) DEFAULT NULL,
  `account_number` varchar(30) DEFAULT NULL,
  `account_holder_name` varchar(100) DEFAULT NULL,
  `bank_name` varchar(150) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `branch_address` varchar(200) DEFAULT NULL,
  `cancelled_cheque` varchar(100) DEFAULT NULL COMMENT 'Document',
  `active` int(11) NOT NULL DEFAULT 1,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (1001,NULL,'Jyotirmoy Saha','8777748122','info@jsaha.in','Barrackpore, West Bengal','kolkata','North 24 Parganas','West Bengal','700123','https://jsaha.in/','J Saha Enterprise','logo.png','info@jsaha.in','','8777748122',NULL,NULL,'kolkata','North 24 Parganas','West Bengal',NULL,NULL,NULL,NULL,'2022-01-01',12,'2022-12-31',50000,1,4000,6000,44000,0,1,1,1,1,1,NULL,15000,'2022-01-01',12,1,NULL,NULL,NULL,1,0,0,0,0,0,0,0,0,0,4,0,0,0,0,1,4,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'A','2024-10-21 14:43:20');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `department_name` varchar(100) DEFAULT NULL,
  `added_by` int(11) NOT NULL DEFAULT 0 COMMENT 'Current User ID',
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,1001,'Sales',3,'A','2024-01-02 17:48:57'),(2,1001,'Back-End',3,'A','2024-01-02 17:49:10'),(3,1001,'HR',3,'A','2024-01-02 17:49:17'),(4,1001,'IT ',6,'A','2024-01-12 18:38:09'),(5,1001,'Domestic Sales',6,'A','2024-01-12 18:38:26'),(6,1001,'International Sales',6,'A','2024-01-12 18:38:41'),(7,1001,'E-commerce',6,'A','2024-02-19 19:38:55');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `designations`
--

DROP TABLE IF EXISTS `designations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `designations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL DEFAULT 1001,
  `designation_title` varchar(150) NOT NULL COMMENT 'Name of the Designatioon',
  `responsibilities` mediumtext DEFAULT NULL COMMENT 'Text Input',
  `experience_required` varchar(20) DEFAULT NULL COMMENT 'Text Input\r\nFormat = 00 Years 00 Months',
  `added_by` int(11) NOT NULL COMMENT 'Current User ID',
  `active` int(11) NOT NULL DEFAULT 1 COMMENT '0 = Inactive 1 = Active',
  `status` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A = Active / Available D = Deleted',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_update_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `designations`
--

LOCK TABLES `designations` WRITE;
/*!40000 ALTER TABLE `designations` DISABLE KEYS */;
INSERT INTO `designations` VALUES (1,1001,'Developer',NULL,'2 Years',1,1,'D','2023-11-20 14:01:10','2023-11-20 14:01:10'),(2,1001,'Designer',NULL,NULL,1,1,'D','2023-11-20 14:03:11','2023-11-20 14:03:11'),(3,1001,'Sales',NULL,NULL,1,1,'D','2023-11-20 14:06:08','2023-11-20 14:06:08'),(7,1001,'HR Manager',NULL,NULL,3,1,'A','2024-01-02 17:50:03','2024-01-02 17:50:03'),(8,1001,'Sales Manager',NULL,NULL,6,1,'A','2024-01-03 13:17:00','2024-01-03 13:17:00'),(9,1001,'Operations Head',NULL,NULL,6,1,'A','2024-01-12 18:40:14','2024-01-12 18:40:14'),(10,1001,'Manager - IT & Admin',NULL,NULL,6,1,'A','2024-01-12 18:40:45','2024-01-12 18:40:45'),(11,1001,'Sr IT Technician ',NULL,NULL,6,1,'A','2024-01-12 18:41:23','2024-01-12 18:41:23'),(12,1001,'Business Development Manager ',NULL,NULL,6,1,'A','2024-01-12 18:41:58','2024-01-12 18:41:58'),(13,1001,'BDM & Talent Advisor',NULL,NULL,6,1,'A','2024-01-12 18:42:50','2024-01-12 18:42:50'),(14,1001,'Recruiter',NULL,NULL,6,1,'A','2024-01-12 18:43:17','2024-01-12 18:43:17'),(15,1001,'Tele calling Executive ',NULL,NULL,6,1,'A','2024-01-12 18:43:50','2024-01-12 18:43:50'),(16,1001,'BDE Domestic Sales',NULL,NULL,6,1,'A','2024-01-12 18:44:10','2024-01-12 18:44:10'),(17,1001,'Web Consultant',NULL,NULL,6,1,'A','2024-01-12 18:44:28','2024-01-12 18:44:28'),(18,1001,'Sr. Web Consultant',NULL,NULL,6,1,'A','2024-01-12 18:44:44','2024-01-12 18:44:44'),(19,1001,'Full Stack Developer','Full Stack Development','3',6,1,'A','2024-01-30 17:24:34','2024-01-30 17:24:34'),(20,1001,'Web Developer',NULL,'2',6,1,'A','2024-01-30 18:02:00','2024-01-30 18:02:00'),(21,1001,'Graphics Designer',NULL,'2',6,1,'A','2024-01-30 18:02:32','2024-01-30 18:02:32'),(22,1001,'IT - Head',NULL,'10',6,1,'A','2024-01-31 18:12:58','2024-01-31 18:12:58'),(23,1001,'Sales & Marketing Executive',NULL,'3',6,1,'A','2024-02-19 19:39:30','2024-02-19 19:39:30'),(24,1001,'Ads Manager',NULL,NULL,6,1,'A','2024-02-20 20:23:22','2024-02-20 20:23:22'),(25,1001,'Voice & Accent Trainer','Training','3',6,1,'A','2024-02-26 17:52:36','2024-02-26 17:52:36'),(26,1001,'Sr HR Recruiter','Recruitment','2',6,1,'A','2024-02-26 17:53:21','2024-02-26 17:53:21');
/*!40000 ALTER TABLE `designations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domestic_clients_actions`
--

DROP TABLE IF EXISTS `domestic_clients_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domestic_clients_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `dc_id` int(11) NOT NULL,
  `action_user_id` int(11) NOT NULL,
  `changed_status` int(11) NOT NULL,
  `previous_status` int(11) NOT NULL DEFAULT 0,
  `creation_date` datetime NOT NULL,
  `infotxt` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domestic_clients_actions`
--

LOCK TABLES `domestic_clients_actions` WRITE;
/*!40000 ALTER TABLE `domestic_clients_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `domestic_clients_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domestic_clients_data`
--

DROP TABLE IF EXISTS `domestic_clients_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domestic_clients_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `business_phone_no` varchar(15) DEFAULT NULL,
  `business_details` longtext DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domestic_clients_data`
--

LOCK TABLES `domestic_clients_data` WRITE;
/*!40000 ALTER TABLE `domestic_clients_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `domestic_clients_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_details`
--

DROP TABLE IF EXISTS `employee_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL DEFAULT 1001,
  `employee_name` varchar(100) NOT NULL,
  `employee_mobile` varchar(15) DEFAULT NULL,
  `employee_email` varchar(30) DEFAULT NULL,
  `employee_date_of_birth` date DEFAULT NULL,
  `employee_father_name` varchar(100) DEFAULT NULL,
  `employee_mother_name` varchar(100) DEFAULT NULL,
  `employee_blood_group` varchar(10) DEFAULT NULL,
  `employee_designation_id` int(11) NOT NULL,
  `employee_date_of_joinning` date DEFAULT NULL,
  `employee_experience_duration` varchar(20) DEFAULT NULL COMMENT 'Format = 00 Years 00 Months',
  `employee_payroll` int(11) NOT NULL DEFAULT 1 COMMENT '1 = company payroll\r\n2 = contact',
  `employee_grade` int(11) NOT NULL DEFAULT 4,
  `employee_id` varchar(10) DEFAULT NULL,
  `department_id` int(11) NOT NULL DEFAULT 0,
  `salary_amount` varchar(10) DEFAULT NULL,
  `webmail_address` varchar(100) DEFAULT NULL,
  `current_address` varchar(200) DEFAULT NULL,
  `permanent_address` varchar(200) DEFAULT NULL,
  `emergency_contact_person_name` varchar(100) DEFAULT NULL,
  `emergency_contact_person_mobile_number` varchar(15) DEFAULT NULL,
  `aadhaar_number` varchar(20) DEFAULT NULL,
  `pan_number` varchar(20) DEFAULT NULL,
  `salary_account_number` varchar(50) DEFAULT NULL,
  `salary_account_ifsc_code` varchar(20) DEFAULT NULL,
  `uan_number` varchar(20) DEFAULT NULL,
  `esic_ip_number` varchar(50) DEFAULT NULL,
  `remarks` mediumtext DEFAULT NULL,
  `remark_by` int(11) DEFAULT NULL COMMENT 'Current User ID',
  `employee_added_by` int(11) NOT NULL COMMENT 'Current User ID',
  `last_working_day` date DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT 1 COMMENT '0 = Inactive\r\n1 = Active\r\n2 = RESIGNED\r\n3 = ABSCONDED\r\n4 = SERVING_NOTICE',
  `status` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A = Active / Available\r\nD = Deleted',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_update_date` datetime NOT NULL DEFAULT current_timestamp(),
  `reporting_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_details`
--

LOCK TABLES `employee_details` WRITE;
/*!40000 ALTER TABLE `employee_details` DISABLE KEYS */;
INSERT INTO `employee_details` VALUES (2,1001,'Admin','9073327562','admin@email.com','1988-02-08','','','',7,'2023-04-05','',1,4,'800145',3,'','','','','','','','','','','','','',3,3,NULL,1,'A','2024-01-03 12:16:58','2024-10-24 21:13:23',12),(6,1001,'mx','8961789009','mx@email.com','1987-12-22','','','',13,'2023-08-23','8',1,4,'800187',3,'','','','','','','','','','','','','',3,6,'0000-00-00',1,'A','2024-01-12 19:03:34','2024-10-24 21:16:24',11),(9,1001,'x','7003985385','x@email.com',NULL,'','','',19,'2023-06-14','',1,4,'800168',2,'','','','','','','','','','','','','',3,6,NULL,1,'A','2024-01-30 18:00:28','2024-10-24 21:14:32',12),(11,1001,'Jyotirmoy Saha','6291248038','30jsaha@gmail.com','2001-08-30','','','',20,'2023-07-28','',1,4,'800184',2,'','','','','','','','','','','','','',3,6,NULL,1,'A','2024-01-30 18:24:14','2024-10-24 21:15:31',11);
/*!40000 ALTER TABLE `employee_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_reporting_manager`
--

DROP TABLE IF EXISTS `employee_reporting_manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_reporting_manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `reporting_manager_user_id` int(11) NOT NULL,
  `assigned_by_user_id` int(11) NOT NULL COMMENT 'Current User ID',
  `assign_date` date DEFAULT NULL,
  `dismiss_date` date DEFAULT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A = Active, D = Deactive',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_reporting_manager`
--

LOCK TABLES `employee_reporting_manager` WRITE;
/*!40000 ALTER TABLE `employee_reporting_manager` DISABLE KEYS */;
INSERT INTO `employee_reporting_manager` VALUES (2,1001,2,3,3,'2024-01-03',NULL,'A','2024-01-03 12:16:58'),(6,1001,6,6,6,'2024-01-12',NULL,'A','2024-01-12 19:03:34'),(7,1001,7,3,3,'2024-01-30',NULL,'A','2024-01-30 16:28:08'),(8,1001,8,6,6,'2024-01-30',NULL,'A','2024-01-30 16:46:45'),(9,1001,9,3,6,'2024-01-30',NULL,'A','2024-01-30 18:00:28'),(10,1001,10,3,6,'2024-01-30',NULL,'A','2024-01-30 18:14:00'),(11,1001,11,3,6,'2024-01-30',NULL,'A','2024-01-30 18:24:14'),(12,1001,12,3,6,'2024-01-30',NULL,'A','2024-01-30 18:31:30'),(13,1001,13,16,6,'2024-01-31',NULL,'A','2024-01-31 13:17:38'),(14,1001,14,16,6,'2024-01-31',NULL,'A','2024-01-31 13:40:38'),(15,1001,15,3,6,'2024-01-31',NULL,'A','2024-01-31 14:05:37'),(16,1001,16,19,6,'2024-01-31',NULL,'A','2024-01-31 17:33:48'),(17,1001,17,19,6,'2024-01-31',NULL,'A','2024-01-31 17:47:57'),(18,1001,18,19,6,'2024-01-31',NULL,'A','2024-01-31 17:58:53'),(19,1001,19,19,6,'2024-01-31',NULL,'A','2024-01-31 18:11:37'),(20,1001,20,3,6,'2024-01-31',NULL,'A','2024-01-31 18:28:46'),(21,1001,21,10,6,'2024-02-12',NULL,'A','2024-02-12 12:06:09'),(22,1001,22,10,6,'2024-02-12',NULL,'A','2024-02-12 14:07:56'),(23,1001,23,19,6,'2024-02-19',NULL,'A','2024-02-19 19:35:48'),(24,1001,24,3,6,'2024-02-19',NULL,'A','2024-02-19 19:47:37'),(25,1001,25,19,6,'2024-02-20',NULL,'A','2024-02-20 20:23:01'),(26,1001,26,3,6,'2024-02-20',NULL,'A','2024-02-20 20:27:04'),(27,1001,27,19,6,'2024-02-26',NULL,'A','2024-02-26 17:36:49'),(28,1001,28,19,6,'2024-02-26',NULL,'A','2024-02-26 17:46:12'),(29,1001,29,16,6,'2024-02-26',NULL,'A','2024-02-26 18:08:52'),(30,1001,30,6,6,'2024-02-27',NULL,'A','2024-02-27 17:49:47'),(31,1001,31,19,6,'2024-03-04',NULL,'A','2024-03-04 19:53:18'),(32,1001,32,19,6,'2024-03-04',NULL,'A','2024-03-04 20:01:39'),(33,1001,33,19,6,'2024-03-14',NULL,'A','2024-03-14 13:24:16'),(34,1001,34,19,6,'2024-03-14',NULL,'A','2024-03-14 13:33:23'),(35,1001,35,16,6,'2024-03-14',NULL,'A','2024-03-14 13:45:39'),(36,1001,36,16,6,'2024-03-15',NULL,'A','2024-03-15 15:11:36'),(37,1001,37,16,6,'2024-03-15',NULL,'A','2024-03-15 16:24:52'),(38,1001,38,16,6,'2024-03-15',NULL,'A','2024-03-15 16:46:19');
/*!40000 ALTER TABLE `employee_reporting_manager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `holidays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_active_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holidays`
--

LOCK TABLES `holidays` WRITE;
/*!40000 ALTER TABLE `holidays` DISABLE KEYS */;
/*!40000 ALTER TABLE `holidays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL DEFAULT 1001,
  `invoice_number` varchar(20) DEFAULT NULL,
  `invoice_count_number` int(11) NOT NULL DEFAULT 1,
  `invoice_date` date DEFAULT NULL,
  `invoice_month` int(11) NOT NULL DEFAULT 0 COMMENT 'Current Month',
  `invoice_year` int(11) NOT NULL DEFAULT 0 COMMENT 'Current Year',
  `company_address` varchar(200) DEFAULT NULL,
  `company_gstin_number` varchar(20) DEFAULT NULL,
  `company_bank_account_no` varchar(20) DEFAULT NULL,
  `company_ifsc_code` varchar(15) DEFAULT NULL,
  `mode_of_payment` int(11) NOT NULL DEFAULT 1 COMMENT '1 = NEFT\r\n2 = CHEQUE\r\n3 = CASH\r\n4 = UPI',
  `billing_name` varchar(100) DEFAULT NULL,
  `billing_address` varchar(250) DEFAULT NULL,
  `billing_gstin` varchar(20) DEFAULT NULL,
  `billing_email` varchar(80) DEFAULT NULL,
  `billing_phone` varchar(15) DEFAULT NULL,
  `taxable_amount` double NOT NULL DEFAULT 0,
  `discount_amount` double NOT NULL DEFAULT 0,
  `is_gst_bill` int(11) NOT NULL DEFAULT 1 COMMENT '1 = YES\r\n2 = NO',
  `cgst_amount` double NOT NULL DEFAULT 0,
  `sgst_amount` double NOT NULL DEFAULT 0,
  `igst_amount` double NOT NULL DEFAULT 0,
  `grand_total_amount` double NOT NULL DEFAULT 0,
  `advance_amount` double NOT NULL DEFAULT 0,
  `due_amount` double NOT NULL DEFAULT 0,
  `status` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A = Active\r\nD = Deactive/Deleted',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice`
--

LOCK TABLES `invoice` WRITE;
/*!40000 ALTER TABLE `invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_details`
--

DROP TABLE IF EXISTS `invoice_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `billing_description` varchar(250) DEFAULT NULL,
  `billing_quantity` int(11) NOT NULL DEFAULT 0,
  `billing_rate` double NOT NULL DEFAULT 0,
  `billing_per` int(11) NOT NULL DEFAULT 0,
  `billing_amount` double NOT NULL DEFAULT 0,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_details`
--

LOCK TABLES `invoice_details` WRITE;
/*!40000 ALTER TABLE `invoice_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_details`
--

DROP TABLE IF EXISTS `leave_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `leave_subject` varchar(200) DEFAULT NULL,
  `leave_matter` longtext DEFAULT NULL,
  `leave_dates` varchar(50) DEFAULT NULL,
  `leave_month` int(11) NOT NULL DEFAULT 0,
  `leave_year` int(11) NOT NULL DEFAULT 0,
  `leave_apply_date` datetime DEFAULT current_timestamp(),
  `response` varchar(250) DEFAULT NULL,
  `response_by_user_id` int(11) NOT NULL,
  `response_date` datetime NOT NULL DEFAULT current_timestamp(),
  `action_taken_status` int(11) NOT NULL DEFAULT 1 COMMENT '1 = APPLIED\r\n2 = PROCESSING\r\n3 = ON-HOLD\r\n4 = ACCEPTED\r\n5 = REJECTED',
  `admin_response` varchar(300) DEFAULT NULL,
  `admin_user_id` int(11) NOT NULL DEFAULT 0,
  `admin_response_date` date DEFAULT NULL,
  `admin_action_taken_status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = NOTHING, 1 = APPLIED 2 = PROCESSING 3 = ON-HOLD 4 = ACCEPTED 5 = REJECTED',
  `reference_doc` varchar(100) DEFAULT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `creation_date` int(11) NOT NULL,
  `infotext` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_details`
--

LOCK TABLES `leave_details` WRITE;
/*!40000 ALTER TABLE `leave_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `leave_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_details`
--

DROP TABLE IF EXISTS `message_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message_txt` varchar(400) DEFAULT NULL,
  `attachment_name` varchar(200) DEFAULT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_details`
--

LOCK TABLES `message_details` WRITE;
/*!40000 ALTER TABLE `message_details` DISABLE KEYS */;
INSERT INTO `message_details` VALUES (1,1001,1,13,NULL,'1678123642299_20241024_211810.jpeg','A','2024-10-24 21:18:10');
/*!40000 ALTER TABLE `message_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL DEFAULT 1001,
  `sender_user_id` int(11) NOT NULL,
  `receiver_user_id` int(11) NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A = Active, D = Deactive/Deleted',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,1001,13,12,'A','2024-10-24 21:18:10');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `new_message_log`
--

DROP TABLE IF EXISTS `new_message_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_message_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `msg_receiver_user_id` int(11) NOT NULL,
  `msg_sender_user_id` int(11) NOT NULL,
  `new_msg` int(11) NOT NULL DEFAULT 1 COMMENT '0 = read, 1 = unread',
  `status` varchar(1) NOT NULL DEFAULT 'A' COMMENT '0 = Active, 1 = Deactive',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `new_message_log`
--

LOCK TABLES `new_message_log` WRITE;
/*!40000 ALTER TABLE `new_message_log` DISABLE KEYS */;
INSERT INTO `new_message_log` VALUES (1,1001,12,13,1,'A','2024-10-24 21:18:10');
/*!40000 ALTER TABLE `new_message_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notices`
--

DROP TABLE IF EXISTS `notices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `notice_subject` varchar(200) DEFAULT NULL,
  `notice_file` varchar(100) DEFAULT NULL,
  `notice_added_by` int(11) NOT NULL DEFAULT 0 COMMENT 'Current User ID',
  `active` int(11) NOT NULL DEFAULT 1 COMMENT '0 = Draft\r\n1 = Published',
  `status` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A = Active, D = Deactive / Deleted',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_active_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notices`
--

LOCK TABLES `notices` WRITE;
/*!40000 ALTER TABLE `notices` DISABLE KEYS */;
/*!40000 ALTER TABLE `notices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_slip`
--

DROP TABLE IF EXISTS `pay_slip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_slip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL DEFAULT 1001,
  `employee_id` int(11) NOT NULL,
  `payslip_month` int(11) NOT NULL COMMENT 'Month Index Number',
  `payslip_file` varchar(100) DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL COMMENT 'Current User ID',
  `active` int(11) NOT NULL DEFAULT 1 COMMENT '0 = Inactive, 1 = Active',
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `accept_status` int(11) NOT NULL DEFAULT 2 COMMENT '1 = Accepted\r\n2 = Pending\r\n3 = Dispute Raised',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_active_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_slip`
--

LOCK TABLES `pay_slip` WRITE;
/*!40000 ALTER TABLE `pay_slip` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_slip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL DEFAULT 0,
  `user_type` int(11) NOT NULL COMMENT '(SADMIN, 7)\r\n(ADMIN, 6)\r\n(MANAGER, 5)\r\n(EMPLOYEE, 4)',
  `name` varchar(100) NOT NULL,
  `email` varchar(70) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `password` varchar(20) NOT NULL,
  `pass_hash` varchar(100) NOT NULL,
  `ref_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Reference Table Id',
  `active` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Active, 0 = Deactive',
  `status` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A = Active, D = Deleted / Removed',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `infotext` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,1001,1001,7,'Super Admin','sadmin@email.com','9123456780','Sadmin.1234','ad2d410ec36b60990d53022a0383713d404e3c2b8d1b604c25226541bc74c390',0,1,'A','2023-12-28 11:03:54',NULL),(6,1001,2,6,'Admin','admin@email.com','7894561230','Admin.1234','9cdca6289d90c1b87395bfcb2a07e1b407710d11141d6c5080fbdfba5360cdff',0,1,'A','2024-01-03 12:16:58',NULL),(12,1001,8,4,'mx','mx@email.com','9789456123','Mx.1234','a0f5cefa56b491f301630e9fc10728399169b29bf6277911c71751ad409d0a13',0,1,'A','2024-01-30 16:46:45',NULL),(13,1001,9,4,'x','x@email.com','8529637410','X.1234','1a9de3ffb90cabb1f7aeae496c232894c0d33411b943a3731e96069c5755cc59',0,1,'A','2024-01-30 18:00:28',NULL),(15,1001,11,4,'Jyotirmoy Saha','30jsaha@gmail.com','6291248038','Joy.1234','196650546410c8620f28703ca0cc63e842319fdaaa85945ac349591b8d2e0c14',0,1,'A','2024-01-30 18:24:14',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-24 21:20:24
