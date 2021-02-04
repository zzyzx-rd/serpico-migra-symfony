-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le :  jeu. 04 fév. 2021 à 10:47
-- Version du serveur :  5.7.26
-- Version de PHP :  7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `symf_serpico`
--

-- --------------------------------------------------------

--
-- Structure de la table `activity`
--

CREATE TABLE `activity` (
  `act_id` int(11) NOT NULL,
  `process_pro_id` int(11) DEFAULT NULL,
  `institution_process_id` int(11) DEFAULT NULL,
  `organization_org_id` int(11) NOT NULL,
  `act_complete` tinyint(1) NOT NULL,
  `act_magnitude` int(11) DEFAULT NULL,
  `act_simplified` tinyint(1) NOT NULL,
  `act_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `act_visibility` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `act_objectives` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `act_status` int(11) DEFAULT NULL,
  `act_progress` int(11) NOT NULL,
  `act_isRewarding` tinyint(1) DEFAULT NULL,
  `act_distrAmount` double DEFAULT NULL,
  `act_res_inertia` double DEFAULT NULL,
  `act_res_benefit_eff` double DEFAULT NULL,
  `act_created_by` int(11) DEFAULT NULL,
  `act_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `act_saved` datetime DEFAULT NULL,
  `act_isFinalized` tinyint(1) NOT NULL,
  `act_finalized` datetime DEFAULT NULL,
  `act_deleted` datetime DEFAULT NULL,
  `act_completed` datetime DEFAULT NULL,
  `act_released` datetime DEFAULT NULL,
  `act_archived` datetime DEFAULT NULL,
  `diff_criteria` tinyint(1) DEFAULT NULL,
  `diff_participants` tinyint(1) NOT NULL,
  `nb_participants` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `activity`
--

INSERT INTO `activity` (`act_id`, `process_pro_id`, `institution_process_id`, `organization_org_id`, `act_complete`, `act_magnitude`, `act_simplified`, `act_name`, `act_visibility`, `act_objectives`, `act_status`, `act_progress`, `act_isRewarding`, `act_distrAmount`, `act_res_inertia`, `act_res_benefit_eff`, `act_created_by`, `act_inserted`, `act_saved`, `act_isFinalized`, `act_finalized`, `act_deleted`, `act_completed`, `act_released`, `act_archived`, `diff_criteria`, `diff_participants`, `nb_participants`) VALUES
(1, NULL, NULL, 1, 0, 1, 1, 'Projet marketing', 'public', '', -1, 0, 0, 0, 0, 0, 1, '2020-08-24 15:38:47', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(2, NULL, NULL, 1, 0, 1, 1, 'Renegocation annuelle', 'public', '', -1, 0, 0, 0, 0, 0, 1, '2020-08-24 16:11:54', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(5, NULL, NULL, 1, 0, 1, 1, 'Crédit étendu', 'public', '', -1, 0, 0, 0, 0, 0, 1, '2020-08-25 21:11:58', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(16, NULL, NULL, 1, 0, 1, 1, 'Reprise 3', 'public', '', 1, 0, 0, 0, 0, 0, 1, '2020-09-26 20:25:01', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(19, NULL, NULL, 1, 0, 1, 1, 'Evaluation 2020', 'public', '', 1, 1, 0, 0, 0, 0, 1, '2020-09-30 19:23:38', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(20, NULL, NULL, 1, 0, 1, 1, 'Livraison', 'public', '', 1, 0, 0, 0, 0, 0, 1, '2020-10-06 16:48:11', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(35, NULL, NULL, 49, 0, 1, 1, 'Livraison VR 2', 'public', '', 1, 1, 0, 0, 0, 0, 104, '2020-10-14 08:05:18', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(40, NULL, NULL, 56, 0, 1, 1, 'Activité avec moi', 'public', '', 1, 1, 0, 0, 0, 0, 118, '2020-10-14 15:45:29', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(41, NULL, NULL, 56, 0, 1, 1, 'The Office Contract', 'public', '', 1, 1, 0, 0, 0, 0, 118, '2020-10-16 14:59:38', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(46, NULL, NULL, 57, 0, 1, 1, 'Projet Union Monétaire', 'public', '', 1, 1, 0, 0, 0, 0, 128, '2020-10-27 11:47:22', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(47, NULL, NULL, 57, 0, 1, 1, 'Pot Commun', 'public', '', 1, 1, 0, 0, 0, 0, 128, '2020-10-27 14:19:45', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(132, NULL, NULL, 57, 0, 1, 1, 'USA', 'public', '', 1, 1, 0, 0, 0, 0, 128, '2020-11-11 09:39:02', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(136, NULL, NULL, 57, 0, 1, 1, 'Top', 'public', '', 1, 0, 0, 0, 0, 0, 128, '2020-11-11 23:51:35', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(137, NULL, NULL, 64, 0, 1, 1, 'Clôture Q4', 'public', '', 0, 0, 0, 0, 0, 0, 145, '2020-11-14 13:08:55', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(138, NULL, NULL, 57, 0, 1, 1, 'Activité au long cours', 'public', '', 1, 1, 0, 0, 0, 0, 128, '2020-11-20 15:24:06', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(139, NULL, NULL, 72, 0, 1, 1, 'Test activité', 'public', '', 1, 1, 0, 0, 0, 0, 161, '2020-11-30 19:08:43', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(143, NULL, NULL, 1, 0, 1, 1, 'Stage ouvert', 'public', '', 1, 1, 0, 0, 0, 0, 1, '2020-12-21 10:28:15', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(152, NULL, NULL, 102, 0, 1, 1, 'Test avec Gertrude', 'public', '', 1, 1, 0, 0, 0, 0, 223, '2021-01-25 09:50:28', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0),
(153, NULL, NULL, 102, 0, 1, 1, 'Intégration David', 'public', '', 1, 1, 0, 0, 0, 0, 223, '2021-02-02 16:11:07', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `answer`
--

CREATE TABLE `answer` (
  `asw_id` int(11) NOT NULL,
  `survey_field_sfi_id` int(11) DEFAULT NULL,
  `survey_sur_id` int(11) DEFAULT NULL,
  `activity_user_par_id` int(11) DEFAULT NULL,
  `asw_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asw_created_by` int(11) DEFAULT NULL,
  `asw_inserted` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `city`
--

CREATE TABLE `city` (
  `cit_id` int(11) NOT NULL,
  `state_sta_id` int(11) DEFAULT NULL,
  `country_cou_id` int(11) DEFAULT NULL,
  `cit_abbr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cit_fullname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cit_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cit_created_by` int(11) DEFAULT NULL,
  `cit_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `city`
--

INSERT INTO `city` (`cit_id`, `state_sta_id`, `country_cou_id`, `cit_abbr`, `cit_fullname`, `cit_name`, `cit_created_by`, `cit_inserted`) VALUES
(1, 1, 129, NULL, NULL, 'Luxembourg', 1, '2020-09-29 13:42:39');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `cli_id` int(11) NOT NULL,
  `organization_org_id` int(11) NOT NULL,
  `client_org_id` int(11) NOT NULL,
  `worker_firm_wfi_id` int(11) DEFAULT NULL,
  `cli_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cli_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cli_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cli_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cli_createdBy` int(11) DEFAULT NULL,
  `cli_inserted` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`cli_id`, `organization_org_id`, `client_org_id`, `worker_firm_wfi_id`, `cli_name`, `cli_type`, `cli_logo`, `cli_email`, `cli_createdBy`, `cli_inserted`) VALUES
(2, 13, 1, NULL, 'Serpico', 'f', NULL, NULL, 1, '2020-08-27 14:12:20'),
(8, 16, 1, NULL, 'Serpico', 'f', NULL, NULL, 1, '2020-08-31 19:22:34'),
(9, 1, 16, 7, 'Creos', 'F', NULL, NULL, 1, '2020-08-31 21:11:27'),
(11, 18, 1, NULL, 'Serpico', 'f', NULL, NULL, 1, '2020-08-31 21:42:02'),
(12, 1, 19, 9, 'BGL BNP Paribas', 'F', NULL, NULL, 1, '2020-08-31 22:24:40'),
(13, 19, 1, NULL, 'Serpico', 'f', NULL, NULL, 1, '2020-08-31 22:24:40'),
(14, 1, 20, 10, 'NVision', 'F', NULL, NULL, 1, '2020-08-31 22:30:19'),
(15, 20, 1, NULL, 'Serpico', 'f', NULL, NULL, 1, '2020-08-31 22:30:19'),
(17, 1, 21, 11, 'Dupont & Nemours', 'F', NULL, NULL, 1, '2020-09-02 12:38:23'),
(18, 21, 1, NULL, 'Serpico', 'f', NULL, NULL, 1, '2020-09-02 12:38:23'),
(19, 1, 13, 6, 'Welkin & Meraki', 'F', NULL, NULL, 1, '2020-09-25 14:19:16'),
(20, 25, 1, 1, 'Serpico', 'f', NULL, NULL, 1, '2020-09-27 20:56:19'),
(21, 1, 25, NULL, 'Landifirm', 'F', NULL, NULL, 1, '2020-09-27 20:56:19'),
(29, 32, 1, 1, 'Serpico', 'f', NULL, NULL, 1, '2020-09-30 08:18:03'),
(31, 32, 1, 1, 'Serpico', 'f', NULL, NULL, 1, '2020-09-30 13:04:31'),
(43, 39, 1, 1, 'Serpico', 'f', NULL, NULL, 1, '2020-09-30 17:52:55'),
(44, 1, 39, 56, 'Luxembourg City Incubator', 'F', NULL, NULL, 1, '2020-09-30 17:52:55'),
(45, 40, 1, 1, 'Serpico', 'f', NULL, NULL, 1, '2020-10-02 13:02:54'),
(46, 1, 40, 58, 'Luxfactory', 'F', NULL, NULL, 1, '2020-10-02 13:02:54'),
(51, 51, 49, 59, 'SalonKee', 'F', NULL, NULL, 104, '2020-10-13 22:37:08'),
(52, 49, 51, 62, 'Vizz', 'F', NULL, NULL, 104, '2020-10-13 22:37:08'),
(53, 25, 56, 63, 'Ministère de l\'Economie', 'F', NULL, NULL, 118, '2020-10-23 15:33:53'),
(54, 56, 25, 21, 'Landifirm', 'F', NULL, NULL, 118, '2020-10-23 15:33:53'),
(96, 57, 64, 65, 'Tatcher Inc.', 'F', NULL, NULL, 128, '2020-10-27 14:19:14'),
(97, 64, 57, 64, 'Velazquez Foundation', 'F', NULL, NULL, 128, '2020-10-27 15:02:10'),
(109, 16, 57, 64, 'Velazquez Foundation', 'F', NULL, NULL, 128, '2020-10-27 15:47:35'),
(110, 57, 16, 7, 'Creos', 'F', NULL, NULL, 128, '2020-10-27 15:47:35'),
(111, 65, 64, 65, 'Tatcher Inc.', 'F', NULL, NULL, 145, '2020-11-15 11:50:21'),
(112, 64, 65, 67, 'Metro Goldwin', 'F', NULL, NULL, 145, '2020-11-15 11:50:21'),
(113, 66, 57, 64, 'Velazquez Foundation', 'F', NULL, NULL, 128, '2020-11-20 17:01:29'),
(114, 57, 66, 68, 'Camille Suteau', 'F', NULL, NULL, 128, '2020-11-20 17:01:29'),
(115, 67, 57, 64, 'Velazquez Foundation', 'F', NULL, NULL, 128, '2020-11-20 17:05:37'),
(116, 57, 67, 69, 'Fabrice Pincet', 'F', NULL, NULL, 128, '2020-11-20 17:05:37'),
(117, 57, 72, 70, 'Hermenon Foundation', 'F', NULL, NULL, 161, '2020-11-30 18:50:54'),
(118, 72, 57, 64, 'Velazquez Foundation', 'F', NULL, NULL, 161, '2020-11-30 18:50:54'),
(119, 100, 100, 77, 'Garnier & Co', 'F', NULL, NULL, 212, '2020-12-17 15:44:58'),
(120, 101, 99, NULL, 'Clément Garnier', 'C', NULL, NULL, 210, '2021-01-09 23:26:26'),
(121, 99, 101, 81, 'Evernote', 'F', NULL, NULL, 210, '2021-01-09 23:26:26'),
(122, 102, 1, 1, 'Serpico', 'f', NULL, NULL, 1, '2021-01-12 10:02:51'),
(123, 1, 102, 82, 'Robeco', 'F', NULL, NULL, 1, '2021-01-12 10:02:51'),
(144, 18, 1, 1, 'Serpico', 'f', NULL, NULL, 1, '2021-01-13 10:50:01'),
(145, 1, 18, 8, 'Ministère des Classes Moyennes', 'F', NULL, NULL, 1, '2021-01-13 10:50:01'),
(147, 1, 99, NULL, 'Clément Garnier', 'F', NULL, NULL, NULL, '2021-01-18 09:13:08'),
(148, 99, 1, 1, 'Serpico', 'f', NULL, NULL, 1, '2021-01-18 09:13:08'),
(149, 1, 100, 77, 'Garnier & Co', 'F', NULL, NULL, 1, '2021-01-18 23:11:46'),
(157, 108, 1, 1, 'Serpico', 'f', NULL, NULL, 1, '2021-01-19 14:08:31'),
(158, 1, 108, 83, 'Floyd Aviation', 'F', NULL, NULL, 1, '2021-01-19 14:08:31'),
(214, 137, 102, 82, 'Robeco', 'F', NULL, NULL, 223, '2021-01-22 11:42:17'),
(216, 102, 19, 9, 'BGL BNP Paribas', 'f', NULL, NULL, 223, '2021-01-22 13:48:58'),
(217, 137, 137, NULL, 'Federico Garcia', 'I', NULL, NULL, 278, '2021-01-24 18:47:54'),
(220, 102, 137, NULL, 'Robeco', 'I', NULL, NULL, 223, '2021-01-24 20:53:18'),
(222, 146, 102, 82, 'Robeco', 'F', NULL, NULL, 223, '2021-01-31 15:20:03'),
(233, 102, 146, 107, 'Weigand & Co', 'F', NULL, NULL, NULL, '2021-01-31 18:17:00'),
(237, 51, 102, 82, 'Robeco', 'F', NULL, NULL, 223, '2021-02-02 11:31:50'),
(238, 102, 51, 62, 'Vizz', 'F', NULL, NULL, NULL, '2021-02-02 11:35:23'),
(239, 147, 102, 82, 'Robeco', 'F', NULL, NULL, 223, '2021-02-02 14:33:52'),
(240, 102, 147, 108, 'De la Cruz Co.', 'f', NULL, NULL, 223, '2021-02-02 14:33:51');

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `con_id` int(11) NOT NULL,
  `con_locale` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `con_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `con_sent` tinyint(1) DEFAULT NULL,
  `con_fullname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `con_compagny` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `con_adress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `con_zipCode` int(11) DEFAULT NULL,
  `con_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `con_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `con_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `con_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `con_message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `con_newsletter` tinyint(1) DEFAULT NULL,
  `con_doc` tinyint(1) DEFAULT NULL,
  `con_mdate` datetime DEFAULT NULL,
  `con_mtime` datetime DEFAULT NULL,
  `con_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `con_confirmed` datetime DEFAULT NULL,
  `con_created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `country`
--

CREATE TABLE `country` (
  `cou_id` int(11) NOT NULL,
  `cou_abbr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cou_fullname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cou_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cou_created_by` int(11) DEFAULT NULL,
  `cou_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `country`
--

INSERT INTO `country` (`cou_id`, `cou_abbr`, `cou_fullname`, `cou_name`, `cou_created_by`, `cou_inserted`) VALUES
(1, 'AF', 'Afghanistan', 'Afghanistan', NULL, '2019-07-28 10:41:05'),
(2, 'AX', 'Aland Islands', 'Aland Islands', NULL, '2019-07-28 10:54:30'),
(3, 'AL', 'Albania', 'Albania', NULL, '2019-07-28 10:54:30'),
(4, 'DZ', 'Algeria', 'Algeria', NULL, '2019-07-28 10:54:30'),
(5, 'AS', 'American Samoa', 'American Samoa', NULL, '2019-07-28 10:54:30'),
(6, 'AD', 'Andorra', 'Andorra', NULL, '2019-07-28 10:54:30'),
(7, 'AO', 'Angola', 'Angola', NULL, '2019-07-28 10:54:30'),
(8, 'AI', 'Anguilla', 'Anguilla', NULL, '2019-07-28 10:54:30'),
(9, 'AQ', 'Antarctica', 'Antarctica', NULL, '2019-07-28 10:54:30'),
(10, 'AG', 'Antigua and Barbuda', 'Antigua and Barbuda', NULL, '2019-07-28 10:54:30'),
(11, 'AR', 'Argentina', 'Argentina', NULL, '2019-07-28 10:54:30'),
(12, 'AM', 'Armenia', 'Armenia', NULL, '2019-07-28 10:54:30'),
(13, 'AW', 'Aruba', 'Aruba', NULL, '2019-07-28 10:54:30'),
(14, 'AU', 'Australia', 'Australia', NULL, '2019-07-28 10:54:30'),
(15, 'AT', 'Austria', 'Austria', NULL, '2019-07-28 10:54:30'),
(16, 'AZ', 'Azerbaijan', 'Azerbaijan', NULL, '2019-07-28 10:54:30'),
(17, 'BS', 'Bahamas', 'Bahamas', NULL, '2019-07-28 10:54:30'),
(18, 'BH', 'Bahrain', 'Bahrain', NULL, '2019-07-28 10:54:30'),
(19, 'BD', 'Bangladesh', 'Bangladesh', NULL, '2019-07-28 10:54:30'),
(20, 'BB', 'Barbados', 'Barbados', NULL, '2019-07-28 10:54:30'),
(21, 'BY', 'Belarus', 'Belarus', NULL, '2019-07-28 10:54:30'),
(22, 'BE', 'Belgium', 'Belgium', NULL, '2019-07-28 10:54:30'),
(23, 'BZ', 'Belize', 'Belize', NULL, '2019-07-28 10:54:30'),
(24, 'BJ', 'Benin', 'Benin', NULL, '2019-07-28 10:54:30'),
(25, 'BM', 'Bermuda', 'Bermuda', NULL, '2019-07-28 10:54:30'),
(26, 'BT', 'Bhutan', 'Bhutan', NULL, '2019-07-28 10:54:30'),
(27, 'BO', 'Bolivia', 'Bolivia', NULL, '2019-07-28 10:54:30'),
(28, 'BA', 'Bosnia and Herzegovina', 'Bosnia and Herzegovina', NULL, '2019-07-28 10:54:30'),
(29, 'BW', 'Botswana', 'Botswana', NULL, '2019-07-28 10:54:30'),
(30, 'BV', 'Bouvet Island (Bouvetoya)', 'Bouvet Island (Bouvetoya)', NULL, '2019-07-28 10:54:30'),
(31, 'BR', 'Brazil', 'Brazil', NULL, '2019-07-28 10:54:30'),
(32, 'IO', 'British Indian Ocean Territory (Chagos Archipelago)', 'British Indian Ocean Territory (Chagos Archipelago)', NULL, '2019-07-28 10:54:30'),
(33, 'VG', 'British Virgin Islands', 'British Virgin Islands', NULL, '2019-07-28 10:54:30'),
(34, 'BN', 'Brunei Darussalam', 'Brunei Darussalam', NULL, '2019-07-28 10:54:30'),
(35, 'BG', 'Bulgaria', 'Bulgaria', NULL, '2019-07-28 10:54:30'),
(36, 'BF', 'Burkina Faso', 'Burkina Faso', NULL, '2019-07-28 10:54:30'),
(37, 'BI', 'Burundi', 'Burundi', NULL, '2019-07-28 10:54:30'),
(38, 'KH', 'Cambodia', 'Cambodia', NULL, '2019-07-28 10:54:30'),
(39, 'CM', 'Cameroon', 'Cameroon', NULL, '2019-07-28 10:54:30'),
(40, 'CA', 'Canada', 'Canada', NULL, '2019-07-28 10:54:30'),
(41, 'CV', 'Cape Verde', 'Cape Verde', NULL, '2019-07-28 10:54:30'),
(42, 'KY', 'Cayman Islands', 'Cayman Islands', NULL, '2019-07-28 10:54:30'),
(43, 'CF', 'Central African Republic', 'Central African Republic', NULL, '2019-07-28 10:54:30'),
(44, 'TD', 'Chad', 'Chad', NULL, '2019-07-28 10:54:30'),
(45, 'CL', 'Chile', 'Chile', NULL, '2019-07-28 10:54:30'),
(46, 'CN', 'China', 'China', NULL, '2019-07-28 10:54:30'),
(47, 'CX', 'Christmas Island', 'Christmas Island', NULL, '2019-07-28 10:54:30'),
(48, 'CC', 'Cocos (Keeling) Islands', 'Cocos (Keeling) Islands', NULL, '2019-07-28 10:54:30'),
(49, 'CO', 'Colombia', 'Colombia', NULL, '2019-07-28 10:54:30'),
(50, 'KM', 'Comoros', 'Comoros', NULL, '2019-07-28 10:54:30'),
(51, 'CD', 'Congo, Democratic Republic of (Kinshasa)', 'Congo, Democratic Republic of (Kinshasa)', NULL, '2019-07-28 10:54:30'),
(52, 'CG', 'Congo, Republic of (Brazzaville)', 'Congo, Republic of (Brazzaville)', NULL, '2019-07-28 10:54:30'),
(53, 'CK', 'Cook Islands', 'Cook Islands', NULL, '2019-07-28 10:54:30'),
(54, 'CR', 'Costa Rica', 'Costa Rica', NULL, '2019-07-28 10:54:30'),
(55, 'CI', 'Cote d\'Ivoire', 'Cote d\'Ivoire', NULL, '2019-07-28 10:54:30'),
(56, 'HR', 'Croatia', 'Croatia', NULL, '2019-07-28 10:54:30'),
(57, 'CU', 'Cuba', 'Cuba', NULL, '2019-07-28 10:54:30'),
(58, 'CY', 'Cyprus', 'Cyprus', NULL, '2019-07-28 10:54:30'),
(59, 'CZ', 'Czech Republic', 'Czech Republic', NULL, '2019-07-28 10:54:30'),
(60, 'DK', 'Denmark', 'Denmark', NULL, '2019-07-28 10:54:30'),
(61, 'DJ', 'Djibouti', 'Djibouti', NULL, '2019-07-28 10:54:30'),
(62, 'DM', 'Dominica', 'Dominica', NULL, '2019-07-28 10:54:30'),
(63, 'DO', 'Dominican Republic', 'Dominican Republic', NULL, '2019-07-28 10:54:30'),
(64, 'EC', 'Ecuador', 'Ecuador', NULL, '2019-07-28 10:54:30'),
(65, 'EG', 'Egypt', 'Egypt', NULL, '2019-07-28 10:54:30'),
(66, 'SV', 'El Salvador', 'El Salvador', NULL, '2019-07-28 10:54:30'),
(67, 'GQ', 'Equatorial Guinea', 'Equatorial Guinea', NULL, '2019-07-28 10:54:30'),
(68, 'ER', 'Eritrea', 'Eritrea', NULL, '2019-07-28 10:54:30'),
(69, 'EE', 'Estonia', 'Estonia', NULL, '2019-07-28 10:54:30'),
(70, 'ET', 'Ethiopia', 'Ethiopia', NULL, '2019-07-28 10:54:30'),
(71, 'FO', 'Faroe Islands', 'Faroe Islands', NULL, '2019-07-28 10:54:30'),
(72, 'FK', 'Falkland Islands (Malvinas)', 'Falkland Islands (Malvinas)', NULL, '2019-07-28 10:54:30'),
(73, 'FJ', 'Fiji the Fiji Islands', 'Fiji the Fiji Islands', NULL, '2019-07-28 10:54:30'),
(74, 'FI', 'Finland', 'Finland', NULL, '2019-07-28 10:54:30'),
(75, 'FR', 'French Republic', 'France', NULL, '2019-07-28 10:54:30'),
(76, 'GF', 'French Guiana', 'French Guiana', NULL, '2019-07-28 10:54:30'),
(77, 'PF', 'French Polynesia', 'French Polynesia', NULL, '2019-07-28 10:54:30'),
(78, 'TF', 'French Southern Territories', 'French Southern Territories', NULL, '2019-07-28 10:54:30'),
(79, 'GA', 'Gabon', 'Gabon', NULL, '2019-07-28 10:54:30'),
(80, 'GM', 'Gambia', 'Gambia', NULL, '2019-07-28 10:54:30'),
(81, 'GE', 'Georgia', 'Georgia', NULL, '2019-07-28 10:54:30'),
(82, 'DE', 'Germany', 'Germany', NULL, '2019-07-28 10:54:30'),
(83, 'GH', 'Ghana', 'Ghana', NULL, '2019-07-28 10:54:30'),
(84, 'GI', 'Gibraltar', 'Gibraltar', NULL, '2019-07-28 10:54:30'),
(85, 'GR', 'Greece', 'Greece', NULL, '2019-07-28 10:54:30'),
(86, 'GL', 'Greenland', 'Greenland', NULL, '2019-07-28 10:54:30'),
(87, 'GD', 'Grenada', 'Grenada', NULL, '2019-07-28 10:54:30'),
(88, 'GP', 'Guadeloupe', 'Guadeloupe', NULL, '2019-07-28 10:54:30'),
(89, 'GU', 'Guam', 'Guam', NULL, '2019-07-28 10:54:30'),
(90, 'GT', 'Guatemala', 'Guatemala', NULL, '2019-07-28 10:54:30'),
(91, 'GG', 'Guernsey', 'Guernsey', NULL, '2019-07-28 10:54:30'),
(92, 'GN', 'Guinea', 'Guinea', NULL, '2019-07-28 10:54:30'),
(93, 'GW', 'Guinea-Bissau', 'Guinea-Bissau', NULL, '2019-07-28 10:54:30'),
(94, 'GY', 'Guyana', 'Guyana', NULL, '2019-07-28 10:54:30'),
(95, 'HT', 'Haiti', 'Haiti', NULL, '2019-07-28 10:54:30'),
(96, 'HM', 'Heard Island and McDonald Islands', 'Heard Island and McDonald Islands', NULL, '2019-07-28 10:54:30'),
(97, 'VA', 'Holy See (Vatican City State)', 'Vatican', NULL, '2019-07-28 10:54:30'),
(98, 'HN', 'Honduras', 'Honduras', NULL, '2019-07-28 10:54:30'),
(99, 'HK', 'Hong Kong', 'Hong Kong', NULL, '2019-07-28 10:54:30'),
(100, 'HU', 'Hungary', 'Hungary', NULL, '2019-07-28 10:54:30'),
(101, 'IS', 'Iceland', 'Iceland', NULL, '2019-07-28 10:54:30'),
(102, 'IN', 'India', 'India', NULL, '2019-07-28 10:54:30'),
(103, 'ID', 'Indonesia', 'Indonesia', NULL, '2019-07-28 10:54:30'),
(104, 'IR', 'Iran', 'Iran', NULL, '2019-07-28 10:54:30'),
(105, 'IQ', 'Iraq', 'Iraq', NULL, '2019-07-28 10:54:30'),
(106, 'IE', 'Ireland', 'Ireland', NULL, '2019-07-28 10:54:30'),
(107, 'IM', 'Isle of Man', 'Isle of Man', NULL, '2019-07-28 10:54:30'),
(108, 'IL', 'Israel', 'Israel', NULL, '2019-07-28 10:54:30'),
(109, 'IT', 'Italy', 'Italy', NULL, '2019-07-28 10:54:30'),
(110, 'JM', 'Jamaica', 'Jamaica', NULL, '2019-07-28 10:54:30'),
(111, 'JP', 'Japan', 'Japan', NULL, '2019-07-28 10:54:30'),
(112, 'JE', 'Jersey', 'Jersey', NULL, '2019-07-28 10:54:30'),
(113, 'JO', 'Jordan', 'Jordan', NULL, '2019-07-28 10:54:30'),
(114, 'KZ', 'Kazakhstan', 'Kazakhstan', NULL, '2019-07-28 10:54:30'),
(115, 'KE', 'Kenya', 'Kenya', NULL, '2019-07-28 10:54:30'),
(116, 'KI', 'Kiribati', 'Kiribati', NULL, '2019-07-28 10:54:30'),
(117, 'KP', 'Korea', 'Korea', NULL, '2019-07-28 10:54:30'),
(118, 'KR', 'Korea', 'Korea', NULL, '2019-07-28 10:54:30'),
(119, 'KW', 'Kuwait', 'Kuwait', NULL, '2019-07-28 10:54:30'),
(120, 'KG', 'Kyrgyz Republic', 'Kyrgyzstan', NULL, '2019-07-28 10:54:30'),
(121, 'LA', 'Lao', 'Lao', NULL, '2019-07-28 10:54:30'),
(122, 'LV', 'Latvia', 'Latvia', NULL, '2019-07-28 10:54:30'),
(123, 'LB', 'Lebanon', 'Lebanon', NULL, '2019-07-28 10:54:30'),
(124, 'LS', 'Lesotho', 'Lesotho', NULL, '2019-07-28 10:54:30'),
(125, 'LR', 'Liberia', 'Liberia', NULL, '2019-07-28 10:54:30'),
(126, 'LY', 'Libyan Arab Jamahiriya', 'Libyan Arab Jamahiriya', NULL, '2019-07-28 10:54:30'),
(127, 'LI', 'Liechtenstein', 'Liechtenstein', NULL, '2019-07-28 10:54:30'),
(128, 'LT', 'Lithuania', 'Lithuania', NULL, '2019-07-28 10:54:30'),
(129, 'LU', 'Luxembourg', 'Luxembourg', NULL, '2019-07-28 10:54:30'),
(130, 'MO', 'Macao', 'Macao', NULL, '2019-07-28 10:54:30'),
(131, 'MK', 'Macedonia', 'Macedonia', NULL, '2019-07-28 10:54:30'),
(132, 'MG', 'Madagascar', 'Madagascar', NULL, '2019-07-28 10:54:30'),
(133, 'MW', 'Malawi', 'Malawi', NULL, '2019-07-28 10:54:30'),
(134, 'MY', 'Malaysia', 'Malaysia', NULL, '2019-07-28 10:54:30'),
(135, 'MV', 'Maldives', 'Maldives', NULL, '2019-07-28 10:54:30'),
(136, 'ML', 'Mali', 'Mali', NULL, '2019-07-28 10:54:30'),
(137, 'MT', 'Malta', 'Malta', NULL, '2019-07-28 10:54:30'),
(138, 'MH', 'Marshall Islands', 'Marshall Islands', NULL, '2019-07-28 10:54:30'),
(139, 'MQ', 'Martinique', 'Martinique', NULL, '2019-07-28 10:54:30'),
(140, 'MR', 'Mauritania', 'Mauritania', NULL, '2019-07-28 10:54:30'),
(141, 'MU', 'Mauritius', 'Mauritius', NULL, '2019-07-28 10:54:30'),
(142, 'YT', 'Mayotte', 'Mayotte', NULL, '2019-07-28 10:54:30'),
(143, 'MX', 'Mexico', 'Mexico', NULL, '2019-07-28 10:54:30'),
(144, 'FM', 'Micronesia', 'Micronesia', NULL, '2019-07-28 10:54:30'),
(145, 'MD', 'Moldova', 'Moldova', NULL, '2019-07-28 10:54:30'),
(146, 'MC', 'Monaco', 'Monaco', NULL, '2019-07-28 10:54:30'),
(147, 'MN', 'Mongolia', 'Mongolia', NULL, '2019-07-28 10:54:30'),
(148, 'ME', 'Montenegro', 'Montenegro', NULL, '2019-07-28 10:54:30'),
(149, 'MS', 'Montserrat', 'Montserrat', NULL, '2019-07-28 10:54:30'),
(150, 'MA', 'Morocco', 'Morocco', NULL, '2019-07-28 10:54:30'),
(151, 'MZ', 'Mozambique', 'Mozambique', NULL, '2019-07-28 10:54:30'),
(152, 'MM', 'Myanmar', 'Myanmar', NULL, '2019-07-28 10:54:30'),
(153, 'NA', 'Namibia', 'Namibia', NULL, '2019-07-28 10:54:30'),
(154, 'NR', 'Nauru', 'Nauru', NULL, '2019-07-28 10:54:30'),
(155, 'NP', 'Nepal', 'Nepal', NULL, '2019-07-28 10:54:30'),
(156, 'AN', 'Netherlands Antilles', 'Netherlands Antilles', NULL, '2019-07-28 10:54:30'),
(157, 'NL', 'Netherlands, The', 'Netherlands', NULL, '2019-07-28 10:54:30'),
(158, 'NC', 'New Caledonia', 'New Caledonia', NULL, '2019-07-28 10:54:30'),
(159, 'NZ', 'New Zealand', 'New Zealand', NULL, '2019-07-28 10:54:30'),
(160, 'NI', 'Nicaragua', 'Nicaragua', NULL, '2019-07-28 10:54:30'),
(161, 'NE', 'Niger', 'Niger', NULL, '2019-07-28 10:54:30'),
(162, 'NG', 'Nigeria', 'Nigeria', NULL, '2019-07-28 10:54:30'),
(163, 'NU', 'Niue', 'Niue', NULL, '2019-07-28 10:54:30'),
(164, 'NF', 'Norfolk Island', 'Norfolk Island', NULL, '2019-07-28 10:54:30'),
(165, 'MP', 'Northern Mariana Islands', 'Northern Mariana Islands', NULL, '2019-07-28 10:54:30'),
(166, 'NO', 'Norway', 'Norway', NULL, '2019-07-28 10:54:30'),
(167, 'OM', 'Oman', 'Oman', NULL, '2019-07-28 10:54:30'),
(168, 'PK', 'Pakistan', 'Pakistan', NULL, '2019-07-28 10:54:30'),
(169, 'PW', 'Palau', 'Palau', NULL, '2019-07-28 10:54:30'),
(170, 'PS', 'Palestinian Territory', 'Palestinian Territory', NULL, '2019-07-28 10:54:30'),
(171, 'PA', 'Panama', 'Panama', NULL, '2019-07-28 10:54:30'),
(172, 'PG', 'Papua New Guinea', 'Papua New Guinea', NULL, '2019-07-28 10:54:30'),
(173, 'PY', 'Paraguay', 'Paraguay', NULL, '2019-07-28 10:54:30'),
(174, 'PE', 'Peru', 'Peru', NULL, '2019-07-28 10:54:30'),
(175, 'PH', 'Philippines', 'Philippines', NULL, '2019-07-28 10:54:30'),
(176, 'PN', 'Pitcairn Islands', 'Pitcairn Islands', NULL, '2019-07-28 10:54:30'),
(177, 'PL', 'Poland', 'Poland', NULL, '2019-07-28 10:54:30'),
(178, 'PT', 'Portuguese Republic', 'Portugal', NULL, '2019-07-28 10:54:30'),
(179, 'PR', 'Puerto Rico', 'Puerto Rico', NULL, '2019-07-28 10:54:30'),
(180, 'QA', 'Qatar', 'Qatar', NULL, '2019-07-28 10:54:30'),
(181, 'RE', 'Reunion', 'Reunion', NULL, '2019-07-28 10:54:30'),
(182, 'RO', 'Romania', 'Romania', NULL, '2019-07-28 10:54:30'),
(183, 'RU', 'Russian Federation', 'Russian Federation', NULL, '2019-07-28 10:54:30'),
(184, 'RW', 'Rwanda', 'Rwanda', NULL, '2019-07-28 10:54:30'),
(185, 'BL', 'Saint Barthelemy', 'Saint Barthelemy', NULL, '2019-07-28 10:54:30'),
(186, 'SH', 'Saint Helena', 'Saint Helena', NULL, '2019-07-28 10:54:30'),
(187, 'KN', 'Saint Kitts and Nevis', 'Saint Kitts and Nevis', NULL, '2019-07-28 10:54:30'),
(188, 'LC', 'Saint Lucia', 'Saint Lucia', NULL, '2019-07-28 10:54:30'),
(189, 'MF', 'Saint Martin', 'Saint Martin', NULL, '2019-07-28 10:54:30'),
(190, 'PM', 'Saint Pierre and Miquelon', 'Saint Pierre and Miquelon', NULL, '2019-07-28 10:54:30'),
(191, 'VC', 'Saint Vincent and the Grenadines', 'Saint Vincent and the Grenadines', NULL, '2019-07-28 10:54:30'),
(192, 'WS', 'Samoa', 'Samoa', NULL, '2019-07-28 10:54:30'),
(193, 'SM', 'San Marino', 'San Marino', NULL, '2019-07-28 10:54:30'),
(194, 'ST', 'Sao Tome and Principe', 'Sao Tome and Principe', NULL, '2019-07-28 10:54:30'),
(195, 'SA', 'Saudi Arabia', 'Saudi Arabia', NULL, '2019-07-28 10:54:30'),
(196, 'SN', 'Senegal', 'Senegal', NULL, '2019-07-28 10:54:30'),
(197, 'RS', 'Serbia', 'Serbia', NULL, '2019-07-28 10:54:30'),
(198, 'SC', 'Seychelles', 'Seychelles', NULL, '2019-07-28 10:54:30'),
(199, 'SL', 'Sierra Leone', 'Sierra Leone', NULL, '2019-07-28 10:54:30'),
(200, 'SG', 'Singapore', 'Singapore', NULL, '2019-07-28 10:54:30'),
(201, 'SK', 'Slovak Republic', 'Slovakia', NULL, '2019-07-28 10:54:30'),
(202, 'SI', 'Slovenia', 'Slovenia', NULL, '2019-07-28 10:54:30'),
(203, 'SB', 'Solomon Islands', 'Solomon Islands', NULL, '2019-07-28 10:54:30'),
(204, 'SO', 'Somali Republic', 'Somalia', NULL, '2019-07-28 10:54:30'),
(205, 'ZA', 'South Africa', 'South Africa', NULL, '2019-07-28 10:54:30'),
(206, 'GS', 'South Georgia and the South Sandwich Islands', 'South Georgia and the South Sandwich Islands', NULL, '2019-07-28 10:54:30'),
(207, 'ES', 'Spain', 'Spain', NULL, '2019-07-28 10:54:30'),
(208, 'LK', 'Sri Lanka', 'Sri Lanka', NULL, '2019-07-28 10:54:30'),
(209, 'SD', 'Sudan', 'Sudan', NULL, '2019-07-28 10:54:30'),
(210, 'SR', 'Suriname', 'Suriname', NULL, '2019-07-28 10:54:30'),
(211, 'SJ', 'Svalbard & Jan Mayen Islands', 'Svalbard & Jan Mayen Islands', NULL, '2019-07-28 10:54:30'),
(212, 'SZ', 'Swaziland', 'Swaziland', NULL, '2019-07-28 10:54:30'),
(213, 'SE', 'Sweden', 'Sweden', NULL, '2019-07-28 10:54:30'),
(214, 'CH', 'Swiss Confederation', 'Switzerland', NULL, '2019-07-28 10:54:30'),
(215, 'SY', 'Syrian Arab Republic', 'Syrian Arab Republic', NULL, '2019-07-28 10:54:30'),
(216, 'TW', 'Taiwan', 'Taiwan', NULL, '2019-07-28 10:54:30'),
(217, 'TJ', 'Tajikistan', 'Tajikistan', NULL, '2019-07-28 10:54:30'),
(218, 'TZ', 'Tanzania', 'Tanzania', NULL, '2019-07-28 10:54:30'),
(219, 'TH', 'Thailand', 'Thailand', NULL, '2019-07-28 10:54:30'),
(220, 'TL', 'Timor-Leste', 'Timor-Leste', NULL, '2019-07-28 10:54:30'),
(221, 'TG', 'Togo', 'Togo', NULL, '2019-07-28 10:54:30'),
(222, 'TK', 'Tokelau', 'Tokelau', NULL, '2019-07-28 10:54:30'),
(223, 'TO', 'Tonga', 'Tonga', NULL, '2019-07-28 10:54:30'),
(224, 'TT', 'Trinidad and Tobago', 'Trinidad and Tobago', NULL, '2019-07-28 10:54:30'),
(225, 'TN', 'Tunisia', 'Tunisia', NULL, '2019-07-28 10:54:30'),
(226, 'TR', 'Turkey', 'Turkey', NULL, '2019-07-28 10:54:30'),
(227, 'TM', 'Turkmenistan', 'Turkmenistan', NULL, '2019-07-28 10:54:30'),
(228, 'TC', 'Turks and Caicos Islands', 'Turks and Caicos Islands', NULL, '2019-07-28 10:54:30'),
(229, 'TV', 'Tuvalu', 'Tuvalu', NULL, '2019-07-28 10:54:30'),
(230, 'UG', 'Uganda', 'Uganda', NULL, '2019-07-28 10:54:30'),
(231, 'UA', 'Ukraine', 'Ukraine', NULL, '2019-07-28 10:54:30'),
(232, 'AE', 'United Arab Emirates', 'United Arab Emirates', NULL, '2019-07-28 10:54:30'),
(233, 'GB', 'United Kingdom', 'United Kingdom', NULL, '2019-07-28 10:54:30'),
(234, 'US', 'United States of America', 'United States', NULL, '2019-07-28 10:54:30'),
(235, 'UM', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', NULL, '2019-07-28 10:54:30'),
(236, 'VI', 'United States Virgin Islands', 'United States Virgin Islands', NULL, '2019-07-28 10:54:30'),
(237, 'UY', 'Uruguay, Eastern Republic of', 'Uruguay', NULL, '2019-07-28 10:54:30'),
(238, 'UZ', 'Uzbekistan', 'Uzbekistan', NULL, '2019-07-28 10:54:30'),
(239, 'VU', 'Vanuatu', 'Vanuatu', NULL, '2019-07-28 10:54:30'),
(240, 'VE', 'Venezuela', 'Venezuela', NULL, '2019-07-28 10:54:30'),
(241, 'VN', 'Vietnam', 'Vietnam', NULL, '2019-07-28 10:54:30'),
(242, 'WF', 'Wallis and Futuna', 'Wallis and Futuna', NULL, '2019-07-28 10:54:30'),
(243, 'EH', 'Western Sahara', 'Western Sahara', NULL, '2019-07-28 10:54:30'),
(244, 'YE', 'Yemen', 'Yemen', NULL, '2019-07-28 10:54:30'),
(245, 'ZM', 'Zambia', 'Zambia', NULL, '2019-07-28 10:54:30'),
(246, 'ZW', 'Zimbabwe', 'Zimbabwe', NULL, '2019-07-28 10:54:30');

-- --------------------------------------------------------

--
-- Structure de la table `criterion`
--

CREATE TABLE `criterion` (
  `crt_id` int(11) NOT NULL,
  `stage_stg_id` int(11) DEFAULT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `criterion_name_cna_id` int(11) DEFAULT NULL,
  `cri_complete` tinyint(1) DEFAULT NULL,
  `cri_type` int(11) DEFAULT NULL,
  `cri_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cri_weight` double DEFAULT NULL,
  `cri_forceComment_compare` tinyint(1) DEFAULT NULL,
  `cri_forceCommentValue` double DEFAULT NULL,
  `cri_forceComment_sign` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cri_lowerbound` double DEFAULT NULL,
  `cri_upperbound` double DEFAULT NULL,
  `cri_step` double DEFAULT NULL,
  `cri_grade_type` int(11) DEFAULT NULL,
  `cri_comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cri_created_by` int(11) DEFAULT NULL,
  `cri_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cri_deleted` datetime DEFAULT NULL,
  `output_out_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `criterion_group`
--

CREATE TABLE `criterion_group` (
  `cgp_id` int(11) NOT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `department_dpt_id` int(11) DEFAULT NULL,
  `cgp_created_by` int(11) DEFAULT NULL,
  `cgp_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cgp_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `criterion_group`
--

INSERT INTO `criterion_group` (`cgp_id`, `organization_org_id`, `department_dpt_id`, `cgp_created_by`, `cgp_inserted`, `cgp_name`) VALUES
(1, 1, NULL, NULL, '2020-08-24 15:34:09', 'Hard skills'),
(2, 1, NULL, NULL, '2020-08-24 15:34:09', 'Soft skills'),
(9, 13, NULL, NULL, '2020-08-27 14:12:20', 'Hard skills'),
(10, 13, NULL, NULL, '2020-08-27 14:12:20', 'Soft skills'),
(15, 16, NULL, NULL, '2020-08-31 19:22:34', 'Hard skills'),
(16, 16, NULL, NULL, '2020-08-31 19:22:34', 'Soft skills'),
(17, 18, NULL, NULL, '2020-08-31 21:42:02', 'Hard skills'),
(18, 18, NULL, NULL, '2020-08-31 21:42:02', 'Soft skills'),
(19, 19, NULL, NULL, '2020-08-31 22:24:40', 'Hard skills'),
(20, 19, NULL, NULL, '2020-08-31 22:24:40', 'Soft skills'),
(21, 20, NULL, NULL, '2020-08-31 22:30:19', 'Hard skills'),
(22, 20, NULL, NULL, '2020-08-31 22:30:19', 'Soft skills'),
(23, 21, NULL, NULL, '2020-09-02 12:38:23', 'Hard skills'),
(24, 21, NULL, NULL, '2020-09-02 12:38:23', 'Soft skills'),
(25, 25, NULL, NULL, '2020-09-03 18:53:55', 'Hard skills'),
(26, 25, NULL, NULL, '2020-09-03 18:53:55', 'Soft skills'),
(27, 25, NULL, NULL, '2020-09-27 20:56:19', 'Hard skills'),
(28, 25, NULL, NULL, '2020-09-27 20:56:19', 'Soft skills'),
(33, 32, NULL, NULL, '2020-09-30 08:18:03', 'Hard skills'),
(34, 32, NULL, NULL, '2020-09-30 08:18:03', 'Soft skills'),
(35, 32, NULL, NULL, '2020-09-30 13:04:31', 'Hard skills'),
(36, 32, NULL, NULL, '2020-09-30 13:04:31', 'Soft skills'),
(47, 39, NULL, NULL, '2020-09-30 17:52:55', 'Hard skills'),
(48, 39, NULL, NULL, '2020-09-30 17:52:55', 'Soft skills'),
(49, 40, NULL, NULL, '2020-10-02 13:02:54', 'Hard skills'),
(50, 40, NULL, NULL, '2020-10-02 13:02:54', 'Soft skills'),
(63, 49, NULL, NULL, '2020-10-13 21:25:06', 'Hard skills'),
(64, 49, NULL, NULL, '2020-10-13 21:25:06', 'Soft skills'),
(67, 51, NULL, NULL, '2020-10-13 22:37:08', 'Hard skills'),
(68, 51, NULL, NULL, '2020-10-13 22:37:08', 'Soft skills'),
(77, 56, NULL, NULL, '2020-10-14 15:44:56', 'Hard skills'),
(78, 56, NULL, NULL, '2020-10-14 15:44:56', 'Soft skills'),
(79, 57, NULL, NULL, '2020-10-27 08:40:19', 'Hard skills'),
(80, 57, NULL, NULL, '2020-10-27 08:40:19', 'Soft skills'),
(93, 64, NULL, NULL, '2020-10-27 11:44:10', 'Hard skills'),
(94, 64, NULL, NULL, '2020-10-27 11:44:10', 'Soft skills'),
(95, 65, NULL, NULL, '2020-11-15 11:50:21', 'Hard skills'),
(96, 65, NULL, NULL, '2020-11-15 11:50:21', 'Soft skills'),
(97, 66, NULL, NULL, '2020-11-20 17:01:29', 'Hard skills'),
(98, 66, NULL, NULL, '2020-11-20 17:01:29', 'Soft skills'),
(99, 67, NULL, NULL, '2020-11-20 17:05:37', 'Hard skills'),
(100, 67, NULL, NULL, '2020-11-20 17:05:37', 'Soft skills'),
(105, 71, NULL, NULL, '2020-11-26 16:18:59', 'Hard skills'),
(106, 71, NULL, NULL, '2020-11-26 16:18:59', 'Soft skills'),
(107, 72, NULL, NULL, '2020-11-26 16:20:15', 'Hard skills'),
(108, 72, NULL, NULL, '2020-11-26 16:20:15', 'Soft skills'),
(151, 94, NULL, NULL, '2020-11-28 16:37:24', 'Hard skills'),
(152, 94, NULL, NULL, '2020-11-28 16:37:24', 'Soft skills'),
(153, 95, NULL, NULL, '2020-11-30 16:24:35', 'Hard skills'),
(154, 95, NULL, NULL, '2020-11-30 16:24:35', 'Soft skills'),
(155, 96, NULL, NULL, '2020-12-01 16:22:36', 'Hard skills'),
(156, 96, NULL, NULL, '2020-12-01 16:22:36', 'Soft skills'),
(157, 97, NULL, NULL, '2020-12-07 16:53:02', 'Hard skills'),
(158, 97, NULL, NULL, '2020-12-07 16:53:02', 'Soft skills'),
(159, 98, NULL, NULL, '2020-12-07 16:58:33', 'Hard skills'),
(160, 98, NULL, NULL, '2020-12-07 16:58:33', 'Soft skills'),
(161, 99, NULL, NULL, '2020-12-07 21:39:29', 'Hard skills'),
(162, 99, NULL, NULL, '2020-12-07 21:39:29', 'Soft skills'),
(163, 100, NULL, NULL, '2020-12-07 21:40:11', 'Hard skills'),
(164, 100, NULL, NULL, '2020-12-07 21:40:11', 'Soft skills'),
(165, 101, NULL, NULL, '2021-01-09 23:26:26', 'Hard skills'),
(166, 101, NULL, NULL, '2021-01-09 23:26:26', 'Soft skills'),
(167, 102, NULL, NULL, '2021-01-12 10:02:51', 'Hard skills'),
(168, 102, NULL, NULL, '2021-01-12 10:02:51', 'Soft skills'),
(179, 108, NULL, NULL, '2021-01-19 14:08:31', 'Hard skills'),
(180, 108, NULL, NULL, '2021-01-19 14:08:31', 'Soft skills'),
(187, 112, NULL, NULL, '2021-01-20 10:23:53', 'Hard skills'),
(188, 112, NULL, NULL, '2021-01-20 10:23:53', 'Soft skills'),
(189, 113, NULL, NULL, '2021-01-20 10:26:29', 'Hard skills'),
(190, 113, NULL, NULL, '2021-01-20 10:26:29', 'Soft skills'),
(191, 114, NULL, NULL, '2021-01-20 10:36:37', 'Hard skills'),
(192, 114, NULL, NULL, '2021-01-20 10:36:37', 'Soft skills'),
(237, 137, NULL, NULL, '2021-01-22 11:42:17', 'Hard skills'),
(238, 137, NULL, NULL, '2021-01-22 11:42:17', 'Soft skills'),
(239, 138, NULL, NULL, '2021-01-24 10:06:38', 'Hard skills'),
(240, 138, NULL, NULL, '2021-01-24 10:06:38', 'Soft skills'),
(241, 139, NULL, NULL, '2021-01-29 11:05:12', 'Hard skills'),
(242, 139, NULL, NULL, '2021-01-29 11:05:12', 'Soft skills'),
(249, 145, NULL, NULL, '2021-01-31 10:58:28', 'Hard skills'),
(250, 145, NULL, NULL, '2021-01-31 10:58:28', 'Soft skills'),
(251, 146, NULL, NULL, '2021-01-31 10:59:22', 'Hard skills'),
(252, 146, NULL, NULL, '2021-01-31 10:59:22', 'Soft skills'),
(253, 147, NULL, NULL, '2021-02-02 14:33:52', 'Hard skills'),
(254, 147, NULL, NULL, '2021-02-02 14:33:52', 'Soft skills'),
(255, 148, NULL, NULL, '2021-02-02 16:12:59', 'Hard skills'),
(256, 148, NULL, NULL, '2021-02-02 16:12:59', 'Soft skills');

-- --------------------------------------------------------

--
-- Structure de la table `criterion_name`
--

CREATE TABLE `criterion_name` (
  `cna_id` int(11) NOT NULL,
  `icon_ico_id` int(11) DEFAULT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `department_dpt_id` int(11) DEFAULT NULL,
  `criterion_group_cgp_id` int(11) DEFAULT NULL,
  `cna_type` int(11) DEFAULT NULL,
  `cna_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `can_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `can_created_by` int(11) DEFAULT NULL,
  `can_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `criterion_name`
--

INSERT INTO `criterion_name` (`cna_id`, `icon_ico_id`, `organization_org_id`, `department_dpt_id`, `criterion_group_cgp_id`, `cna_type`, `cna_name`, `can_unit`, `can_created_by`, `can_inserted`) VALUES
(1, NULL, NULL, NULL, NULL, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(2, NULL, NULL, NULL, NULL, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(3, NULL, NULL, NULL, NULL, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(4, NULL, NULL, NULL, NULL, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(5, NULL, NULL, NULL, NULL, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(6, NULL, NULL, NULL, NULL, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(7, NULL, 1, NULL, 1, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(8, NULL, 1, NULL, 1, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(9, NULL, 1, NULL, 1, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(10, NULL, 1, NULL, 2, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(11, NULL, 1, NULL, 2, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(12, NULL, 1, NULL, 2, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(31, NULL, 13, NULL, 9, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(32, NULL, 13, NULL, 9, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(33, NULL, 13, NULL, 9, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(34, NULL, 13, NULL, 10, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(35, NULL, 13, NULL, 10, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(36, NULL, 13, NULL, 10, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(49, NULL, 16, NULL, 15, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(50, NULL, 16, NULL, 15, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(51, NULL, 16, NULL, 15, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(52, NULL, 16, NULL, 16, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(53, NULL, 16, NULL, 16, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(54, NULL, 16, NULL, 16, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(55, NULL, 18, NULL, 17, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(56, NULL, 18, NULL, 17, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(57, NULL, 18, NULL, 17, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(58, NULL, 18, NULL, 18, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(59, NULL, 18, NULL, 18, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(60, NULL, 18, NULL, 18, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(61, NULL, 19, NULL, 19, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(62, NULL, 19, NULL, 19, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(63, NULL, 19, NULL, 19, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(64, NULL, 19, NULL, 20, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(65, NULL, 19, NULL, 20, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(66, NULL, 19, NULL, 20, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(67, NULL, 20, NULL, 21, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(68, NULL, 20, NULL, 21, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(69, NULL, 20, NULL, 21, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(70, NULL, 20, NULL, 22, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(71, NULL, 20, NULL, 22, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(72, NULL, 20, NULL, 22, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(73, NULL, 21, NULL, 23, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(74, NULL, 21, NULL, 23, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(75, NULL, 21, NULL, 23, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(76, NULL, 21, NULL, 24, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(77, NULL, 21, NULL, 24, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(78, NULL, 21, NULL, 24, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(79, NULL, 25, NULL, 25, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(80, NULL, 25, NULL, 25, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(81, NULL, 25, NULL, 25, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(82, NULL, 25, NULL, 26, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(83, NULL, 25, NULL, 26, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(84, NULL, 25, NULL, 26, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(85, NULL, 25, NULL, 27, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(86, NULL, 25, NULL, 27, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(87, NULL, 25, NULL, 27, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(88, NULL, 25, NULL, 28, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(89, NULL, 25, NULL, 28, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(90, NULL, 25, NULL, 28, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(103, NULL, 32, NULL, 33, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(104, NULL, 32, NULL, 33, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(105, NULL, 32, NULL, 33, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(106, NULL, 32, NULL, 34, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(107, NULL, 32, NULL, 34, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(108, NULL, 32, NULL, 34, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(109, NULL, 32, NULL, 35, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(110, NULL, 32, NULL, 35, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(111, NULL, 32, NULL, 35, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(112, NULL, 32, NULL, 36, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(113, NULL, 32, NULL, 36, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(114, NULL, 32, NULL, 36, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(145, NULL, 39, NULL, 47, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(146, NULL, 39, NULL, 47, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(147, NULL, 39, NULL, 47, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(148, NULL, 39, NULL, 48, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(149, NULL, 39, NULL, 48, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(150, NULL, 39, NULL, 48, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(151, NULL, 40, NULL, 49, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(152, NULL, 40, NULL, 49, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(153, NULL, 40, NULL, 49, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(154, NULL, 40, NULL, 50, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(155, NULL, 40, NULL, 50, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(156, NULL, 40, NULL, 50, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(193, NULL, 49, NULL, 63, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(194, NULL, 49, NULL, 63, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(195, NULL, 49, NULL, 63, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(196, NULL, 49, NULL, 64, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(197, NULL, 49, NULL, 64, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(198, NULL, 49, NULL, 64, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(205, NULL, 51, NULL, 67, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(206, NULL, 51, NULL, 67, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(207, NULL, 51, NULL, 67, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(208, NULL, 51, NULL, 68, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(209, NULL, 51, NULL, 68, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(210, NULL, 51, NULL, 68, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(235, NULL, 56, NULL, 77, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(236, NULL, 56, NULL, 77, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(237, NULL, 56, NULL, 77, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(238, NULL, 56, NULL, 78, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(239, NULL, 56, NULL, 78, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(240, NULL, 56, NULL, 78, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(241, NULL, 57, NULL, 79, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(242, NULL, 57, NULL, 79, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(243, NULL, 57, NULL, 79, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(244, NULL, 57, NULL, 80, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(245, NULL, 57, NULL, 80, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(246, NULL, 57, NULL, 80, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(283, NULL, 64, NULL, 93, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(284, NULL, 64, NULL, 93, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(285, NULL, 64, NULL, 93, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(286, NULL, 64, NULL, 94, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(287, NULL, 64, NULL, 94, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(288, NULL, 64, NULL, 94, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(289, NULL, 65, NULL, 95, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(290, NULL, 65, NULL, 95, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(291, NULL, 65, NULL, 95, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(292, NULL, 65, NULL, 96, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(293, NULL, 65, NULL, 96, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(294, NULL, 65, NULL, 96, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(295, NULL, 66, NULL, 97, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(296, NULL, 66, NULL, 97, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(297, NULL, 66, NULL, 97, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(298, NULL, 66, NULL, 98, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(299, NULL, 66, NULL, 98, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(300, NULL, 66, NULL, 98, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(301, NULL, 67, NULL, 99, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(302, NULL, 67, NULL, 99, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(303, NULL, 67, NULL, 99, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(304, NULL, 67, NULL, 100, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(305, NULL, 67, NULL, 100, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(306, NULL, 67, NULL, 100, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(319, NULL, 71, NULL, 105, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(320, NULL, 71, NULL, 105, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(321, NULL, 71, NULL, 105, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(322, NULL, 71, NULL, 106, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(323, NULL, 71, NULL, 106, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(324, NULL, 71, NULL, 106, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(325, NULL, 72, NULL, 107, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(326, NULL, 72, NULL, 107, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(327, NULL, 72, NULL, 107, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(328, NULL, 72, NULL, 108, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(329, NULL, 72, NULL, 108, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(330, NULL, 72, NULL, 108, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(457, NULL, 94, NULL, 151, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(458, NULL, 94, NULL, 151, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(459, NULL, 94, NULL, 151, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(460, NULL, 94, NULL, 152, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(461, NULL, 94, NULL, 152, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(462, NULL, 94, NULL, 152, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(463, NULL, 95, NULL, 153, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(464, NULL, 95, NULL, 153, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(465, NULL, 95, NULL, 153, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(466, NULL, 95, NULL, 154, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(467, NULL, 95, NULL, 154, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(468, NULL, 95, NULL, 154, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(469, NULL, 96, NULL, 155, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(470, NULL, 96, NULL, 155, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(471, NULL, 96, NULL, 155, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(472, NULL, 96, NULL, 156, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(473, NULL, 96, NULL, 156, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(474, NULL, 96, NULL, 156, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(475, NULL, 97, NULL, 157, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(476, NULL, 97, NULL, 157, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(477, NULL, 97, NULL, 157, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(478, NULL, 97, NULL, 158, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(479, NULL, 97, NULL, 158, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(480, NULL, 97, NULL, 158, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(481, NULL, 98, NULL, 159, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(482, NULL, 98, NULL, 159, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(483, NULL, 98, NULL, 159, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(484, NULL, 98, NULL, 160, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(485, NULL, 98, NULL, 160, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(486, NULL, 98, NULL, 160, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(487, NULL, 99, NULL, 161, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(488, NULL, 99, NULL, 161, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(489, NULL, 99, NULL, 161, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(490, NULL, 99, NULL, 162, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(491, NULL, 99, NULL, 162, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(492, NULL, 99, NULL, 162, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(493, NULL, 100, NULL, 163, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(494, NULL, 100, NULL, 163, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(495, NULL, 100, NULL, 163, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(496, NULL, 100, NULL, 164, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(497, NULL, 100, NULL, 164, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(498, NULL, 100, NULL, 164, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(499, NULL, 101, NULL, 165, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(500, NULL, 101, NULL, 165, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(501, NULL, 101, NULL, 165, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(502, NULL, 101, NULL, 166, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(503, NULL, 101, NULL, 166, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(504, NULL, 101, NULL, 166, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(505, NULL, 102, NULL, 167, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(506, NULL, 102, NULL, 167, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(507, NULL, 102, NULL, 167, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(508, NULL, 102, NULL, 168, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(509, NULL, 102, NULL, 168, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(510, NULL, 102, NULL, 168, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(541, NULL, 108, NULL, 179, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(542, NULL, 108, NULL, 179, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(543, NULL, 108, NULL, 179, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(544, NULL, 108, NULL, 180, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(545, NULL, 108, NULL, 180, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(546, NULL, 108, NULL, 180, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(565, NULL, 112, NULL, 187, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(566, NULL, 112, NULL, 187, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(567, NULL, 112, NULL, 187, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(568, NULL, 112, NULL, 188, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(569, NULL, 112, NULL, 188, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(570, NULL, 112, NULL, 188, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(571, NULL, 113, NULL, 189, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(572, NULL, 113, NULL, 189, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(573, NULL, 113, NULL, 189, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(574, NULL, 113, NULL, 190, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(575, NULL, 113, NULL, 190, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(576, NULL, 113, NULL, 190, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(577, NULL, 114, NULL, 191, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(578, NULL, 114, NULL, 191, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(579, NULL, 114, NULL, 191, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(580, NULL, 114, NULL, 192, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(581, NULL, 114, NULL, 192, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(582, NULL, 114, NULL, 192, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(715, NULL, 137, NULL, 237, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(716, NULL, 137, NULL, 237, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(717, NULL, 137, NULL, 237, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(718, NULL, 137, NULL, 238, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(719, NULL, 137, NULL, 238, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(720, NULL, 137, NULL, 238, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(721, NULL, 138, NULL, 239, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(722, NULL, 138, NULL, 239, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(723, NULL, 138, NULL, 239, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(724, NULL, 138, NULL, 240, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(725, NULL, 138, NULL, 240, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(726, NULL, 138, NULL, 240, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(727, NULL, 139, NULL, 241, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(728, NULL, 139, NULL, 241, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(729, NULL, 139, NULL, 241, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(730, NULL, 139, NULL, 242, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(731, NULL, 139, NULL, 242, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(732, NULL, 139, NULL, 242, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(751, NULL, 145, NULL, 249, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(752, NULL, 145, NULL, 249, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(753, NULL, 145, NULL, 249, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(754, NULL, 145, NULL, 250, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(755, NULL, 145, NULL, 250, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(756, NULL, 145, NULL, 250, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(757, NULL, 146, NULL, 251, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(758, NULL, 146, NULL, 251, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(759, NULL, 146, NULL, 251, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(760, NULL, 146, NULL, 252, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(761, NULL, 146, NULL, 252, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(762, NULL, 146, NULL, 252, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(763, NULL, 147, NULL, 253, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(764, NULL, 147, NULL, 253, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(765, NULL, 147, NULL, 253, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(766, NULL, 147, NULL, 254, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(767, NULL, 147, NULL, 254, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(768, NULL, 147, NULL, 254, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09'),
(769, NULL, 148, NULL, 255, 1, 'Python', '', NULL, '2020-08-24 15:34:09'),
(770, NULL, 148, NULL, 255, 1, 'Macro Excel', '', NULL, '2020-08-24 15:34:09'),
(771, NULL, 148, NULL, 255, 1, 'Gestion de projet', '', NULL, '2020-08-24 15:34:09'),
(772, NULL, 148, NULL, 256, 2, 'Ponctualité', '', NULL, '2020-08-24 15:34:09'),
(773, NULL, 148, NULL, 256, 2, 'Confiance en soi', '', NULL, '2020-08-24 15:34:09'),
(774, NULL, 148, NULL, 256, 2, 'Travail en équipe', '', NULL, '2020-08-24 15:34:09');

-- --------------------------------------------------------

--
-- Structure de la table `decision`
--

CREATE TABLE `decision` (
  `dec_id` int(11) NOT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `activity_act_id` int(11) DEFAULT NULL,
  `stage_stg_id` int(11) DEFAULT NULL,
  `dec_type` int(11) DEFAULT NULL,
  `req_anon` int(11) DEFAULT NULL,
  `dec_anon` tinyint(1) DEFAULT NULL,
  `val_usr_id` int(11) DEFAULT NULL,
  `dec_result` int(11) DEFAULT NULL,
  `dec_created_by` int(11) DEFAULT NULL,
  `dec_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dec_decided` datetime DEFAULT NULL,
  `dec_validated` datetime DEFAULT NULL,
  `req_usr_id` int(11) DEFAULT NULL,
  `dec_usr_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `department`
--

CREATE TABLE `department` (
  `dpt_id` int(11) NOT NULL,
  `organization_org_id` int(11) NOT NULL,
  `dpt_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dpt_created_by` int(11) DEFAULT NULL,
  `dpt_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dpt_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `department`
--

INSERT INTO `department` (`dpt_id`, `organization_org_id`, `dpt_name`, `dpt_created_by`, `dpt_inserted`, `dpt_deleted`) VALUES
(1, 1, 'Development', NULL, '2020-08-24 15:34:09', NULL),
(3, 25, 'Management', NULL, '2020-09-03 18:53:55', NULL),
(23, 102, 'Impots', NULL, '2021-01-28 22:27:32', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20200824153245', '2020-08-24 15:32:49', 4238),
('DoctrineMigrations\\Version20200826092141', '2020-08-26 09:22:20', 99),
('DoctrineMigrations\\Version20200826094953', '2020-08-26 09:49:59', 44),
('DoctrineMigrations\\Version20200827092501', '2020-08-27 09:25:06', 68),
('DoctrineMigrations\\Version20200827121611', '2020-08-27 12:16:18', 42),
('DoctrineMigrations\\Version20200827134532', '2020-08-27 13:45:36', 96),
('DoctrineMigrations\\Version20200827134642', '2020-08-27 13:53:09', 61),
('DoctrineMigrations\\Version20200827135314', '2020-08-27 13:53:19', 43),
('DoctrineMigrations\\Version20200827171625', '2020-08-27 17:16:29', 86),
('DoctrineMigrations\\Version20200828172504', '2020-08-28 17:25:21', 419),
('DoctrineMigrations\\Version20200829140154', '2020-08-29 14:02:01', 175),
('DoctrineMigrations\\Version20200912091012', '2020-09-12 09:10:44', 208),
('DoctrineMigrations\\Version20201014091357', '2020-10-14 09:15:12', 135);

-- --------------------------------------------------------

--
-- Structure de la table `document_author`
--

CREATE TABLE `document_author` (
  `dau_id` int(11) NOT NULL,
  `event_document_evd_id` int(11) NOT NULL,
  `user_usr_id` int(11) DEFAULT NULL,
  `dau_leader` tinyint(1) DEFAULT NULL,
  `dau_created_by` int(11) DEFAULT NULL,
  `dau_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `document_author`
--

INSERT INTO `document_author` (`dau_id`, `event_document_evd_id`, `user_usr_id`, `dau_leader`, `dau_created_by`, `dau_inserted`) VALUES
(1, 32, 128, 0, NULL, '2020-11-14 11:20:12'),
(2, 33, 145, 0, NULL, '2020-11-14 13:21:49'),
(5, 36, 1, 0, NULL, '2020-12-01 13:45:32'),
(11, 42, 1, 0, NULL, '2020-12-19 14:35:05'),
(12, 43, 223, 0, NULL, '2021-01-29 15:18:23');

-- --------------------------------------------------------

--
-- Structure de la table `dynamic_translation`
--

CREATE TABLE `dynamic_translation` (
  `dtr_id` int(11) NOT NULL,
  `dtr_entity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dtr_entity_id` int(11) NOT NULL,
  `dtr_fr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dtr_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dtr_de` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dtr_es` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dtr_lu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dtr_entity_prop` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `dtr_created_by` int(11) DEFAULT NULL,
  `dtr_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dynamic_translation`
--

INSERT INTO `dynamic_translation` (`dtr_id`, `dtr_entity`, `dtr_entity_id`, `dtr_fr`, `dtr_en`, `dtr_de`, `dtr_es`, `dtr_lu`, `dtr_entity_prop`, `organization_org_id`, `dtr_created_by`, `dtr_inserted`) VALUES
(1, 'EventGroupName', 1, 'Rendu / livrable', 'Deliverable', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 18:10:29'),
(2, 'EventGroupName', 2, 'Suggestion', 'Suggestion', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 18:14:03'),
(3, 'EventGroupName', 3, 'Incident', 'Incident', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 18:14:03'),
(4, 'EventGroupName', 4, 'Flux financier', 'Cashflow', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 18:14:03'),
(5, 'EventGroupName', 5, 'Requête', 'Request', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 18:14:03'),
(6, 'EventGroupName', 6, 'Information', 'Information', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 18:14:03'),
(16, 'EventName', 1, 'Rapport', 'Report', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 21:26:18'),
(17, 'EventName', 2, 'Presentation', 'Présentation', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 21:26:18'),
(18, 'EventName', 3, 'Contrat', 'Contract', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 21:26:18'),
(19, 'EventName', 4, 'Données / Graphique', 'Data / Graph', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 21:26:18'),
(20, 'EventName', 5, 'Enregistrement son/vidéo', 'Video / Sound recording', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 21:26:18'),
(21, 'EventName', 6, 'Compte-rendu', 'Minutes', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 21:26:18'),
(22, 'EventName', 7, 'Devis / Proposition contractuelle', 'Estimate / Contract proposition', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 21:26:18'),
(23, 'EventName', 8, 'Livraison', 'Physical delivery', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 21:26:18'),
(24, 'EventName', 9, 'Paiement partiel', 'Partial payment', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-14 21:26:18'),
(25, 'EventName', 11, 'Remboursement', 'Reimbursement', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:34:58'),
(26, 'EventName', 15, 'Article', 'Article', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:34:58'),
(27, 'EventName', 16, 'Post réseau social', 'Social media post', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:34:58'),
(28, 'EventName', 18, 'Report de date', 'Rescheduling', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:34:58'),
(29, 'EventName', 19, 'Amélioration', 'Enhancement', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:34:58'),
(30, 'EventName', 20, 'Service additionnel', 'Additional service', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:34:58'),
(31, 'EventName', 22, 'Retard probable', 'Possible delay', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:34:58'),
(32, 'EventName', 21, 'Retour / Feedback', 'Feedback', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:34:58'),
(33, 'EventName', 23, 'Modifications', 'Changes', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:40:27'),
(34, 'EventName', 24, 'Information additionnelle', 'Additional info', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:40:27'),
(35, 'EventName', 25, 'Candidature', 'Application', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:40:27'),
(36, 'EventName', 26, 'Livraison', 'Delivery', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:40:27'),
(37, 'EventName', 27, 'Paiement manquant', 'Missing payment', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:40:27'),
(38, 'EventName', 28, 'En retard', 'Belated', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:40:27'),
(39, 'EventName', 33, 'Retour / Feedback', 'Feedback', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:40:27'),
(40, 'EventName', 34, 'Délai additionnel', 'Additional delay', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:40:27'),
(41, 'EventName', 35, 'Modifications', 'Changes', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:53:21'),
(42, 'EventName', 36, 'Information supplémentaire', 'Additional information', NULL, NULL, NULL, 'name', NULL, NULL, '2020-10-15 12:53:21'),
(43, 'EventType', 1319, 'Rappo', 'Report', NULL, NULL, NULL, 'name', NULL, 213, '2020-12-17 19:20:27');

-- --------------------------------------------------------

--
-- Structure de la table `element_update`
--

CREATE TABLE `element_update` (
  `upd_id` int(11) NOT NULL,
  `department_dpt_id` int(11) DEFAULT NULL,
  `position_pos_id` int(11) DEFAULT NULL,
  `institution_process_inp_id` int(11) DEFAULT NULL,
  `activity_act_id` int(11) DEFAULT NULL,
  `stage_stg_id` int(11) DEFAULT NULL,
  `event_eve_id` int(11) DEFAULT NULL,
  `event_document_evd_id` int(11) DEFAULT NULL,
  `event_comment_evc_id` int(11) DEFAULT NULL,
  `output_otp_id` int(11) DEFAULT NULL,
  `criterion_crt_id` int(11) DEFAULT NULL,
  `participation_par_id` int(11) DEFAULT NULL,
  `result_res_id` int(11) DEFAULT NULL,
  `user_usr_id` int(11) DEFAULT NULL,
  `upd_type` int(11) DEFAULT NULL,
  `upd_prop` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upd_created_by` int(11) DEFAULT NULL,
  `upd_viewed` datetime DEFAULT NULL,
  `upd_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd_mailed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `element_update`
--

INSERT INTO `element_update` (`upd_id`, `department_dpt_id`, `position_pos_id`, `institution_process_inp_id`, `activity_act_id`, `stage_stg_id`, `event_eve_id`, `event_document_evd_id`, `event_comment_evc_id`, `output_otp_id`, `criterion_crt_id`, `participation_par_id`, `result_res_id`, `user_usr_id`, `upd_type`, `upd_prop`, `upd_created_by`, `upd_viewed`, `upd_inserted`, `upd_mailed`) VALUES
(92, NULL, NULL, NULL, 132, 134, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 145, 0, 'content', 128, '2020-11-11 14:14:49', '2020-11-11 09:39:02', NULL),
(116, NULL, NULL, NULL, 136, 138, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 145, 0, 'content', 128, '2020-11-11 23:57:50', '2020-11-11 23:51:35', NULL),
(135, NULL, NULL, NULL, 136, 138, 145, NULL, NULL, NULL, NULL, NULL, NULL, 128, 0, NULL, 145, '2020-11-14 10:35:47', '2020-11-14 10:35:29', NULL),
(136, NULL, NULL, NULL, 136, 138, 145, NULL, 96, NULL, NULL, NULL, NULL, 128, 0, 'content', 145, '2020-11-19 10:07:31', '2020-11-14 10:36:14', '2020-11-18 23:48:36'),
(137, NULL, NULL, NULL, 136, 138, 145, 32, NULL, NULL, NULL, NULL, NULL, 145, 0, NULL, 128, '2020-11-14 11:21:06', '2020-11-14 11:20:12', NULL),
(138, NULL, NULL, NULL, 137, 139, 146, NULL, NULL, NULL, NULL, NULL, NULL, 145, 0, NULL, 128, '2020-11-14 13:19:27', '2020-11-14 13:10:36', NULL),
(139, NULL, NULL, NULL, 137, 139, 147, NULL, NULL, NULL, NULL, NULL, NULL, 128, 0, NULL, 145, '2020-11-19 10:07:31', '2020-11-14 13:21:49', '2020-11-18 23:48:36'),
(140, NULL, NULL, NULL, 137, 139, 147, 33, NULL, NULL, NULL, NULL, NULL, 128, 0, NULL, 145, '2020-11-19 10:07:31', '2020-11-14 13:21:49', '2020-11-18 23:48:36'),
(141, NULL, NULL, NULL, 136, 138, 145, NULL, NULL, NULL, NULL, NULL, NULL, 145, 1, 'dates', 128, '2020-11-20 15:30:36', '2020-11-20 09:05:55', NULL),
(142, NULL, NULL, NULL, 136, 138, 145, NULL, NULL, NULL, NULL, NULL, NULL, 145, 1, 'onsetDate', 128, '2020-11-20 15:30:36', '2020-11-20 09:06:08', NULL),
(143, NULL, NULL, NULL, 136, 138, 145, NULL, NULL, NULL, NULL, NULL, NULL, 145, 1, 'expResDate', 128, '2020-11-20 15:30:36', '2020-11-20 09:07:13', NULL),
(144, NULL, NULL, NULL, 136, 138, 145, NULL, NULL, NULL, NULL, NULL, NULL, 145, 1, 'dates', 128, '2020-11-20 15:30:36', '2020-11-20 09:30:04', NULL),
(145, NULL, NULL, NULL, 138, 140, 149, NULL, NULL, NULL, NULL, NULL, NULL, 145, 0, NULL, 128, '2020-11-20 15:30:36', '2020-11-20 15:30:17', NULL),
(146, NULL, NULL, NULL, 138, 140, 149, NULL, NULL, NULL, NULL, NULL, NULL, 145, 1, 'onsetDate', 128, '2020-12-02 11:32:18', '2020-11-20 15:33:22', '2020-11-21 11:05:42'),
(147, NULL, NULL, NULL, 138, 140, 149, NULL, NULL, NULL, NULL, NULL, NULL, 145, 1, 'onsetDate', 128, '2020-12-02 11:32:18', '2020-11-20 15:33:36', '2020-11-21 11:05:42'),
(148, NULL, NULL, NULL, 136, 138, 145, 32, NULL, NULL, NULL, NULL, NULL, 145, 1, 'content', 128, '2020-12-02 11:32:18', '2020-11-23 17:27:21', '2020-11-23 19:26:11'),
(149, NULL, NULL, NULL, 136, 138, 145, 32, NULL, NULL, NULL, NULL, NULL, 145, 1, 'content', 128, '2020-12-02 11:32:18', '2020-11-23 17:35:58', '2020-11-23 19:26:11'),
(165, NULL, NULL, NULL, 132, 134, 158, NULL, NULL, NULL, NULL, NULL, NULL, 145, 0, NULL, 128, '2020-12-02 11:32:18', '2020-11-24 17:52:04', '2020-11-24 17:55:05'),
(166, NULL, NULL, NULL, 132, 134, 158, NULL, 104, NULL, NULL, NULL, NULL, 145, 0, 'content', 128, '2020-12-02 11:32:18', '2020-11-24 17:52:04', '2020-11-24 17:55:05'),
(167, NULL, NULL, NULL, 139, 141, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 128, 0, 'content', 161, '2020-12-02 11:29:00', '2020-11-30 19:08:43', '2020-11-30 19:17:20'),
(172, NULL, NULL, NULL, 20, 22, 161, NULL, NULL, NULL, NULL, NULL, NULL, 8, 0, NULL, 1, NULL, '2020-12-01 13:45:32', '2020-12-01 14:11:52'),
(173, NULL, NULL, NULL, 20, 22, 161, 36, NULL, NULL, NULL, NULL, NULL, 8, 0, NULL, 1, NULL, '2020-12-01 13:45:32', '2020-12-01 14:11:52'),
(174, NULL, NULL, NULL, 20, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 1, 'enddate', 1, NULL, '2020-12-08 16:59:05', '2020-12-08 17:20:12'),
(175, NULL, NULL, NULL, 20, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 199, 1, 'enddate', 1, '2021-01-24 10:06:49', '2020-12-08 16:59:05', '2020-12-08 17:20:12'),
(176, NULL, NULL, NULL, 20, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 1, 'enddate', 1, NULL, '2020-12-08 17:04:03', '2020-12-08 17:20:12'),
(177, NULL, NULL, NULL, 20, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 199, 1, 'enddate', 1, '2021-01-24 10:06:49', '2020-12-08 17:04:03', '2020-12-08 17:20:12'),
(178, NULL, NULL, NULL, 19, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, 1, 'startdate', 1, NULL, '2020-12-14 17:36:48', '2020-12-15 15:25:35'),
(185, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 0, 'content', 223, '2021-01-29 11:05:21', '2021-01-25 09:50:28', '2021-01-25 10:04:51'),
(186, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, 'startdate', 223, '2021-01-29 11:05:21', '2021-01-25 11:29:02', '2021-01-25 11:47:46'),
(187, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, 'startdate', 223, '2021-01-29 11:05:21', '2021-01-25 11:42:47', '2021-01-25 11:47:46'),
(188, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, 'startdate', 223, '2021-01-29 11:05:21', '2021-01-25 11:43:22', '2021-01-25 11:47:46'),
(189, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, 'startdate', 223, '2021-01-29 11:05:21', '2021-01-25 11:51:26', '2021-01-25 12:27:46'),
(190, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, 'startdate', 223, '2021-01-29 11:05:21', '2021-01-25 11:53:04', '2021-01-25 12:27:46'),
(191, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, 'startdate', 223, '2021-01-29 11:05:21', '2021-01-25 11:57:35', '2021-01-25 12:27:46'),
(192, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, 'startdate', 223, '2021-01-29 11:05:21', '2021-01-25 12:04:13', '2021-01-25 12:27:46'),
(193, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, 'startdate', 223, '2021-01-29 11:05:21', '2021-01-25 12:06:09', '2021-01-25 12:27:46'),
(194, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, 'startdate', 223, '2021-01-29 11:05:21', '2021-01-25 12:08:20', '2021-01-25 12:27:46'),
(195, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, 'startdate', 223, '2021-01-29 11:05:21', '2021-01-25 12:08:39', '2021-01-25 12:27:46'),
(196, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, 'startdate', 223, '2021-01-29 11:05:21', '2021-01-25 12:13:10', '2021-01-25 12:27:46'),
(197, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, 'startdate', 223, '2021-01-29 11:05:21', '2021-01-25 12:16:42', '2021-01-25 12:27:46'),
(198, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, 'startdate', 223, '2021-01-29 11:05:21', '2021-01-25 12:19:21', '2021-01-25 12:27:46'),
(199, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:16:20', '2021-01-25 15:21:54'),
(200, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:16:31', '2021-01-25 15:21:54'),
(201, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:17:07', '2021-01-25 15:21:54'),
(202, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:17:36', '2021-01-25 15:21:54'),
(203, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:19:53', '2021-01-25 15:21:54'),
(204, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:20:02', '2021-01-25 15:21:54'),
(205, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:24:59', '2021-01-25 16:01:53'),
(206, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:25:07', '2021-01-25 16:01:53'),
(207, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:29:36', '2021-01-25 16:01:53'),
(208, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:30:41', '2021-01-25 16:01:53'),
(209, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:31:32', '2021-01-25 16:01:53'),
(210, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:31:42', '2021-01-25 16:01:53'),
(211, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:37:04', '2021-01-25 16:01:53'),
(212, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 15:37:39', '2021-01-25 16:01:53'),
(213, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 16:01:48', '2021-01-25 16:01:53'),
(214, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 16:01:54', '2021-01-25 16:33:17'),
(215, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 16:02:07', '2021-01-25 16:33:17'),
(216, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 16:03:40', '2021-01-25 16:33:17'),
(217, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 16:03:44', '2021-01-25 16:33:17'),
(218, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 16:04:30', '2021-01-25 16:33:17'),
(219, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 16:04:34', '2021-01-25 16:33:17'),
(220, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 16:06:29', '2021-01-25 16:33:17'),
(221, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 16:06:33', '2021-01-25 16:33:17'),
(222, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 16:06:40', '2021-01-25 16:33:17'),
(223, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 16:06:43', '2021-01-25 16:33:17'),
(224, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, '2021-01-29 11:05:21', '2021-01-25 16:07:17', '2021-01-25 16:33:17'),
(265, NULL, NULL, NULL, 152, 154, 182, NULL, NULL, NULL, NULL, NULL, NULL, 226, 0, NULL, 223, '2021-01-29 15:14:59', '2021-01-29 15:13:57', NULL),
(266, NULL, NULL, NULL, 152, 154, 182, NULL, NULL, NULL, NULL, NULL, NULL, 199, 0, NULL, 223, NULL, '2021-01-29 15:13:57', '2021-01-29 15:49:13'),
(267, NULL, NULL, NULL, 152, 154, 182, 43, NULL, NULL, NULL, NULL, NULL, 226, 0, NULL, 223, '2021-01-29 15:19:50', '2021-01-29 15:18:23', NULL),
(268, NULL, NULL, NULL, 152, 154, 182, 43, NULL, NULL, NULL, NULL, NULL, 199, 0, NULL, 223, NULL, '2021-01-29 15:18:23', '2021-01-29 15:49:13'),
(269, NULL, NULL, NULL, 152, 154, 182, NULL, 105, NULL, NULL, NULL, NULL, 223, 0, 'content', 226, '2021-01-29 15:30:34', '2021-01-29 15:30:24', NULL),
(270, NULL, NULL, NULL, 152, 154, 182, NULL, 105, NULL, NULL, NULL, NULL, 199, 0, 'content', 226, NULL, '2021-01-29 15:30:24', '2021-01-29 15:49:13'),
(271, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 300, 1, NULL, 223, NULL, '2021-02-01 10:45:11', '2021-02-01 15:17:47'),
(272, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 199, 1, NULL, 223, NULL, '2021-02-01 10:45:11', '2021-02-01 11:08:01'),
(273, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, NULL, '2021-02-01 10:45:11', '2021-02-01 11:08:01'),
(274, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 300, 1, NULL, 223, NULL, '2021-02-01 10:45:28', '2021-02-01 15:17:47'),
(275, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 199, 1, NULL, 223, NULL, '2021-02-01 10:45:28', '2021-02-01 11:08:01'),
(276, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, NULL, '2021-02-01 10:45:28', '2021-02-01 11:08:01'),
(277, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 300, 1, NULL, 223, NULL, '2021-02-01 11:24:03', '2021-02-01 15:17:47'),
(278, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 199, 1, NULL, 223, NULL, '2021-02-01 11:24:03', '2021-02-01 15:17:47'),
(279, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, NULL, '2021-02-01 11:24:03', '2021-02-01 15:17:47'),
(280, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 300, 1, NULL, 223, NULL, '2021-02-01 11:24:27', '2021-02-01 15:17:47'),
(281, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 199, 1, NULL, 223, NULL, '2021-02-01 11:24:27', '2021-02-01 15:17:47'),
(282, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, NULL, '2021-02-01 11:24:27', '2021-02-01 15:17:47'),
(283, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 300, 1, NULL, 223, NULL, '2021-02-01 16:45:14', '2021-02-01 17:23:29'),
(284, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 199, 1, NULL, 223, NULL, '2021-02-01 16:45:14', '2021-02-01 17:23:29'),
(285, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, NULL, '2021-02-01 16:45:14', '2021-02-01 17:23:29'),
(286, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 300, 1, NULL, 223, NULL, '2021-02-01 16:48:32', '2021-02-01 17:23:29'),
(287, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 199, 1, NULL, 223, NULL, '2021-02-01 16:48:32', '2021-02-01 17:23:29'),
(288, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, NULL, '2021-02-01 16:48:32', '2021-02-01 17:23:29'),
(289, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 300, 1, NULL, 223, NULL, '2021-02-01 16:55:26', '2021-02-01 17:23:29'),
(290, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 199, 1, NULL, 223, NULL, '2021-02-01 16:55:26', '2021-02-01 17:23:29'),
(291, NULL, NULL, NULL, 152, 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 226, 1, NULL, 223, NULL, '2021-02-01 16:55:26', '2021-02-01 17:23:29'),
(292, NULL, NULL, NULL, 153, 155, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 304, 0, 'content', 223, '2021-02-02 16:13:19', '2021-02-02 16:11:07', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

CREATE TABLE `event` (
  `eve_id` int(11) NOT NULL,
  `activity_act_id` int(11) DEFAULT NULL,
  `stage_stg_id` int(11) DEFAULT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `event_type_evt_id` int(11) DEFAULT NULL,
  `eve_type` int(11) DEFAULT NULL,
  `eve_priority` int(11) DEFAULT NULL,
  `eve_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eve_created_by` int(11) DEFAULT NULL,
  `eve_onset_date` datetime DEFAULT NULL,
  `eve_expres_date` datetime DEFAULT NULL,
  `eve_res_date` datetime DEFAULT NULL,
  `eve_inserted` datetime DEFAULT CURRENT_TIMESTAMP,
  `eve_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event`
--

INSERT INTO `event` (`eve_id`, `activity_act_id`, `stage_stg_id`, `organization_org_id`, `event_type_evt_id`, `eve_type`, `eve_priority`, `eve_name`, `eve_created_by`, `eve_onset_date`, `eve_expres_date`, `eve_res_date`, `eve_inserted`, `eve_deleted`) VALUES
(17, NULL, 43, 56, 115, 1, NULL, NULL, 118, '2020-10-18 00:05:03', NULL, NULL, '2020-10-18 00:05:02', NULL),
(18, NULL, 42, 56, 141, 1, NULL, NULL, 118, '2020-10-18 13:48:31', NULL, NULL, '2020-10-18 13:48:31', NULL),
(19, NULL, 43, 56, 139, 1, NULL, NULL, 118, '2020-10-19 09:59:17', NULL, NULL, '2020-10-19 09:59:17', NULL),
(22, NULL, 49, 64, 339, 1, NULL, NULL, 145, '2020-10-28 00:00:00', NULL, NULL, '2020-10-28 13:44:02', NULL),
(145, NULL, 138, 64, 356, 1, NULL, NULL, 145, '2020-11-13 00:00:00', '2020-11-20 00:00:00', NULL, '2020-11-14 10:35:29', NULL),
(146, NULL, 139, 57, 145, 1, NULL, NULL, 128, '2020-11-14 00:00:00', '2020-11-14 00:00:00', NULL, '2020-11-14 13:10:36', NULL),
(147, NULL, 139, 64, 366, 1, NULL, NULL, 145, '2020-11-14 00:00:00', '2020-11-14 00:00:00', NULL, '2020-11-14 13:21:49', NULL),
(149, NULL, 140, 57, 163, 1, NULL, NULL, 128, '2020-11-22 00:00:00', '2020-11-20 00:00:00', NULL, '2020-11-20 15:30:17', NULL),
(158, NULL, 134, 57, 160, 1, NULL, NULL, 128, '2020-11-24 00:00:00', '2020-11-24 00:00:00', NULL, '2020-11-24 17:52:04', NULL),
(161, NULL, 22, 1, 1, 1, NULL, NULL, 1, '2020-12-01 00:00:00', '2020-12-01 00:00:00', NULL, '2020-12-01 13:45:32', NULL),
(182, NULL, 154, 102, 1375, 1, NULL, NULL, 223, '2021-01-29 15:13:57', NULL, NULL, '2021-01-29 15:13:57', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `event_comment`
--

CREATE TABLE `event_comment` (
  `evc_id` int(11) NOT NULL,
  `evc_author` int(11) DEFAULT NULL,
  `event_eve_id` int(11) DEFAULT NULL,
  `evc_content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evc_created_by` int(11) DEFAULT NULL,
  `evc_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parent_id` int(11) DEFAULT NULL,
  `evc_modified` datetime DEFAULT NULL,
  `organization_org_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event_comment`
--

INSERT INTO `event_comment` (`evc_id`, `evc_author`, `event_eve_id`, `evc_content`, `evc_created_by`, `evc_inserted`, `parent_id`, `evc_modified`, `organization_org_id`) VALUES
(3, 118, 19, 'Thomas je prépare ta convention', 118, '2020-10-19 09:59:17', NULL, NULL, 56),
(14, 118, 17, 'Coucou c\'est moi', 118, '2020-10-24 14:02:09', NULL, '2020-10-24 14:02:20', 56),
(15, 125, 17, 'Pas valable', 125, '2020-10-24 23:53:51', 14, NULL, NULL),
(29, 128, 22, 'C\'est du bon travail d\'orfèvre !', 128, '2020-10-29 19:23:58', NULL, '2020-10-29 19:42:40', 57),
(44, 145, 22, 'C\'est top !!', 145, '2020-10-29 21:27:27', NULL, NULL, 64),
(45, 128, 22, 'Ne penses-tu pas qu\'il faille uploader une nouvelle version', 128, '2020-10-30 08:58:17', NULL, NULL, 57),
(46, 145, 22, 'Pourquoi une telle modif ? Tout me semble ok', 145, '2020-10-30 16:34:09', NULL, NULL, 64),
(96, 145, 145, 'Payé : 400 €', 145, '2020-11-14 10:36:14', NULL, NULL, 64),
(104, 128, 158, '200$ payé', 128, '2020-11-24 17:52:04', NULL, NULL, 57),
(105, 226, 182, 'Pas mal du tout !', 226, '2021-01-29 15:30:24', NULL, '2021-01-29 16:09:41', 19);

-- --------------------------------------------------------

--
-- Structure de la table `event_document`
--

CREATE TABLE `event_document` (
  `evd_id` int(11) NOT NULL,
  `event_eve_id` int(11) DEFAULT NULL,
  `evd_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evd_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evd_created_by` int(11) DEFAULT NULL,
  `evd_inserted` datetime DEFAULT CURRENT_TIMESTAMP,
  `evd_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evd_size` int(11) DEFAULT NULL,
  `evd_mime` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evd_modified` datetime DEFAULT NULL,
  `organization_org_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event_document`
--

INSERT INTO `event_document` (`evd_id`, `event_eve_id`, `evd_title`, `evd_path`, `evd_created_by`, `evd_inserted`, `evd_type`, `evd_size`, `evd_mime`, `evd_modified`, `organization_org_id`) VALUES
(28, 17, 'Contract', 'F4S-10-Rules-Programme-Signed-5f92e8490083a.pdf', 118, '2020-10-23 14:27:21', 'pdf', 868639, 'application/pdf', NULL, 56),
(29, 22, 'Doc de travail', '202010-ZZYZX-Lux-Market-Study-5f9975a21a759.pptx', 145, '2020-10-28 13:44:02', 'pptx', 297173, 'application/vnd.openxmlformats-officedocument.presentationml.presentation', NULL, 64),
(32, 145, 'Facture_WF', 'wellsf-5fbbf2fe68bed.jpeg', 128, '2020-11-14 11:20:12', 'jpeg', 15241, 'image/jpeg', '2020-11-23 17:35:58', 57),
(33, 147, 'Questionnaire vierge', 'Questionnaire-client-5fafd9ed44dda.docx', 145, '2020-11-14 13:21:49', 'docx', 11774, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', NULL, 64),
(36, 161, 'Salaires', '210000-ZZYZX-Recap-cotisations-5fc648fc38967.xlsx', 1, '2020-12-01 13:45:32', 'xlsx', 17376, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', NULL, 1),
(42, NULL, 'Zz', 'QR-SpTH-5fde0f99a399c.pdf', 1, '2020-12-19 14:35:05', 'pdf', 56471, 'application/pdf', NULL, 1),
(43, 182, 'Lettre de résumé', '202101-Amende-Lettre-police-6014273fe46cb.pdf', 223, '2021-01-29 15:18:23', 'pdf', 363075, 'application/pdf', NULL, 102);

-- --------------------------------------------------------

--
-- Structure de la table `event_group`
--

CREATE TABLE `event_group` (
  `evg_id` int(11) NOT NULL,
  `event_group_name_egn_id` int(11) DEFAULT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `department_dpt_id` int(11) DEFAULT NULL,
  `evg_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evg_created_by` int(11) DEFAULT NULL,
  `evg_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `evg_enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event_group`
--

INSERT INTO `event_group` (`evg_id`, `event_group_name_egn_id`, `organization_org_id`, `department_dpt_id`, `evg_name`, `evg_created_by`, `evg_inserted`, `evg_enabled`) VALUES
(1, 1, 1, NULL, NULL, NULL, '2020-08-29 16:03:15', 1),
(2, 2, 1, NULL, NULL, NULL, '2020-08-29 16:03:15', 1),
(3, 3, 1, NULL, NULL, NULL, '2020-10-13 21:22:31', 1),
(4, 1, 49, NULL, NULL, NULL, '2020-10-13 21:25:06', 1),
(5, 2, 49, NULL, NULL, NULL, '2020-10-13 21:25:06', 1),
(6, 3, 49, NULL, NULL, NULL, '2020-10-13 21:25:06', 1),
(10, 1, 51, NULL, NULL, NULL, '2020-10-13 22:37:08', 1),
(11, 2, 51, NULL, NULL, NULL, '2020-10-13 22:37:08', 1),
(12, 3, 51, NULL, NULL, NULL, '2020-10-13 22:37:08', 1),
(13, 4, 1, NULL, NULL, NULL, '2020-10-14 13:48:18', 1),
(14, 5, 1, NULL, NULL, NULL, '2020-10-14 13:48:18', 1),
(15, 6, 1, NULL, NULL, NULL, '2020-10-14 14:02:49', 1),
(16, 4, 49, NULL, NULL, NULL, '2020-10-14 14:48:24', 1),
(17, 5, 49, NULL, NULL, NULL, '2020-10-14 14:48:24', 1),
(18, 6, 49, NULL, NULL, NULL, '2020-10-14 14:48:24', 1),
(19, 4, 51, NULL, NULL, NULL, '2020-10-14 14:48:24', 1),
(20, 5, 51, NULL, NULL, NULL, '2020-10-14 14:48:24', 1),
(21, 6, 51, NULL, NULL, NULL, '2020-10-14 14:48:24', 1),
(46, 1, 56, NULL, 'Deliverable', NULL, '2020-10-14 15:44:56', 1),
(47, 2, 56, NULL, 'Suggestion', NULL, '2020-10-14 15:44:56', 1),
(48, 3, 56, NULL, 'Incident', NULL, '2020-10-14 15:44:56', 1),
(49, 4, 56, NULL, 'Cashflow', NULL, '2020-10-14 15:44:56', 1),
(50, 5, 56, NULL, 'Request', NULL, '2020-10-14 15:44:56', 1),
(51, 6, 56, NULL, 'Information', NULL, '2020-10-14 15:44:56', 1),
(52, 1, 57, NULL, NULL, NULL, '2020-10-27 08:40:19', 1),
(53, 2, 57, NULL, NULL, NULL, '2020-10-27 08:40:19', 1),
(54, 3, 57, NULL, NULL, NULL, '2020-10-27 08:40:19', 1),
(55, 4, 57, NULL, NULL, NULL, '2020-10-27 08:40:19', 1),
(56, 5, 57, NULL, NULL, NULL, '2020-10-27 08:40:19', 1),
(57, 6, 57, NULL, NULL, NULL, '2020-10-27 08:40:19', 1),
(94, 1, 64, NULL, NULL, NULL, '2020-10-27 11:44:10', 1),
(95, 2, 64, NULL, NULL, NULL, '2020-10-27 11:44:10', 1),
(96, 3, 64, NULL, NULL, NULL, '2020-10-27 11:44:10', 1),
(97, 4, 64, NULL, NULL, NULL, '2020-10-27 11:44:10', 1),
(98, 5, 64, NULL, NULL, NULL, '2020-10-27 11:44:10', 1),
(99, 6, 64, NULL, NULL, NULL, '2020-10-27 11:44:10', 1),
(100, 1, 65, NULL, NULL, NULL, '2020-11-15 11:50:21', 1),
(101, 2, 65, NULL, NULL, NULL, '2020-11-15 11:50:21', 1),
(102, 3, 65, NULL, NULL, NULL, '2020-11-15 11:50:21', 1),
(103, 4, 65, NULL, NULL, NULL, '2020-11-15 11:50:21', 1),
(104, 5, 65, NULL, NULL, NULL, '2020-11-15 11:50:21', 1),
(105, 6, 65, NULL, NULL, NULL, '2020-11-15 11:50:21', 1),
(106, 1, 66, NULL, NULL, NULL, '2020-11-20 17:01:29', 1),
(107, 2, 66, NULL, NULL, NULL, '2020-11-20 17:01:29', 1),
(108, 3, 66, NULL, NULL, NULL, '2020-11-20 17:01:29', 1),
(109, 4, 66, NULL, NULL, NULL, '2020-11-20 17:01:29', 1),
(110, 5, 66, NULL, NULL, NULL, '2020-11-20 17:01:29', 1),
(111, 6, 66, NULL, NULL, NULL, '2020-11-20 17:01:29', 1),
(112, 1, 67, NULL, NULL, NULL, '2020-11-20 17:05:37', 1),
(113, 2, 67, NULL, NULL, NULL, '2020-11-20 17:05:37', 1),
(114, 3, 67, NULL, NULL, NULL, '2020-11-20 17:05:37', 1),
(115, 4, 67, NULL, NULL, NULL, '2020-11-20 17:05:37', 1),
(116, 5, 67, NULL, NULL, NULL, '2020-11-20 17:05:37', 1),
(117, 6, 67, NULL, NULL, NULL, '2020-11-20 17:05:37', 1),
(130, 1, 71, NULL, NULL, NULL, '2020-11-26 16:18:59', 1),
(131, 2, 71, NULL, NULL, NULL, '2020-11-26 16:18:59', 1),
(132, 3, 71, NULL, NULL, NULL, '2020-11-26 16:18:59', 1),
(133, 4, 71, NULL, NULL, NULL, '2020-11-26 16:18:59', 1),
(134, 5, 71, NULL, NULL, NULL, '2020-11-26 16:18:59', 1),
(135, 6, 71, NULL, NULL, NULL, '2020-11-26 16:18:59', 1),
(136, 1, 72, NULL, NULL, NULL, '2020-11-26 16:20:15', 1),
(137, 2, 72, NULL, NULL, NULL, '2020-11-26 16:20:15', 1),
(138, 3, 72, NULL, NULL, NULL, '2020-11-26 16:20:15', 1),
(139, 4, 72, NULL, NULL, NULL, '2020-11-26 16:20:15', 1),
(140, 5, 72, NULL, NULL, NULL, '2020-11-26 16:20:15', 1),
(141, 6, 72, NULL, NULL, NULL, '2020-11-26 16:20:15', 1),
(268, 1, 94, NULL, NULL, NULL, '2020-11-28 16:37:24', 1),
(269, 2, 94, NULL, NULL, NULL, '2020-11-28 16:37:24', 1),
(270, 3, 94, NULL, NULL, NULL, '2020-11-28 16:37:24', 1),
(271, 4, 94, NULL, NULL, NULL, '2020-11-28 16:37:24', 1),
(272, 5, 94, NULL, NULL, NULL, '2020-11-28 16:37:24', 1),
(273, 6, 94, NULL, NULL, NULL, '2020-11-28 16:37:24', 1),
(274, 1, 95, NULL, NULL, NULL, '2020-11-30 16:24:35', 1),
(275, 2, 95, NULL, NULL, NULL, '2020-11-30 16:24:35', 1),
(276, 3, 95, NULL, NULL, NULL, '2020-11-30 16:24:35', 1),
(277, 4, 95, NULL, NULL, NULL, '2020-11-30 16:24:35', 1),
(278, 5, 95, NULL, NULL, NULL, '2020-11-30 16:24:35', 1),
(279, 6, 95, NULL, NULL, NULL, '2020-11-30 16:24:35', 1),
(280, 1, 96, NULL, NULL, NULL, '2020-12-01 16:22:36', 1),
(281, 2, 96, NULL, NULL, NULL, '2020-12-01 16:22:36', 1),
(282, 3, 96, NULL, NULL, NULL, '2020-12-01 16:22:36', 1),
(283, 4, 96, NULL, NULL, NULL, '2020-12-01 16:22:36', 1),
(284, 5, 96, NULL, NULL, NULL, '2020-12-01 16:22:36', 1),
(285, 6, 96, NULL, NULL, NULL, '2020-12-01 16:22:36', 1),
(286, 1, 97, NULL, NULL, NULL, '2020-12-07 16:53:02', 1),
(287, 2, 97, NULL, NULL, NULL, '2020-12-07 16:53:02', 1),
(288, 3, 97, NULL, NULL, NULL, '2020-12-07 16:53:02', 1),
(289, 4, 97, NULL, NULL, NULL, '2020-12-07 16:53:02', 1),
(290, 5, 97, NULL, NULL, NULL, '2020-12-07 16:53:02', 1),
(291, 6, 97, NULL, NULL, NULL, '2020-12-07 16:53:02', 1),
(292, 1, 98, NULL, NULL, NULL, '2020-12-07 16:58:33', 1),
(293, 2, 98, NULL, NULL, NULL, '2020-12-07 16:58:33', 1),
(294, 3, 98, NULL, NULL, NULL, '2020-12-07 16:58:33', 1),
(295, 4, 98, NULL, NULL, NULL, '2020-12-07 16:58:33', 1),
(296, 5, 98, NULL, NULL, NULL, '2020-12-07 16:58:33', 1),
(297, 6, 98, NULL, NULL, NULL, '2020-12-07 16:58:33', 1),
(298, 1, 99, NULL, NULL, NULL, '2020-12-07 21:39:29', 1),
(299, 2, 99, NULL, NULL, NULL, '2020-12-07 21:39:29', 1),
(300, 3, 99, NULL, NULL, NULL, '2020-12-07 21:39:29', 1),
(301, 4, 99, NULL, NULL, NULL, '2020-12-07 21:39:29', 1),
(302, 5, 99, NULL, NULL, NULL, '2020-12-07 21:39:29', 1),
(303, 6, 99, NULL, NULL, NULL, '2020-12-07 21:39:29', 1),
(304, 1, 100, NULL, NULL, NULL, '2020-12-07 21:40:11', 1),
(305, 2, 100, NULL, NULL, NULL, '2020-12-07 21:40:11', 1),
(306, 3, 100, NULL, NULL, NULL, '2020-12-07 21:40:11', 1),
(307, 4, 100, NULL, NULL, NULL, '2020-12-07 21:40:11', 1),
(308, 5, 100, NULL, NULL, NULL, '2020-12-07 21:40:11', 1),
(309, 6, 100, NULL, NULL, NULL, '2020-12-07 21:40:11', 1),
(310, 1, 101, NULL, NULL, NULL, '2021-01-09 23:26:26', 1),
(311, 2, 101, NULL, NULL, NULL, '2021-01-09 23:26:26', 1),
(312, 3, 101, NULL, NULL, NULL, '2021-01-09 23:26:26', 1),
(313, 4, 101, NULL, NULL, NULL, '2021-01-09 23:26:26', 1),
(314, 5, 101, NULL, NULL, NULL, '2021-01-09 23:26:26', 1),
(315, 6, 101, NULL, NULL, NULL, '2021-01-09 23:26:26', 1),
(316, 1, 102, NULL, NULL, NULL, '2021-01-12 10:02:51', 1),
(317, 2, 102, NULL, NULL, NULL, '2021-01-12 10:02:51', 1),
(318, 3, 102, NULL, NULL, NULL, '2021-01-12 10:02:51', 1),
(319, 4, 102, NULL, NULL, NULL, '2021-01-12 10:02:51', 1),
(320, 5, 102, NULL, NULL, NULL, '2021-01-12 10:02:51', 1),
(321, 6, 102, NULL, NULL, NULL, '2021-01-12 10:02:51', 1),
(352, 1, 108, NULL, NULL, NULL, '2021-01-19 14:08:31', 1),
(353, 2, 108, NULL, NULL, NULL, '2021-01-19 14:08:31', 1),
(354, 3, 108, NULL, NULL, NULL, '2021-01-19 14:08:31', 1),
(355, 4, 108, NULL, NULL, NULL, '2021-01-19 14:08:31', 1),
(356, 5, 108, NULL, NULL, NULL, '2021-01-19 14:08:31', 1),
(357, 6, 108, NULL, NULL, NULL, '2021-01-19 14:08:31', 1),
(376, 1, 112, NULL, NULL, NULL, '2021-01-20 10:23:53', 1),
(377, 2, 112, NULL, NULL, NULL, '2021-01-20 10:23:53', 1),
(378, 3, 112, NULL, NULL, NULL, '2021-01-20 10:23:53', 1),
(379, 4, 112, NULL, NULL, NULL, '2021-01-20 10:23:53', 1),
(380, 5, 112, NULL, NULL, NULL, '2021-01-20 10:23:53', 1),
(381, 6, 112, NULL, NULL, NULL, '2021-01-20 10:23:53', 1),
(382, 1, 113, NULL, NULL, NULL, '2021-01-20 10:26:29', 1),
(383, 2, 113, NULL, NULL, NULL, '2021-01-20 10:26:29', 1),
(384, 3, 113, NULL, NULL, NULL, '2021-01-20 10:26:29', 1),
(385, 4, 113, NULL, NULL, NULL, '2021-01-20 10:26:29', 1),
(386, 5, 113, NULL, NULL, NULL, '2021-01-20 10:26:29', 1),
(387, 6, 113, NULL, NULL, NULL, '2021-01-20 10:26:29', 1),
(388, 1, 114, NULL, NULL, NULL, '2021-01-20 10:36:37', 1),
(389, 2, 114, NULL, NULL, NULL, '2021-01-20 10:36:37', 1),
(390, 3, 114, NULL, NULL, NULL, '2021-01-20 10:36:37', 1),
(391, 4, 114, NULL, NULL, NULL, '2021-01-20 10:36:37', 1),
(392, 5, 114, NULL, NULL, NULL, '2021-01-20 10:36:37', 1),
(393, 6, 114, NULL, NULL, NULL, '2021-01-20 10:36:37', 1),
(526, 1, 137, NULL, NULL, NULL, '2021-01-22 11:42:17', 1),
(527, 2, 137, NULL, NULL, NULL, '2021-01-22 11:42:17', 1),
(528, 3, 137, NULL, NULL, NULL, '2021-01-22 11:42:17', 1),
(529, 4, 137, NULL, NULL, NULL, '2021-01-22 11:42:17', 1),
(530, 5, 137, NULL, NULL, NULL, '2021-01-22 11:42:17', 1),
(531, 6, 137, NULL, NULL, NULL, '2021-01-22 11:42:17', 1),
(532, 1, 138, NULL, NULL, NULL, '2021-01-24 10:06:38', 1),
(533, 2, 138, NULL, NULL, NULL, '2021-01-24 10:06:38', 1),
(534, 3, 138, NULL, NULL, NULL, '2021-01-24 10:06:38', 1),
(535, 4, 138, NULL, NULL, NULL, '2021-01-24 10:06:38', 1),
(536, 5, 138, NULL, NULL, NULL, '2021-01-24 10:06:38', 1),
(537, 6, 138, NULL, NULL, NULL, '2021-01-24 10:06:38', 1),
(538, 1, 139, NULL, NULL, NULL, '2021-01-29 11:05:12', 1),
(539, 2, 139, NULL, NULL, NULL, '2021-01-29 11:05:12', 1),
(540, 3, 139, NULL, NULL, NULL, '2021-01-29 11:05:12', 1),
(541, 4, 139, NULL, NULL, NULL, '2021-01-29 11:05:12', 1),
(542, 5, 139, NULL, NULL, NULL, '2021-01-29 11:05:12', 1),
(543, 6, 139, NULL, NULL, NULL, '2021-01-29 11:05:12', 1),
(562, 1, 145, NULL, NULL, NULL, '2021-01-31 10:58:28', 1),
(563, 2, 145, NULL, NULL, NULL, '2021-01-31 10:58:28', 1),
(564, 3, 145, NULL, NULL, NULL, '2021-01-31 10:58:28', 1),
(565, 4, 145, NULL, NULL, NULL, '2021-01-31 10:58:28', 1),
(566, 5, 145, NULL, NULL, NULL, '2021-01-31 10:58:28', 1),
(567, 6, 145, NULL, NULL, NULL, '2021-01-31 10:58:28', 1),
(568, 1, 146, NULL, NULL, NULL, '2021-01-31 10:59:22', 1),
(569, 2, 146, NULL, NULL, NULL, '2021-01-31 10:59:22', 1),
(570, 3, 146, NULL, NULL, NULL, '2021-01-31 10:59:22', 1),
(571, 4, 146, NULL, NULL, NULL, '2021-01-31 10:59:22', 1),
(572, 5, 146, NULL, NULL, NULL, '2021-01-31 10:59:22', 1),
(573, 6, 146, NULL, NULL, NULL, '2021-01-31 10:59:22', 1),
(574, 1, 147, NULL, NULL, NULL, '2021-02-02 14:33:52', 1),
(575, 2, 147, NULL, NULL, NULL, '2021-02-02 14:33:52', 1),
(576, 3, 147, NULL, NULL, NULL, '2021-02-02 14:33:52', 1),
(577, 4, 147, NULL, NULL, NULL, '2021-02-02 14:33:52', 1),
(578, 5, 147, NULL, NULL, NULL, '2021-02-02 14:33:52', 1),
(579, 6, 147, NULL, NULL, NULL, '2021-02-02 14:33:52', 1),
(580, 1, 148, NULL, NULL, NULL, '2021-02-02 16:12:59', 1),
(581, 2, 148, NULL, NULL, NULL, '2021-02-02 16:12:59', 1),
(582, 3, 148, NULL, NULL, NULL, '2021-02-02 16:12:59', 1),
(583, 4, 148, NULL, NULL, NULL, '2021-02-02 16:12:59', 1),
(584, 5, 148, NULL, NULL, NULL, '2021-02-02 16:12:59', 1),
(585, 6, 148, NULL, NULL, NULL, '2021-02-02 16:12:59', 1);

-- --------------------------------------------------------

--
-- Structure de la table `event_group_name`
--

CREATE TABLE `event_group_name` (
  `egn_id` int(11) NOT NULL,
  `egn_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `egn_created_by` int(11) DEFAULT NULL,
  `egn_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event_group_name`
--

INSERT INTO `event_group_name` (`egn_id`, `egn_name`, `egn_created_by`, `egn_inserted`) VALUES
(1, 'Deliverable', NULL, '2020-10-14 14:36:58'),
(2, 'Suggestion', NULL, '2020-10-14 14:36:58'),
(3, 'Incident', NULL, '2020-10-14 14:36:58'),
(4, 'Cashflow', NULL, '2020-10-14 14:36:58'),
(5, 'Request', NULL, '2020-10-14 14:36:58'),
(6, 'Information', NULL, '2020-10-14 14:36:58');

-- --------------------------------------------------------

--
-- Structure de la table `event_name`
--

CREATE TABLE `event_name` (
  `evn_id` int(11) NOT NULL,
  `event_group_name_egn_id` int(11) DEFAULT NULL,
  `icon_ico_id` int(11) DEFAULT NULL,
  `evn_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evn_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evn_created_by` int(11) DEFAULT NULL,
  `evn_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event_name`
--

INSERT INTO `event_name` (`evn_id`, `event_group_name_egn_id`, `icon_ico_id`, `evn_name`, `evn_description`, `evn_created_by`, `evn_inserted`) VALUES
(1, 1, 37, 'Report', 'A written document summarizing a situation or problem', NULL, '2020-08-29 00:00:00'),
(2, 1, 48, 'Presentation', 'A presentation, PDF of animated, which presents a concept, project, situation...', NULL, '2020-08-29 00:00:00'),
(3, 1, 46, 'Contract', 'A document, which once signed bounds two or more parties', NULL, '2020-10-14 01:40:02'),
(4, 1, 47, 'Spreadsheet / Chart', 'A document containing data, tables or graphics', NULL, '2020-10-14 01:40:02'),
(5, 1, 42, 'Recording (video / voice)', 'A animated sound or video document', NULL, '2020-10-14 01:40:41'),
(6, 1, 45, 'Minutes', 'A document summarizing a meeting, with participants, objectives, and assigned tasks', NULL, '2020-10-14 01:42:44'),
(7, 1, 49, 'Proposition / Estimate', 'A document to be validated, before delivering the service', NULL, '2020-10-14 12:07:14'),
(8, 1, 50, 'Physical delivery', 'A physical delivery', NULL, '2020-10-14 12:07:14'),
(9, 4, 26, 'Partial payment', NULL, NULL, '2020-10-14 12:08:19'),
(10, 4, 26, 'Total payment', NULL, NULL, '2020-10-14 12:08:46'),
(11, 4, 26, 'Reimbursement', NULL, NULL, '2020-10-14 12:08:46'),
(15, 1, 45, 'Article', 'A written article relating a topic', NULL, '2020-10-14 12:08:46'),
(16, 1, 51, 'Social media post', 'A post done on social media', NULL, '2020-10-14 12:08:46'),
(18, 2, 15, 'Rescheduling', 'Propose a new date', NULL, '2020-10-14 12:27:31'),
(19, 2, 17, 'Enhancement', NULL, NULL, '2020-10-14 12:27:31'),
(20, 2, 53, 'Additional service', NULL, NULL, '2020-10-14 12:28:03'),
(21, 6, 54, 'Feedback', NULL, NULL, '2020-10-14 12:28:46'),
(22, 6, 15, 'Possible delay', NULL, NULL, '2020-10-14 12:28:46'),
(23, 6, 55, 'Changes', NULL, NULL, '2020-10-14 12:29:24'),
(24, 6, 59, 'Additional information needed', NULL, NULL, '2020-10-14 12:29:24'),
(25, 1, 45, 'Application', NULL, NULL, '2020-10-14 13:27:13'),
(26, 3, 57, 'Delivery', NULL, NULL, '2020-10-14 14:57:45'),
(27, 3, 56, 'Missing payment', NULL, NULL, '2020-10-14 14:57:45'),
(28, 3, 3, 'Belated', NULL, NULL, '2020-10-14 14:57:45'),
(33, 5, 54, 'Feedback', NULL, NULL, '2020-10-14 14:59:32'),
(34, 5, 15, 'Additional delay', NULL, NULL, '2020-10-14 14:59:32'),
(35, 5, 55, 'Changes', NULL, NULL, '2020-10-14 14:59:32'),
(36, 5, 59, 'Information needed', NULL, NULL, '2020-10-14 14:59:32');

-- --------------------------------------------------------

--
-- Structure de la table `event_type`
--

CREATE TABLE `event_type` (
  `evt_id` int(11) NOT NULL,
  `icon_ico_id` int(11) DEFAULT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `event_group_evg_id` int(11) DEFAULT NULL,
  `event_name_evn_id` int(11) DEFAULT NULL,
  `evt_type` int(11) DEFAULT NULL,
  `evt_created_by` int(11) DEFAULT NULL,
  `evt_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `evt_enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event_type`
--

INSERT INTO `event_type` (`evt_id`, `icon_ico_id`, `organization_org_id`, `event_group_evg_id`, `event_name_evn_id`, `evt_type`, `evt_created_by`, `evt_inserted`, `evt_enabled`) VALUES
(1, 1, 1, 1, 1, NULL, NULL, '2020-08-29 00:00:00', 1),
(2, 2, 1, 2, 2, NULL, NULL, '2020-08-29 00:00:00', 1),
(115, 58, 56, 46, 1, NULL, NULL, '2020-10-14 15:44:56', 1),
(116, 48, 56, 46, 2, NULL, NULL, '2020-10-14 15:44:56', 1),
(117, 46, 56, 46, 3, NULL, NULL, '2020-10-14 15:44:56', 1),
(118, 47, 56, 46, 4, NULL, NULL, '2020-10-14 15:44:56', 1),
(119, 42, 56, 46, 5, NULL, NULL, '2020-10-14 15:44:56', 1),
(120, 45, 56, 46, 6, NULL, NULL, '2020-10-14 15:44:56', 1),
(121, 49, 56, 46, 7, NULL, NULL, '2020-10-14 15:44:56', 1),
(122, 50, 56, 46, 8, NULL, NULL, '2020-10-14 15:44:56', 1),
(123, 45, 56, 46, 15, NULL, NULL, '2020-10-14 15:44:56', 1),
(124, 51, 56, 46, 16, NULL, NULL, '2020-10-14 15:44:56', 1),
(125, 45, 56, 46, 25, NULL, NULL, '2020-10-14 15:44:56', 1),
(126, 15, 56, 47, 18, NULL, NULL, '2020-10-14 15:44:56', 1),
(127, 17, 56, 47, 19, NULL, NULL, '2020-10-14 15:44:56', 1),
(128, 53, 56, 47, 20, NULL, NULL, '2020-10-14 15:44:56', 1),
(129, 57, 56, 48, 26, NULL, NULL, '2020-10-14 15:44:56', 1),
(130, 56, 56, 48, 27, NULL, NULL, '2020-10-14 15:44:56', 1),
(131, 3, 56, 48, 28, NULL, NULL, '2020-10-14 15:44:56', 1),
(132, 26, 56, 49, 9, NULL, NULL, '2020-10-14 15:44:56', 1),
(133, 26, 56, 49, 10, NULL, NULL, '2020-10-14 15:44:56', 1),
(134, 26, 56, 49, 11, NULL, NULL, '2020-10-14 15:44:56', 1),
(135, 54, 56, 50, 33, NULL, NULL, '2020-10-14 15:44:56', 1),
(136, 15, 56, 50, 34, NULL, NULL, '2020-10-14 15:44:56', 1),
(137, 55, 56, 50, 35, NULL, NULL, '2020-10-14 15:44:56', 1),
(138, 59, 56, 50, 36, NULL, NULL, '2020-10-14 15:44:56', 1),
(139, 54, 56, 51, 21, NULL, NULL, '2020-10-14 15:44:56', 1),
(140, 15, 56, 51, 22, NULL, NULL, '2020-10-14 15:44:56', 1),
(141, 55, 56, 51, 23, NULL, NULL, '2020-10-14 15:44:56', 1),
(142, 59, 56, 51, 24, NULL, NULL, '2020-10-14 15:44:56', 1),
(143, 58, 57, 52, 1, NULL, NULL, '2020-10-27 08:40:19', 1),
(144, 48, 57, 52, 2, NULL, NULL, '2020-10-27 08:40:19', 1),
(145, 46, 57, 52, 3, NULL, NULL, '2020-10-27 08:40:19', 1),
(146, 47, 57, 52, 4, NULL, NULL, '2020-10-27 08:40:19', 1),
(147, 42, 57, 52, 5, NULL, NULL, '2020-10-27 08:40:19', 1),
(148, 45, 57, 52, 6, NULL, NULL, '2020-10-27 08:40:19', 1),
(149, 49, 57, 52, 7, NULL, NULL, '2020-10-27 08:40:19', 1),
(150, 50, 57, 52, 8, NULL, NULL, '2020-10-27 08:40:19', 1),
(151, 45, 57, 52, 15, NULL, NULL, '2020-10-27 08:40:19', 1),
(152, 51, 57, 52, 16, NULL, NULL, '2020-10-27 08:40:19', 1),
(153, 45, 57, 52, 25, NULL, NULL, '2020-10-27 08:40:19', 1),
(154, 15, 57, 53, 18, NULL, NULL, '2020-10-27 08:40:19', 1),
(155, 17, 57, 53, 19, NULL, NULL, '2020-10-27 08:40:19', 1),
(156, 53, 57, 53, 20, NULL, NULL, '2020-10-27 08:40:19', 1),
(157, 57, 57, 54, 26, NULL, NULL, '2020-10-27 08:40:19', 1),
(158, 56, 57, 54, 27, NULL, NULL, '2020-10-27 08:40:19', 1),
(159, 3, 57, 54, 28, NULL, NULL, '2020-10-27 08:40:19', 1),
(160, 26, 57, 55, 9, NULL, NULL, '2020-10-27 08:40:19', 1),
(161, 26, 57, 55, 10, NULL, NULL, '2020-10-27 08:40:19', 1),
(162, 26, 57, 55, 11, NULL, NULL, '2020-10-27 08:40:19', 1),
(163, 54, 57, 56, 33, NULL, NULL, '2020-10-27 08:40:19', 1),
(164, 15, 57, 56, 34, NULL, NULL, '2020-10-27 08:40:19', 1),
(165, 55, 57, 56, 35, NULL, NULL, '2020-10-27 08:40:19', 1),
(166, 59, 57, 56, 36, NULL, NULL, '2020-10-27 08:40:19', 1),
(167, 54, 57, 57, 21, NULL, NULL, '2020-10-27 08:40:19', 1),
(168, 15, 57, 57, 22, NULL, NULL, '2020-10-27 08:40:19', 1),
(169, 55, 57, 57, 23, NULL, NULL, '2020-10-27 08:40:19', 1),
(170, 59, 57, 57, 24, NULL, NULL, '2020-10-27 08:40:19', 1),
(339, 58, 64, 94, 1, NULL, NULL, '2020-10-27 11:44:10', 1),
(340, 48, 64, 94, 2, NULL, NULL, '2020-10-27 11:44:10', 1),
(341, 46, 64, 94, 3, NULL, NULL, '2020-10-27 11:44:10', 1),
(342, 47, 64, 94, 4, NULL, NULL, '2020-10-27 11:44:10', 1),
(343, 42, 64, 94, 5, NULL, NULL, '2020-10-27 11:44:10', 1),
(344, 45, 64, 94, 6, NULL, NULL, '2020-10-27 11:44:10', 1),
(345, 49, 64, 94, 7, NULL, NULL, '2020-10-27 11:44:10', 1),
(346, 50, 64, 94, 8, NULL, NULL, '2020-10-27 11:44:10', 1),
(347, 45, 64, 94, 15, NULL, NULL, '2020-10-27 11:44:10', 1),
(348, 51, 64, 94, 16, NULL, NULL, '2020-10-27 11:44:10', 1),
(349, 45, 64, 94, 25, NULL, NULL, '2020-10-27 11:44:10', 1),
(350, 15, 64, 95, 18, NULL, NULL, '2020-10-27 11:44:10', 1),
(351, 17, 64, 95, 19, NULL, NULL, '2020-10-27 11:44:10', 1),
(352, 53, 64, 95, 20, NULL, NULL, '2020-10-27 11:44:10', 1),
(353, 57, 64, 96, 26, NULL, NULL, '2020-10-27 11:44:10', 1),
(354, 56, 64, 96, 27, NULL, NULL, '2020-10-27 11:44:10', 1),
(355, 3, 64, 96, 28, NULL, NULL, '2020-10-27 11:44:10', 1),
(356, 26, 64, 97, 9, NULL, NULL, '2020-10-27 11:44:10', 1),
(357, 26, 64, 97, 10, NULL, NULL, '2020-10-27 11:44:10', 1),
(358, 26, 64, 97, 11, NULL, NULL, '2020-10-27 11:44:10', 1),
(359, 54, 64, 98, 33, NULL, NULL, '2020-10-27 11:44:10', 1),
(360, 15, 64, 98, 34, NULL, NULL, '2020-10-27 11:44:10', 1),
(361, 55, 64, 98, 35, NULL, NULL, '2020-10-27 11:44:10', 1),
(362, 59, 64, 98, 36, NULL, NULL, '2020-10-27 11:44:10', 1),
(363, 54, 64, 99, 21, NULL, NULL, '2020-10-27 11:44:10', 1),
(364, 15, 64, 99, 22, NULL, NULL, '2020-10-27 11:44:10', 1),
(365, 55, 64, 99, 23, NULL, NULL, '2020-10-27 11:44:10', 1),
(366, 59, 64, 99, 24, NULL, NULL, '2020-10-27 11:44:10', 1),
(367, 58, 65, 100, 1, NULL, NULL, '2020-11-15 11:50:21', 1),
(368, 48, 65, 100, 2, NULL, NULL, '2020-11-15 11:50:21', 1),
(369, 46, 65, 100, 3, NULL, NULL, '2020-11-15 11:50:21', 1),
(370, 47, 65, 100, 4, NULL, NULL, '2020-11-15 11:50:21', 1),
(371, 42, 65, 100, 5, NULL, NULL, '2020-11-15 11:50:21', 1),
(372, 45, 65, 100, 6, NULL, NULL, '2020-11-15 11:50:21', 1),
(373, 49, 65, 100, 7, NULL, NULL, '2020-11-15 11:50:21', 1),
(374, 50, 65, 100, 8, NULL, NULL, '2020-11-15 11:50:21', 1),
(375, 45, 65, 100, 15, NULL, NULL, '2020-11-15 11:50:21', 1),
(376, 51, 65, 100, 16, NULL, NULL, '2020-11-15 11:50:21', 1),
(377, 45, 65, 100, 25, NULL, NULL, '2020-11-15 11:50:21', 1),
(378, 15, 65, 101, 18, NULL, NULL, '2020-11-15 11:50:21', 1),
(379, 17, 65, 101, 19, NULL, NULL, '2020-11-15 11:50:21', 1),
(380, 53, 65, 101, 20, NULL, NULL, '2020-11-15 11:50:21', 1),
(381, 57, 65, 102, 26, NULL, NULL, '2020-11-15 11:50:21', 1),
(382, 56, 65, 102, 27, NULL, NULL, '2020-11-15 11:50:21', 1),
(383, 3, 65, 102, 28, NULL, NULL, '2020-11-15 11:50:21', 1),
(384, 26, 65, 103, 9, NULL, NULL, '2020-11-15 11:50:21', 1),
(385, 26, 65, 103, 10, NULL, NULL, '2020-11-15 11:50:21', 1),
(386, 26, 65, 103, 11, NULL, NULL, '2020-11-15 11:50:21', 1),
(387, 54, 65, 104, 33, NULL, NULL, '2020-11-15 11:50:21', 1),
(388, 15, 65, 104, 34, NULL, NULL, '2020-11-15 11:50:21', 1),
(389, 55, 65, 104, 35, NULL, NULL, '2020-11-15 11:50:21', 1),
(390, 59, 65, 104, 36, NULL, NULL, '2020-11-15 11:50:21', 1),
(391, 54, 65, 105, 21, NULL, NULL, '2020-11-15 11:50:21', 1),
(392, 15, 65, 105, 22, NULL, NULL, '2020-11-15 11:50:21', 1),
(393, 55, 65, 105, 23, NULL, NULL, '2020-11-15 11:50:21', 1),
(394, 59, 65, 105, 24, NULL, NULL, '2020-11-15 11:50:21', 1),
(395, 58, 66, 106, 1, NULL, NULL, '2020-11-20 17:01:29', 1),
(396, 48, 66, 106, 2, NULL, NULL, '2020-11-20 17:01:29', 1),
(397, 46, 66, 106, 3, NULL, NULL, '2020-11-20 17:01:29', 1),
(398, 47, 66, 106, 4, NULL, NULL, '2020-11-20 17:01:29', 1),
(399, 42, 66, 106, 5, NULL, NULL, '2020-11-20 17:01:29', 1),
(400, 45, 66, 106, 6, NULL, NULL, '2020-11-20 17:01:29', 1),
(401, 49, 66, 106, 7, NULL, NULL, '2020-11-20 17:01:29', 1),
(402, 50, 66, 106, 8, NULL, NULL, '2020-11-20 17:01:29', 1),
(403, 45, 66, 106, 15, NULL, NULL, '2020-11-20 17:01:29', 1),
(404, 51, 66, 106, 16, NULL, NULL, '2020-11-20 17:01:29', 1),
(405, 45, 66, 106, 25, NULL, NULL, '2020-11-20 17:01:29', 1),
(406, 15, 66, 107, 18, NULL, NULL, '2020-11-20 17:01:29', 1),
(407, 17, 66, 107, 19, NULL, NULL, '2020-11-20 17:01:29', 1),
(408, 53, 66, 107, 20, NULL, NULL, '2020-11-20 17:01:29', 1),
(409, 57, 66, 108, 26, NULL, NULL, '2020-11-20 17:01:29', 1),
(410, 56, 66, 108, 27, NULL, NULL, '2020-11-20 17:01:29', 1),
(411, 3, 66, 108, 28, NULL, NULL, '2020-11-20 17:01:29', 1),
(412, 26, 66, 109, 9, NULL, NULL, '2020-11-20 17:01:29', 1),
(413, 26, 66, 109, 10, NULL, NULL, '2020-11-20 17:01:29', 1),
(414, 26, 66, 109, 11, NULL, NULL, '2020-11-20 17:01:29', 1),
(415, 54, 66, 110, 33, NULL, NULL, '2020-11-20 17:01:29', 1),
(416, 15, 66, 110, 34, NULL, NULL, '2020-11-20 17:01:29', 1),
(417, 55, 66, 110, 35, NULL, NULL, '2020-11-20 17:01:29', 1),
(418, 59, 66, 110, 36, NULL, NULL, '2020-11-20 17:01:29', 1),
(419, 54, 66, 111, 21, NULL, NULL, '2020-11-20 17:01:29', 1),
(420, 15, 66, 111, 22, NULL, NULL, '2020-11-20 17:01:29', 1),
(421, 55, 66, 111, 23, NULL, NULL, '2020-11-20 17:01:29', 1),
(422, 59, 66, 111, 24, NULL, NULL, '2020-11-20 17:01:29', 1),
(423, 58, 67, 112, 1, NULL, NULL, '2020-11-20 17:05:37', 1),
(424, 48, 67, 112, 2, NULL, NULL, '2020-11-20 17:05:37', 1),
(425, 46, 67, 112, 3, NULL, NULL, '2020-11-20 17:05:37', 1),
(426, 47, 67, 112, 4, NULL, NULL, '2020-11-20 17:05:37', 1),
(427, 42, 67, 112, 5, NULL, NULL, '2020-11-20 17:05:37', 1),
(428, 45, 67, 112, 6, NULL, NULL, '2020-11-20 17:05:37', 1),
(429, 49, 67, 112, 7, NULL, NULL, '2020-11-20 17:05:37', 1),
(430, 50, 67, 112, 8, NULL, NULL, '2020-11-20 17:05:37', 1),
(431, 45, 67, 112, 15, NULL, NULL, '2020-11-20 17:05:37', 1),
(432, 51, 67, 112, 16, NULL, NULL, '2020-11-20 17:05:37', 1),
(433, 45, 67, 112, 25, NULL, NULL, '2020-11-20 17:05:37', 1),
(434, 15, 67, 113, 18, NULL, NULL, '2020-11-20 17:05:37', 1),
(435, 17, 67, 113, 19, NULL, NULL, '2020-11-20 17:05:37', 1),
(436, 53, 67, 113, 20, NULL, NULL, '2020-11-20 17:05:37', 1),
(437, 57, 67, 114, 26, NULL, NULL, '2020-11-20 17:05:37', 1),
(438, 56, 67, 114, 27, NULL, NULL, '2020-11-20 17:05:37', 1),
(439, 3, 67, 114, 28, NULL, NULL, '2020-11-20 17:05:37', 1),
(440, 26, 67, 115, 9, NULL, NULL, '2020-11-20 17:05:37', 1),
(441, 26, 67, 115, 10, NULL, NULL, '2020-11-20 17:05:37', 1),
(442, 26, 67, 115, 11, NULL, NULL, '2020-11-20 17:05:37', 1),
(443, 54, 67, 116, 33, NULL, NULL, '2020-11-20 17:05:37', 1),
(444, 15, 67, 116, 34, NULL, NULL, '2020-11-20 17:05:37', 1),
(445, 55, 67, 116, 35, NULL, NULL, '2020-11-20 17:05:37', 1),
(446, 59, 67, 116, 36, NULL, NULL, '2020-11-20 17:05:37', 1),
(447, 54, 67, 117, 21, NULL, NULL, '2020-11-20 17:05:37', 1),
(448, 15, 67, 117, 22, NULL, NULL, '2020-11-20 17:05:37', 1),
(449, 55, 67, 117, 23, NULL, NULL, '2020-11-20 17:05:37', 1),
(450, 59, 67, 117, 24, NULL, NULL, '2020-11-20 17:05:37', 1),
(507, 58, 71, 130, 1, NULL, NULL, '2020-11-26 16:18:59', 1),
(508, 48, 71, 130, 2, NULL, NULL, '2020-11-26 16:18:59', 1),
(509, 46, 71, 130, 3, NULL, NULL, '2020-11-26 16:18:59', 1),
(510, 47, 71, 130, 4, NULL, NULL, '2020-11-26 16:18:59', 1),
(511, 42, 71, 130, 5, NULL, NULL, '2020-11-26 16:18:59', 1),
(512, 45, 71, 130, 6, NULL, NULL, '2020-11-26 16:18:59', 1),
(513, 49, 71, 130, 7, NULL, NULL, '2020-11-26 16:18:59', 1),
(514, 50, 71, 130, 8, NULL, NULL, '2020-11-26 16:18:59', 1),
(515, 45, 71, 130, 15, NULL, NULL, '2020-11-26 16:18:59', 1),
(516, 51, 71, 130, 16, NULL, NULL, '2020-11-26 16:18:59', 1),
(517, 45, 71, 130, 25, NULL, NULL, '2020-11-26 16:18:59', 1),
(518, 15, 71, 131, 18, NULL, NULL, '2020-11-26 16:18:59', 1),
(519, 17, 71, 131, 19, NULL, NULL, '2020-11-26 16:18:59', 1),
(520, 53, 71, 131, 20, NULL, NULL, '2020-11-26 16:18:59', 1),
(521, 57, 71, 132, 26, NULL, NULL, '2020-11-26 16:18:59', 1),
(522, 56, 71, 132, 27, NULL, NULL, '2020-11-26 16:18:59', 1),
(523, 3, 71, 132, 28, NULL, NULL, '2020-11-26 16:18:59', 1),
(524, 26, 71, 133, 9, NULL, NULL, '2020-11-26 16:18:59', 1),
(525, 26, 71, 133, 10, NULL, NULL, '2020-11-26 16:18:59', 1),
(526, 26, 71, 133, 11, NULL, NULL, '2020-11-26 16:18:59', 1),
(527, 54, 71, 134, 33, NULL, NULL, '2020-11-26 16:18:59', 1),
(528, 15, 71, 134, 34, NULL, NULL, '2020-11-26 16:18:59', 1),
(529, 55, 71, 134, 35, NULL, NULL, '2020-11-26 16:18:59', 1),
(530, 59, 71, 134, 36, NULL, NULL, '2020-11-26 16:18:59', 1),
(531, 54, 71, 135, 21, NULL, NULL, '2020-11-26 16:18:59', 1),
(532, 15, 71, 135, 22, NULL, NULL, '2020-11-26 16:18:59', 1),
(533, 55, 71, 135, 23, NULL, NULL, '2020-11-26 16:18:59', 1),
(534, 59, 71, 135, 24, NULL, NULL, '2020-11-26 16:18:59', 1),
(535, 58, 72, 136, 1, NULL, NULL, '2020-11-26 16:20:15', 1),
(536, 48, 72, 136, 2, NULL, NULL, '2020-11-26 16:20:15', 1),
(537, 46, 72, 136, 3, NULL, NULL, '2020-11-26 16:20:15', 1),
(538, 47, 72, 136, 4, NULL, NULL, '2020-11-26 16:20:15', 1),
(539, 42, 72, 136, 5, NULL, NULL, '2020-11-26 16:20:15', 1),
(540, 45, 72, 136, 6, NULL, NULL, '2020-11-26 16:20:15', 1),
(541, 49, 72, 136, 7, NULL, NULL, '2020-11-26 16:20:15', 1),
(542, 50, 72, 136, 8, NULL, NULL, '2020-11-26 16:20:15', 1),
(543, 45, 72, 136, 15, NULL, NULL, '2020-11-26 16:20:15', 1),
(544, 51, 72, 136, 16, NULL, NULL, '2020-11-26 16:20:15', 1),
(545, 45, 72, 136, 25, NULL, NULL, '2020-11-26 16:20:15', 1),
(546, 15, 72, 137, 18, NULL, NULL, '2020-11-26 16:20:15', 1),
(547, 17, 72, 137, 19, NULL, NULL, '2020-11-26 16:20:15', 1),
(548, 53, 72, 137, 20, NULL, NULL, '2020-11-26 16:20:15', 1),
(549, 57, 72, 138, 26, NULL, NULL, '2020-11-26 16:20:15', 1),
(550, 56, 72, 138, 27, NULL, NULL, '2020-11-26 16:20:15', 1),
(551, 3, 72, 138, 28, NULL, NULL, '2020-11-26 16:20:15', 1),
(552, 26, 72, 139, 9, NULL, NULL, '2020-11-26 16:20:15', 1),
(553, 26, 72, 139, 10, NULL, NULL, '2020-11-26 16:20:15', 1),
(554, 26, 72, 139, 11, NULL, NULL, '2020-11-26 16:20:15', 1),
(555, 54, 72, 140, 33, NULL, NULL, '2020-11-26 16:20:15', 1),
(556, 15, 72, 140, 34, NULL, NULL, '2020-11-26 16:20:15', 1),
(557, 55, 72, 140, 35, NULL, NULL, '2020-11-26 16:20:15', 1),
(558, 59, 72, 140, 36, NULL, NULL, '2020-11-26 16:20:15', 1),
(559, 54, 72, 141, 21, NULL, NULL, '2020-11-26 16:20:15', 1),
(560, 15, 72, 141, 22, NULL, NULL, '2020-11-26 16:20:15', 1),
(561, 55, 72, 141, 23, NULL, NULL, '2020-11-26 16:20:15', 1),
(562, 59, 72, 141, 24, NULL, NULL, '2020-11-26 16:20:15', 1),
(1151, 58, 94, 268, 1, NULL, NULL, '2020-11-28 16:37:24', 1),
(1152, 48, 94, 268, 2, NULL, NULL, '2020-11-28 16:37:24', 1),
(1153, 46, 94, 268, 3, NULL, NULL, '2020-11-28 16:37:24', 1),
(1154, 47, 94, 268, 4, NULL, NULL, '2020-11-28 16:37:24', 1),
(1155, 42, 94, 268, 5, NULL, NULL, '2020-11-28 16:37:24', 1),
(1156, 45, 94, 268, 6, NULL, NULL, '2020-11-28 16:37:24', 1),
(1157, 49, 94, 268, 7, NULL, NULL, '2020-11-28 16:37:24', 1),
(1158, 50, 94, 268, 8, NULL, NULL, '2020-11-28 16:37:24', 1),
(1159, 45, 94, 268, 15, NULL, NULL, '2020-11-28 16:37:24', 1),
(1160, 51, 94, 268, 16, NULL, NULL, '2020-11-28 16:37:24', 1),
(1161, 45, 94, 268, 25, NULL, NULL, '2020-11-28 16:37:24', 1),
(1162, 15, 94, 269, 18, NULL, NULL, '2020-11-28 16:37:24', 1),
(1163, 17, 94, 269, 19, NULL, NULL, '2020-11-28 16:37:24', 1),
(1164, 53, 94, 269, 20, NULL, NULL, '2020-11-28 16:37:24', 1),
(1165, 57, 94, 270, 26, NULL, NULL, '2020-11-28 16:37:24', 1),
(1166, 56, 94, 270, 27, NULL, NULL, '2020-11-28 16:37:24', 1),
(1167, 3, 94, 270, 28, NULL, NULL, '2020-11-28 16:37:24', 1),
(1168, 26, 94, 271, 9, NULL, NULL, '2020-11-28 16:37:24', 1),
(1169, 26, 94, 271, 10, NULL, NULL, '2020-11-28 16:37:24', 1),
(1170, 26, 94, 271, 11, NULL, NULL, '2020-11-28 16:37:24', 1),
(1171, 54, 94, 272, 33, NULL, NULL, '2020-11-28 16:37:24', 1),
(1172, 15, 94, 272, 34, NULL, NULL, '2020-11-28 16:37:24', 1),
(1173, 55, 94, 272, 35, NULL, NULL, '2020-11-28 16:37:24', 1),
(1174, 59, 94, 272, 36, NULL, NULL, '2020-11-28 16:37:24', 1),
(1175, 54, 94, 273, 21, NULL, NULL, '2020-11-28 16:37:24', 1),
(1176, 15, 94, 273, 22, NULL, NULL, '2020-11-28 16:37:24', 1),
(1177, 55, 94, 273, 23, NULL, NULL, '2020-11-28 16:37:24', 1),
(1178, 59, 94, 273, 24, NULL, NULL, '2020-11-28 16:37:24', 1),
(1179, 58, 95, 274, 1, NULL, NULL, '2020-11-30 16:24:35', 1),
(1180, 48, 95, 274, 2, NULL, NULL, '2020-11-30 16:24:35', 1),
(1181, 46, 95, 274, 3, NULL, NULL, '2020-11-30 16:24:35', 1),
(1182, 47, 95, 274, 4, NULL, NULL, '2020-11-30 16:24:35', 1),
(1183, 42, 95, 274, 5, NULL, NULL, '2020-11-30 16:24:35', 1),
(1184, 45, 95, 274, 6, NULL, NULL, '2020-11-30 16:24:35', 1),
(1185, 49, 95, 274, 7, NULL, NULL, '2020-11-30 16:24:35', 1),
(1186, 50, 95, 274, 8, NULL, NULL, '2020-11-30 16:24:35', 1),
(1187, 45, 95, 274, 15, NULL, NULL, '2020-11-30 16:24:35', 1),
(1188, 51, 95, 274, 16, NULL, NULL, '2020-11-30 16:24:35', 1),
(1189, 45, 95, 274, 25, NULL, NULL, '2020-11-30 16:24:35', 1),
(1190, 15, 95, 275, 18, NULL, NULL, '2020-11-30 16:24:35', 1),
(1191, 17, 95, 275, 19, NULL, NULL, '2020-11-30 16:24:35', 1),
(1192, 53, 95, 275, 20, NULL, NULL, '2020-11-30 16:24:35', 1),
(1193, 57, 95, 276, 26, NULL, NULL, '2020-11-30 16:24:35', 1),
(1194, 56, 95, 276, 27, NULL, NULL, '2020-11-30 16:24:35', 1),
(1195, 3, 95, 276, 28, NULL, NULL, '2020-11-30 16:24:35', 1),
(1196, 26, 95, 277, 9, NULL, NULL, '2020-11-30 16:24:35', 1),
(1197, 26, 95, 277, 10, NULL, NULL, '2020-11-30 16:24:35', 1),
(1198, 26, 95, 277, 11, NULL, NULL, '2020-11-30 16:24:35', 1),
(1199, 54, 95, 278, 33, NULL, NULL, '2020-11-30 16:24:35', 1),
(1200, 15, 95, 278, 34, NULL, NULL, '2020-11-30 16:24:35', 1),
(1201, 55, 95, 278, 35, NULL, NULL, '2020-11-30 16:24:35', 1),
(1202, 59, 95, 278, 36, NULL, NULL, '2020-11-30 16:24:35', 1),
(1203, 54, 95, 279, 21, NULL, NULL, '2020-11-30 16:24:35', 1),
(1204, 15, 95, 279, 22, NULL, NULL, '2020-11-30 16:24:35', 1),
(1205, 55, 95, 279, 23, NULL, NULL, '2020-11-30 16:24:35', 1),
(1206, 59, 95, 279, 24, NULL, NULL, '2020-11-30 16:24:35', 1),
(1207, 58, 96, 280, 1, NULL, NULL, '2020-12-01 16:22:36', 1),
(1208, 48, 96, 280, 2, NULL, NULL, '2020-12-01 16:22:36', 1),
(1209, 46, 96, 280, 3, NULL, NULL, '2020-12-01 16:22:36', 1),
(1210, 47, 96, 280, 4, NULL, NULL, '2020-12-01 16:22:36', 1),
(1211, 42, 96, 280, 5, NULL, NULL, '2020-12-01 16:22:36', 1),
(1212, 45, 96, 280, 6, NULL, NULL, '2020-12-01 16:22:36', 1),
(1213, 49, 96, 280, 7, NULL, NULL, '2020-12-01 16:22:36', 1),
(1214, 50, 96, 280, 8, NULL, NULL, '2020-12-01 16:22:36', 1),
(1215, 45, 96, 280, 15, NULL, NULL, '2020-12-01 16:22:36', 1),
(1216, 51, 96, 280, 16, NULL, NULL, '2020-12-01 16:22:36', 1),
(1217, 45, 96, 280, 25, NULL, NULL, '2020-12-01 16:22:36', 1),
(1218, 15, 96, 281, 18, NULL, NULL, '2020-12-01 16:22:36', 1),
(1219, 17, 96, 281, 19, NULL, NULL, '2020-12-01 16:22:36', 1),
(1220, 53, 96, 281, 20, NULL, NULL, '2020-12-01 16:22:36', 1),
(1221, 57, 96, 282, 26, NULL, NULL, '2020-12-01 16:22:36', 1),
(1222, 56, 96, 282, 27, NULL, NULL, '2020-12-01 16:22:36', 1),
(1223, 3, 96, 282, 28, NULL, NULL, '2020-12-01 16:22:36', 1),
(1224, 26, 96, 283, 9, NULL, NULL, '2020-12-01 16:22:36', 1),
(1225, 26, 96, 283, 10, NULL, NULL, '2020-12-01 16:22:36', 1),
(1226, 26, 96, 283, 11, NULL, NULL, '2020-12-01 16:22:36', 1),
(1227, 54, 96, 284, 33, NULL, NULL, '2020-12-01 16:22:36', 1),
(1228, 15, 96, 284, 34, NULL, NULL, '2020-12-01 16:22:36', 1),
(1229, 55, 96, 284, 35, NULL, NULL, '2020-12-01 16:22:36', 1),
(1230, 59, 96, 284, 36, NULL, NULL, '2020-12-01 16:22:36', 1),
(1231, 54, 96, 285, 21, NULL, NULL, '2020-12-01 16:22:36', 1),
(1232, 15, 96, 285, 22, NULL, NULL, '2020-12-01 16:22:36', 1),
(1233, 55, 96, 285, 23, NULL, NULL, '2020-12-01 16:22:36', 1),
(1234, 59, 96, 285, 24, NULL, NULL, '2020-12-01 16:22:36', 1),
(1235, 58, 97, 286, 1, NULL, NULL, '2020-12-07 16:53:02', 1),
(1236, 48, 97, 286, 2, NULL, NULL, '2020-12-07 16:53:02', 1),
(1237, 46, 97, 286, 3, NULL, NULL, '2020-12-07 16:53:02', 1),
(1238, 47, 97, 286, 4, NULL, NULL, '2020-12-07 16:53:02', 1),
(1239, 42, 97, 286, 5, NULL, NULL, '2020-12-07 16:53:02', 1),
(1240, 45, 97, 286, 6, NULL, NULL, '2020-12-07 16:53:02', 1),
(1241, 49, 97, 286, 7, NULL, NULL, '2020-12-07 16:53:02', 1),
(1242, 50, 97, 286, 8, NULL, NULL, '2020-12-07 16:53:02', 1),
(1243, 45, 97, 286, 15, NULL, NULL, '2020-12-07 16:53:02', 1),
(1244, 51, 97, 286, 16, NULL, NULL, '2020-12-07 16:53:02', 1),
(1245, 45, 97, 286, 25, NULL, NULL, '2020-12-07 16:53:02', 1),
(1246, 15, 97, 287, 18, NULL, NULL, '2020-12-07 16:53:02', 1),
(1247, 17, 97, 287, 19, NULL, NULL, '2020-12-07 16:53:02', 1),
(1248, 53, 97, 287, 20, NULL, NULL, '2020-12-07 16:53:02', 1),
(1249, 57, 97, 288, 26, NULL, NULL, '2020-12-07 16:53:02', 1),
(1250, 56, 97, 288, 27, NULL, NULL, '2020-12-07 16:53:02', 1),
(1251, 3, 97, 288, 28, NULL, NULL, '2020-12-07 16:53:02', 1),
(1252, 26, 97, 289, 9, NULL, NULL, '2020-12-07 16:53:02', 1),
(1253, 26, 97, 289, 10, NULL, NULL, '2020-12-07 16:53:02', 1),
(1254, 26, 97, 289, 11, NULL, NULL, '2020-12-07 16:53:02', 1),
(1255, 54, 97, 290, 33, NULL, NULL, '2020-12-07 16:53:02', 1),
(1256, 15, 97, 290, 34, NULL, NULL, '2020-12-07 16:53:02', 1),
(1257, 55, 97, 290, 35, NULL, NULL, '2020-12-07 16:53:02', 1),
(1258, 59, 97, 290, 36, NULL, NULL, '2020-12-07 16:53:02', 1),
(1259, 54, 97, 291, 21, NULL, NULL, '2020-12-07 16:53:02', 1),
(1260, 15, 97, 291, 22, NULL, NULL, '2020-12-07 16:53:02', 1),
(1261, 55, 97, 291, 23, NULL, NULL, '2020-12-07 16:53:02', 1),
(1262, 59, 97, 291, 24, NULL, NULL, '2020-12-07 16:53:02', 1),
(1263, 58, 98, 292, 1, NULL, NULL, '2020-12-07 16:58:33', 1),
(1264, 48, 98, 292, 2, NULL, NULL, '2020-12-07 16:58:33', 1),
(1265, 46, 98, 292, 3, NULL, NULL, '2020-12-07 16:58:33', 1),
(1266, 47, 98, 292, 4, NULL, NULL, '2020-12-07 16:58:33', 1),
(1267, 42, 98, 292, 5, NULL, NULL, '2020-12-07 16:58:33', 1),
(1268, 45, 98, 292, 6, NULL, NULL, '2020-12-07 16:58:33', 1),
(1269, 49, 98, 292, 7, NULL, NULL, '2020-12-07 16:58:33', 1),
(1270, 50, 98, 292, 8, NULL, NULL, '2020-12-07 16:58:33', 1),
(1271, 45, 98, 292, 15, NULL, NULL, '2020-12-07 16:58:33', 1),
(1272, 51, 98, 292, 16, NULL, NULL, '2020-12-07 16:58:33', 1),
(1273, 45, 98, 292, 25, NULL, NULL, '2020-12-07 16:58:33', 1),
(1274, 15, 98, 293, 18, NULL, NULL, '2020-12-07 16:58:33', 1),
(1275, 17, 98, 293, 19, NULL, NULL, '2020-12-07 16:58:33', 1),
(1276, 53, 98, 293, 20, NULL, NULL, '2020-12-07 16:58:33', 1),
(1277, 57, 98, 294, 26, NULL, NULL, '2020-12-07 16:58:33', 1),
(1278, 56, 98, 294, 27, NULL, NULL, '2020-12-07 16:58:33', 1),
(1279, 3, 98, 294, 28, NULL, NULL, '2020-12-07 16:58:33', 1),
(1280, 26, 98, 295, 9, NULL, NULL, '2020-12-07 16:58:33', 1),
(1281, 26, 98, 295, 10, NULL, NULL, '2020-12-07 16:58:33', 1),
(1282, 26, 98, 295, 11, NULL, NULL, '2020-12-07 16:58:33', 1),
(1283, 54, 98, 296, 33, NULL, NULL, '2020-12-07 16:58:33', 1),
(1284, 15, 98, 296, 34, NULL, NULL, '2020-12-07 16:58:33', 1),
(1285, 55, 98, 296, 35, NULL, NULL, '2020-12-07 16:58:33', 1),
(1286, 59, 98, 296, 36, NULL, NULL, '2020-12-07 16:58:33', 1),
(1287, 54, 98, 297, 21, NULL, NULL, '2020-12-07 16:58:33', 1),
(1288, 15, 98, 297, 22, NULL, NULL, '2020-12-07 16:58:33', 1),
(1289, 55, 98, 297, 23, NULL, NULL, '2020-12-07 16:58:33', 1),
(1290, 59, 98, 297, 24, NULL, NULL, '2020-12-07 16:58:33', 1),
(1291, 58, 99, 298, 1, NULL, NULL, '2020-12-07 21:39:29', 1),
(1292, 48, 99, 298, 2, NULL, NULL, '2020-12-07 21:39:29', 1),
(1293, 46, 99, 298, 3, NULL, NULL, '2020-12-07 21:39:29', 1),
(1294, 47, 99, 298, 4, NULL, NULL, '2020-12-07 21:39:29', 1),
(1295, 42, 99, 298, 5, NULL, NULL, '2020-12-07 21:39:29', 1),
(1296, 45, 99, 298, 6, NULL, NULL, '2020-12-07 21:39:29', 1),
(1297, 49, 99, 298, 7, NULL, NULL, '2020-12-07 21:39:29', 1),
(1298, 50, 99, 298, 8, NULL, NULL, '2020-12-07 21:39:29', 1),
(1299, 45, 99, 298, 15, NULL, NULL, '2020-12-07 21:39:29', 1),
(1300, 51, 99, 298, 16, NULL, NULL, '2020-12-07 21:39:29', 1),
(1301, 45, 99, 298, 25, NULL, NULL, '2020-12-07 21:39:29', 1),
(1302, 15, 99, 299, 18, NULL, NULL, '2020-12-07 21:39:29', 1),
(1303, 17, 99, 299, 19, NULL, NULL, '2020-12-07 21:39:29', 1),
(1304, 53, 99, 299, 20, NULL, NULL, '2020-12-07 21:39:29', 1),
(1305, 57, 99, 300, 26, NULL, NULL, '2020-12-07 21:39:29', 1),
(1306, 56, 99, 300, 27, NULL, NULL, '2020-12-07 21:39:29', 1),
(1307, 3, 99, 300, 28, NULL, NULL, '2020-12-07 21:39:29', 1),
(1308, 26, 99, 301, 9, NULL, NULL, '2020-12-07 21:39:29', 1),
(1309, 26, 99, 301, 10, NULL, NULL, '2020-12-07 21:39:29', 1),
(1310, 26, 99, 301, 11, NULL, NULL, '2020-12-07 21:39:29', 1),
(1311, 54, 99, 302, 33, NULL, NULL, '2020-12-07 21:39:29', 1),
(1312, 15, 99, 302, 34, NULL, NULL, '2020-12-07 21:39:29', 1),
(1313, 55, 99, 302, 35, NULL, NULL, '2020-12-07 21:39:29', 1),
(1314, 59, 99, 302, 36, NULL, NULL, '2020-12-07 21:39:29', 1),
(1315, 54, 99, 303, 21, NULL, NULL, '2020-12-07 21:39:29', 1),
(1316, 15, 99, 303, 22, NULL, NULL, '2020-12-07 21:39:29', 1),
(1317, 55, 99, 303, 23, NULL, NULL, '2020-12-07 21:39:29', 1),
(1318, 59, 99, 303, 24, NULL, NULL, '2020-12-07 21:39:29', 1),
(1319, 58, 100, 304, 1, NULL, NULL, '2020-12-07 21:40:11', 1),
(1320, 48, 100, 304, 2, NULL, NULL, '2020-12-07 21:40:11', 1),
(1321, 46, 100, 304, 3, NULL, NULL, '2020-12-07 21:40:11', 1),
(1322, 47, 100, 304, 4, NULL, NULL, '2020-12-07 21:40:11', 1),
(1323, 42, 100, 304, 5, NULL, NULL, '2020-12-07 21:40:11', 1),
(1324, 45, 100, 304, 6, NULL, NULL, '2020-12-07 21:40:11', 1),
(1325, 49, 100, 304, 7, NULL, NULL, '2020-12-07 21:40:11', 1),
(1326, 50, 100, 304, 8, NULL, NULL, '2020-12-07 21:40:11', 1),
(1327, 45, 100, 304, 15, NULL, NULL, '2020-12-07 21:40:11', 1),
(1328, 51, 100, 304, 16, NULL, NULL, '2020-12-07 21:40:11', 1),
(1329, 45, 100, 304, 25, NULL, NULL, '2020-12-07 21:40:11', 1),
(1330, 15, 100, 305, 18, NULL, NULL, '2020-12-07 21:40:11', 1),
(1331, 17, 100, 305, 19, NULL, NULL, '2020-12-07 21:40:11', 1),
(1332, 53, 100, 305, 20, NULL, NULL, '2020-12-07 21:40:11', 1),
(1333, 57, 100, 306, 26, NULL, NULL, '2020-12-07 21:40:11', 1),
(1334, 56, 100, 306, 27, NULL, NULL, '2020-12-07 21:40:11', 1),
(1335, 3, 100, 306, 28, NULL, NULL, '2020-12-07 21:40:11', 1),
(1336, 26, 100, 307, 9, NULL, NULL, '2020-12-07 21:40:11', 1),
(1337, 26, 100, 307, 10, NULL, NULL, '2020-12-07 21:40:11', 1),
(1338, 26, 100, 307, 11, NULL, NULL, '2020-12-07 21:40:11', 1),
(1339, 54, 100, 308, 33, NULL, NULL, '2020-12-07 21:40:11', 1),
(1340, 15, 100, 308, 34, NULL, NULL, '2020-12-07 21:40:11', 1),
(1341, 55, 100, 308, 35, NULL, NULL, '2020-12-07 21:40:11', 1),
(1342, 59, 100, 308, 36, NULL, NULL, '2020-12-07 21:40:11', 1),
(1343, 54, 100, 309, 21, NULL, NULL, '2020-12-07 21:40:11', 1),
(1344, 15, 100, 309, 22, NULL, NULL, '2020-12-07 21:40:11', 1),
(1345, 55, 100, 309, 23, NULL, NULL, '2020-12-07 21:40:11', 1),
(1346, 59, 100, 309, 24, NULL, NULL, '2020-12-07 21:40:11', 1),
(1347, 37, 101, 310, 1, NULL, NULL, '2021-01-09 23:26:26', 1),
(1348, 48, 101, 310, 2, NULL, NULL, '2021-01-09 23:26:26', 1),
(1349, 46, 101, 310, 3, NULL, NULL, '2021-01-09 23:26:26', 1),
(1350, 47, 101, 310, 4, NULL, NULL, '2021-01-09 23:26:26', 1),
(1351, 42, 101, 310, 5, NULL, NULL, '2021-01-09 23:26:26', 1),
(1352, 45, 101, 310, 6, NULL, NULL, '2021-01-09 23:26:26', 1),
(1353, 49, 101, 310, 7, NULL, NULL, '2021-01-09 23:26:26', 1),
(1354, 50, 101, 310, 8, NULL, NULL, '2021-01-09 23:26:26', 1),
(1355, 45, 101, 310, 15, NULL, NULL, '2021-01-09 23:26:26', 1),
(1356, 51, 101, 310, 16, NULL, NULL, '2021-01-09 23:26:26', 1),
(1357, 45, 101, 310, 25, NULL, NULL, '2021-01-09 23:26:26', 1),
(1358, 15, 101, 311, 18, NULL, NULL, '2021-01-09 23:26:26', 1),
(1359, 17, 101, 311, 19, NULL, NULL, '2021-01-09 23:26:26', 1),
(1360, 53, 101, 311, 20, NULL, NULL, '2021-01-09 23:26:26', 1),
(1361, 57, 101, 312, 26, NULL, NULL, '2021-01-09 23:26:26', 1),
(1362, 56, 101, 312, 27, NULL, NULL, '2021-01-09 23:26:26', 1),
(1363, 3, 101, 312, 28, NULL, NULL, '2021-01-09 23:26:26', 1),
(1364, 26, 101, 313, 9, NULL, NULL, '2021-01-09 23:26:26', 1),
(1365, 26, 101, 313, 10, NULL, NULL, '2021-01-09 23:26:26', 1),
(1366, 26, 101, 313, 11, NULL, NULL, '2021-01-09 23:26:26', 1),
(1367, 54, 101, 314, 33, NULL, NULL, '2021-01-09 23:26:26', 1),
(1368, 15, 101, 314, 34, NULL, NULL, '2021-01-09 23:26:26', 1),
(1369, 55, 101, 314, 35, NULL, NULL, '2021-01-09 23:26:26', 1),
(1370, 59, 101, 314, 36, NULL, NULL, '2021-01-09 23:26:26', 1),
(1371, 54, 101, 315, 21, NULL, NULL, '2021-01-09 23:26:26', 1),
(1372, 15, 101, 315, 22, NULL, NULL, '2021-01-09 23:26:26', 1),
(1373, 55, 101, 315, 23, NULL, NULL, '2021-01-09 23:26:26', 1),
(1374, 59, 101, 315, 24, NULL, NULL, '2021-01-09 23:26:26', 1),
(1375, 37, 102, 316, 1, NULL, NULL, '2021-01-12 10:02:51', 1),
(1376, 48, 102, 316, 2, NULL, NULL, '2021-01-12 10:02:51', 1),
(1377, 46, 102, 316, 3, NULL, NULL, '2021-01-12 10:02:51', 1),
(1378, 47, 102, 316, 4, NULL, NULL, '2021-01-12 10:02:51', 1),
(1379, 42, 102, 316, 5, NULL, NULL, '2021-01-12 10:02:51', 1),
(1380, 45, 102, 316, 6, NULL, NULL, '2021-01-12 10:02:51', 1),
(1381, 49, 102, 316, 7, NULL, NULL, '2021-01-12 10:02:51', 1),
(1382, 50, 102, 316, 8, NULL, NULL, '2021-01-12 10:02:51', 1),
(1383, 45, 102, 316, 15, NULL, NULL, '2021-01-12 10:02:51', 1),
(1384, 51, 102, 316, 16, NULL, NULL, '2021-01-12 10:02:51', 1),
(1385, 45, 102, 316, 25, NULL, NULL, '2021-01-12 10:02:51', 1),
(1386, 15, 102, 317, 18, NULL, NULL, '2021-01-12 10:02:51', 1),
(1387, 17, 102, 317, 19, NULL, NULL, '2021-01-12 10:02:51', 1),
(1388, 53, 102, 317, 20, NULL, NULL, '2021-01-12 10:02:51', 1),
(1389, 57, 102, 318, 26, NULL, NULL, '2021-01-12 10:02:51', 1),
(1390, 56, 102, 318, 27, NULL, NULL, '2021-01-12 10:02:51', 1),
(1391, 3, 102, 318, 28, NULL, NULL, '2021-01-12 10:02:51', 1),
(1392, 26, 102, 319, 9, NULL, NULL, '2021-01-12 10:02:51', 1),
(1393, 26, 102, 319, 10, NULL, NULL, '2021-01-12 10:02:51', 1),
(1394, 26, 102, 319, 11, NULL, NULL, '2021-01-12 10:02:51', 1),
(1395, 54, 102, 320, 33, NULL, NULL, '2021-01-12 10:02:51', 1),
(1396, 15, 102, 320, 34, NULL, NULL, '2021-01-12 10:02:51', 1),
(1397, 55, 102, 320, 35, NULL, NULL, '2021-01-12 10:02:51', 1),
(1398, 59, 102, 320, 36, NULL, NULL, '2021-01-12 10:02:51', 1),
(1399, 54, 102, 321, 21, NULL, NULL, '2021-01-12 10:02:51', 1),
(1400, 15, 102, 321, 22, NULL, NULL, '2021-01-12 10:02:51', 1),
(1401, 55, 102, 321, 23, NULL, NULL, '2021-01-12 10:02:51', 1),
(1402, 59, 102, 321, 24, NULL, NULL, '2021-01-12 10:02:51', 1),
(1543, 37, 108, 352, 1, NULL, NULL, '2021-01-19 14:08:31', 1),
(1544, 48, 108, 352, 2, NULL, NULL, '2021-01-19 14:08:31', 1),
(1545, 46, 108, 352, 3, NULL, NULL, '2021-01-19 14:08:31', 1),
(1546, 47, 108, 352, 4, NULL, NULL, '2021-01-19 14:08:31', 1),
(1547, 42, 108, 352, 5, NULL, NULL, '2021-01-19 14:08:31', 1),
(1548, 45, 108, 352, 6, NULL, NULL, '2021-01-19 14:08:31', 1),
(1549, 49, 108, 352, 7, NULL, NULL, '2021-01-19 14:08:31', 1),
(1550, 50, 108, 352, 8, NULL, NULL, '2021-01-19 14:08:31', 1),
(1551, 45, 108, 352, 15, NULL, NULL, '2021-01-19 14:08:31', 1),
(1552, 51, 108, 352, 16, NULL, NULL, '2021-01-19 14:08:31', 1),
(1553, 45, 108, 352, 25, NULL, NULL, '2021-01-19 14:08:31', 1),
(1554, 15, 108, 353, 18, NULL, NULL, '2021-01-19 14:08:31', 1),
(1555, 17, 108, 353, 19, NULL, NULL, '2021-01-19 14:08:31', 1),
(1556, 53, 108, 353, 20, NULL, NULL, '2021-01-19 14:08:31', 1),
(1557, 57, 108, 354, 26, NULL, NULL, '2021-01-19 14:08:31', 1),
(1558, 56, 108, 354, 27, NULL, NULL, '2021-01-19 14:08:31', 1),
(1559, 3, 108, 354, 28, NULL, NULL, '2021-01-19 14:08:31', 1),
(1560, 26, 108, 355, 9, NULL, NULL, '2021-01-19 14:08:31', 1),
(1561, 26, 108, 355, 10, NULL, NULL, '2021-01-19 14:08:31', 1),
(1562, 26, 108, 355, 11, NULL, NULL, '2021-01-19 14:08:31', 1),
(1563, 54, 108, 356, 33, NULL, NULL, '2021-01-19 14:08:31', 1),
(1564, 15, 108, 356, 34, NULL, NULL, '2021-01-19 14:08:31', 1),
(1565, 55, 108, 356, 35, NULL, NULL, '2021-01-19 14:08:31', 1),
(1566, 59, 108, 356, 36, NULL, NULL, '2021-01-19 14:08:31', 1),
(1567, 54, 108, 357, 21, NULL, NULL, '2021-01-19 14:08:31', 1),
(1568, 15, 108, 357, 22, NULL, NULL, '2021-01-19 14:08:31', 1),
(1569, 55, 108, 357, 23, NULL, NULL, '2021-01-19 14:08:31', 1),
(1570, 59, 108, 357, 24, NULL, NULL, '2021-01-19 14:08:31', 1),
(1655, 37, 112, 376, 1, NULL, NULL, '2021-01-20 10:23:53', 1),
(1656, 48, 112, 376, 2, NULL, NULL, '2021-01-20 10:23:53', 1),
(1657, 46, 112, 376, 3, NULL, NULL, '2021-01-20 10:23:53', 1),
(1658, 47, 112, 376, 4, NULL, NULL, '2021-01-20 10:23:53', 1),
(1659, 42, 112, 376, 5, NULL, NULL, '2021-01-20 10:23:53', 1),
(1660, 45, 112, 376, 6, NULL, NULL, '2021-01-20 10:23:53', 1),
(1661, 49, 112, 376, 7, NULL, NULL, '2021-01-20 10:23:53', 1),
(1662, 50, 112, 376, 8, NULL, NULL, '2021-01-20 10:23:53', 1),
(1663, 45, 112, 376, 15, NULL, NULL, '2021-01-20 10:23:53', 1),
(1664, 51, 112, 376, 16, NULL, NULL, '2021-01-20 10:23:53', 1),
(1665, 45, 112, 376, 25, NULL, NULL, '2021-01-20 10:23:53', 1),
(1666, 15, 112, 377, 18, NULL, NULL, '2021-01-20 10:23:53', 1),
(1667, 17, 112, 377, 19, NULL, NULL, '2021-01-20 10:23:53', 1),
(1668, 53, 112, 377, 20, NULL, NULL, '2021-01-20 10:23:53', 1),
(1669, 57, 112, 378, 26, NULL, NULL, '2021-01-20 10:23:53', 1),
(1670, 56, 112, 378, 27, NULL, NULL, '2021-01-20 10:23:53', 1),
(1671, 3, 112, 378, 28, NULL, NULL, '2021-01-20 10:23:53', 1),
(1672, 26, 112, 379, 9, NULL, NULL, '2021-01-20 10:23:53', 1),
(1673, 26, 112, 379, 10, NULL, NULL, '2021-01-20 10:23:53', 1),
(1674, 26, 112, 379, 11, NULL, NULL, '2021-01-20 10:23:53', 1),
(1675, 54, 112, 380, 33, NULL, NULL, '2021-01-20 10:23:53', 1),
(1676, 15, 112, 380, 34, NULL, NULL, '2021-01-20 10:23:53', 1),
(1677, 55, 112, 380, 35, NULL, NULL, '2021-01-20 10:23:53', 1),
(1678, 59, 112, 380, 36, NULL, NULL, '2021-01-20 10:23:53', 1),
(1679, 54, 112, 381, 21, NULL, NULL, '2021-01-20 10:23:53', 1),
(1680, 15, 112, 381, 22, NULL, NULL, '2021-01-20 10:23:53', 1),
(1681, 55, 112, 381, 23, NULL, NULL, '2021-01-20 10:23:53', 1),
(1682, 59, 112, 381, 24, NULL, NULL, '2021-01-20 10:23:53', 1),
(1683, 37, 113, 382, 1, NULL, NULL, '2021-01-20 10:26:29', 1),
(1684, 48, 113, 382, 2, NULL, NULL, '2021-01-20 10:26:29', 1),
(1685, 46, 113, 382, 3, NULL, NULL, '2021-01-20 10:26:29', 1),
(1686, 47, 113, 382, 4, NULL, NULL, '2021-01-20 10:26:29', 1),
(1687, 42, 113, 382, 5, NULL, NULL, '2021-01-20 10:26:29', 1),
(1688, 45, 113, 382, 6, NULL, NULL, '2021-01-20 10:26:29', 1),
(1689, 49, 113, 382, 7, NULL, NULL, '2021-01-20 10:26:29', 1),
(1690, 50, 113, 382, 8, NULL, NULL, '2021-01-20 10:26:29', 1),
(1691, 45, 113, 382, 15, NULL, NULL, '2021-01-20 10:26:29', 1),
(1692, 51, 113, 382, 16, NULL, NULL, '2021-01-20 10:26:29', 1),
(1693, 45, 113, 382, 25, NULL, NULL, '2021-01-20 10:26:29', 1),
(1694, 15, 113, 383, 18, NULL, NULL, '2021-01-20 10:26:29', 1),
(1695, 17, 113, 383, 19, NULL, NULL, '2021-01-20 10:26:29', 1),
(1696, 53, 113, 383, 20, NULL, NULL, '2021-01-20 10:26:29', 1),
(1697, 57, 113, 384, 26, NULL, NULL, '2021-01-20 10:26:29', 1),
(1698, 56, 113, 384, 27, NULL, NULL, '2021-01-20 10:26:29', 1),
(1699, 3, 113, 384, 28, NULL, NULL, '2021-01-20 10:26:29', 1),
(1700, 26, 113, 385, 9, NULL, NULL, '2021-01-20 10:26:29', 1),
(1701, 26, 113, 385, 10, NULL, NULL, '2021-01-20 10:26:29', 1),
(1702, 26, 113, 385, 11, NULL, NULL, '2021-01-20 10:26:29', 1),
(1703, 54, 113, 386, 33, NULL, NULL, '2021-01-20 10:26:29', 1),
(1704, 15, 113, 386, 34, NULL, NULL, '2021-01-20 10:26:29', 1),
(1705, 55, 113, 386, 35, NULL, NULL, '2021-01-20 10:26:29', 1),
(1706, 59, 113, 386, 36, NULL, NULL, '2021-01-20 10:26:29', 1),
(1707, 54, 113, 387, 21, NULL, NULL, '2021-01-20 10:26:29', 1),
(1708, 15, 113, 387, 22, NULL, NULL, '2021-01-20 10:26:29', 1),
(1709, 55, 113, 387, 23, NULL, NULL, '2021-01-20 10:26:29', 1),
(1710, 59, 113, 387, 24, NULL, NULL, '2021-01-20 10:26:29', 1),
(1711, 37, 114, 388, 1, NULL, NULL, '2021-01-20 10:36:37', 1),
(1712, 48, 114, 388, 2, NULL, NULL, '2021-01-20 10:36:37', 1),
(1713, 46, 114, 388, 3, NULL, NULL, '2021-01-20 10:36:37', 1),
(1714, 47, 114, 388, 4, NULL, NULL, '2021-01-20 10:36:37', 1),
(1715, 42, 114, 388, 5, NULL, NULL, '2021-01-20 10:36:37', 1),
(1716, 45, 114, 388, 6, NULL, NULL, '2021-01-20 10:36:37', 1),
(1717, 49, 114, 388, 7, NULL, NULL, '2021-01-20 10:36:37', 1),
(1718, 50, 114, 388, 8, NULL, NULL, '2021-01-20 10:36:37', 1),
(1719, 45, 114, 388, 15, NULL, NULL, '2021-01-20 10:36:37', 1),
(1720, 51, 114, 388, 16, NULL, NULL, '2021-01-20 10:36:37', 1),
(1721, 45, 114, 388, 25, NULL, NULL, '2021-01-20 10:36:37', 1),
(1722, 15, 114, 389, 18, NULL, NULL, '2021-01-20 10:36:37', 1),
(1723, 17, 114, 389, 19, NULL, NULL, '2021-01-20 10:36:37', 1),
(1724, 53, 114, 389, 20, NULL, NULL, '2021-01-20 10:36:37', 1),
(1725, 57, 114, 390, 26, NULL, NULL, '2021-01-20 10:36:37', 1),
(1726, 56, 114, 390, 27, NULL, NULL, '2021-01-20 10:36:37', 1),
(1727, 3, 114, 390, 28, NULL, NULL, '2021-01-20 10:36:37', 1),
(1728, 26, 114, 391, 9, NULL, NULL, '2021-01-20 10:36:37', 1),
(1729, 26, 114, 391, 10, NULL, NULL, '2021-01-20 10:36:37', 1),
(1730, 26, 114, 391, 11, NULL, NULL, '2021-01-20 10:36:37', 1),
(1731, 54, 114, 392, 33, NULL, NULL, '2021-01-20 10:36:37', 1),
(1732, 15, 114, 392, 34, NULL, NULL, '2021-01-20 10:36:37', 1),
(1733, 55, 114, 392, 35, NULL, NULL, '2021-01-20 10:36:37', 1),
(1734, 59, 114, 392, 36, NULL, NULL, '2021-01-20 10:36:37', 1),
(1735, 54, 114, 393, 21, NULL, NULL, '2021-01-20 10:36:37', 1),
(1736, 15, 114, 393, 22, NULL, NULL, '2021-01-20 10:36:37', 1),
(1737, 55, 114, 393, 23, NULL, NULL, '2021-01-20 10:36:37', 1),
(1738, 59, 114, 393, 24, NULL, NULL, '2021-01-20 10:36:37', 1),
(2355, 37, 137, 526, 1, NULL, NULL, '2021-01-22 11:42:17', 1),
(2356, 48, 137, 526, 2, NULL, NULL, '2021-01-22 11:42:17', 1),
(2357, 46, 137, 526, 3, NULL, NULL, '2021-01-22 11:42:17', 1),
(2358, 47, 137, 526, 4, NULL, NULL, '2021-01-22 11:42:17', 1),
(2359, 42, 137, 526, 5, NULL, NULL, '2021-01-22 11:42:17', 1),
(2360, 45, 137, 526, 6, NULL, NULL, '2021-01-22 11:42:17', 1),
(2361, 49, 137, 526, 7, NULL, NULL, '2021-01-22 11:42:17', 1),
(2362, 50, 137, 526, 8, NULL, NULL, '2021-01-22 11:42:17', 1),
(2363, 45, 137, 526, 15, NULL, NULL, '2021-01-22 11:42:17', 1),
(2364, 51, 137, 526, 16, NULL, NULL, '2021-01-22 11:42:17', 1),
(2365, 45, 137, 526, 25, NULL, NULL, '2021-01-22 11:42:17', 1),
(2366, 15, 137, 527, 18, NULL, NULL, '2021-01-22 11:42:17', 1),
(2367, 17, 137, 527, 19, NULL, NULL, '2021-01-22 11:42:17', 1),
(2368, 53, 137, 527, 20, NULL, NULL, '2021-01-22 11:42:17', 1),
(2369, 57, 137, 528, 26, NULL, NULL, '2021-01-22 11:42:17', 1),
(2370, 56, 137, 528, 27, NULL, NULL, '2021-01-22 11:42:17', 1),
(2371, 3, 137, 528, 28, NULL, NULL, '2021-01-22 11:42:17', 1),
(2372, 26, 137, 529, 9, NULL, NULL, '2021-01-22 11:42:17', 1),
(2373, 26, 137, 529, 10, NULL, NULL, '2021-01-22 11:42:17', 1),
(2374, 26, 137, 529, 11, NULL, NULL, '2021-01-22 11:42:17', 1),
(2375, 54, 137, 530, 33, NULL, NULL, '2021-01-22 11:42:17', 1),
(2376, 15, 137, 530, 34, NULL, NULL, '2021-01-22 11:42:17', 1),
(2377, 55, 137, 530, 35, NULL, NULL, '2021-01-22 11:42:17', 1),
(2378, 59, 137, 530, 36, NULL, NULL, '2021-01-22 11:42:17', 1),
(2379, 54, 137, 531, 21, NULL, NULL, '2021-01-22 11:42:17', 1),
(2380, 15, 137, 531, 22, NULL, NULL, '2021-01-22 11:42:17', 1),
(2381, 55, 137, 531, 23, NULL, NULL, '2021-01-22 11:42:17', 1),
(2382, 59, 137, 531, 24, NULL, NULL, '2021-01-22 11:42:17', 1),
(2383, 37, 138, 532, 1, NULL, NULL, '2021-01-24 10:06:38', 1),
(2384, 48, 138, 532, 2, NULL, NULL, '2021-01-24 10:06:38', 1),
(2385, 46, 138, 532, 3, NULL, NULL, '2021-01-24 10:06:38', 1),
(2386, 47, 138, 532, 4, NULL, NULL, '2021-01-24 10:06:38', 1),
(2387, 42, 138, 532, 5, NULL, NULL, '2021-01-24 10:06:38', 1),
(2388, 45, 138, 532, 6, NULL, NULL, '2021-01-24 10:06:38', 1),
(2389, 49, 138, 532, 7, NULL, NULL, '2021-01-24 10:06:38', 1),
(2390, 50, 138, 532, 8, NULL, NULL, '2021-01-24 10:06:38', 1),
(2391, 45, 138, 532, 15, NULL, NULL, '2021-01-24 10:06:38', 1),
(2392, 51, 138, 532, 16, NULL, NULL, '2021-01-24 10:06:38', 1),
(2393, 45, 138, 532, 25, NULL, NULL, '2021-01-24 10:06:38', 1),
(2394, 15, 138, 533, 18, NULL, NULL, '2021-01-24 10:06:38', 1),
(2395, 17, 138, 533, 19, NULL, NULL, '2021-01-24 10:06:38', 1),
(2396, 53, 138, 533, 20, NULL, NULL, '2021-01-24 10:06:38', 1),
(2397, 57, 138, 534, 26, NULL, NULL, '2021-01-24 10:06:38', 1),
(2398, 56, 138, 534, 27, NULL, NULL, '2021-01-24 10:06:38', 1),
(2399, 3, 138, 534, 28, NULL, NULL, '2021-01-24 10:06:38', 1),
(2400, 26, 138, 535, 9, NULL, NULL, '2021-01-24 10:06:38', 1),
(2401, 26, 138, 535, 10, NULL, NULL, '2021-01-24 10:06:38', 1),
(2402, 26, 138, 535, 11, NULL, NULL, '2021-01-24 10:06:38', 1),
(2403, 54, 138, 536, 33, NULL, NULL, '2021-01-24 10:06:38', 1),
(2404, 15, 138, 536, 34, NULL, NULL, '2021-01-24 10:06:38', 1),
(2405, 55, 138, 536, 35, NULL, NULL, '2021-01-24 10:06:38', 1),
(2406, 59, 138, 536, 36, NULL, NULL, '2021-01-24 10:06:38', 1),
(2407, 54, 138, 537, 21, NULL, NULL, '2021-01-24 10:06:38', 1),
(2408, 15, 138, 537, 22, NULL, NULL, '2021-01-24 10:06:38', 1),
(2409, 55, 138, 537, 23, NULL, NULL, '2021-01-24 10:06:38', 1),
(2410, 59, 138, 537, 24, NULL, NULL, '2021-01-24 10:06:38', 1),
(2411, 37, 139, 538, 1, NULL, NULL, '2021-01-29 11:05:12', 1),
(2412, 48, 139, 538, 2, NULL, NULL, '2021-01-29 11:05:12', 1),
(2413, 46, 139, 538, 3, NULL, NULL, '2021-01-29 11:05:12', 1),
(2414, 47, 139, 538, 4, NULL, NULL, '2021-01-29 11:05:12', 1),
(2415, 42, 139, 538, 5, NULL, NULL, '2021-01-29 11:05:12', 1),
(2416, 45, 139, 538, 6, NULL, NULL, '2021-01-29 11:05:12', 1),
(2417, 49, 139, 538, 7, NULL, NULL, '2021-01-29 11:05:12', 1),
(2418, 50, 139, 538, 8, NULL, NULL, '2021-01-29 11:05:12', 1),
(2419, 45, 139, 538, 15, NULL, NULL, '2021-01-29 11:05:12', 1),
(2420, 51, 139, 538, 16, NULL, NULL, '2021-01-29 11:05:12', 1),
(2421, 45, 139, 538, 25, NULL, NULL, '2021-01-29 11:05:12', 1),
(2422, 15, 139, 539, 18, NULL, NULL, '2021-01-29 11:05:12', 1),
(2423, 17, 139, 539, 19, NULL, NULL, '2021-01-29 11:05:12', 1),
(2424, 53, 139, 539, 20, NULL, NULL, '2021-01-29 11:05:12', 1),
(2425, 57, 139, 540, 26, NULL, NULL, '2021-01-29 11:05:12', 1),
(2426, 56, 139, 540, 27, NULL, NULL, '2021-01-29 11:05:12', 1),
(2427, 3, 139, 540, 28, NULL, NULL, '2021-01-29 11:05:12', 1),
(2428, 26, 139, 541, 9, NULL, NULL, '2021-01-29 11:05:12', 1),
(2429, 26, 139, 541, 10, NULL, NULL, '2021-01-29 11:05:12', 1),
(2430, 26, 139, 541, 11, NULL, NULL, '2021-01-29 11:05:12', 1),
(2431, 54, 139, 542, 33, NULL, NULL, '2021-01-29 11:05:12', 1),
(2432, 15, 139, 542, 34, NULL, NULL, '2021-01-29 11:05:12', 1),
(2433, 55, 139, 542, 35, NULL, NULL, '2021-01-29 11:05:12', 1),
(2434, 59, 139, 542, 36, NULL, NULL, '2021-01-29 11:05:12', 1),
(2435, 54, 139, 543, 21, NULL, NULL, '2021-01-29 11:05:12', 1),
(2436, 15, 139, 543, 22, NULL, NULL, '2021-01-29 11:05:12', 1),
(2437, 55, 139, 543, 23, NULL, NULL, '2021-01-29 11:05:12', 1),
(2438, 59, 139, 543, 24, NULL, NULL, '2021-01-29 11:05:12', 1),
(2523, 37, 145, 562, 1, NULL, NULL, '2021-01-31 10:58:28', 1),
(2524, 48, 145, 562, 2, NULL, NULL, '2021-01-31 10:58:28', 1),
(2525, 46, 145, 562, 3, NULL, NULL, '2021-01-31 10:58:28', 1),
(2526, 47, 145, 562, 4, NULL, NULL, '2021-01-31 10:58:28', 1),
(2527, 42, 145, 562, 5, NULL, NULL, '2021-01-31 10:58:28', 1),
(2528, 45, 145, 562, 6, NULL, NULL, '2021-01-31 10:58:28', 1),
(2529, 49, 145, 562, 7, NULL, NULL, '2021-01-31 10:58:28', 1),
(2530, 50, 145, 562, 8, NULL, NULL, '2021-01-31 10:58:28', 1),
(2531, 45, 145, 562, 15, NULL, NULL, '2021-01-31 10:58:28', 1),
(2532, 51, 145, 562, 16, NULL, NULL, '2021-01-31 10:58:28', 1),
(2533, 45, 145, 562, 25, NULL, NULL, '2021-01-31 10:58:28', 1),
(2534, 15, 145, 563, 18, NULL, NULL, '2021-01-31 10:58:28', 1),
(2535, 17, 145, 563, 19, NULL, NULL, '2021-01-31 10:58:28', 1),
(2536, 53, 145, 563, 20, NULL, NULL, '2021-01-31 10:58:28', 1),
(2537, 57, 145, 564, 26, NULL, NULL, '2021-01-31 10:58:28', 1),
(2538, 56, 145, 564, 27, NULL, NULL, '2021-01-31 10:58:28', 1),
(2539, 3, 145, 564, 28, NULL, NULL, '2021-01-31 10:58:28', 1),
(2540, 26, 145, 565, 9, NULL, NULL, '2021-01-31 10:58:28', 1),
(2541, 26, 145, 565, 10, NULL, NULL, '2021-01-31 10:58:28', 1),
(2542, 26, 145, 565, 11, NULL, NULL, '2021-01-31 10:58:28', 1),
(2543, 54, 145, 566, 33, NULL, NULL, '2021-01-31 10:58:28', 1),
(2544, 15, 145, 566, 34, NULL, NULL, '2021-01-31 10:58:28', 1),
(2545, 55, 145, 566, 35, NULL, NULL, '2021-01-31 10:58:28', 1),
(2546, 59, 145, 566, 36, NULL, NULL, '2021-01-31 10:58:28', 1),
(2547, 54, 145, 567, 21, NULL, NULL, '2021-01-31 10:58:28', 1),
(2548, 15, 145, 567, 22, NULL, NULL, '2021-01-31 10:58:28', 1),
(2549, 55, 145, 567, 23, NULL, NULL, '2021-01-31 10:58:28', 1),
(2550, 59, 145, 567, 24, NULL, NULL, '2021-01-31 10:58:28', 1),
(2551, 37, 146, 568, 1, NULL, NULL, '2021-01-31 10:59:22', 1),
(2552, 48, 146, 568, 2, NULL, NULL, '2021-01-31 10:59:22', 1),
(2553, 46, 146, 568, 3, NULL, NULL, '2021-01-31 10:59:22', 1),
(2554, 47, 146, 568, 4, NULL, NULL, '2021-01-31 10:59:22', 1),
(2555, 42, 146, 568, 5, NULL, NULL, '2021-01-31 10:59:22', 1),
(2556, 45, 146, 568, 6, NULL, NULL, '2021-01-31 10:59:22', 1),
(2557, 49, 146, 568, 7, NULL, NULL, '2021-01-31 10:59:22', 1),
(2558, 50, 146, 568, 8, NULL, NULL, '2021-01-31 10:59:22', 1),
(2559, 45, 146, 568, 15, NULL, NULL, '2021-01-31 10:59:22', 1),
(2560, 51, 146, 568, 16, NULL, NULL, '2021-01-31 10:59:22', 1),
(2561, 45, 146, 568, 25, NULL, NULL, '2021-01-31 10:59:22', 1),
(2562, 15, 146, 569, 18, NULL, NULL, '2021-01-31 10:59:22', 1),
(2563, 17, 146, 569, 19, NULL, NULL, '2021-01-31 10:59:22', 1),
(2564, 53, 146, 569, 20, NULL, NULL, '2021-01-31 10:59:22', 1),
(2565, 57, 146, 570, 26, NULL, NULL, '2021-01-31 10:59:22', 1),
(2566, 56, 146, 570, 27, NULL, NULL, '2021-01-31 10:59:22', 1),
(2567, 3, 146, 570, 28, NULL, NULL, '2021-01-31 10:59:22', 1),
(2568, 26, 146, 571, 9, NULL, NULL, '2021-01-31 10:59:22', 1),
(2569, 26, 146, 571, 10, NULL, NULL, '2021-01-31 10:59:22', 1),
(2570, 26, 146, 571, 11, NULL, NULL, '2021-01-31 10:59:22', 1),
(2571, 54, 146, 572, 33, NULL, NULL, '2021-01-31 10:59:22', 1),
(2572, 15, 146, 572, 34, NULL, NULL, '2021-01-31 10:59:22', 1),
(2573, 55, 146, 572, 35, NULL, NULL, '2021-01-31 10:59:22', 1),
(2574, 59, 146, 572, 36, NULL, NULL, '2021-01-31 10:59:22', 1),
(2575, 54, 146, 573, 21, NULL, NULL, '2021-01-31 10:59:22', 1),
(2576, 15, 146, 573, 22, NULL, NULL, '2021-01-31 10:59:22', 1),
(2577, 55, 146, 573, 23, NULL, NULL, '2021-01-31 10:59:22', 1),
(2578, 59, 146, 573, 24, NULL, NULL, '2021-01-31 10:59:22', 1),
(2579, 37, 147, 574, 1, NULL, NULL, '2021-02-02 14:33:52', 1),
(2580, 48, 147, 574, 2, NULL, NULL, '2021-02-02 14:33:52', 1),
(2581, 46, 147, 574, 3, NULL, NULL, '2021-02-02 14:33:52', 1),
(2582, 47, 147, 574, 4, NULL, NULL, '2021-02-02 14:33:52', 1),
(2583, 42, 147, 574, 5, NULL, NULL, '2021-02-02 14:33:52', 1),
(2584, 45, 147, 574, 6, NULL, NULL, '2021-02-02 14:33:52', 1),
(2585, 49, 147, 574, 7, NULL, NULL, '2021-02-02 14:33:52', 1),
(2586, 50, 147, 574, 8, NULL, NULL, '2021-02-02 14:33:52', 1),
(2587, 45, 147, 574, 15, NULL, NULL, '2021-02-02 14:33:52', 1),
(2588, 51, 147, 574, 16, NULL, NULL, '2021-02-02 14:33:52', 1),
(2589, 45, 147, 574, 25, NULL, NULL, '2021-02-02 14:33:52', 1),
(2590, 15, 147, 575, 18, NULL, NULL, '2021-02-02 14:33:52', 1),
(2591, 17, 147, 575, 19, NULL, NULL, '2021-02-02 14:33:52', 1),
(2592, 53, 147, 575, 20, NULL, NULL, '2021-02-02 14:33:52', 1),
(2593, 57, 147, 576, 26, NULL, NULL, '2021-02-02 14:33:52', 1),
(2594, 56, 147, 576, 27, NULL, NULL, '2021-02-02 14:33:52', 1),
(2595, 3, 147, 576, 28, NULL, NULL, '2021-02-02 14:33:52', 1),
(2596, 26, 147, 577, 9, NULL, NULL, '2021-02-02 14:33:52', 1),
(2597, 26, 147, 577, 10, NULL, NULL, '2021-02-02 14:33:52', 1),
(2598, 26, 147, 577, 11, NULL, NULL, '2021-02-02 14:33:52', 1),
(2599, 54, 147, 578, 33, NULL, NULL, '2021-02-02 14:33:52', 1),
(2600, 15, 147, 578, 34, NULL, NULL, '2021-02-02 14:33:52', 1),
(2601, 55, 147, 578, 35, NULL, NULL, '2021-02-02 14:33:52', 1),
(2602, 59, 147, 578, 36, NULL, NULL, '2021-02-02 14:33:52', 1),
(2603, 54, 147, 579, 21, NULL, NULL, '2021-02-02 14:33:52', 1),
(2604, 15, 147, 579, 22, NULL, NULL, '2021-02-02 14:33:52', 1),
(2605, 55, 147, 579, 23, NULL, NULL, '2021-02-02 14:33:52', 1),
(2606, 59, 147, 579, 24, NULL, NULL, '2021-02-02 14:33:52', 1),
(2607, 37, 148, 580, 1, NULL, NULL, '2021-02-02 16:12:59', 1),
(2608, 48, 148, 580, 2, NULL, NULL, '2021-02-02 16:12:59', 1),
(2609, 46, 148, 580, 3, NULL, NULL, '2021-02-02 16:12:59', 1),
(2610, 47, 148, 580, 4, NULL, NULL, '2021-02-02 16:12:59', 1),
(2611, 42, 148, 580, 5, NULL, NULL, '2021-02-02 16:12:59', 1),
(2612, 45, 148, 580, 6, NULL, NULL, '2021-02-02 16:12:59', 1),
(2613, 49, 148, 580, 7, NULL, NULL, '2021-02-02 16:12:59', 1),
(2614, 50, 148, 580, 8, NULL, NULL, '2021-02-02 16:12:59', 1),
(2615, 45, 148, 580, 15, NULL, NULL, '2021-02-02 16:12:59', 1),
(2616, 51, 148, 580, 16, NULL, NULL, '2021-02-02 16:12:59', 1),
(2617, 45, 148, 580, 25, NULL, NULL, '2021-02-02 16:12:59', 1),
(2618, 15, 148, 581, 18, NULL, NULL, '2021-02-02 16:12:59', 1),
(2619, 17, 148, 581, 19, NULL, NULL, '2021-02-02 16:12:59', 1),
(2620, 53, 148, 581, 20, NULL, NULL, '2021-02-02 16:12:59', 1),
(2621, 57, 148, 582, 26, NULL, NULL, '2021-02-02 16:12:59', 1),
(2622, 56, 148, 582, 27, NULL, NULL, '2021-02-02 16:12:59', 1),
(2623, 3, 148, 582, 28, NULL, NULL, '2021-02-02 16:12:59', 1),
(2624, 26, 148, 583, 9, NULL, NULL, '2021-02-02 16:12:59', 1),
(2625, 26, 148, 583, 10, NULL, NULL, '2021-02-02 16:12:59', 1),
(2626, 26, 148, 583, 11, NULL, NULL, '2021-02-02 16:12:59', 1),
(2627, 54, 148, 584, 33, NULL, NULL, '2021-02-02 16:12:59', 1),
(2628, 15, 148, 584, 34, NULL, NULL, '2021-02-02 16:12:59', 1),
(2629, 55, 148, 584, 35, NULL, NULL, '2021-02-02 16:12:59', 1),
(2630, 59, 148, 584, 36, NULL, NULL, '2021-02-02 16:12:59', 1),
(2631, 54, 148, 585, 21, NULL, NULL, '2021-02-02 16:12:59', 1),
(2632, 15, 148, 585, 22, NULL, NULL, '2021-02-02 16:12:59', 1),
(2633, 55, 148, 585, 23, NULL, NULL, '2021-02-02 16:12:59', 1),
(2634, 59, 148, 585, 24, NULL, NULL, '2021-02-02 16:12:59', 1);

-- --------------------------------------------------------

--
-- Structure de la table `external_user`
--

CREATE TABLE `external_user` (
  `ext_id` int(11) NOT NULL,
  `user_usr_id` int(11) DEFAULT NULL,
  `client_cli_id` int(11) NOT NULL,
  `ext_firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ext_lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ext_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ext_positionName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ext_weight_value` double DEFAULT NULL,
  `ext_owner` tinyint(1) DEFAULT NULL,
  `ext_created_by` int(11) DEFAULT NULL,
  `ext_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ext_last_connected` datetime DEFAULT NULL,
  `ext_deleted` datetime DEFAULT NULL,
  `ext_synth` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `external_user`
--

INSERT INTO `external_user` (`ext_id`, `user_usr_id`, `client_cli_id`, `ext_firstname`, `ext_lastname`, `ext_email`, `ext_positionName`, `ext_weight_value`, `ext_owner`, `ext_created_by`, `ext_inserted`, `ext_last_connected`, `ext_deleted`, `ext_synth`) VALUES
(1, 1, 2, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, NULL, '2020-08-27 14:12:20', NULL, NULL, NULL),
(2, 2, 2, 'Guillaume', 'dBdG', 'gdbdg@yopmail.com', NULL, 100, NULL, NULL, '2020-08-27 14:12:20', NULL, NULL, NULL),
(3, 3, 2, 'Steve', 'Jobs', 'sjobs@yopmail.com', NULL, 100, NULL, NULL, '2020-08-27 14:12:20', NULL, NULL, NULL),
(14, 1, 8, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, NULL, '2020-08-31 19:22:34', NULL, NULL, NULL),
(15, 2, 8, 'Guillaume', 'dBdG', 'gdbdg@yopmail.com', NULL, 100, NULL, NULL, '2020-08-31 19:22:34', NULL, NULL, NULL),
(16, 3, 8, 'Steve', 'Jobs', 'sjobs@yopmail.com', NULL, 100, NULL, NULL, '2020-08-31 19:22:34', NULL, NULL, NULL),
(17, 53, 8, 'Pierre', 'Laroche', 'p.laroche@gmail.com', 'Chef', 100, NULL, NULL, '2020-08-31 19:22:34', NULL, NULL, NULL),
(18, 56, 9, 'Serpico', 'Creos', NULL, NULL, 0, 1, NULL, '2020-08-31 21:11:27', NULL, NULL, 1),
(19, 1, 11, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, NULL, '2020-08-31 21:42:02', NULL, NULL, NULL),
(20, 2, 11, 'Guillaume', 'dBdG', 'gdbdg@yopmail.com', NULL, 100, NULL, NULL, '2020-08-31 21:42:02', NULL, NULL, NULL),
(21, 3, 11, 'Steve', 'Jobs', 'sjobs@yopmail.com', NULL, 100, NULL, NULL, '2020-08-31 21:42:02', NULL, NULL, NULL),
(22, 53, 11, 'Pierre', 'Laroche', 'p.laroche@gmail.com', 'Chef', 100, NULL, NULL, '2020-08-31 21:42:02', NULL, NULL, NULL),
(24, 1, 13, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, NULL, '2020-08-31 22:24:40', NULL, NULL, NULL),
(25, 2, 13, 'Guillaume', 'dBdG', 'gdbdg@yopmail.com', NULL, 100, NULL, NULL, '2020-08-31 22:24:40', NULL, NULL, NULL),
(26, 3, 13, 'Steve', 'Jobs', 'sjobs@yopmail.com', NULL, 100, NULL, NULL, '2020-08-31 22:24:40', NULL, NULL, NULL),
(27, 53, 13, 'Pierre', 'Laroche', 'p.laroche@gmail.com', 'Chef', 100, NULL, NULL, '2020-08-31 22:24:40', NULL, NULL, NULL),
(28, 58, 12, 'Serpico', 'BGL BNP Paribas', NULL, NULL, 0, 1, NULL, '2020-08-31 22:24:40', NULL, NULL, 1),
(29, 1, 15, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, NULL, '2020-08-31 22:30:19', NULL, NULL, NULL),
(30, 2, 15, 'Guillaume', 'dBdG', 'gdbdg@yopmail.com', NULL, 100, NULL, NULL, '2020-08-31 22:30:19', NULL, NULL, NULL),
(31, 3, 15, 'Steve', 'Jobs', 'sjobs@yopmail.com', NULL, 100, NULL, NULL, '2020-08-31 22:30:19', NULL, NULL, NULL),
(32, 53, 15, 'Pierre', 'Laroche', 'p.laroche@gmail.com', 'Chef', 100, NULL, NULL, '2020-08-31 22:30:19', NULL, NULL, NULL),
(33, 59, 14, 'Serpico', 'Nvision', NULL, NULL, 0, 1, NULL, '2020-08-31 22:30:19', NULL, NULL, 1),
(35, 1, 18, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, NULL, '2020-09-02 12:38:23', NULL, NULL, NULL),
(36, 2, 18, 'Guillaume', 'dBdG', 'gdbdg@yopmail.com', NULL, 100, NULL, NULL, '2020-09-02 12:38:23', NULL, NULL, NULL),
(37, 3, 18, 'Steve', 'Jobs', 'sjobs@yopmail.com', NULL, 100, NULL, NULL, '2020-09-02 12:38:23', NULL, NULL, NULL),
(38, 53, 18, 'Pierre', 'Laroche', 'p.laroche@gmail.com', 'Chef', 100, NULL, NULL, '2020-09-02 12:38:23', NULL, NULL, NULL),
(39, 60, 17, 'Serpico', 'DuPont & Nemours', NULL, NULL, 0, 1, NULL, '2020-09-02 12:38:23', NULL, NULL, 1),
(40, 61, 17, 'Floriane', 'Moutet', NULL, 'Directrice Emboutissage', 100, NULL, NULL, '2020-09-02 12:39:42', NULL, NULL, NULL),
(41, 8, 19, 'Serpico', 'Welkin & Meraki', NULL, NULL, 0, 1, NULL, '2020-09-25 14:19:16', NULL, NULL, 1),
(42, 51, 19, 'Pierre', 'Legrand', NULL, 'Responsable Commercial', 100, NULL, NULL, '2020-09-25 14:43:19', NULL, NULL, 0),
(43, 1, 20, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, NULL, '2020-09-27 20:56:19', NULL, NULL, 0),
(44, 2, 20, 'Guillaume', 'dBdG', 'gdbdg@yopmail.com', NULL, 100, NULL, NULL, '2020-09-27 20:56:19', NULL, NULL, 0),
(45, 3, 20, 'Steve', 'Jobs', 'sjobs@yopmail.com', NULL, 100, NULL, NULL, '2020-09-27 20:56:19', NULL, NULL, 0),
(46, 53, 20, 'Pierre', 'Laroche', 'p.laroche@gmail.com', 'Chef', 100, NULL, NULL, '2020-09-27 20:56:19', NULL, NULL, 0),
(47, 65, 21, 'Serpico', 'Landifirm', NULL, NULL, 0, 1, NULL, '2020-09-27 20:56:19', NULL, NULL, 1),
(48, 71, 21, 'Mathias', 'Keune', NULL, NULL, 0, NULL, 1, '2020-09-27 20:56:19', NULL, NULL, 0),
(60, 1, 29, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, NULL, '2020-09-30 08:18:03', NULL, NULL, 0),
(61, 2, 29, 'Guillaume', 'dBdG', 'gdbdg@yopmail.com', NULL, 100, NULL, NULL, '2020-09-30 08:18:03', NULL, NULL, 0),
(62, 3, 29, 'Steve', 'Jobs', 'sjobs@yopmail.com', NULL, 100, NULL, NULL, '2020-09-30 08:18:03', NULL, NULL, 0),
(63, 53, 29, 'Pierre', 'Laroche', 'p.laroche@gmail.com', 'Chef', 100, NULL, NULL, '2020-09-30 08:18:03', NULL, NULL, 0),
(66, 1, 31, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, NULL, '2020-09-30 13:04:31', NULL, NULL, 0),
(67, 2, 31, 'Guillaume', 'dBdG', 'gdbdg@yopmail.com', NULL, 100, NULL, NULL, '2020-09-30 13:04:31', NULL, NULL, 0),
(68, 3, 31, 'Steve', 'Jobs', 'sjobs@yopmail.com', NULL, 100, NULL, NULL, '2020-09-30 13:04:31', NULL, NULL, 0),
(69, 53, 31, 'Pierre', 'Laroche', 'p.laroche@gmail.com', 'Chef', 100, NULL, NULL, '2020-09-30 13:04:31', NULL, NULL, 0),
(102, 1, 43, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, NULL, '2020-09-30 17:52:55', NULL, NULL, 0),
(103, 2, 43, 'Guillaume', 'dBdG', 'gdbdg@yopmail.com', NULL, 100, NULL, NULL, '2020-09-30 17:52:55', NULL, NULL, 0),
(104, 3, 43, 'Steve', 'Jobs', 'sjobs@yopmail.com', NULL, 100, NULL, NULL, '2020-09-30 17:52:55', NULL, NULL, 0),
(105, 53, 43, 'Pierre', 'Laroche', 'p.laroche@gmail.com', 'Chef', 100, NULL, NULL, '2020-09-30 17:52:55', NULL, NULL, 0),
(106, 90, 44, 'Serpico', 'Luxembourg City Incubator', NULL, NULL, 0, 1, NULL, '2020-09-30 17:52:55', NULL, NULL, 1),
(107, 91, 44, 'Marin', 'Guérin', NULL, NULL, 0, NULL, 1, '2020-09-30 17:52:55', NULL, NULL, 0),
(108, 1, 45, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, NULL, '2020-10-02 13:02:54', NULL, NULL, 0),
(109, 2, 45, 'Guillaume', 'dBdG', 'gdbdg@yopmail.com', NULL, 100, NULL, NULL, '2020-10-02 13:02:54', NULL, NULL, 0),
(110, 3, 45, 'Steve', 'Jobs', 'sjobs@yopmail.com', NULL, 100, NULL, NULL, '2020-10-02 13:02:54', NULL, NULL, 0),
(111, 53, 45, 'Pierre', 'Laroche', 'p.laroche@gmail.com', 'Chef', 100, NULL, NULL, '2020-10-02 13:02:54', NULL, NULL, 0),
(112, 92, 46, 'Serpico', 'Luxfactory', NULL, NULL, 0, 1, NULL, '2020-10-02 13:02:55', NULL, NULL, 1),
(113, 93, 46, 'Jerome', 'Grandidier', 'j.grandidier@yopmail.com', NULL, 0, NULL, 1, '2020-10-02 13:02:55', NULL, NULL, 0),
(121, 104, 51, 'Gilberto', 'Fernandez', 'g.fernandez@yopmail.com', NULL, 100, NULL, NULL, '2020-10-13 22:37:08', NULL, NULL, NULL),
(122, 105, 51, 'ZZ', 'SalonKee', NULL, NULL, 100, NULL, NULL, '2020-10-13 22:37:08', NULL, NULL, NULL),
(123, 108, 52, 'SalonKee', 'Vizz', NULL, NULL, 0, 1, NULL, '2020-10-13 22:37:08', NULL, NULL, 1),
(124, 109, 52, 'Mathias', 'Keune', 'moien.keune@yopmail.com', NULL, 0, NULL, 104, '2020-10-13 22:37:08', NULL, NULL, NULL),
(135, 118, 53, 'Michele', 'Gallo', 'm.gallo@yopmail.com', NULL, 100, NULL, NULL, '2020-10-23 15:33:53', NULL, NULL, NULL),
(136, 119, 53, 'ZZ', 'Ministère de l\'Economie', NULL, NULL, 100, NULL, NULL, '2020-10-23 15:33:53', NULL, NULL, NULL),
(137, 65, 54, 'Ministère de l\'Economie', 'Landifirm', NULL, NULL, 0, 1, NULL, '2020-10-23 15:33:53', NULL, NULL, 1),
(138, 125, 54, 'Christian', 'Gillot', 'c.gillot@yopmail.com', NULL, 0, NULL, 118, '2020-10-23 15:33:53', NULL, NULL, NULL),
(221, 144, 96, 'Velazquez Foundation', 'Tatcher Inc.', NULL, NULL, NULL, 1, NULL, '2020-10-27 14:19:14', NULL, NULL, 1),
(222, 145, 96, 'Margret', 'Tatcher', 'm.tatcher@yopmail.com', NULL, 100, NULL, 128, '2020-10-27 14:19:14', NULL, NULL, NULL),
(223, 128, 97, 'Rodrigo', 'Velazquez', 'r.velazquez@yopmail.com', NULL, 100, NULL, 128, '2020-10-27 15:02:10', NULL, NULL, NULL),
(224, 129, 97, 'Creos', 'Velazquez Foundation', NULL, NULL, NULL, NULL, 128, '2020-10-27 15:02:10', NULL, NULL, NULL),
(249, 56, 110, 'Velazquez Foundation', 'Creos', NULL, NULL, NULL, 1, NULL, '2020-10-27 15:47:35', NULL, NULL, 1),
(250, 147, 110, 'Donald', 'Joe', 'd.joe@yopmail.com', NULL, 100, NULL, 128, '2020-10-27 15:47:35', NULL, NULL, NULL),
(251, 144, 111, 'Metro Goldwin', 'Tatcher Inc.', NULL, NULL, NULL, NULL, 145, '2020-11-15 11:50:21', NULL, NULL, 1),
(252, 145, 111, 'Margret', 'Tatcher', 'm.tatcher@yopmail.com', NULL, 100, NULL, 145, '2020-11-15 11:50:21', NULL, NULL, NULL),
(253, 148, 112, 'Tatcher Inc.', 'Metro Goldwin', NULL, NULL, NULL, 1, NULL, '2020-11-15 11:50:21', NULL, NULL, 1),
(254, 149, 112, 'Sandra', 'Mouget', 's.mouget@yopmail.com', NULL, 100, NULL, 145, '2020-11-15 11:50:21', NULL, NULL, NULL),
(255, 128, 113, 'Rodrigo', 'Velazquez', 'gchatelain@dealdrive.lu', NULL, 100, NULL, 128, '2020-11-20 17:01:29', NULL, NULL, NULL),
(256, 129, 113, 'Camille Suteau', 'Velazquez Foundation', NULL, NULL, NULL, NULL, 128, '2020-11-20 17:01:29', NULL, NULL, 1),
(257, 150, 114, 'Velazquez Foundation', 'Camille Suteau', NULL, NULL, NULL, 1, NULL, '2020-11-20 17:01:29', NULL, NULL, 1),
(258, 151, 114, 'Camille', 'Suteau', 'c.suteau@yopmail.com', NULL, 100, NULL, 128, '2020-11-20 17:01:29', NULL, NULL, NULL),
(259, 128, 115, 'Rodrigo', 'Velazquez', 'gchatelain@dealdrive.lu', NULL, 100, NULL, 128, '2020-11-20 17:05:37', NULL, NULL, NULL),
(260, 129, 115, 'Fabrice Pincet', 'Velazquez Foundation', NULL, NULL, NULL, NULL, 128, '2020-11-20 17:05:37', NULL, NULL, 1),
(261, 152, 116, 'Velazquez Foundation', 'Fabrice Pincet', NULL, NULL, NULL, 1, NULL, '2020-11-20 17:05:37', NULL, NULL, 1),
(262, 153, 116, 'Fabrice', 'Pincet', 'f.pincet@yopmail.com', NULL, 100, NULL, 128, '2020-11-20 17:05:37', NULL, NULL, NULL),
(263, 161, 117, 'Lucien', 'Hermenon', 'l.hermenon@yopmail.com', NULL, 100, NULL, 161, '2020-11-30 18:50:54', NULL, NULL, NULL),
(264, 129, 118, 'Hermenon Foundation', 'Velazquez Foundation', NULL, NULL, NULL, 1, NULL, '2020-11-30 18:50:54', NULL, NULL, 1),
(265, 128, 118, 'Diego', 'Velazquez', 'gchatelain@dealdrive.lu', NULL, 100, NULL, 161, '2020-11-30 18:50:54', NULL, NULL, NULL),
(269, 199, 12, 'George', 'Faventyne', 'g.faventyne@gmail.com', NULL, 100, NULL, 1, '2020-12-05 14:07:47', NULL, NULL, NULL),
(270, 214, 17, 'Fabrice', 'Duplantier', 'f.duplantier@yopmail.com', NULL, 100, NULL, 1, '2020-12-10 14:21:42', NULL, NULL, NULL),
(271, 215, 17, 'Julie', 'Joyeuse', 'j.joyeuse@yopmail.com', NULL, 100, NULL, 1, '2020-12-10 14:38:31', NULL, NULL, NULL),
(272, 212, 119, 'Garnier & Co', 'Garnier & Co', NULL, NULL, NULL, NULL, 213, '2020-12-17 15:44:58', NULL, NULL, 1),
(273, 213, 119, 'Clément', 'Garnier', 'c.garnier@yopmail.com', NULL, 100, NULL, 213, '2020-12-17 15:44:58', NULL, NULL, NULL),
(274, 210, 120, 'Clément', 'Garnier', 'c.garnier@yopmail.com', NULL, 100, NULL, 210, '2021-01-09 23:26:26', NULL, NULL, NULL),
(275, 221, 121, 'Clément Garnier', 'Evernote', NULL, NULL, NULL, 1, NULL, '2021-01-09 23:26:26', NULL, NULL, 1),
(276, 1, 122, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, 1, '2021-01-12 10:02:51', NULL, NULL, NULL),
(277, 2, 122, 'Guillaume', 'dBdG', 'gdbdg@yopmail.com', NULL, 100, NULL, 1, '2021-01-12 10:02:51', NULL, NULL, NULL),
(278, 3, 122, 'Steve', 'Jobs', 'sjobs@yopmail.com', NULL, 100, NULL, 1, '2021-01-12 10:02:51', NULL, NULL, NULL),
(279, 53, 122, 'Pierre', 'Laroche', 'p.laroche@yopmail.com', 'Chef', 100, NULL, 1, '2021-01-12 10:02:51', NULL, NULL, NULL),
(280, 222, 123, 'Serpico', 'Robeco', NULL, NULL, NULL, 1, NULL, '2021-01-12 10:02:51', NULL, NULL, 1),
(281, 223, 123, 'Cristopher', 'Roscoffe', 'ch.rostoffe@yopmail.com', 'Barista', NULL, NULL, NULL, '2021-01-12 11:25:23', NULL, NULL, NULL),
(332, 1, 144, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, 1, '2021-01-13 10:50:01', NULL, NULL, NULL),
(333, 2, 144, 'Guillaume', 'dBdG', 'gdbdg@yopmail.com', NULL, 100, NULL, 1, '2021-01-13 10:50:01', NULL, NULL, NULL),
(334, 3, 144, 'Steve', 'Jobs', 'sjobs@yopmail.com', NULL, 100, NULL, 1, '2021-01-13 10:50:01', NULL, NULL, NULL),
(335, 53, 144, 'Pierre', 'Laroche', 'p.laroche@yopmail.com', 'Chef', 100, NULL, 1, '2021-01-13 10:50:01', NULL, NULL, NULL),
(336, 57, 145, 'Serpico', 'Ministère des Classes Moyennes', NULL, NULL, NULL, 1, NULL, '2021-01-13 10:50:01', NULL, NULL, 1),
(338, 210, 147, 'Clément', 'Garnier', 'c.garnier@yopmail.com', NULL, NULL, NULL, NULL, '2021-01-18 09:13:08', NULL, NULL, NULL),
(339, 1, 148, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, 1, '2021-01-18 09:13:08', NULL, NULL, NULL),
(340, 1, 119, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, 212, '2021-01-18 23:11:46', NULL, NULL, NULL),
(341, 212, 149, 'Serpico', 'Garnier & Co', NULL, NULL, NULL, 1, NULL, '2021-01-18 23:11:46', NULL, NULL, 1),
(343, 226, 12, 'Gertrude', 'Bernard', 'g.bernard@yopmail.com', NULL, NULL, NULL, NULL, '2021-01-19 11:46:15', NULL, NULL, NULL),
(351, 1, 157, 'Guillaume', 'Chatelain', 'gchatelain@yopmail.com', NULL, 100, NULL, 1, '2021-01-19 14:08:31', NULL, NULL, NULL),
(352, 229, 158, 'Serpico', 'Floyd Aviation', NULL, NULL, NULL, 1, NULL, '2021-01-19 14:08:31', NULL, NULL, 1),
(434, 223, 214, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-22 11:42:17', NULL, NULL, NULL),
(437, 58, 216, 'Robeco', 'BGL BNP Paribas', NULL, NULL, NULL, 1, NULL, '2021-01-22 13:48:58', NULL, NULL, 1),
(442, 199, 216, 'George', 'Faventyne', 'g.faventyne@gmail.com', NULL, NULL, NULL, NULL, '2021-01-22 15:54:23', NULL, NULL, NULL),
(446, 226, 216, 'Gertrud', 'Bernard', NULL, 'Cheffe', NULL, NULL, NULL, '2021-01-24 09:08:47', NULL, NULL, NULL),
(447, 222, 217, 'Federico Garcia', 'Robeco', NULL, NULL, NULL, NULL, 278, '2021-01-24 18:47:54', NULL, NULL, 1),
(448, 223, 217, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 278, '2021-01-24 18:47:54', NULL, NULL, NULL),
(449, 222, 217, 'Federico Garcia', 'Robeco', NULL, NULL, NULL, NULL, 278, '2021-01-24 18:48:22', NULL, NULL, 1),
(450, 223, 217, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 278, '2021-01-24 18:48:22', NULL, NULL, NULL),
(451, 222, 217, 'Federico Garcia', 'Robeco', NULL, NULL, NULL, NULL, 278, '2021-01-24 18:54:21', NULL, NULL, 1),
(452, 223, 217, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 278, '2021-01-24 18:54:21', NULL, NULL, NULL),
(453, 222, 217, 'Federico Garcia', 'Robeco', NULL, NULL, NULL, NULL, 278, '2021-01-24 18:54:39', NULL, NULL, 1),
(454, 223, 217, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 278, '2021-01-24 18:54:39', NULL, NULL, NULL),
(456, 222, 217, 'Federico Garcia', 'Robeco', NULL, NULL, NULL, NULL, 278, '2021-01-24 19:12:02', NULL, NULL, 1),
(457, 223, 217, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 278, '2021-01-24 19:12:02', NULL, NULL, NULL),
(458, 222, 217, 'Federico Garcia', 'Robeco', NULL, NULL, NULL, NULL, 278, '2021-01-24 19:12:11', NULL, NULL, 1),
(459, 223, 217, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 278, '2021-01-24 19:12:11', NULL, NULL, NULL),
(461, 222, 217, 'Federico Garcia', 'Robeco', NULL, NULL, NULL, NULL, 278, '2021-01-24 20:53:18', NULL, NULL, 1),
(462, 223, 217, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 278, '2021-01-24 20:53:18', NULL, NULL, NULL),
(463, 278, 220, 'Robeco', 'Federico Garcia', 'f.garcia@yopmail.com', NULL, NULL, NULL, NULL, '2021-01-24 20:53:18', NULL, NULL, 1),
(465, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 15:20:03', NULL, NULL, NULL),
(467, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 15:27:17', NULL, NULL, NULL),
(469, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 17:29:23', NULL, NULL, NULL),
(471, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 17:31:59', NULL, NULL, NULL),
(473, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 17:32:35', NULL, NULL, NULL),
(475, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 17:55:58', NULL, NULL, NULL),
(477, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 17:58:08', NULL, NULL, NULL),
(479, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 18:00:06', NULL, NULL, NULL),
(481, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 18:03:26', NULL, NULL, NULL),
(483, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 18:12:24', NULL, NULL, NULL),
(485, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 18:13:13', NULL, NULL, NULL),
(486, 300, 233, 'Francis', 'Lalanne', 'f.lalanne@yopmail.com', NULL, NULL, NULL, NULL, '2021-01-31 18:17:00', NULL, NULL, NULL),
(487, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 18:17:00', NULL, NULL, NULL),
(489, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 18:39:26', NULL, NULL, NULL),
(491, 223, 222, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-01-31 18:42:29', NULL, NULL, NULL),
(493, 223, 237, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-02-02 11:31:50', NULL, NULL, NULL),
(494, 109, 238, 'Mathias', 'Keune', 'moien.keune@yopmail.com', NULL, NULL, NULL, NULL, '2021-02-02 11:35:23', NULL, NULL, NULL),
(495, 223, 237, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-02-02 11:35:23', NULL, NULL, NULL),
(496, 222, 239, 'De la Cruz Co.', 'Robeco', NULL, NULL, NULL, NULL, 223, '2021-02-02 14:33:52', NULL, NULL, 1),
(497, 223, 239, 'Cristof', 'Rostoff', 'c.rostoff@yopmail.com', NULL, 100, NULL, 223, '2021-02-02 14:33:52', NULL, NULL, NULL),
(498, 303, 240, 'Robeco', 'De la Cruz Co.', NULL, NULL, NULL, 1, NULL, '2021-02-02 14:33:52', NULL, NULL, 1),
(499, 304, 240, 'David', 'Recibo', 'd.recibo@yopmail.com', NULL, NULL, NULL, NULL, '2021-02-02 14:40:45', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `generated_error`
--

CREATE TABLE `generated_error` (
  `err_id` int(11) NOT NULL,
  `err_usr_id` int(11) DEFAULT NULL,
  `err_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `err_req_uri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `err_referer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `err_locale` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `err_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `err_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `err_line` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `err_message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `err_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `err_created_by` int(11) DEFAULT NULL,
  `err_route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `err_solved` datetime DEFAULT CURRENT_TIMESTAMP,
  `err_feedback` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `generated_error`
--

INSERT INTO `generated_error` (`err_id`, `err_usr_id`, `err_method`, `err_req_uri`, `err_referer`, `err_locale`, `err_agent`, `err_file`, `err_line`, `err_message`, `err_inserted`, `err_created_by`, `err_route`, `err_solved`, `err_feedback`) VALUES
(1, 1, 'GET', '/myactivities', 'users', 'fr', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36', 'activities_dashboard.html.twig', '1687', 'Variable \"frr\" does not exist.', '2020-12-09 15:43:09', NULL, NULL, '2020-12-10 10:19:17', NULL),
(2, 1, 'GET', '/myactivities', 'users', 'fr', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36', 'errorNotification.html.twig', '35', 'Variable \"requestURI\" does not exist.', '2020-12-09 15:43:09', NULL, NULL, '2020-12-10 10:19:17', NULL),
(3, 1, 'GET', '/myactivities', 'users', 'fr', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36', 'activities_dashboard.html.twig', '1687', 'Variable \"frr\" does not exist.', '2020-12-09 15:43:47', NULL, NULL, '2020-12-10 10:19:17', NULL),
(4, 1, 'GET', '/myactivities', 'users', 'fr', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36', 'errorNotification.html.twig', '66', 'Variable \"company_name\" does not exist.', '2020-12-09 15:43:47', NULL, NULL, '2020-12-10 10:19:17', NULL),
(5, 1, 'GET', '/myactivities', 'users', 'fr', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36', 'activities_dashboard.html.twig', '1687', 'Variable \"frr\" does not exist.', '2020-12-09 15:45:07', NULL, NULL, '2020-12-10 10:19:17', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `generated_image`
--

CREATE TABLE `generated_image` (
  `gim_id` int(11) NOT NULL,
  `criterion_name_cna_id` int(11) DEFAULT NULL,
  `gim_all` int(11) DEFAULT NULL,
  `gim_type` int(11) DEFAULT NULL,
  `gim_tid` int(11) DEFAULT NULL,
  `gim_uid` int(11) DEFAULT NULL,
  `gim_aid` int(11) DEFAULT NULL,
  `gim_ov` tinyint(1) DEFAULT NULL,
  `gim_sid` int(11) DEFAULT NULL,
  `gim_cid` int(11) DEFAULT NULL,
  `gim_role` int(11) DEFAULT NULL,
  `gim_createdBy` int(11) DEFAULT NULL,
  `gim_val` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gim_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `grade`
--

CREATE TABLE `grade` (
  `grd_id` int(11) NOT NULL,
  `activity_user_team_tea_id` int(11) DEFAULT NULL,
  `activity_user_user_usr_id` int(11) NOT NULL,
  `activity_act_id` int(11) NOT NULL,
  `criterion_crt_id` int(11) NOT NULL,
  `stage_stg_id` int(11) NOT NULL,
  `grd_type` int(11) DEFAULT NULL,
  `grd_graded_usr_id` int(11) DEFAULT NULL,
  `grd_graded_tea_id` int(11) DEFAULT NULL,
  `grd_value` double DEFAULT NULL,
  `grd_comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grd_created_by` int(11) DEFAULT NULL,
  `grd_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `icon`
--

CREATE TABLE `icon` (
  `ico_id` int(11) NOT NULL,
  `ico_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ico_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ico_unicode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ico_created_by` int(11) DEFAULT NULL,
  `ico_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `icon`
--

INSERT INTO `icon` (`ico_id`, `ico_type`, `ico_name`, `ico_unicode`, `ico_created_by`, `ico_inserted`) VALUES
(1, 'fa', 'flag-checkered', 'f11e', NULL, '2019-03-17 18:07:11'),
(2, 'fa', 'gem', 'f3a5', NULL, '2019-03-17 18:07:11'),
(3, 'fa', 'hourglass-end', 'f253', NULL, '2019-03-17 18:07:11'),
(4, 'far', 'map', 'f279', NULL, '2019-03-17 18:07:11'),
(5, 'fa', 'bolt', 'f0e7', NULL, '2019-03-17 18:07:11'),
(6, 'fa', 'list-ol', 'f0cb', NULL, '2019-03-17 18:07:11'),
(7, 'fa', 'trophy', 'f091', NULL, '2019-03-17 18:07:11'),
(8, 'far', 'handshake', 'f2b5', NULL, '2019-03-17 18:07:11'),
(9, 'fa', 'search', 'f002', NULL, '2019-03-17 18:07:11'),
(10, 'fa', 'heart', 'f004', NULL, '2019-03-17 18:07:11'),
(11, 'far', 'smile', 'f118', NULL, '2019-03-17 18:07:11'),
(12, 'fa', 'users', 'f0c0', NULL, '2019-03-17 18:07:11'),
(13, 'fa', 'leaf', 'f06c', NULL, '2019-03-17 18:07:11'),
(14, 'fa', 'volume-up', 'f028', NULL, '2019-03-17 18:07:11'),
(15, 'far', 'clock', 'f017', NULL, '2019-03-17 18:07:11'),
(16, 'far', 'lightbulb', 'f0eb', NULL, '2019-03-17 18:07:11'),
(17, 'fa', 'rocket', 'f135', NULL, '2019-03-17 18:07:11'),
(18, 'fa', 'fire', 'f06d', NULL, '2019-08-15 18:13:15'),
(19, 'fa', 'money-bill', 'f0d6', NULL, '2019-08-15 18:13:15'),
(21, 'fa', 'music', 'f001', NULL, '2019-08-15 18:15:42'),
(22, 'fa', 'shield-alt', 'f132', NULL, '2019-08-15 18:15:42'),
(23, 'fa', 'graduation-cap', 'f19d', NULL, '2019-08-15 18:18:29'),
(24, 'fa', 'building', 'f1ad', NULL, '2019-08-15 18:18:29'),
(25, 'far', 'building', 'f0f7', NULL, '2019-08-15 18:20:36'),
(26, 'fa', 'dollar-sign', 'f155', NULL, '2019-08-15 18:20:36'),
(27, 'fa', 'laptop', 'f109', NULL, '2019-08-15 18:21:37'),
(28, 'fa', 'street-view', 'f21d', NULL, '2019-08-15 18:21:37'),
(29, 'fa', 'car', 'f1b9', NULL, '2019-08-15 18:22:50'),
(30, 'fa', 'wifi', 'f1eb', NULL, '2019-08-15 18:22:50'),
(32, 'fa', 'medkit', 'f0fa', NULL, '2019-08-15 18:24:02'),
(33, 'm', 'sd_storage', 'e1c2', NULL, '2019-08-15 18:26:06'),
(34, 'm', 'receipt', 'e8b0', NULL, '2019-08-15 18:26:06'),
(35, 'fa', 'gavel', 'f0e3', NULL, '2019-08-15 18:37:25'),
(36, 'fa', 'university', 'f19c', NULL, '2019-08-15 18:40:16'),
(37, 'm', 'money_off', 'e25c', NULL, '2019-08-15 18:40:16'),
(38, 'fa', 'cubes', 'f1b3', NULL, '2019-08-15 18:41:05'),
(39, 'fa', 'book', 'f02d', NULL, '2019-08-15 18:48:52'),
(40, 'fa', 'utensils', 'f0f5', NULL, '2019-08-15 18:48:52'),
(41, 'fa', 'shopping-cart', 'f07a', NULL, '2019-08-15 18:51:27'),
(42, 'fa', 'file-audio', 'f1c7', NULL, '2020-10-14 16:42:18'),
(43, 'fa', 'file', 'f15b', NULL, '2020-10-14 16:42:18'),
(44, 'fa', 'file-video', 'f1c8', NULL, '2020-10-14 16:42:18'),
(45, 'fa', 'file-alt', 'f15c', NULL, '2020-10-14 16:42:18'),
(46, 'fa', 'file-contract', 'f56c', NULL, '2020-10-14 16:42:18'),
(47, 'fa', 'file-excel', 'f1c3', NULL, '2020-10-14 16:42:18'),
(48, 'fa', 'file-powerpoint', 'f1c4', NULL, '2020-10-14 16:44:16'),
(49, 'fa', 'file-invoice-dollar', 'f571', NULL, '2020-10-14 16:48:58'),
(50, 'fa', 'truck', 'f0d1', NULL, '2020-10-14 16:50:15'),
(51, 'fab', 'linkedin', 'f08c', NULL, '2020-10-14 16:54:26'),
(52, 'm', 'access_time', NULL, NULL, '2020-10-14 16:56:37'),
(53, 'fa', 'layer-group', 'f5fd', NULL, '2020-10-14 17:00:37'),
(54, 'fa', 'comment-dots', 'f4ad', NULL, '2020-10-14 17:01:39'),
(55, 'fa', 'undo', 'f0e2', NULL, '2020-10-14 17:05:39'),
(56, 'fa', 'search-dollar', 'f688', NULL, '2020-10-14 17:08:26'),
(57, 'fa', 'bahai', 'f666', NULL, '2020-10-14 17:11:02'),
(58, 'fa', 'book', 'f02d', NULL, '2020-10-14 17:12:12'),
(59, 'fa', 'info-circle', 'f05a', NULL, '2020-10-14 17:17:49'),
(60, 'fa', 'info', 'f129', NULL, '2020-10-14 17:19:00');

-- --------------------------------------------------------

--
-- Structure de la table `institution_process`
--

CREATE TABLE `institution_process` (
  `inp_id` int(11) NOT NULL,
  `organization_org_id` int(11) NOT NULL,
  `process_pro_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `inp_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inp_approvable` tinyint(1) DEFAULT NULL,
  `inp_gradable` tinyint(1) DEFAULT NULL,
  `inp_created_by` int(11) DEFAULT NULL,
  `inp_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `inp_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `institution_process`
--

INSERT INTO `institution_process` (`inp_id`, `organization_org_id`, `process_pro_id`, `parent_id`, `inp_name`, `inp_approvable`, `inp_gradable`, `inp_created_by`, `inp_inserted`, `inp_deleted`) VALUES
(1, 1, 1, NULL, 'Validation', 0, 1, NULL, '2020-09-10 23:21:17', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `iprocess_criterion`
--

CREATE TABLE `iprocess_criterion` (
  `crt_id` int(11) NOT NULL,
  `iprocess_stage_stg_id` int(11) DEFAULT NULL,
  `iprocess_inp_id` int(11) DEFAULT NULL,
  `criterion_name_cna_id` int(11) DEFAULT NULL,
  `crt_type` int(11) DEFAULT NULL,
  `crt_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crt_weight` double DEFAULT NULL,
  `crt_forceComment_compare` tinyint(1) DEFAULT NULL,
  `crt_forceComment_value` double DEFAULT NULL,
  `crt_forceComment_sign` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crt_lowerBound` double DEFAULT NULL,
  `crt_upperbound` double DEFAULT NULL,
  `crt_step` double DEFAULT NULL,
  `crt_comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crt_created_by` int(11) DEFAULT NULL,
  `crt_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `iprocess_participation`
--

CREATE TABLE `iprocess_participation` (
  `par_id` int(11) NOT NULL,
  `team_tea_id` int(11) DEFAULT NULL,
  `iprocess_inp_id` int(11) DEFAULT NULL,
  `iprocess_stage_stg_id` int(11) DEFAULT NULL,
  `iprocess_criterion_crt_id` int(11) DEFAULT NULL,
  `user_usr_id` int(11) DEFAULT NULL,
  `par_status` int(11) DEFAULT NULL,
  `par_leader` tinyint(1) DEFAULT NULL,
  `par_type` int(11) DEFAULT NULL,
  `par_mWeight` double DEFAULT NULL,
  `par_precomment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `par_created_by` int(11) DEFAULT NULL,
  `par_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `par_deleted` datetime DEFAULT NULL,
  `external_useR_ext_usr_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `iprocess_stage`
--

CREATE TABLE `iprocess_stage` (
  `stg_id` int(11) NOT NULL,
  `iprocess_inp_id` int(11) DEFAULT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `stg_master_user_id` int(11) DEFAULT NULL,
  `stg_complete` tinyint(1) DEFAULT NULL,
  `stg_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_mod` int(11) DEFAULT NULL,
  `stg_visibility` int(11) DEFAULT NULL,
  `stg_status` double DEFAULT NULL,
  `stg_desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_progress` double DEFAULT NULL,
  `stg_weight` double DEFAULT NULL,
  `stg_dperiod` int(11) DEFAULT NULL,
  `stg_dfrequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_dorigin` int(11) DEFAULT NULL,
  `stg_ffrequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_forigin` int(11) DEFAULT NULL,
  `stg_definite_dates` tinyint(1) DEFAULT NULL,
  `stg_startdate` datetime DEFAULT NULL,
  `stg_enddate` datetime DEFAULT NULL,
  `stg_deadline_nbDays` int(11) DEFAULT NULL,
  `stg_deadline_mailSent` tinyint(1) DEFAULT NULL,
  `stg_created_by` int(11) DEFAULT NULL,
  `stg_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stg_isFinalized` tinyint(1) DEFAULT NULL,
  `stg_finalized` datetime DEFAULT NULL,
  `stg_deleted` datetime DEFAULT NULL,
  `stg_gcompleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mail`
--

CREATE TABLE `mail` (
  `mail_id` int(11) NOT NULL,
  `user_usr_id` int(11) DEFAULT NULL,
  `worker_individual_win_id` int(11) DEFAULT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `worker_firm_wfi_id` int(11) DEFAULT NULL,
  `activity_act_id` int(11) DEFAULT NULL,
  `stage_stg_id` int(11) DEFAULT NULL,
  `mail_persona` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_read` datetime DEFAULT NULL,
  `mail_createdBy` int(11) DEFAULT NULL,
  `mail_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mail_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_language` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `mail`
--

INSERT INTO `mail` (`mail_id`, `user_usr_id`, `worker_individual_win_id`, `organization_org_id`, `worker_firm_wfi_id`, `activity_act_id`, `stage_stg_id`, `mail_persona`, `mail_token`, `mail_read`, `mail_createdBy`, `mail_inserted`, `mail_type`, `mail_language`) VALUES
(13, 51, NULL, 13, NULL, NULL, NULL, NULL, 'f11e3c0b9c19441281f2311addab9516', NULL, NULL, '2020-08-27 17:34:40', 'externalInvitation', 'fr'),
(15, 51, NULL, 13, NULL, NULL, NULL, NULL, 'd304ff505520b17bc42c96a28c42e04f', NULL, NULL, '2020-08-27 19:02:34', 'passwordModify', 'fr'),
(16, 51, NULL, 13, NULL, NULL, NULL, NULL, 'aaa1a2fc056b8b019a24d9ed370aa622', NULL, NULL, '2020-08-27 19:09:32', 'passwordModify', 'fr'),
(17, 51, NULL, 13, NULL, NULL, NULL, NULL, 'd067c23e28a0d0dcb79b0c224da746c2', NULL, NULL, '2020-08-27 19:10:52', 'passwordModify', 'fr'),
(18, 51, NULL, 13, NULL, NULL, NULL, NULL, '2977ed1463a44697cc7b8af53d5db150', NULL, NULL, '2020-08-27 19:13:35', 'passwordModify', 'fr'),
(19, 51, NULL, 13, NULL, NULL, NULL, NULL, '15abc7638aba906015a368d5f57ba99a', NULL, NULL, '2020-08-27 19:21:03', 'passwordModify', 'fr'),
(20, 51, NULL, 13, NULL, NULL, NULL, NULL, '0d42a439b20ba9ac1c62e943e573ea47', NULL, NULL, '2020-08-27 19:31:20', 'passwordModify', 'fr'),
(21, 53, NULL, 1, NULL, NULL, NULL, NULL, '45e38868bf067fcecf9cd4815a0ee677', NULL, NULL, '2020-08-31 11:16:01', 'registration', 'fr'),
(22, 1, NULL, 1, NULL, NULL, NULL, NULL, '1918387f745d2f34e428b59487485590', NULL, NULL, '2020-08-31 11:21:32', 'passwordModify', 'fr'),
(23, 1, NULL, 1, NULL, NULL, NULL, NULL, 'ad6b24122f1e295ed9fd24be8f06fc32', NULL, NULL, '2020-08-31 11:57:29', 'passwordModify', 'fr'),
(24, 61, NULL, 21, NULL, NULL, NULL, NULL, '49c0d93f611cb95ff4813aadac928998', NULL, NULL, '2020-09-02 12:39:43', 'externalInvitation', 'en'),
(25, 66, NULL, 25, NULL, NULL, NULL, NULL, 'a7a3f3b044c7af0481382c11b74360a1', NULL, NULL, '2020-09-03 18:53:56', 'registration', 'en'),
(27, 68, NULL, 13, NULL, NULL, NULL, NULL, '39400482e31b01c4ae6bb799de3bdef8', NULL, NULL, '2020-09-25 14:41:47', 'externalInvitation', 'en'),
(28, 93, NULL, 40, NULL, NULL, NULL, NULL, '2e61980f99732ed72cd0dd303cf23c16', NULL, NULL, '2020-10-02 13:02:55', 'externalInvitation', 'fr'),
(29, 1, NULL, 1, NULL, NULL, NULL, NULL, 'f2097afcfac222bdd0364c3be11f2e5e', NULL, NULL, '2020-10-07 13:11:42', 'launchingFollowupSubscription', 'en'),
(30, 1, NULL, 1, NULL, NULL, NULL, NULL, '20b87b38d1c795f355b6b2ef5fe8956a', NULL, NULL, '2020-10-07 13:25:00', 'launchingFollowupSubscription', 'en'),
(33, 1, NULL, 1, NULL, NULL, NULL, NULL, 'b409fdb0683eb871be3beaa42763586c', NULL, NULL, '2020-10-12 16:14:22', 'userSignupInfo', 'fr'),
(35, 1, NULL, 1, NULL, NULL, NULL, NULL, '6e1e4033635bf628e54368302cfcd064', NULL, NULL, '2020-10-12 16:35:09', 'userSignupInfo', 'fr'),
(37, 1, NULL, 1, NULL, NULL, NULL, NULL, 'e439821fb84b59da55f0e1d9eb9916f4', NULL, NULL, '2020-10-12 16:41:04', 'userSignupInfo', 'fr'),
(39, 1, NULL, 1, NULL, NULL, NULL, NULL, 'bc51facc668321d6feff628195846c3f', NULL, NULL, '2020-10-13 07:54:22', 'userSignupInfo', 'fr'),
(41, 1, NULL, 1, NULL, NULL, NULL, NULL, '317f126ecb7bcab954b925c06c2cf920', NULL, NULL, '2020-10-13 09:22:50', 'userSignupInfo', 'fr'),
(59, 1, NULL, 1, NULL, NULL, NULL, NULL, '23ae275755d90abe40f5f47bdd07b42d', NULL, NULL, '2020-10-13 21:21:08', 'userSignupInfo', 'fr'),
(60, 104, NULL, NULL, NULL, NULL, NULL, NULL, 'fae4e02199f8528c9bcc0edd83191cb0', NULL, NULL, '2020-10-13 21:21:08', 'subscriptionConfirmation', 'fr'),
(63, 109, NULL, 51, NULL, NULL, NULL, NULL, '860f00e63ce3270f5573ebe2418170a8', NULL, NULL, '2020-10-13 22:37:08', 'externalInvitation', 'fr'),
(66, 104, NULL, 49, NULL, 35, NULL, NULL, 'c0174e88294f116474bc38da383543ce', NULL, NULL, '2020-10-14 08:05:18', 'activityParticipation', 'fr'),
(67, 109, NULL, 51, NULL, 35, NULL, NULL, '2f5809d4c507643fa673a1e83b28ad66', NULL, NULL, '2020-10-14 08:05:20', 'activityParticipation', 'fr'),
(68, 1, NULL, 1, NULL, NULL, NULL, NULL, 'fb363bd3feafd2cbcafd244cec674683', NULL, NULL, '2020-10-14 13:10:13', 'userSignupInfo', 'fr'),
(71, 1, NULL, 1, NULL, NULL, NULL, NULL, '19940689859c25243a7bf082212ec66f', NULL, NULL, '2020-10-14 13:50:11', 'userSignupInfo', 'fr'),
(74, 1, NULL, 1, NULL, NULL, NULL, NULL, '7b1f155db6977cef5cc5df4ea1147841', NULL, NULL, '2020-10-14 13:56:22', 'userSignupInfo', 'fr'),
(77, 1, NULL, 1, NULL, NULL, NULL, NULL, 'e6f5f5edd491e8172d1790fa0fd16a50', NULL, NULL, '2020-10-14 15:23:19', 'userSignupInfo', 'fr'),
(80, 1, NULL, 1, NULL, NULL, NULL, NULL, 'c0c8b0e21d9035a029320a6f00f1b04e', NULL, NULL, '2020-10-14 15:42:56', 'userSignupInfo', 'fr'),
(81, 118, NULL, NULL, NULL, NULL, NULL, NULL, '6c34c7d24ddf72f1dea62486168a578b', NULL, NULL, '2020-10-14 15:42:56', 'subscriptionConfirmation', 'fr'),
(82, 118, NULL, 56, NULL, 40, NULL, NULL, '8abc2961871f7be3fa6ef8e67a6bf3da', NULL, NULL, '2020-10-14 15:45:29', 'activityParticipation', 'fr'),
(83, 118, NULL, 56, NULL, 41, NULL, NULL, 'f7a619ef8bf113f4fcceb04e3d91569c', NULL, NULL, '2020-10-16 14:59:38', 'activityParticipation', 'fr'),
(84, 118, NULL, 56, NULL, NULL, NULL, NULL, '0ed2fe53b276c1e8597d9440bfc0091a', NULL, NULL, '2020-10-16 15:04:46', 'eventNotification', 'fr'),
(85, 118, NULL, 56, NULL, NULL, NULL, NULL, 'a8bd600d6703433bbab5c7ecfe8b1f74', NULL, NULL, '2020-10-16 15:05:39', 'eventNotification', 'fr'),
(86, 118, NULL, 56, NULL, NULL, NULL, NULL, '19eed4ebffecd1a644bef8b743a3051a', NULL, NULL, '2020-10-16 15:06:33', 'eventNotification', 'fr'),
(87, 118, NULL, 56, NULL, NULL, NULL, NULL, '825558eed3b5b0cae4c60adaba006ec6', NULL, NULL, '2020-10-16 15:07:24', 'eventNotification', 'fr'),
(88, 118, NULL, 56, NULL, NULL, NULL, NULL, '09950ff65aec0dd7553f662438cb5da6', NULL, NULL, '2020-10-16 15:09:02', 'eventNotification', 'fr'),
(89, 118, NULL, 56, NULL, NULL, NULL, NULL, '2c602aa2274ae8aed72f60ff41f2bedb', NULL, NULL, '2020-10-16 15:09:28', 'eventNotification', 'fr'),
(90, 118, NULL, 56, NULL, NULL, NULL, NULL, '024b474b97425e15c3f7ee790b59af5a', NULL, NULL, '2020-10-16 15:09:53', 'eventNotification', 'fr'),
(91, 118, NULL, 56, NULL, NULL, NULL, NULL, '74ea131437cd5e3b317c0980e5dff029', NULL, NULL, '2020-10-16 15:10:32', 'eventNotification', 'fr'),
(92, 118, NULL, 56, NULL, NULL, NULL, NULL, '9a5251a245fb4c3fe01c6947e15339d0', NULL, NULL, '2020-10-16 15:13:50', 'eventNotification', 'fr'),
(93, 118, NULL, 56, NULL, NULL, NULL, NULL, '8d68b7801dcca0c873286eb7d4cef061', NULL, NULL, '2020-10-16 15:14:54', 'eventNotification', 'fr'),
(94, 118, NULL, 56, NULL, NULL, NULL, NULL, '5005b49b08dec6440a0e1eca56cd3b32', NULL, NULL, '2020-10-16 17:31:57', 'eventNotification', 'fr'),
(95, 118, NULL, 56, NULL, NULL, NULL, NULL, '1e559f100a48e0c18ba5e7794366be5c', NULL, NULL, '2020-10-17 14:45:45', 'eventNotification', 'fr'),
(96, 118, NULL, 56, NULL, NULL, NULL, NULL, '522d633e9f30259d0fccb8e1c02b73f5', NULL, NULL, '2020-10-17 18:55:02', 'eventNotification', 'fr'),
(97, 118, NULL, 56, NULL, NULL, NULL, NULL, 'c02d3f4ce3f7c14a36a62028342b1991', NULL, NULL, '2020-10-17 19:48:35', 'eventNotification', 'fr'),
(98, 118, NULL, 56, NULL, NULL, NULL, NULL, '25a4e0a9d75231290ce02d4364489668', NULL, NULL, '2020-10-17 19:55:45', 'eventNotification', 'fr'),
(99, 118, NULL, 56, NULL, NULL, NULL, NULL, 'c37782f30455e507b2453ec7ed66a2a5', NULL, NULL, '2020-10-17 20:02:56', 'eventNotification', 'fr'),
(100, 118, NULL, 56, NULL, NULL, NULL, NULL, '571b729121a9f15a3e1346ab893cc852', NULL, NULL, '2020-10-17 21:51:29', 'eventNotification', 'fr'),
(106, 118, NULL, 56, NULL, NULL, NULL, NULL, 'b0f3102b952794765d16d120ba0e72d6', NULL, NULL, '2020-10-18 00:05:03', 'eventNotification', 'fr'),
(107, 118, NULL, 56, NULL, NULL, NULL, NULL, '474c446b47badcaab37ddabfcdb334b1', NULL, NULL, '2020-10-18 13:48:31', 'eventNotification', 'fr'),
(108, 118, NULL, 56, NULL, NULL, NULL, NULL, 'ab8817014af7ffbc0465cbfa0c2108ec', NULL, NULL, '2020-10-19 09:59:17', 'eventNotification', 'fr'),
(109, 53, NULL, 1, NULL, 20, NULL, NULL, 'a079319d9bb614a7e9c286ce26b2fc50', NULL, NULL, '2020-10-22 09:09:23', 'activityParticipation', 'fr'),
(110, 53, NULL, 1, NULL, 20, NULL, NULL, '71c9e1a694cc33f0e47f3d2296f6cab5', NULL, NULL, '2020-10-22 09:24:08', 'activityParticipation', 'fr'),
(111, 53, NULL, 1, NULL, 20, NULL, NULL, 'f5614e1e357de4f880e5b57c9e2429b2', NULL, NULL, '2020-10-22 09:36:36', 'activityParticipation', 'fr'),
(112, 118, NULL, 56, NULL, NULL, NULL, NULL, '2a78641f95d78655f9380eb0a280dd97', NULL, NULL, '2020-10-23 10:10:16', 'eventNotification', 'fr'),
(113, 71, NULL, 25, NULL, NULL, NULL, NULL, 'a2e97e168e309b7ea5f92efc93102cab', NULL, NULL, '2020-10-23 12:12:19', 'externalInvitation', 'fr'),
(114, 71, NULL, 25, NULL, NULL, NULL, NULL, '83e4068cac21003d9eb2a8ef922ac8fd', NULL, NULL, '2020-10-23 12:13:31', 'externalInvitation', 'fr'),
(115, 71, NULL, 25, NULL, NULL, NULL, NULL, '82d64523601d6fc589eb8c032229b4b5', NULL, NULL, '2020-10-23 12:15:47', 'externalInvitation', 'fr'),
(116, 71, NULL, 25, NULL, NULL, NULL, NULL, '546e573ee7a32cc9962052a0a8bdf7e1', NULL, NULL, '2020-10-23 12:18:56', 'externalInvitation', 'fr'),
(120, 125, NULL, 25, NULL, NULL, NULL, NULL, 'c4c2c45eeee687c2d64f7f955e861e93', NULL, NULL, '2020-10-23 15:33:53', 'externalInvitation', 'en'),
(121, 125, NULL, 25, NULL, 41, NULL, NULL, '5f8d692a9a4bffd6e15c95e5c54493dd', NULL, NULL, '2020-10-24 18:52:56', 'activityParticipation', 'fr'),
(122, 125, NULL, 25, NULL, 41, NULL, NULL, 'd437875abf47eefbda220fe338ed4eb2', NULL, NULL, '2020-10-24 19:25:36', 'activityParticipation', 'fr'),
(123, 125, NULL, 25, NULL, 41, NULL, NULL, '29a47c049a8f41c4afc596d210ebf338', NULL, NULL, '2020-10-24 19:25:37', 'activityParticipation', 'fr'),
(124, 125, NULL, 25, NULL, 41, NULL, NULL, '65bc70dc75117ced70c4bfedbb073753', NULL, NULL, '2020-10-24 19:26:37', 'activityParticipation', 'fr'),
(125, 125, NULL, 25, NULL, 41, NULL, NULL, '7a466223cb59ad1aef69d027cde7547b', NULL, NULL, '2020-10-24 19:26:49', 'activityParticipation', 'fr'),
(126, 125, NULL, 25, NULL, 41, NULL, NULL, '1174c900df93fdda7da6c1a71072b1c0', NULL, NULL, '2020-10-24 19:27:07', 'activityParticipation', 'fr'),
(127, 125, NULL, 25, NULL, 41, NULL, NULL, '536350c212d12d4bd572c3e515391b05', NULL, NULL, '2020-10-24 19:32:25', 'activityParticipation', 'fr'),
(128, 125, NULL, 25, NULL, 41, NULL, NULL, '2a699c0948aa9a8bb93ecfc9d1187ce4', NULL, NULL, '2020-10-24 19:35:30', 'activityParticipation', 'fr'),
(129, 125, NULL, 25, NULL, 41, NULL, NULL, '967432538556c23d225667cbcaaf75c7', NULL, NULL, '2020-10-24 19:40:18', 'activityParticipation', 'fr'),
(130, 125, NULL, 25, NULL, 41, NULL, NULL, '89b4d02ab3e02bbb75593b4b9ff1bed7', NULL, NULL, '2020-10-24 19:42:36', 'activityParticipation', 'fr'),
(131, 118, NULL, 56, NULL, 41, NULL, NULL, '5b2139656e3b4c54f73434998edfa164', NULL, NULL, '2020-10-24 20:18:02', 'activityParticipation', 'fr'),
(132, 125, NULL, 25, NULL, 41, NULL, NULL, '2bd0e8547e5049ce85f5ac4864d4c9c1', NULL, NULL, '2020-10-24 20:43:47', 'activityParticipation', 'fr'),
(133, 125, NULL, 25, NULL, 41, NULL, NULL, '0ac6ccb0677a14d43b53f015fc5907c4', NULL, NULL, '2020-10-24 20:44:19', 'activityParticipation', 'fr'),
(134, 1, NULL, 1, NULL, NULL, NULL, NULL, '0423a418a63b2a1ddb212853dc4b0f41', NULL, NULL, '2020-10-26 15:01:57', 'userSignupInfo', 'fr'),
(135, 126, NULL, NULL, NULL, NULL, NULL, NULL, '655434dfbc8ccb6e4bbd4fb3cd8356c7', NULL, NULL, '2020-10-26 15:01:57', 'subscriptionConfirmation', 'fr'),
(136, 1, NULL, 1, NULL, NULL, NULL, NULL, '4fce88c10e9ac0247567be9ab87beeb2', NULL, NULL, '2020-10-26 15:59:52', 'userSignupInfo', 'fr'),
(137, 127, NULL, NULL, NULL, NULL, NULL, NULL, 'e4021b1b8c814a7bfeda2dc1dc0677cf', NULL, NULL, '2020-10-26 15:59:52', 'subscriptionConfirmation', 'fr'),
(138, 1, NULL, 1, NULL, NULL, NULL, NULL, '5cb71b565689b5e4586c4d6c59b5e7f8', NULL, NULL, '2020-10-26 23:17:28', 'passwordModify', 'fr'),
(139, 1, NULL, 1, NULL, NULL, NULL, NULL, '9350f6a20ca1e84696c74e84e8f0fe9d', NULL, NULL, '2020-10-26 23:17:40', 'passwordModify', 'fr'),
(140, 1, NULL, 1, NULL, NULL, NULL, NULL, 'e7d282445095087ab93406bf2e962d40', NULL, NULL, '2020-10-26 23:17:52', 'passwordModify', 'fr'),
(141, 1, NULL, 1, NULL, NULL, NULL, NULL, '349b37a61c94ea4921053c71c84ac028', NULL, NULL, '2020-10-26 23:23:44', 'passwordModify', 'fr'),
(142, 1, NULL, 1, NULL, NULL, NULL, NULL, 'b206634c0814572086819bd3085409ab', NULL, NULL, '2020-10-27 08:38:28', 'userSignupInfo', 'fr'),
(143, 128, NULL, NULL, NULL, NULL, NULL, NULL, '54cd6a592b0e6670ca91c6d2ca3051d1', NULL, NULL, '2020-10-27 08:38:28', 'subscriptionConfirmation', 'fr'),
(157, 128, NULL, 57, NULL, NULL, NULL, NULL, '778f4a7ca15eae551b9fc68d4b5a01a9', NULL, NULL, '2020-10-27 11:17:59', 'passwordModify', 'en'),
(158, 128, NULL, 57, NULL, NULL, NULL, NULL, 'd952b52ed2077edf65ae80ba5d6cc0b2', NULL, NULL, '2020-10-27 11:19:34', 'passwordModify', 'en'),
(159, 127, NULL, NULL, NULL, NULL, NULL, NULL, 'd22e1ab26894c368858d40403d2da31a', NULL, NULL, '2020-10-27 11:22:19', 'passwordModify', 'en'),
(160, 127, NULL, NULL, NULL, NULL, NULL, NULL, 'afbcff431cf92a09bc16c817adb92bfe', NULL, NULL, '2020-10-27 11:23:21', 'passwordModify', 'en'),
(161, 128, NULL, 57, NULL, NULL, NULL, NULL, 'cc52b75c983e607e9aa8bb694dc3b4c5', NULL, NULL, '2020-10-27 11:27:48', 'passwordModify', 'en'),
(162, 127, NULL, NULL, NULL, NULL, NULL, NULL, '5140c512ce91dc584511b6143cd98aa9', NULL, NULL, '2020-10-27 11:29:08', 'passwordModify', 'en'),
(163, 1, NULL, 1, NULL, NULL, NULL, NULL, '23ad96a623a84c9873765636c9e89c6c', NULL, NULL, '2020-10-27 11:32:05', 'userSignupInfo', 'fr'),
(166, 127, NULL, NULL, NULL, NULL, NULL, NULL, '3afb62a27a3581e7e34c3589c32b110a', NULL, NULL, '2020-10-27 11:35:49', 'passwordModify', 'fr'),
(167, 145, NULL, 64, NULL, NULL, NULL, NULL, '1a4ff68aeae1066eb082f76b87602393', NULL, NULL, '2020-10-27 11:44:11', 'externalInvitation', 'fr'),
(169, 145, NULL, 64, NULL, NULL, NULL, NULL, '18c30dc502c5330bad8dbc34a0f32b65', NULL, NULL, '2020-10-27 13:00:25', 'externalInvitation', 'fr'),
(170, 145, NULL, 64, NULL, NULL, NULL, NULL, 'ade599e91ec4dd8c4e006f377d2e4f55', NULL, NULL, '2020-10-27 13:00:50', 'externalInvitation', 'fr'),
(171, 145, NULL, 64, NULL, NULL, NULL, NULL, 'd48ba69ec1c828d133169374e0b4a44c', NULL, NULL, '2020-10-27 13:03:01', 'externalInvitation', 'fr'),
(172, 145, NULL, 64, NULL, NULL, NULL, NULL, '5a3bda848b01b62f7a7baad98147c71a', NULL, NULL, '2020-10-27 13:15:22', 'externalInvitation', 'fr'),
(173, 145, NULL, 64, NULL, NULL, NULL, NULL, '7aa0ea9dcc501d061f46443f4e89a7d5', NULL, NULL, '2020-10-27 13:48:57', 'externalInvitation', 'fr'),
(174, 145, NULL, 64, NULL, NULL, NULL, NULL, '92c179cd48cf6686a6945039207f5e52', NULL, NULL, '2020-10-27 13:51:10', 'externalInvitation', 'fr'),
(175, 145, NULL, 64, NULL, NULL, NULL, NULL, '48690e5afbc5868f646085d6777a8893', NULL, NULL, '2020-10-27 13:52:02', 'externalInvitation', 'fr'),
(176, 145, NULL, 64, NULL, NULL, NULL, NULL, '930ca4d98fb69ed87799082f6013faca', NULL, NULL, '2020-10-27 13:52:24', 'externalInvitation', 'fr'),
(177, 145, NULL, 64, NULL, NULL, NULL, NULL, '919634f05c119400f4729d162f074cec', NULL, NULL, '2020-10-27 13:53:02', 'externalInvitation', 'fr'),
(178, 145, NULL, 64, NULL, NULL, NULL, NULL, '2a3e35999933a9731ae8d06046f98da1', NULL, NULL, '2020-10-27 13:53:33', 'externalInvitation', 'fr'),
(179, 145, NULL, 64, NULL, NULL, NULL, NULL, 'c2412d133969f194320b75753ba3e155', NULL, NULL, '2020-10-27 14:10:29', 'externalInvitation', 'fr'),
(180, 145, NULL, 64, NULL, NULL, NULL, NULL, '2084ef097db99be9683aeeaaa413b285', NULL, NULL, '2020-10-27 14:11:33', 'externalInvitation', 'fr'),
(181, 145, NULL, 64, NULL, NULL, NULL, NULL, '719644d7c9323697ecb3f12e2af94705', NULL, NULL, '2020-10-27 14:19:14', 'externalInvitation', 'fr'),
(182, 128, NULL, 57, NULL, 47, NULL, NULL, 'da69796ad84616bebd4c18cc8f889ba2', NULL, NULL, '2020-10-27 14:19:45', 'activityParticipation', 'fr'),
(183, 145, NULL, 64, NULL, 47, NULL, NULL, 'bcb46802a33d7590059999f821348850', NULL, NULL, '2020-10-27 14:19:45', 'activityParticipation', 'fr'),
(184, 128, NULL, 57, NULL, NULL, NULL, NULL, '3009b7b438731b99613488d2372fe8bf', NULL, NULL, '2020-10-27 15:00:37', 'eventNotification', 'fr'),
(186, 147, NULL, 16, NULL, NULL, NULL, NULL, '0ea4ac3f6cd632fdfac904c436dfb6fb', NULL, NULL, '2020-10-27 15:11:43', 'externalInvitation', 'fr'),
(187, 147, NULL, 16, NULL, NULL, NULL, NULL, '0fea2ae79e38ff916033560efd929c46', NULL, NULL, '2020-10-27 15:37:58', 'externalInvitation', 'fr'),
(188, 147, NULL, 16, NULL, NULL, NULL, NULL, 'a94e6c7052330a9793d2fe64efcb4041', NULL, NULL, '2020-10-27 15:40:48', 'externalInvitation', 'fr'),
(189, 147, NULL, 16, NULL, NULL, NULL, NULL, '9fd8e2dd5a9edfa8bc3ee2b7520aabe9', NULL, NULL, '2020-10-27 15:44:07', 'externalInvitation', 'fr'),
(190, 147, NULL, 16, NULL, NULL, NULL, NULL, '61c7cdc241f17a7513f64f50289c6a0c', NULL, NULL, '2020-10-27 15:45:36', 'externalInvitation', 'fr'),
(191, 147, NULL, 16, NULL, NULL, NULL, NULL, '92bbbefaa614d0aff7fd6e6c10c5132f', NULL, NULL, '2020-10-27 15:47:35', 'externalInvitation', 'fr'),
(192, 118, NULL, 56, NULL, NULL, NULL, NULL, '64c92c9af5cc4c0c3899349aa95c7bac', NULL, NULL, '2020-10-28 13:58:15', 'passwordModify', 'fr'),
(193, 128, NULL, 57, NULL, NULL, NULL, NULL, '42f776ccad19d58b66097dc714b7b875', NULL, NULL, '2020-10-29 19:55:41', 'eventUpdate', 'fr'),
(194, 128, NULL, 57, NULL, NULL, NULL, NULL, '9e535425b4f0431169093b687006ae6a', NULL, NULL, '2020-10-29 20:02:43', 'eventUpdate', 'fr'),
(195, 128, NULL, 57, NULL, NULL, NULL, NULL, 'dbc9420c41b4a745bbc6fa1216fda980', NULL, NULL, '2020-10-29 20:12:42', 'eventUpdate', 'fr'),
(196, 128, NULL, 57, NULL, NULL, NULL, NULL, '7c1856fa3a8c821f77efb4c07dd343db', NULL, NULL, '2020-10-29 20:20:01', 'eventUpdate', 'fr'),
(197, 128, NULL, 57, NULL, NULL, NULL, NULL, 'b95328b958e46ae3c75ff82d3ed2321a', NULL, NULL, '2020-10-29 20:24:11', 'eventUpdate', 'fr'),
(198, 128, NULL, 57, NULL, NULL, NULL, NULL, '41a867ebb35568ab9181d44d42f479f9', NULL, NULL, '2020-10-29 20:27:03', 'eventUpdate', 'fr'),
(199, 128, NULL, 57, NULL, NULL, NULL, NULL, '75407e8a7dffafd0c7db90580bcc2ba5', NULL, NULL, '2020-10-29 20:29:32', 'eventUpdate', 'fr'),
(200, 128, NULL, 57, NULL, NULL, NULL, NULL, '53d35753d96f0bfa54dec98bce89ddea', NULL, NULL, '2020-10-29 20:48:53', 'eventUpdate', 'fr'),
(201, 128, NULL, 57, NULL, NULL, NULL, NULL, 'b9f993c70195ee71c6d07b675d890f6f', NULL, NULL, '2020-10-29 20:55:58', 'eventUpdate', 'fr'),
(202, 128, NULL, 57, NULL, NULL, NULL, NULL, 'db5d3870cdfe720191feb186119327cf', NULL, NULL, '2020-10-29 21:26:59', 'eventUpdate', 'fr'),
(203, 128, NULL, 57, NULL, NULL, NULL, NULL, '1d1f3d386bba0cc4d1961380323998f8', NULL, NULL, '2020-10-29 21:27:27', 'eventUpdate', 'fr'),
(204, 145, NULL, 64, NULL, NULL, NULL, NULL, '3dc3ab31b1d4e2866dd8235540481de3', NULL, NULL, '2020-10-30 08:58:17', 'eventUpdate', 'fr'),
(205, 128, NULL, 57, NULL, NULL, NULL, NULL, 'babd1487d857475e607ba220fde82b67', NULL, NULL, '2020-11-08 09:29:45', 'passwordModify', 'en'),
(206, 128, NULL, 57, NULL, NULL, NULL, NULL, '77a7eb3a7d35833e00bc74faaf2c4dc8', NULL, NULL, '2020-11-08 09:30:57', 'passwordModify', 'fr'),
(207, 128, NULL, 57, NULL, NULL, NULL, NULL, 'd97f435970683143ef52a398a187ec8b', NULL, NULL, '2020-11-11 14:08:40', 'eventNotification', 'fr'),
(208, 145, NULL, 64, NULL, NULL, NULL, NULL, '68f716353cafc6cd46b6165bb8b3f14b', NULL, NULL, '2020-11-11 14:15:46', 'eventNotification', 'fr'),
(209, 145, NULL, 64, NULL, NULL, NULL, NULL, '978fe8c6a4a338fa1794f3e99c35f974', NULL, NULL, '2020-11-11 14:23:11', 'eventNotification', 'fr'),
(210, 145, NULL, 64, NULL, NULL, NULL, NULL, 'a1aca7b296781def4e9b561b17a437b6', NULL, NULL, '2020-11-11 21:48:05', 'eventNotification', 'fr'),
(211, 145, NULL, 64, NULL, NULL, NULL, NULL, '788708c7ae3f0b4f4465630ddd999479', NULL, NULL, '2020-11-11 21:53:33', 'eventNotification', 'fr'),
(212, 145, NULL, 64, NULL, NULL, NULL, NULL, 'a90459efdde464212923442888069003', NULL, NULL, '2020-11-11 22:27:45', 'eventNotification', 'fr'),
(213, 145, NULL, 64, NULL, NULL, NULL, NULL, '43e4d43c9c0c5902349e2d058de96930', NULL, NULL, '2020-11-12 23:00:25', 'eventNotification', 'fr'),
(214, 145, NULL, 64, NULL, NULL, NULL, NULL, '6b0e654ba9f3d84db18e9fce2349cc0e', NULL, NULL, '2020-11-12 23:01:27', 'eventNotification', 'fr'),
(215, 145, NULL, 64, NULL, NULL, NULL, NULL, 'a93d9107b0bd05b7b602902448a2a2d7', NULL, NULL, '2020-11-12 23:02:30', 'eventNotification', 'fr'),
(216, 145, NULL, 64, NULL, NULL, NULL, NULL, '82356c2242ab53f395bf02c7933034f2', NULL, NULL, '2020-11-12 23:05:11', 'eventNotification', 'fr'),
(217, 128, NULL, 57, NULL, NULL, NULL, NULL, '70a81fb4b27a4801db5200adce9564b0', NULL, NULL, '2020-11-12 23:05:48', 'eventNotification', 'fr'),
(218, 128, NULL, 57, NULL, NULL, NULL, NULL, 'c35026f83decd47b90aa3ef6c444f0bd', NULL, NULL, '2020-11-12 23:11:22', 'eventNotification', 'fr'),
(219, 128, NULL, 57, NULL, NULL, NULL, NULL, 'e5095bcd240af3ce99a6e9e6ed816ffe', NULL, NULL, '2020-11-12 23:13:26', 'eventNotification', 'fr'),
(220, 128, NULL, 57, NULL, NULL, NULL, NULL, '9702c695b1c1c6a9ea79530cdb8c55ae', NULL, NULL, '2020-11-12 23:14:33', 'eventNotification', 'fr'),
(221, 128, NULL, 57, NULL, NULL, NULL, NULL, '3c726532b343c2624309f8291b91cf4d', NULL, NULL, '2020-11-12 23:17:11', 'eventNotification', 'fr'),
(222, 145, NULL, 64, NULL, NULL, NULL, NULL, '3fd97095b1e5e8095e18261a5a238f39', NULL, NULL, '2020-11-12 23:20:43', 'eventNotification', 'fr'),
(223, 128, NULL, 57, NULL, NULL, NULL, NULL, '38c64057f245cda2f6ebc79de49e1815', NULL, NULL, '2020-11-12 23:27:29', 'eventNotification', 'fr'),
(224, 128, NULL, 57, NULL, NULL, NULL, NULL, '5c552dd19305a21a55d4308a7904992e', NULL, NULL, '2020-11-12 23:28:40', 'eventNotification', 'fr'),
(225, 128, NULL, 57, NULL, NULL, NULL, NULL, '4d5ce03b2e9cd51eaf5c9dc50e86d1c6', NULL, NULL, '2020-11-12 23:32:54', 'eventNotification', 'fr'),
(226, 128, NULL, 57, NULL, NULL, NULL, NULL, '3291d5425b306e32709869c89124b303', NULL, NULL, '2020-11-12 23:34:52', 'eventNotification', 'fr'),
(227, 128, NULL, 57, NULL, NULL, NULL, NULL, '494783ea278be39b255bd47dfdc053c4', NULL, NULL, '2020-11-13 10:06:26', 'eventNotification', 'fr'),
(228, 128, NULL, 57, NULL, NULL, NULL, NULL, 'a0ee862f22963780750f051e47685139', NULL, NULL, '2020-11-13 13:24:59', 'eventNotification', 'fr'),
(229, 128, NULL, 57, NULL, NULL, NULL, NULL, '6c44fdde903c2b2549345334a42f7879', NULL, NULL, '2020-11-13 13:27:44', 'eventNotification', 'fr'),
(230, 128, NULL, 57, NULL, NULL, NULL, NULL, '8094f4fe88a29ae996d798b4c1a3e682', NULL, NULL, '2020-11-13 13:29:49', 'eventNotification', 'fr'),
(231, 128, NULL, 57, NULL, NULL, NULL, NULL, '708c8c9806e8c670ffc41a2a0f5c45f3', NULL, NULL, '2020-11-13 13:31:45', 'eventNotification', 'fr'),
(232, 128, NULL, 57, NULL, NULL, NULL, NULL, 'bb81386cc8a2411de3880a2c0785efd6', NULL, NULL, '2020-11-13 13:32:25', 'eventNotification', 'fr'),
(233, 128, NULL, 57, NULL, NULL, NULL, NULL, '52b354b7d1a6e5c38a682f87e9254041', NULL, NULL, '2020-11-13 13:34:34', 'eventNotification', 'fr'),
(234, 128, NULL, 57, NULL, NULL, NULL, NULL, '9bddf5471e7ca00317dd5ace8df816d3', NULL, NULL, '2020-11-13 14:33:45', 'eventNotification', 'fr'),
(235, 128, NULL, 57, NULL, NULL, NULL, NULL, 'a2ba32ab3daf2fdb2b4ab5eb43a251be', NULL, NULL, '2020-11-13 14:34:41', 'eventNotification', 'fr'),
(236, 128, NULL, 57, NULL, NULL, NULL, NULL, 'c00b58c66f1792d539ac294da628e830', NULL, NULL, '2020-11-13 14:35:28', 'eventNotification', 'fr'),
(237, 128, NULL, 57, NULL, NULL, NULL, NULL, 'ddf5710b210a37d7697a69bf5e9c056f', NULL, NULL, '2020-11-13 14:36:22', 'eventNotification', 'fr'),
(238, 128, NULL, 57, NULL, NULL, NULL, NULL, 'b31a68f534082a7df154500429efb97e', NULL, NULL, '2020-11-13 14:36:56', 'eventNotification', 'fr'),
(239, 128, NULL, 57, NULL, NULL, NULL, NULL, '869a7f0fcf9fe951358b93d9c27c63f6', NULL, NULL, '2020-11-13 14:37:22', 'eventNotification', 'fr'),
(240, 128, NULL, 57, NULL, NULL, NULL, NULL, 'd4393ec3ed2696d12c635b329daf6a01', NULL, NULL, '2020-11-13 14:39:16', 'eventNotification', 'fr'),
(241, 128, NULL, 57, NULL, NULL, NULL, NULL, '6530f0b0e14680244d46bddf5a133471', NULL, NULL, '2020-11-13 14:40:52', 'eventNotification', 'fr'),
(242, 128, NULL, 57, NULL, NULL, NULL, NULL, '2ad3afd1c2cd45256e5a4c10fdb500cf', NULL, NULL, '2020-11-13 15:06:19', 'eventNotification', 'fr'),
(243, 128, NULL, 57, NULL, NULL, NULL, NULL, '6b13f055a46ea5f709a9334aa1cb7bf6', NULL, NULL, '2020-11-13 15:06:59', 'eventNotification', 'fr'),
(244, 128, NULL, 57, NULL, NULL, NULL, NULL, '6e8a29cfea10e08474dacda52bfd85ca', NULL, NULL, '2020-11-13 15:07:31', 'eventNotification', 'fr'),
(245, 128, NULL, 57, NULL, NULL, NULL, NULL, '614a51d8401a02970602368441651f20', NULL, NULL, '2020-11-13 15:07:54', 'eventNotification', 'fr'),
(246, 128, NULL, 57, NULL, NULL, NULL, NULL, '42a9713745743c152382ae82048beb10', NULL, NULL, '2020-11-13 15:08:16', 'eventNotification', 'fr'),
(247, 128, NULL, 57, NULL, NULL, NULL, NULL, 'fe80184f5bf26c8c14320ba8c9d6f807', NULL, NULL, '2020-11-13 15:12:11', 'eventNotification', 'fr'),
(248, 128, NULL, 57, NULL, NULL, NULL, NULL, '0b80ce61b4c5dc2f74afed05b63b564b', NULL, NULL, '2020-11-13 15:13:45', 'eventNotification', 'fr'),
(249, 128, NULL, 57, NULL, NULL, NULL, NULL, '183285551a8a959edce2c0af27041fe7', NULL, NULL, '2020-11-13 15:14:59', 'eventNotification', 'fr'),
(250, 128, NULL, 57, NULL, NULL, NULL, NULL, '92ea52238db0bee24a151a4e77115ebd', NULL, NULL, '2020-11-13 15:26:07', 'eventNotification', 'fr'),
(251, 128, NULL, 57, NULL, NULL, NULL, NULL, '1fad14de2dbcbdea8ae144ad6a7af5ef', NULL, NULL, '2020-11-13 15:26:40', 'eventNotification', 'fr'),
(252, 128, NULL, 57, NULL, NULL, NULL, NULL, '1a4a77d167fb8293a04e4998c017d53f', NULL, NULL, '2020-11-13 15:27:27', 'eventNotification', 'fr'),
(253, 128, NULL, 57, NULL, NULL, NULL, NULL, '722c0a0e2ac87a6d42964a6ab268b8f9', NULL, NULL, '2020-11-13 15:30:53', 'eventNotification', 'fr'),
(254, 128, NULL, 57, NULL, NULL, NULL, NULL, 'ac81617a48616c4b6adb6d509e6c9fa6', NULL, NULL, '2020-11-13 15:41:02', 'eventNotification', 'fr'),
(255, 128, NULL, 57, NULL, NULL, NULL, NULL, '9dc086caca94cde75ec9d6d57a97d3e1', NULL, NULL, '2020-11-13 15:41:05', 'eventNotification', 'fr'),
(256, 128, NULL, 57, NULL, NULL, NULL, NULL, 'd1dc920c447385467de744f33a791494', NULL, NULL, '2020-11-13 15:42:02', 'eventNotification', 'fr'),
(257, 128, NULL, 57, NULL, NULL, NULL, NULL, '74103a713943e4ad5f9565b1079db917', NULL, NULL, '2020-11-13 15:43:31', 'eventNotification', 'fr'),
(258, 128, NULL, 57, NULL, NULL, NULL, NULL, '6ff58e63a0cb4eb033fc93381bcd010f', NULL, NULL, '2020-11-13 15:53:59', 'eventNotification', 'fr'),
(259, 128, NULL, 57, NULL, NULL, NULL, NULL, '58c655136762410982e08f11ffc0e9fc', NULL, NULL, '2020-11-13 15:55:48', 'eventNotification', 'fr'),
(260, 128, NULL, 57, NULL, NULL, NULL, NULL, 'af923a6535e974430ac1899114a301e0', NULL, NULL, '2020-11-13 17:41:09', 'eventNotification', 'fr'),
(261, 128, NULL, 57, NULL, NULL, NULL, NULL, 'f49f24d126a457641947e6430dabad14', NULL, NULL, '2020-11-13 17:42:26', 'eventNotification', 'fr'),
(262, 128, NULL, 57, NULL, NULL, NULL, NULL, '991df014ce40e73e38c2c91defe5fa9f', NULL, NULL, '2020-11-13 17:46:25', 'eventNotification', 'fr'),
(263, 128, NULL, 57, NULL, NULL, NULL, NULL, '34b6f7944989c235e3c4c83711081c7c', NULL, NULL, '2020-11-13 17:58:58', 'eventNotification', 'fr'),
(264, 128, NULL, 57, NULL, NULL, NULL, NULL, '27cf299d5e88577366ca5801169581eb', NULL, NULL, '2020-11-13 18:03:05', 'eventNotification', 'fr'),
(265, 128, NULL, 57, NULL, NULL, NULL, NULL, '29b7bf4f6fafb39ca11e0f18f0f8fe3a', NULL, NULL, '2020-11-13 18:03:48', 'eventNotification', 'fr'),
(266, 128, NULL, 57, NULL, NULL, NULL, NULL, 'c9a0be467cfcf053aacfdc2977c66e47', NULL, NULL, '2020-11-13 18:05:24', 'eventNotification', 'fr'),
(267, 128, NULL, 57, NULL, NULL, NULL, NULL, '186f303b185c216b9728dafdf3b971b2', NULL, NULL, '2020-11-13 18:09:41', 'eventNotification', 'fr'),
(268, 128, NULL, 57, NULL, NULL, NULL, NULL, 'f2d51a758b3570e2579622a9967fbe20', NULL, NULL, '2020-11-13 18:15:01', 'eventNotification', 'fr'),
(269, 128, NULL, 57, NULL, NULL, NULL, NULL, '84728f4036f539cf3a1f25c5a8e563ad', NULL, NULL, '2020-11-13 18:20:47', 'eventNotification', 'fr'),
(270, 128, NULL, 57, NULL, NULL, NULL, NULL, '6ef0135e2bb60fe750acb7bcb506e6b8', NULL, NULL, '2020-11-13 18:22:42', 'eventNotification', 'fr'),
(271, 128, NULL, 57, NULL, NULL, NULL, NULL, '0cd565a1be30dbae21377512c4723a40', NULL, NULL, '2020-11-13 18:23:31', 'eventNotification', 'fr'),
(272, 128, NULL, 57, NULL, NULL, NULL, NULL, '91e32bc7407d811a08511134acdef713', NULL, NULL, '2020-11-13 18:25:01', 'eventNotification', 'fr'),
(273, 128, NULL, 57, NULL, NULL, NULL, NULL, '1763efb78a6f6a69ed39cace42e5b592', NULL, NULL, '2020-11-13 18:25:48', 'eventNotification', 'fr'),
(274, 128, NULL, 57, NULL, NULL, NULL, NULL, '855df5299a7577ac0835772354ef3fe8', NULL, NULL, '2020-11-13 18:28:24', 'eventNotification', 'fr'),
(275, 128, NULL, 57, NULL, NULL, NULL, NULL, '3a188f043205b9a005ff39d70f759df2', NULL, NULL, '2020-11-13 18:30:04', 'eventNotification', 'fr'),
(276, 128, NULL, 57, NULL, NULL, NULL, NULL, 'cf5df00dadcbb5ad496813909d4d6fc2', NULL, NULL, '2020-11-13 18:30:47', 'eventNotification', 'fr'),
(277, 128, NULL, 57, NULL, NULL, NULL, NULL, 'a897196d42ed14d23d2321c5110a0831', NULL, NULL, '2020-11-13 18:33:00', 'eventNotification', 'fr'),
(278, 128, NULL, 57, NULL, NULL, NULL, NULL, '47b5961822f08b3d2700f24351e29d17', NULL, NULL, '2020-11-13 18:40:43', 'eventNotification', 'fr'),
(279, 128, NULL, 57, NULL, NULL, NULL, NULL, '74c5e07f399cb91b1a46eb90bc063187', NULL, NULL, '2020-11-13 18:42:44', 'eventNotification', 'fr'),
(280, 128, NULL, 57, NULL, NULL, NULL, NULL, '4595d856ecdd855d25d86aa5b3ca0985', NULL, NULL, '2020-11-13 18:45:02', 'eventNotification', 'fr'),
(281, 128, NULL, 57, NULL, NULL, NULL, NULL, '23b13267ccd86ad6aff3c27ad0f6a533', NULL, NULL, '2020-11-13 18:47:08', 'eventNotification', 'fr'),
(282, 128, NULL, 57, NULL, NULL, NULL, NULL, '2256ad4b9a4a8815ef89e9f6347b31e0', NULL, NULL, '2020-11-13 18:52:30', 'eventNotification', 'fr'),
(283, 128, NULL, 57, NULL, NULL, NULL, NULL, 'b97de5a9a20cbdab7cb298a61abdf358', NULL, NULL, '2020-11-13 18:59:29', 'eventNotification', 'fr'),
(284, 128, NULL, 57, NULL, NULL, NULL, NULL, '6c5bd45ae006fe9ba55189e8a4252b3f', NULL, NULL, '2020-11-13 19:03:16', 'eventNotification', 'fr'),
(285, 128, NULL, 57, NULL, NULL, NULL, NULL, '16c767ab851dc5d290cb7246e31dbc92', NULL, NULL, '2020-11-13 19:04:28', 'eventNotification', 'fr'),
(286, 128, NULL, 57, NULL, NULL, NULL, NULL, '6c5f0fd0d2eb9817c840730fcc384d18', NULL, NULL, '2020-11-13 20:00:47', 'eventNotification', 'fr'),
(287, 128, NULL, 57, NULL, NULL, NULL, NULL, 'f29a3668c4c059eefc5dcc8bbf625932', NULL, NULL, '2020-11-13 20:02:10', 'eventNotification', 'fr'),
(288, 128, NULL, 57, NULL, NULL, NULL, NULL, '937146c55cf951bfabd124df406c26da', NULL, NULL, '2020-11-13 20:04:55', 'eventNotification', 'fr'),
(289, 128, NULL, 57, NULL, NULL, NULL, NULL, '31d6e9f224b8d3146b93cff48e0a5983', NULL, NULL, '2020-11-13 20:06:23', 'eventNotification', 'fr'),
(290, 128, NULL, 57, NULL, NULL, NULL, NULL, '49ac2d4c6bc4ac9c682d4bb27ac8dba2', NULL, NULL, '2020-11-13 20:08:23', 'eventNotification', 'fr'),
(291, 128, NULL, 57, NULL, NULL, NULL, NULL, 'f817d79153aba94d5b1e48c54684d830', NULL, NULL, '2020-11-13 20:09:24', 'eventNotification', 'fr'),
(292, 128, NULL, 57, NULL, NULL, NULL, NULL, 'd9a5c27f238060751f0bab966bf43350', NULL, NULL, '2020-11-13 20:11:27', 'eventNotification', 'fr'),
(293, 128, NULL, 57, NULL, NULL, NULL, NULL, 'bb011e0a3f44e5ecc0579eb0ccd5f43b', NULL, NULL, '2020-11-13 20:15:44', 'eventNotification', 'fr'),
(294, 149, NULL, 65, NULL, NULL, NULL, NULL, 'b20b1562a7196f3313fab8df0276a94b', NULL, NULL, '2020-11-15 11:50:21', 'externalInvitation', 'fr'),
(295, 1, NULL, 1, NULL, NULL, NULL, NULL, 'a92708316ca97a67931871e84c815f00', NULL, NULL, '2020-11-16 09:15:34', 'updateNotification', 'fr'),
(296, 1, NULL, 1, NULL, NULL, NULL, NULL, '51d237a855a7a8f6a7bbc6c9978189cb', NULL, NULL, '2020-11-17 09:49:38', 'updateNotification', 'fr'),
(297, 1, NULL, 1, NULL, NULL, NULL, NULL, 'd98dd7626a0f5f47f7d166650d481adf', NULL, NULL, '2020-11-17 09:50:12', 'updateNotification', 'fr'),
(298, 1, NULL, 1, NULL, NULL, NULL, NULL, '54349f86762d1299048ce8abd72826c3', NULL, NULL, '2020-11-17 09:51:45', 'updateNotification', 'fr'),
(299, 128, NULL, 57, NULL, NULL, NULL, NULL, '775b6b7519d7fd24dca03753ef8896fa', NULL, NULL, '2020-11-17 10:24:29', 'updateNotification', 'fr'),
(300, 128, NULL, 57, NULL, NULL, NULL, NULL, '5b38c10094c3fa0cbe5ef4325b786b59', NULL, NULL, '2020-11-17 10:27:33', 'updateNotification', 'fr'),
(301, 128, NULL, 57, NULL, NULL, NULL, NULL, 'f62554a408d4905343f28b7348be42ff', NULL, NULL, '2020-11-17 11:31:58', 'updateNotification', 'fr'),
(302, 128, NULL, 57, NULL, NULL, NULL, NULL, '9a9f4bf8351f67b8047ab66a08e83848', NULL, NULL, '2020-11-18 09:18:03', 'updateNotification', 'fr'),
(303, 128, NULL, 57, NULL, NULL, NULL, NULL, '01d54dec19bf4b586fe8cc63533a49db', NULL, NULL, '2020-11-18 10:23:19', 'updateNotification', 'fr'),
(304, 128, NULL, 57, NULL, NULL, NULL, NULL, 'e735e6d7990058022a785b9048795d98', NULL, NULL, '2020-11-18 16:46:18', 'updateNotification', 'fr'),
(305, 128, NULL, 57, NULL, NULL, NULL, NULL, 'bf97cc7d1f99d2379e55f53c19c2e945', NULL, NULL, '2020-11-18 16:47:51', 'updateNotification', 'fr'),
(306, 128, NULL, 57, NULL, NULL, NULL, NULL, '3b3c56d44a155808b59f16b11baec4b5', NULL, NULL, '2020-11-18 17:15:56', 'updateNotification', 'fr'),
(307, 128, NULL, 57, NULL, NULL, NULL, NULL, 'fe2ad3e923c1d7541e5b6957b923f393', NULL, NULL, '2020-11-18 17:21:10', 'updateNotification', 'fr'),
(308, 128, NULL, 57, NULL, NULL, NULL, NULL, '5ee131525b228acebc704941b4710d94', NULL, NULL, '2020-11-18 17:22:23', 'updateNotification', 'fr'),
(309, 128, NULL, 57, NULL, NULL, NULL, NULL, '5aac88d86b2d3930d824759f5ab9be50', NULL, NULL, '2020-11-18 17:34:43', 'updateNotification', 'fr'),
(310, 128, NULL, 57, NULL, NULL, NULL, NULL, '2e94de6608e9f801f26f4f2ade38f86c', NULL, NULL, '2020-11-18 17:38:08', 'updateNotification', 'fr'),
(311, 128, NULL, 57, NULL, NULL, NULL, NULL, '67b28aa87c55eefca82091fedeb5cd35', NULL, NULL, '2020-11-18 17:55:33', 'updateNotification', 'fr'),
(312, 128, NULL, 57, NULL, NULL, NULL, NULL, 'fd2c4c311fe6bb11bff703f7ccae438a', NULL, NULL, '2020-11-18 17:58:32', 'updateNotification', 'fr'),
(313, 128, NULL, 57, NULL, NULL, NULL, NULL, '127fab556832941f6d6c5f06f1757417', NULL, NULL, '2020-11-18 17:59:07', 'updateNotification', 'fr'),
(314, 128, NULL, 57, NULL, NULL, NULL, NULL, 'cf5c8d737865569138ca241d80cafdd5', NULL, NULL, '2020-11-18 18:17:35', 'updateNotification', 'fr'),
(315, 128, NULL, 57, NULL, NULL, NULL, NULL, '519430fe05e76e75a8a8b24c85549078', NULL, NULL, '2020-11-18 18:22:03', 'updateNotification', 'fr'),
(316, 128, NULL, 57, NULL, NULL, NULL, NULL, '979c099a1f0c05a949ed777d97f56876', NULL, NULL, '2020-11-18 22:37:41', 'updateNotification', 'fr'),
(317, 128, NULL, 57, NULL, NULL, NULL, NULL, 'f55bb2bce830331d997a2b248d8af4db', NULL, NULL, '2020-11-18 22:39:25', 'updateNotification', 'fr'),
(318, 128, NULL, 57, NULL, NULL, NULL, NULL, 'a811d890392830fb57a2bcc449bf2f4f', NULL, NULL, '2020-11-18 22:40:16', 'updateNotification', 'fr'),
(319, 128, NULL, 57, NULL, NULL, NULL, NULL, 'b3ab00f9b49d82d021ca7e8ead9cfe8c', NULL, NULL, '2020-11-18 22:40:39', 'updateNotification', 'fr'),
(320, 128, NULL, 57, NULL, NULL, NULL, NULL, '25190d3e3f1b6faf5683efeb5955de0a', NULL, NULL, '2020-11-18 22:42:08', 'updateNotification', 'fr'),
(321, 128, NULL, 57, NULL, NULL, NULL, NULL, '53745d63a23c53f175caf9d1c5d28925', NULL, NULL, '2020-11-18 22:43:18', 'updateNotification', 'fr'),
(322, 128, NULL, 57, NULL, NULL, NULL, NULL, '851079e4a30fc85e4ef9e5a00162bced', NULL, NULL, '2020-11-18 23:03:56', 'updateNotification', 'fr'),
(323, 128, NULL, 57, NULL, NULL, NULL, NULL, '7a86188f5877b7945fe2f916e3a04df9', NULL, NULL, '2020-11-18 23:04:59', 'updateNotification', 'fr'),
(324, 128, NULL, 57, NULL, NULL, NULL, NULL, '290f79fbb975fe0ceb9174c8b5e168d8', NULL, NULL, '2020-11-18 23:48:36', 'updateNotification', 'fr'),
(325, 145, NULL, 64, NULL, 138, NULL, NULL, '0443ff5c312ce105270f1440f02b7ed8', NULL, NULL, '2020-11-20 15:29:20', 'activityParticipation', 'fr'),
(326, 151, NULL, 66, NULL, NULL, NULL, NULL, '067039705f50aa806fcbf6bd142e3f05', NULL, NULL, '2020-11-20 17:01:29', 'externalInvitation', 'fr'),
(327, 153, NULL, 67, NULL, NULL, NULL, NULL, '9e68b2769018f086f567d7509a544e7d', NULL, NULL, '2020-11-20 17:05:37', 'externalInvitation', 'fr'),
(328, 145, NULL, 64, NULL, NULL, NULL, NULL, '87adf6f20b5092cc16545a3bbaf121f9', NULL, NULL, '2020-11-21 11:05:42', 'updateNotification', 'fr'),
(329, 128, NULL, 57, NULL, NULL, NULL, NULL, '9971561b30981583bc0ba68632e2ff80', NULL, NULL, '2020-11-23 10:03:18', 'passwordChangeConfirmation', 'en'),
(330, 128, NULL, 57, NULL, NULL, NULL, NULL, '036c0705cbb1da1b2e635e177348f78a', NULL, NULL, '2020-11-23 10:56:13', 'passwordChangeConfirmation', 'fr'),
(331, 128, NULL, 57, NULL, NULL, NULL, NULL, '8eca977ebd7aafc1e8ed3d423e610fd9', NULL, NULL, '2020-11-23 11:20:57', 'passwordChangeConfirmation', 'fr'),
(332, 128, NULL, 57, NULL, NULL, NULL, NULL, '57a4f262a52d8e46663aa8cebeb7b19a', NULL, NULL, '2020-11-23 15:25:03', 'emailModify', 'fr'),
(333, 128, NULL, 57, NULL, NULL, NULL, NULL, 'bd42f016efa078c255e1c00fc98ce7c4', NULL, NULL, '2020-11-23 15:25:35', 'emailModify', 'fr'),
(334, 128, NULL, 57, NULL, NULL, NULL, NULL, '12dfa9bdb296680079d1115b7c78aa9d', NULL, NULL, '2020-11-23 15:41:18', 'emailModify', 'fr'),
(335, 128, NULL, 57, NULL, NULL, NULL, NULL, 'e63ee660f4af2880d28383f96f10a6cf', NULL, NULL, '2020-11-23 15:46:38', 'emailModify', 'fr'),
(336, 128, NULL, 57, NULL, NULL, NULL, NULL, '2778eecca54a6254ba6ea9a69e072f93', NULL, NULL, '2020-11-23 15:55:59', 'emailModify', 'fr'),
(337, 128, NULL, 57, NULL, NULL, NULL, NULL, 'a45a56530101a5dac9bbeb31e21d3213', NULL, NULL, '2020-11-23 16:02:29', 'emailModify', 'fr'),
(338, 128, NULL, 57, NULL, NULL, NULL, NULL, 'c6af3b5c6fd852e1b83d192ca7c4fc9b', NULL, NULL, '2020-11-23 16:37:57', 'emailModify', 'fr'),
(339, 145, NULL, 64, NULL, NULL, NULL, NULL, '159c9b5de2065d43a3c1728b688bb818', NULL, NULL, '2020-11-23 19:26:11', 'updateNotification', 'fr'),
(340, 128, NULL, 57, NULL, NULL, NULL, NULL, '9e578174d854f0daa91cf1f007c2b83e', NULL, NULL, '2020-11-23 22:15:49', 'emailModify', 'fr'),
(341, 1, NULL, 1, NULL, NULL, NULL, NULL, '0999b04d69a9959c9089af31f4962e20', NULL, NULL, '2020-11-24 14:38:30', 'userSignupInfo', 'fr'),
(342, 154, NULL, NULL, NULL, NULL, NULL, NULL, '177bc143cde474894d772bb0d3bcd7d0', NULL, NULL, '2020-11-24 14:38:30', 'subscriptionConfirmation', 'fr'),
(343, 145, NULL, 64, NULL, NULL, NULL, NULL, '34a119ee6bb571996c4e2aa3c598dc22', NULL, NULL, '2020-11-24 16:55:06', 'updateNotification', 'fr'),
(344, 145, NULL, 64, NULL, NULL, NULL, NULL, '287019fdef5552e28ad88766b57569fa', NULL, NULL, '2020-11-24 17:15:06', 'updateNotification', 'fr'),
(345, 145, NULL, 64, NULL, NULL, NULL, NULL, 'f09dc314b6f7d55bfc577d6864138de3', NULL, NULL, '2020-11-24 17:35:05', 'updateNotification', 'fr'),
(346, 145, NULL, 64, NULL, NULL, NULL, NULL, '13f815b732f842de9a627eaa12de7bcd', NULL, NULL, '2020-11-24 17:55:05', 'updateNotification', 'fr'),
(347, 1, NULL, 1, NULL, NULL, NULL, NULL, '27c2bc34cad1e9aa3799972783469d9f', NULL, NULL, '2020-11-26 14:15:19', 'userSignupInfo', 'fr'),
(348, 1, NULL, 1, NULL, NULL, NULL, NULL, '45f477b71db120ddf8e21dc6ace5dbe2', NULL, NULL, '2020-11-26 14:16:47', 'userSignupInfo', 'fr'),
(350, 1, NULL, 1, NULL, NULL, NULL, NULL, '796f4df1b4847ec54b23c453e8b211ea', NULL, NULL, '2020-11-26 14:21:38', 'userSignupInfo', 'fr'),
(352, 1, NULL, 1, NULL, NULL, NULL, NULL, '89348830e508ded3b32f0532c1135205', NULL, NULL, '2020-11-26 16:15:06', 'userSignupInfo', 'fr'),
(354, 1, NULL, 1, NULL, NULL, NULL, NULL, 'ee42730af18e122b9ec55fd744d3b0e6', NULL, NULL, '2020-11-26 16:18:59', 'userSignupInfo', 'fr'),
(355, 161, NULL, 71, NULL, NULL, NULL, NULL, '55eb4d9eb1e9ed2320f48b72b0751bd1', NULL, NULL, '2020-11-26 16:18:59', 'subscriptionConfirmation', 'fr'),
(356, 161, NULL, 72, NULL, NULL, NULL, NULL, '8cfc200ba82b0572c6c55622dcb99f0e', NULL, NULL, '2020-11-27 15:07:08', 'passwordModify', 'en'),
(357, 161, NULL, 72, NULL, NULL, NULL, NULL, 'ab507f2153f9e32e0dc469c20ad297e0', NULL, NULL, '2020-11-27 15:33:15', 'passwordModify', 'fr'),
(358, 161, NULL, 72, NULL, NULL, NULL, NULL, 'ae475097703f5e64a45841ec0487c7c8', NULL, NULL, '2020-11-27 16:57:06', 'passwordModify', 'en'),
(359, 1, NULL, 1, NULL, NULL, NULL, NULL, 'e4584a51d3017fcbec34db351c6dc407', NULL, NULL, '2020-11-27 20:54:43', 'userSignupInfo', 'fr'),
(361, 1, NULL, 1, NULL, NULL, NULL, NULL, '3ccd43216c95aa24016e40c9863682c2', NULL, NULL, '2020-11-27 21:08:08', 'userSignupInfo', 'fr'),
(363, 1, NULL, 1, NULL, NULL, NULL, NULL, 'e5eb6b1323ecfede58ccda81d8e58be8', NULL, NULL, '2020-11-27 21:11:55', 'userSignupInfo', 'fr'),
(364, 1, NULL, 1, NULL, NULL, NULL, NULL, 'e5139beeb09645d077b10deb4c2936a7', NULL, NULL, '2020-11-27 21:30:09', 'userSignupInfo', 'fr'),
(366, 1, NULL, 1, NULL, NULL, NULL, NULL, '9abf627a0579a18ee9d30bf0afd73a5f', NULL, NULL, '2020-11-27 21:35:46', 'userSignupInfo', 'fr'),
(368, 1, NULL, 1, NULL, NULL, NULL, NULL, '68bc6c3b74a6467310ce62bf8bc61d70', NULL, NULL, '2020-11-27 21:48:36', 'userSignupInfo', 'fr'),
(370, 1, NULL, 1, NULL, NULL, NULL, NULL, '43681042ec88250f45ca57652264457e', NULL, NULL, '2020-11-28 16:37:24', 'userSignupInfo', 'fr'),
(371, 191, NULL, 94, NULL, NULL, NULL, NULL, 'c30264dba3b2c9458dda237f8e34623d', NULL, NULL, '2020-11-28 16:37:25', 'subscriptionConfirmation', 'fr'),
(372, 1, NULL, 1, NULL, NULL, NULL, NULL, 'c3659b508bbada97f78d099f130ad866', NULL, NULL, '2020-11-30 16:24:35', 'userSignupInfo', 'fr'),
(373, 195, NULL, 95, NULL, NULL, NULL, NULL, 'd7bb72de2eb76a7dbfb5c45b4dbfa16a', NULL, NULL, '2020-11-30 16:24:36', 'subscriptionConfirmation', 'fr'),
(374, 128, NULL, 57, NULL, NULL, NULL, NULL, '0c681c1b314a752b0587a00b4276347a', NULL, NULL, '2020-11-30 18:50:54', 'externalInvitation', 'fr'),
(375, 128, NULL, 57, NULL, NULL, NULL, NULL, 'ac6d31d29714e7ab99fdb1a63f01bbf0', NULL, NULL, '2020-11-30 18:50:55', 'externalInvitation', 'fr'),
(376, 128, NULL, 57, NULL, NULL, NULL, NULL, '96d41c9bca6aad23fc195d4278fc68be', NULL, NULL, '2020-11-30 19:17:20', 'updateNotification', 'fr'),
(377, 8, NULL, 13, NULL, NULL, NULL, NULL, 'c7dec38136ed8c410f65d4256ea29ea5', NULL, NULL, '2020-12-01 14:11:52', 'updateNotification', 'fr'),
(378, 199, NULL, 19, NULL, NULL, NULL, NULL, '2fdf2dca246d2c4bfd87adf8655438b2', NULL, NULL, '2020-12-05 14:07:48', 'externalInvitation', 'fr'),
(379, 1, NULL, 1, NULL, NULL, NULL, NULL, '9273ba492497d80213e67ad039b6373b', NULL, NULL, '2020-12-07 16:53:05', 'userSignupInfo', 'fr'),
(380, 200, NULL, 97, NULL, NULL, NULL, NULL, '1390bd23d30f254c958f970ef986d35e', NULL, NULL, '2020-12-07 16:53:06', 'subscriptionConfirmation', 'fr'),
(381, 1, NULL, 1, NULL, NULL, NULL, NULL, '04357f53be0b137cc71637635d7607c6', NULL, NULL, '2020-12-07 21:39:30', 'userSignupInfo', 'fr'),
(382, 210, NULL, 99, NULL, NULL, NULL, NULL, '827bf7da55ce9290b38c2757267e53fa', NULL, NULL, '2020-12-07 21:39:32', 'subscriptionConfirmation', 'fr'),
(383, 8, NULL, 13, NULL, NULL, NULL, NULL, 'b00c75f8b15263825fb68a5e9605107e', NULL, NULL, '2020-12-08 17:20:12', 'updateNotification', 'fr'),
(384, 214, NULL, 21, NULL, NULL, NULL, NULL, '625c2a9e2bd03e5c38814de3de75f9b9', NULL, NULL, '2020-12-10 14:21:42', 'externalInvitation', 'fr'),
(385, 215, NULL, 21, NULL, NULL, NULL, NULL, '58ab6feee9192e7b1d7563829e221fc7', NULL, NULL, '2020-12-10 14:38:31', 'externalInvitation', 'fr'),
(386, 91, NULL, 39, NULL, NULL, NULL, NULL, '907d20504f6d0bb98cc902df8c5a40af', NULL, NULL, '2020-12-15 15:25:35', 'updateNotification', 'fr'),
(391, 220, NULL, 100, NULL, NULL, NULL, NULL, '0dad730ff4e973d7c0773e04cf4792a5', NULL, NULL, '2021-01-07 22:32:04', 'internalInvitation', 'fr'),
(392, 223, NULL, 102, NULL, NULL, NULL, NULL, 'd77b1cc79043ecc97836cc9db1831c15', NULL, NULL, '2021-01-12 11:25:23', 'registration', 'fr'),
(394, 61, NULL, 21, NULL, 20, NULL, NULL, 'fa225359e3883418b545d43927079a86', NULL, NULL, '2021-01-14 16:35:14', 'activityParticipation', 'fr'),
(395, 61, NULL, 21, NULL, 20, NULL, NULL, '98a88fa07235eb3d1005785e43da6bd1', NULL, NULL, '2021-01-14 16:40:41', 'activityParticipation', 'fr'),
(396, 61, NULL, 21, NULL, 20, NULL, NULL, '48c94a6cc767b1b6b4ba6c100f04028a', NULL, NULL, '2021-01-14 16:43:45', 'activityParticipation', 'fr'),
(397, 61, NULL, 21, NULL, 20, NULL, NULL, '3af00768a6f0ae7e1a9d00a20c3a5373', NULL, NULL, '2021-01-14 16:52:23', 'activityParticipation', 'fr'),
(398, 61, NULL, 21, NULL, 20, NULL, NULL, 'f26bcb69aa5f2f6f667a0ef75a8dabc7', NULL, NULL, '2021-01-14 16:53:50', 'activityParticipation', 'fr'),
(399, 61, NULL, 21, NULL, 20, NULL, NULL, '4f74c18443254ba0d75a3710f0ab0be5', NULL, NULL, '2021-01-14 16:59:43', 'activityParticipation', 'fr'),
(400, 61, NULL, 21, NULL, 20, NULL, NULL, '5aab0e18ca885f16589ccaa73488e5bd', NULL, NULL, '2021-01-14 17:03:34', 'activityParticipation', 'fr'),
(401, 61, NULL, 21, NULL, 20, NULL, NULL, '3b93c3894d9899d8862139837aa79bbe', NULL, NULL, '2021-01-14 17:04:07', 'activityParticipation', 'fr'),
(402, 210, NULL, 99, NULL, 20, NULL, NULL, '1eed34b61121dfd67859367c23e76383', NULL, NULL, '2021-01-17 16:36:37', 'activityParticipation', 'fr'),
(403, 210, NULL, 99, NULL, 20, NULL, NULL, '7ae0a085a230ff7cb2591c7f3764dd61', NULL, NULL, '2021-01-17 16:59:29', 'activityParticipation', 'fr'),
(404, 210, NULL, 99, NULL, 20, NULL, NULL, 'ae5ad6bc2aa5e8d31f051a8793da02c8', NULL, NULL, '2021-01-18 09:13:08', 'activityParticipation', 'fr'),
(405, 210, NULL, 99, NULL, 20, NULL, NULL, 'b1edbc13ea23113f7db7514a86d1caf0', NULL, NULL, '2021-01-18 16:16:51', 'activityParticipation', 'fr'),
(406, 225, NULL, 100, NULL, NULL, NULL, NULL, '7153406d0c3ecdd9fae980f3d899fb76', NULL, NULL, '2021-01-18 23:19:48', 'registration', 'fr'),
(407, 226, NULL, 19, NULL, NULL, NULL, NULL, '72c4662e8c2418ae245bbd3093ecd6cd', NULL, NULL, '2021-01-19 11:46:15', 'registration', 'fr'),
(408, 230, NULL, 108, NULL, NULL, NULL, NULL, '2bb6cd62a029d55027612c4fb1b8f5f6', NULL, NULL, '2021-01-19 14:09:01', 'registration', 'fr'),
(409, 1, NULL, 1, NULL, NULL, NULL, NULL, '3d265b819599ec8ba3a176ccdc114e7a', NULL, NULL, '2021-01-19 23:45:12', 'userSignupInfo', 'fr'),
(411, 1, NULL, 1, NULL, NULL, NULL, NULL, '4f74d8033490a932a8f1b64c9b9d080c', NULL, NULL, '2021-01-20 10:15:48', 'userSignupInfo', 'fr'),
(413, 1, NULL, 1, NULL, NULL, NULL, NULL, '26abb950f1b21bf8eb77a213a061b0b2', NULL, NULL, '2021-01-20 10:23:57', 'userSignupInfo', 'fr'),
(414, 237, NULL, 112, NULL, NULL, NULL, NULL, '01c28bac0ad86a68f836b9c5a1e02ff6', NULL, NULL, '2021-01-20 10:23:58', 'subscriptionConfirmation', 'fr'),
(415, 1, NULL, 1, NULL, NULL, NULL, NULL, '97a6b1d269d0315944053100c67aac36', NULL, NULL, '2021-01-20 10:26:32', 'userSignupInfo', 'fr'),
(416, 239, NULL, 113, NULL, NULL, NULL, NULL, '642f43b56d7730e63bca1f05a0c63821', NULL, NULL, '2021-01-20 10:26:33', 'subscriptionConfirmation', 'fr'),
(420, 278, NULL, 137, NULL, NULL, NULL, NULL, '05588b4291618774024675ea370c1544', NULL, NULL, '2021-01-22 13:43:46', 'registration', 'fr'),
(421, 278, NULL, 137, NULL, NULL, NULL, NULL, '9a83e3ec8f9fb2c86413eb6946e4a57a', NULL, NULL, '2021-01-22 13:46:05', 'registration', 'fr'),
(422, 226, NULL, 19, NULL, NULL, NULL, NULL, '314afa1e956b8b515a99e94c462cc660', NULL, NULL, '2021-01-22 19:34:34', 'registration', 'fr'),
(423, 226, NULL, 19, NULL, NULL, NULL, NULL, '37668fe1f631f320dd47a5db1b967d26', NULL, NULL, '2021-01-23 11:30:41', 'registration', 'fr'),
(424, 226, NULL, 19, NULL, NULL, NULL, NULL, '6d7d5d959f7c894913e250ac3221660b', NULL, NULL, '2021-01-24 09:00:00', 'externalInvitation', 'fr'),
(425, 226, NULL, 19, NULL, NULL, NULL, NULL, '4a778c0bb4d6577c82dddfefbd1e74c0', NULL, NULL, '2021-01-24 09:02:39', 'externalInvitation', 'fr'),
(426, 226, NULL, 19, NULL, NULL, NULL, NULL, '3be2017d9016294419be588c2aa8d319', NULL, NULL, '2021-01-24 09:06:58', 'externalInvitation', 'fr'),
(427, 226, NULL, 19, NULL, NULL, NULL, NULL, 'd4c8d5ebb932cf4a9bb174f592049f1e', NULL, NULL, '2021-01-24 09:08:47', 'externalInvitation', 'fr'),
(428, 278, NULL, 137, NULL, NULL, NULL, NULL, 'ad041cee32fd71cdb3e0e4c6b9a9f0ff', NULL, NULL, '2021-01-24 19:01:03', 'updateNotification', 'fr'),
(429, 278, NULL, 137, NULL, NULL, NULL, NULL, '6f6c4fb11dcff39a4b15c0f043ee6094', NULL, NULL, '2021-01-24 19:21:03', 'updateNotification', 'fr'),
(430, 278, NULL, 137, NULL, NULL, NULL, NULL, 'e345c9638451aa22bf8ee6165be57d91', NULL, NULL, '2021-01-24 20:53:18', 'externalInvitation', 'fr'),
(431, 278, NULL, 137, NULL, NULL, NULL, NULL, '5e173a4089307b052a23d66abab8069d', NULL, NULL, '2021-01-24 21:01:04', 'updateNotification', 'fr'),
(432, 226, NULL, 19, NULL, NULL, NULL, NULL, '0f453c78dbd84002da97210f65d58a49', NULL, NULL, '2021-01-25 10:04:51', 'updateNotification', 'fr'),
(433, 226, NULL, 19, NULL, NULL, NULL, NULL, 'd91a6684bb156bcf884d6af322682e30', NULL, NULL, '2021-01-25 11:47:46', 'updateNotification', 'fr'),
(434, 226, NULL, 19, NULL, NULL, NULL, NULL, 'e40eaa4baef7b802c95068b25061af38', NULL, NULL, '2021-01-25 12:27:46', 'updateNotification', 'fr'),
(435, 226, NULL, 19, NULL, NULL, NULL, NULL, 'f6fe57fa9fdccbfde711e8965a090afb', NULL, NULL, '2021-01-25 15:21:54', 'updateNotification', 'fr'),
(436, 226, NULL, 19, NULL, NULL, NULL, NULL, '78f46a02cabfbcf9deadabe252e694be', NULL, NULL, '2021-01-25 16:01:53', 'updateNotification', 'fr'),
(437, 226, NULL, 19, NULL, NULL, NULL, NULL, '90291236158272971168c52514eaa61b', NULL, NULL, '2021-01-25 16:33:17', 'updateNotification', 'fr'),
(438, 199, NULL, 19, NULL, 152, NULL, NULL, '8d943b5b6612619846e577cf0e69e75d', NULL, NULL, '2021-01-25 17:34:12', 'activityParticipation', 'fr'),
(439, 199, NULL, 19, NULL, 152, NULL, NULL, 'f956bd0ab138f717366695855875e7ef', NULL, NULL, '2021-01-25 17:39:04', 'activityParticipation', 'fr'),
(442, 199, NULL, 19, NULL, NULL, NULL, NULL, 'c62169104c15a470493c9b07dc1d2bcc', NULL, NULL, '2021-01-29 11:35:05', 'updateNotification', 'fr'),
(443, 199, NULL, 19, NULL, NULL, NULL, NULL, '16fbb95b7de81b8b36829fb524111609', NULL, NULL, '2021-01-29 14:03:45', 'updateNotification', 'fr'),
(444, 199, NULL, 19, NULL, NULL, NULL, NULL, '559787977883a15dd6ef1ca654473c38', NULL, NULL, '2021-01-29 15:49:13', 'updateNotification', 'fr'),
(445, 2, NULL, 1, NULL, NULL, NULL, NULL, 'a282f305a87d090f30c2cb96c6a26b4c', NULL, NULL, '2021-01-31 10:04:32', 'userSignupInfo', 'fr');
INSERT INTO `mail` (`mail_id`, `user_usr_id`, `worker_individual_win_id`, `organization_org_id`, `worker_firm_wfi_id`, `activity_act_id`, `stage_stg_id`, `mail_persona`, `mail_token`, `mail_read`, `mail_createdBy`, `mail_inserted`, `mail_type`, `mail_language`) VALUES
(446, 8, NULL, 13, NULL, NULL, NULL, NULL, '8d41b066acc7bbf6f9fbea1ea97260a9', NULL, NULL, '2021-01-31 10:04:33', 'userSignupInfo', 'fr'),
(448, 2, NULL, 1, NULL, NULL, NULL, NULL, 'e808581a363d4df300d27aa3fe3b5dab', NULL, NULL, '2021-01-31 10:48:13', 'userSignupInfo', 'fr'),
(449, 8, NULL, 13, NULL, NULL, NULL, NULL, '4369fc82b92042bc8628db2ed5ffe669', NULL, NULL, '2021-01-31 10:48:14', 'userSignupInfo', 'fr'),
(451, 2, NULL, 1, NULL, NULL, NULL, NULL, 'e6fd883895c9cdb7541d5da4e2367906', NULL, NULL, '2021-01-31 10:58:32', 'userSignupInfo', 'fr'),
(452, 8, NULL, 13, NULL, NULL, NULL, NULL, 'f1d6afddfa2eba28c11b6c40b5cb3316', NULL, NULL, '2021-01-31 10:58:33', 'userSignupInfo', 'fr'),
(453, 299, NULL, 145, NULL, NULL, NULL, NULL, '5cad7182cd39ca2d4a8eedd3edbc43a0', NULL, NULL, '2021-01-31 10:58:33', 'subscriptionConfirmation', 'fr'),
(454, 300, NULL, 146, NULL, 152, NULL, NULL, '29aa795593148043c69e599ab6d51006', NULL, NULL, '2021-01-31 15:20:03', 'activityParticipation', 'fr'),
(455, 300, NULL, 146, NULL, 152, NULL, NULL, '7467d3ae197bf50253f79f47ddf7d6d8', NULL, NULL, '2021-01-31 15:27:17', 'activityParticipation', 'fr'),
(456, 300, NULL, 146, NULL, 152, NULL, NULL, 'ccd22fd7027194a146b2d25bc7a2cdf7', NULL, NULL, '2021-01-31 17:29:23', 'activityParticipation', 'fr'),
(457, 300, NULL, 146, NULL, 152, NULL, NULL, 'f079423570463953605470df9ba85863', NULL, NULL, '2021-01-31 17:31:59', 'activityParticipation', 'fr'),
(458, 300, NULL, 146, NULL, 152, NULL, NULL, '6ee120426c1e1e337dfdbb136f6e1821', NULL, NULL, '2021-01-31 17:32:35', 'activityParticipation', 'fr'),
(459, 300, NULL, 146, NULL, 152, NULL, NULL, '04c1e105c5dee97f87cdfd2e1ab79ec2', NULL, NULL, '2021-01-31 17:55:58', 'activityParticipation', 'fr'),
(460, 300, NULL, 146, NULL, 152, NULL, NULL, 'a49bf5cdfc94b081f3db67219c19564a', NULL, NULL, '2021-01-31 17:58:08', 'activityParticipation', 'fr'),
(461, 300, NULL, 146, NULL, 152, NULL, NULL, 'aff25b09cbdac366faf360407d819ec5', NULL, NULL, '2021-01-31 18:00:06', 'activityParticipation', 'fr'),
(462, 300, NULL, 146, NULL, 152, NULL, NULL, 'cffb37b72629d070645ce8e91abbafae', NULL, NULL, '2021-01-31 18:03:26', 'activityParticipation', 'fr'),
(463, 300, NULL, 146, NULL, 152, NULL, NULL, 'f80194caa1442686bac52915e75826d1', NULL, NULL, '2021-01-31 18:12:24', 'activityParticipation', 'fr'),
(464, 300, NULL, 146, NULL, 152, NULL, NULL, 'fd2e849fd56b871ec7525b91484d8d28', NULL, NULL, '2021-01-31 18:13:13', 'activityParticipation', 'fr'),
(465, 300, NULL, 146, NULL, 152, NULL, NULL, 'f25ff0edfdc5c060754ab2ba33f70c38', NULL, NULL, '2021-01-31 18:17:00', 'activityParticipation', 'fr'),
(466, 300, NULL, 146, NULL, 152, NULL, NULL, '09cfdb8c2d867b51a2c8b9e4f733f73d', NULL, NULL, '2021-01-31 18:39:26', 'activityParticipation', 'fr'),
(467, 300, NULL, 146, NULL, 152, NULL, NULL, 'a11dd055f87d1e52f918c04bca685d25', NULL, NULL, '2021-01-31 18:42:29', 'activityParticipation', 'fr'),
(468, 300, NULL, 146, NULL, 152, NULL, NULL, 'd16362db1869f333598a8520fcbfef93', NULL, NULL, '2021-01-31 23:26:26', 'activityParticipation', 'fr'),
(469, 199, NULL, 19, NULL, NULL, NULL, NULL, 'cfd20cc82a751f1f312db41746c3fb8e', NULL, NULL, '2021-02-01 11:08:01', 'updateNotification', 'fr'),
(470, 199, NULL, 19, NULL, NULL, NULL, NULL, '44237b4539ad05399b802c1c9c625185', NULL, NULL, '2021-02-01 15:17:47', 'updateNotification', 'fr'),
(471, 199, NULL, 19, NULL, NULL, NULL, NULL, 'aca5aa2b0961bce747c81fa347ff18c9', NULL, NULL, '2021-02-01 17:23:29', 'updateNotification', 'fr'),
(472, 109, NULL, 51, NULL, 152, NULL, NULL, '4b209fcc75adab3ae0bd7937e43d9491', NULL, NULL, '2021-02-02 11:20:50', 'activityParticipation', 'fr'),
(473, 109, NULL, 51, NULL, 152, NULL, NULL, '5264ee96d46330aa33694b79f0a862a4', NULL, NULL, '2021-02-02 11:22:50', 'activityParticipation', 'fr'),
(474, 109, NULL, 51, NULL, 152, NULL, NULL, 'ce78742d455196d62f4eb16317457f89', NULL, NULL, '2021-02-02 11:31:51', 'activityParticipation', 'fr'),
(475, 109, NULL, 51, NULL, 152, NULL, NULL, 'e99a8fce7a19c05787fe5fe28ce016c7', NULL, NULL, '2021-02-02 11:35:23', 'activityParticipation', 'fr'),
(476, 109, NULL, 51, NULL, 152, NULL, NULL, '695b8e427ef2fa1121849feaccb9beca', NULL, NULL, '2021-02-02 11:38:22', 'activityParticipation', 'fr'),
(477, 304, NULL, 147, NULL, NULL, NULL, NULL, '4d51821bb36c4d4eafed5ff1117884fa', NULL, NULL, '2021-02-02 14:40:45', 'externalInvitation', 'fr');

-- --------------------------------------------------------

--
-- Structure de la table `member`
--

CREATE TABLE `member` (
  `mem_id` int(11) NOT NULL,
  `team_tea_id` int(11) NOT NULL,
  `user_usr_id` int(11) DEFAULT NULL,
  `external_user_ext_usr_id` int(11) NOT NULL,
  `mem_leader` tinyint(1) DEFAULT NULL,
  `mem_created_by` int(11) DEFAULT NULL,
  `mem_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mem_deleted` datetime DEFAULT NULL,
  `mem_is_deleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `option_name`
--

CREATE TABLE `option_name` (
  `ona_id` int(11) NOT NULL,
  `ona_type` int(11) DEFAULT NULL,
  `ona_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ona_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ona_created_by` int(11) DEFAULT NULL,
  `ona_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `option_name`
--

INSERT INTO `option_name` (`ona_id`, `ona_type`, `ona_name`, `ona_description`, `ona_created_by`, `ona_inserted`) VALUES
(1, 0, 'enabledUserCreatingUser', 'Enables administrators to define specified users, other than administrators, who have the sole and revokable privilege to add users to the organization.', NULL, '2020-08-24 15:34:09'),
(2, 0, 'mailDeadlineNbDays', 'Sets the number of days prior to grading deadline where users get notified of it', NULL, '2020-08-24 15:34:09'),
(3, 0, 'enabledSuperiorSubRights', 'Enables superior(s) to have access to management and results of their subordinates', NULL, '2020-08-24 15:34:09'),
(4, 0, 'enabledSuperiorSettingTargets', 'Enables superior(s) to set targets to their direct subordinates', NULL, '2020-08-24 15:34:09'),
(5, 0, 'enabledSuperiorModifySubordinate', 'Enable superior(s) to modify their subordinate info/data', NULL, '2020-08-24 15:34:09'),
(6, 0, 'enabledSuperiorOverviewSubResults', 'Enable superior(s) to view their subordinate results throughout time', NULL, '2020-08-24 15:34:09'),
(7, 0, 'enabledUserSeeSnapshotPeersResults', 'Enable users within a team to see their peer snapshot results', NULL, '2020-08-24 15:34:09'),
(8, 0, 'enabledUserSeeSnapshotSupResults', 'Enable users to view snapshot results of their superior', NULL, '2020-08-24 15:34:09'),
(9, 0, 'enabledUserSeeAllUsers', 'Enable users to view all firms users in their \'Colleagues & Teams\' tab', NULL, '2020-08-24 15:34:09'),
(10, 0, 'enabledCNamesOutsideCGroups', 'Enables users to see their ranking, based on their previous finished activities in the organization', NULL, '2020-08-24 15:34:09'),
(11, 0, 'enabledUserSeeRanking', 'Enables users to see their ranking, based on their previous finished activities in the organization', NULL, '2020-08-24 15:34:09'),
(12, 0, 'activitiesAccessAndResultsView', 'Depending on user role, defines range of activities accessibility, level of detail, scope of results,  depending or not of his stage ownership', NULL, '2020-08-24 15:34:09');

-- --------------------------------------------------------

--
-- Structure de la table `organization`
--

CREATE TABLE `organization` (
  `org_id` int(11) NOT NULL,
  `worker_firm_wfi_id` int(11) DEFAULT NULL,
  `org_legalname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_commname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_isClient` tinyint(1) DEFAULT NULL,
  `org_oth_language` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_weight_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_created_by` int(11) DEFAULT NULL,
  `org_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `org_last_checked_plan` datetime DEFAULT NULL,
  `org_expired` datetime DEFAULT NULL,
  `org_testing_reminder_sent` tinyint(1) DEFAULT NULL,
  `org_deleted` datetime DEFAULT NULL,
  `org_routine_pstatus` datetime DEFAULT NULL,
  `org_routine_greminders` datetime DEFAULT NULL,
  `org_users_CSV` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_plan` int(11) NOT NULL DEFAULT '3',
  `org_cus_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_usr_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `organization`
--

INSERT INTO `organization` (`org_id`, `worker_firm_wfi_id`, `org_legalname`, `org_commname`, `org_type`, `org_isClient`, `org_oth_language`, `org_weight_type`, `org_created_by`, `org_inserted`, `org_last_checked_plan`, `org_expired`, `org_testing_reminder_sent`, `org_deleted`, `org_routine_pstatus`, `org_routine_greminders`, `org_users_CSV`, `org_logo`, `org_plan`, `org_cus_id`, `payment_usr_id`) VALUES
(1, 1, 'Serpico Inc.', 'Serpico', 'f', 1, 'FR', '', NULL, '2020-08-24 15:34:09', '2020-12-04 09:05:31', '2020-12-04 00:00:00', NULL, NULL, NULL, NULL, '', 'dealdrive-5fd9d60359a3b.jpeg', 2, 'cus_IWotmfCgkWy4Pd', NULL),
(13, 6, '', 'Welkin & Meraki', 'F', NULL, 'FR', 'role', NULL, '2020-08-27 14:12:20', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(16, 7, '', 'Creos', 'F', NULL, 'FR', 'role', NULL, '2020-08-31 19:22:34', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(18, 8, '', 'Ministère des Classes Moyennes', 'F', NULL, 'FR', 'role', NULL, '2020-08-31 21:42:02', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(19, 9, '', 'BGL BNP Paribas', 'F', NULL, 'FR', 'role', NULL, '2020-08-31 22:24:40', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 3, 'cus_IoivvEaVc7YLhq', NULL),
(20, 10, '', 'NVision', 'F', NULL, 'FR', 'role', NULL, '2020-08-31 22:30:19', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(21, 11, '', 'DuPont & Nemours', 'F', NULL, 'FR', 'role', NULL, '2020-09-02 12:38:23', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(25, 21, 'Landifirm', 'Landifirm', 'F', 1, 'FR', 'role', NULL, '2020-09-03 18:53:55', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(32, 57, '', 'Focus', 'F', NULL, 'FR', 'role', 1, '2020-09-30 08:18:03', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(39, 56, '', 'Luxembourg City Incubator', 'F', NULL, 'FR', 'role', 1, '2020-09-30 17:52:55', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(40, 58, '', 'Luxfactory', 'F', NULL, 'FR', 'role', 1, '2020-10-02 13:02:54', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 1, NULL, NULL),
(49, 59, '', 'SalonKee', 'F', NULL, 'FR', 'role', 104, '2020-10-13 21:25:06', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(51, 62, '', 'Vizz', 'F', NULL, 'FR', 'role', 104, '2020-10-13 22:37:08', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(56, 63, '', 'Ministère de l\'Economie', 'F', NULL, 'FR', 'role', 118, '2020-10-14 15:44:56', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(57, 64, '', 'Velazquez Foundation', 'F', NULL, 'FR', 'role', 128, '2020-10-27 08:40:19', '2020-12-03 10:31:28', '2020-12-10 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(64, 65, '', 'Tatcher Inc.', 'F', NULL, 'FR', 'role', 128, '2020-10-27 11:44:10', '2020-12-06 12:41:46', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(65, 67, '', 'Metro Goldwin', 'F', NULL, 'FR', 'role', 145, '2020-11-15 11:50:21', '2020-11-18 15:42:43', '2100-01-01 00:00:00', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(66, 68, '', 'Camille Suteau', 'I', NULL, 'FR', 'role', 128, '2020-11-20 17:01:29', NULL, '2020-12-11 17:01:29', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(67, 69, '', 'Fabrice Pincet', 'I', NULL, 'FR', 'role', 128, '2020-11-20 17:05:37', NULL, '2020-12-11 17:05:37', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(71, NULL, '', 'Lucien Hermenon', 'C', NULL, 'FR', '', NULL, '2020-11-26 16:18:59', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(72, 70, '', 'Hermenon Foundation', 'F', NULL, 'FR', 'role', 161, '2020-11-26 16:20:15', NULL, '2020-12-17 16:20:15', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(94, NULL, '', 'Vincent Tinot', 'C', NULL, 'FR', '', NULL, '2020-11-28 16:37:24', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(95, NULL, '', 'Xavier Bettel', 'C', NULL, 'FR', '', NULL, '2020-11-30 16:24:35', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(96, 75, '', 'Le Mosellan', 'F', NULL, 'FR', 'role', 191, '2020-12-01 16:22:36', NULL, '2020-12-22 16:22:36', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(97, NULL, '', 'Maya Coumes', 'C', NULL, 'FR', '', NULL, '2020-12-07 16:53:02', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, 'cus_IWqeI6YBnwXUPz', NULL),
(98, 76, '', 'Coumes Inc', 'F', NULL, 'FR', 'role', 200, '2020-12-07 21:23:00', NULL, '2020-12-28 16:58:33', NULL, NULL, NULL, NULL, '', NULL, 2, 'cus_IWuzKzREtI1GAq', NULL),
(99, NULL, '', 'Clément Garnier', 'C', NULL, 'FR', '', NULL, '2020-12-07 21:39:29', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, 'cus_IWvH5JmXeSB1cV', NULL),
(100, 77, '', 'Garnier & Co', 'F', NULL, 'FR', 'role', 210, '2020-12-07 21:40:11', NULL, '2020-12-28 21:40:11', NULL, NULL, NULL, NULL, '', NULL, 2, 'cus_IWvKgaa509dx7n', NULL),
(101, 81, NULL, 'Evernote', 'F', NULL, 'FR', 'role', 210, '2021-01-09 23:26:26', NULL, '2021-01-30 23:26:26', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(102, 82, NULL, 'Robeco', 'F', NULL, 'FR', 'role', 1, '2021-01-12 10:02:51', NULL, '2021-02-02 10:02:51', NULL, NULL, NULL, NULL, '', 'Capture-d-ecran-2021-01-31-a-12-57-39-60169cb4d6c31.png', 2, 'cus_InEKrZIkVStxQ3', NULL),
(108, 83, NULL, 'Floyd Aviation', 'F', NULL, 'FR', 'role', 1, '2021-01-19 14:08:31', NULL, '2021-02-09 14:08:31', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(112, NULL, NULL, 'Jerome Garcin', 'C', NULL, 'FR', '', NULL, '2021-01-20 10:23:53', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, 'cus_InEJiMz1gwCPVG', NULL),
(113, NULL, NULL, 'Pierre Garcin', 'C', NULL, 'FR', '', NULL, '2021-01-20 10:26:29', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, 'cus_InEMAemVhDZBs5', NULL),
(114, NULL, NULL, 'Cristof Rostoff', 'C', NULL, 'FR', '', NULL, '2021-01-20 10:36:37', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(137, NULL, NULL, 'Federico Garcia', 'I', NULL, 'FR', 'role', 223, '2021-01-22 11:42:17', NULL, '2021-02-12 11:42:17', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(138, NULL, NULL, 'George Faventyne', 'C', NULL, 'FR', '', NULL, '2021-01-24 10:06:38', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(139, NULL, NULL, 'Gertrude Bernard', 'C', NULL, 'FR', '', NULL, '2021-01-29 11:05:12', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(140, NULL, NULL, 'Jerome Stumper', 'C', NULL, 'FR', '', NULL, '2021-01-29 20:17:34', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(141, NULL, NULL, 'Jerome Stumper', 'C', NULL, 'FR', '', NULL, '2021-01-29 20:24:45', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL),
(145, NULL, NULL, 'Francis Lalanne', 'C', NULL, 'FR', '', NULL, '2021-01-31 10:58:28', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, 'cus_IrMMRqomZxUJUk', NULL),
(146, 107, NULL, 'Weigand & Co', 'F', NULL, 'FR', 'role', 299, '2021-01-31 10:59:22', NULL, '2021-02-21 10:59:22', NULL, NULL, NULL, NULL, '', NULL, 2, 'cus_IrMNlIGGlF1Dc2', NULL),
(147, 108, NULL, 'De la Cruz Co.', 'F', NULL, 'FR', 'role', 223, '2021-02-02 14:33:52', NULL, '2021-02-23 14:33:52', NULL, NULL, NULL, NULL, '', NULL, 2, NULL, NULL),
(148, NULL, NULL, 'David Recibo', 'C', NULL, 'FR', '', NULL, '2021-02-02 16:12:59', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `organization_payment_method`
--

CREATE TABLE `organization_payment_method` (
  `opm_id` int(11) NOT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `opm_pmid` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opm_created_by` int(11) DEFAULT NULL,
  `opm_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `organization_user_option`
--

CREATE TABLE `organization_user_option` (
  `opt_id` int(11) NOT NULL,
  `option_name_ona_id` int(11) DEFAULT NULL,
  `organization_org_id` int(11) NOT NULL,
  `department_dpt_id` int(11) DEFAULT NULL,
  `position_pos_id` int(11) DEFAULT NULL,
  `title_tit_id` int(11) DEFAULT NULL,
  `user_usr_id` int(11) DEFAULT NULL,
  `opt_bool_value` tinyint(1) DEFAULT NULL,
  `opt_int_value` double DEFAULT NULL,
  `opt_int_value_2` double DEFAULT NULL,
  `opt_float_value` double DEFAULT NULL,
  `opt_string_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opt_enabled` tinyint(1) DEFAULT NULL,
  `opt_created_by` int(11) DEFAULT NULL,
  `opt_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `org_role` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `organization_user_option`
--

INSERT INTO `organization_user_option` (`opt_id`, `option_name_ona_id`, `organization_org_id`, `department_dpt_id`, `position_pos_id`, `title_tit_id`, `user_usr_id`, `opt_bool_value`, `opt_int_value`, `opt_int_value_2`, `opt_float_value`, `opt_string_value`, `opt_enabled`, `opt_created_by`, `opt_inserted`, `org_role`) VALUES
(1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-24 15:34:09', NULL),
(2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-08-24 15:34:09', NULL),
(3, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-24 15:34:09', NULL),
(4, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-24 15:34:09', NULL),
(5, 5, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-24 15:34:09', NULL),
(6, 6, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-24 15:34:09', NULL),
(7, 7, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-24 15:34:09', NULL),
(8, 8, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-24 15:34:09', NULL),
(9, 9, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-24 15:34:09', NULL),
(10, 10, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-24 15:34:09', NULL),
(11, 11, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-24 15:34:09', NULL),
(12, 12, 1, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-08-24 15:34:09', 1),
(13, 12, 1, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-08-24 15:34:09', 2),
(14, 12, 1, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-08-24 15:34:09', 3),
(71, 1, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-27 14:12:20', NULL),
(72, 2, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-08-27 14:12:20', NULL),
(73, 3, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-27 14:12:20', NULL),
(74, 4, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-27 14:12:20', NULL),
(75, 5, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-27 14:12:20', NULL),
(76, 6, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-27 14:12:20', NULL),
(77, 7, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-27 14:12:20', NULL),
(78, 8, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-27 14:12:20', NULL),
(79, 9, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-27 14:12:20', NULL),
(80, 10, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-27 14:12:20', NULL),
(81, 11, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-27 14:12:20', NULL),
(82, 12, 13, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-08-27 14:12:20', 1),
(83, 12, 13, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-08-27 14:12:20', 2),
(84, 12, 13, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-08-27 14:12:20', 3),
(155, 1, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 19:22:34', NULL),
(156, 2, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-08-31 19:22:34', NULL),
(157, 3, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 19:22:34', NULL),
(158, 4, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 19:22:34', NULL),
(159, 5, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 19:22:34', NULL),
(160, 6, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 19:22:34', NULL),
(161, 7, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 19:22:34', NULL),
(162, 8, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 19:22:34', NULL),
(163, 9, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 19:22:34', NULL),
(164, 10, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 19:22:34', NULL),
(165, 11, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 19:22:34', NULL),
(166, 12, 16, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-08-31 19:22:34', 1),
(167, 12, 16, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-08-31 19:22:34', 2),
(168, 12, 16, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-08-31 19:22:34', 3),
(183, 1, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 21:42:02', NULL),
(184, 2, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-08-31 21:42:02', NULL),
(185, 3, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 21:42:02', NULL),
(186, 4, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 21:42:02', NULL),
(187, 5, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 21:42:02', NULL),
(188, 6, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 21:42:02', NULL),
(189, 7, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 21:42:02', NULL),
(190, 8, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 21:42:02', NULL),
(191, 9, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 21:42:02', NULL),
(192, 10, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 21:42:02', NULL),
(193, 11, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 21:42:02', NULL),
(194, 12, 18, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-08-31 21:42:02', 1),
(195, 12, 18, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-08-31 21:42:02', 2),
(196, 12, 18, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-08-31 21:42:02', 3),
(211, 1, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:24:40', NULL),
(212, 2, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-08-31 22:24:40', NULL),
(213, 3, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:24:40', NULL),
(214, 4, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:24:40', NULL),
(215, 5, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:24:40', NULL),
(216, 6, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:24:40', NULL),
(217, 7, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:24:40', NULL),
(218, 8, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:24:40', NULL),
(219, 9, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:24:40', NULL),
(220, 10, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:24:40', NULL),
(221, 11, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:24:40', NULL),
(222, 12, 19, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-08-31 22:24:40', 1),
(223, 12, 19, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-08-31 22:24:40', 2),
(224, 12, 19, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-08-31 22:24:40', 3),
(239, 1, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:30:19', NULL),
(240, 2, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-08-31 22:30:19', NULL),
(241, 3, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:30:19', NULL),
(242, 4, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:30:19', NULL),
(243, 5, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:30:19', NULL),
(244, 6, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:30:19', NULL),
(245, 7, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:30:19', NULL),
(246, 8, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:30:19', NULL),
(247, 9, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:30:19', NULL),
(248, 10, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:30:19', NULL),
(249, 11, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-08-31 22:30:19', NULL),
(250, 12, 20, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-08-31 22:30:19', 1),
(251, 12, 20, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-08-31 22:30:19', 2),
(252, 12, 20, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-08-31 22:30:19', 3),
(267, 1, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-02 12:38:23', NULL),
(268, 2, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-09-02 12:38:23', NULL),
(269, 3, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-02 12:38:23', NULL),
(270, 4, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-02 12:38:23', NULL),
(271, 5, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-02 12:38:23', NULL),
(272, 6, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-02 12:38:23', NULL),
(273, 7, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-02 12:38:23', NULL),
(274, 8, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-02 12:38:23', NULL),
(275, 9, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-02 12:38:23', NULL),
(276, 10, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-02 12:38:23', NULL),
(277, 11, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-02 12:38:23', NULL),
(278, 12, 21, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-09-02 12:38:23', 1),
(279, 12, 21, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-09-02 12:38:23', 2),
(280, 12, 21, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-09-02 12:38:23', 3),
(295, 1, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-03 18:53:55', NULL),
(296, 2, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-09-03 18:53:55', NULL),
(297, 3, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-03 18:53:55', NULL),
(298, 4, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-03 18:53:55', NULL),
(299, 5, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-03 18:53:55', NULL),
(300, 6, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-03 18:53:55', NULL),
(301, 7, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-03 18:53:55', NULL),
(302, 8, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-03 18:53:55', NULL),
(303, 9, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-03 18:53:55', NULL),
(304, 10, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-03 18:53:55', NULL),
(305, 11, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-03 18:53:55', NULL),
(306, 12, 25, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-09-03 18:53:55', 1),
(307, 12, 25, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-09-03 18:53:55', 2),
(308, 12, 25, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-09-03 18:53:55', 3),
(323, 1, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-27 20:56:19', NULL),
(324, 2, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-09-27 20:56:19', NULL),
(325, 3, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-27 20:56:19', NULL),
(326, 4, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-27 20:56:19', NULL),
(327, 5, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-27 20:56:19', NULL),
(328, 6, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-27 20:56:19', NULL),
(329, 7, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-27 20:56:19', NULL),
(330, 8, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-27 20:56:19', NULL),
(331, 9, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-27 20:56:19', NULL),
(332, 10, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-27 20:56:19', NULL),
(333, 11, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-27 20:56:19', NULL),
(334, 12, 25, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-09-27 20:56:19', 1),
(335, 12, 25, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-09-27 20:56:19', 2),
(336, 12, 25, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-09-27 20:56:19', 3),
(407, 1, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 08:18:03', NULL),
(408, 2, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-09-30 08:18:03', NULL),
(409, 3, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 08:18:03', NULL),
(410, 4, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 08:18:03', NULL),
(411, 5, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 08:18:03', NULL),
(412, 6, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 08:18:03', NULL),
(413, 7, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 08:18:03', NULL),
(414, 8, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 08:18:03', NULL),
(415, 9, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 08:18:03', NULL),
(416, 10, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 08:18:03', NULL),
(417, 11, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 08:18:03', NULL),
(418, 12, 32, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-09-30 08:18:03', 1),
(419, 12, 32, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-09-30 08:18:03', 2),
(420, 12, 32, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-09-30 08:18:03', 3),
(435, 1, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 13:04:31', NULL),
(436, 2, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-09-30 13:04:31', NULL),
(437, 3, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 13:04:31', NULL),
(438, 4, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 13:04:31', NULL),
(439, 5, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 13:04:31', NULL),
(440, 6, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 13:04:31', NULL),
(441, 7, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 13:04:31', NULL),
(442, 8, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 13:04:31', NULL),
(443, 9, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 13:04:31', NULL),
(444, 10, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 13:04:31', NULL),
(445, 11, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 13:04:31', NULL),
(446, 12, 32, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-09-30 13:04:31', 1),
(447, 12, 32, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-09-30 13:04:31', 2),
(448, 12, 32, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-09-30 13:04:31', 3),
(603, 1, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 17:52:55', NULL),
(604, 2, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-09-30 17:52:55', NULL),
(605, 3, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 17:52:55', NULL),
(606, 4, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 17:52:55', NULL),
(607, 5, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 17:52:55', NULL),
(608, 6, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 17:52:55', NULL),
(609, 7, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 17:52:55', NULL),
(610, 8, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 17:52:55', NULL),
(611, 9, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 17:52:55', NULL),
(612, 10, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 17:52:55', NULL),
(613, 11, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-09-30 17:52:55', NULL),
(614, 12, 39, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-09-30 17:52:55', 1),
(615, 12, 39, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-09-30 17:52:55', 2),
(616, 12, 39, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-09-30 17:52:55', 3),
(631, 1, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-02 13:02:54', NULL),
(632, 2, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-10-02 13:02:54', NULL),
(633, 3, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-02 13:02:54', NULL),
(634, 4, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-02 13:02:54', NULL),
(635, 5, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-02 13:02:54', NULL),
(636, 6, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-02 13:02:54', NULL),
(637, 7, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-02 13:02:54', NULL),
(638, 8, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-02 13:02:54', NULL),
(639, 9, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-02 13:02:54', NULL),
(640, 10, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-02 13:02:54', NULL),
(641, 11, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-02 13:02:54', NULL),
(642, 12, 40, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-10-02 13:02:54', 1),
(643, 12, 40, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-10-02 13:02:54', 2),
(644, 12, 40, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-10-02 13:02:54', 3),
(827, 1, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 21:25:06', NULL),
(828, 2, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-10-13 21:25:06', NULL),
(829, 3, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 21:25:06', NULL),
(830, 4, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 21:25:06', NULL),
(831, 5, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 21:25:06', NULL),
(832, 6, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 21:25:06', NULL),
(833, 7, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 21:25:06', NULL),
(834, 8, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 21:25:06', NULL),
(835, 9, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 21:25:06', NULL),
(836, 10, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 21:25:06', NULL),
(837, 11, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 21:25:06', NULL),
(838, 12, 49, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-10-13 21:25:06', 1),
(839, 12, 49, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-10-13 21:25:06', 2),
(840, 12, 49, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-10-13 21:25:06', 3),
(883, 1, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 22:37:08', NULL),
(884, 2, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-10-13 22:37:08', NULL),
(885, 3, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 22:37:08', NULL),
(886, 4, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 22:37:08', NULL),
(887, 5, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 22:37:08', NULL),
(888, 6, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 22:37:08', NULL),
(889, 7, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 22:37:08', NULL),
(890, 8, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 22:37:08', NULL),
(891, 9, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 22:37:08', NULL),
(892, 10, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 22:37:08', NULL),
(893, 11, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-13 22:37:08', NULL),
(894, 12, 51, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-10-13 22:37:08', 1),
(895, 12, 51, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-10-13 22:37:08', 2),
(896, 12, 51, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-10-13 22:37:08', 3),
(1023, 1, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-14 15:44:56', NULL),
(1024, 2, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-10-14 15:44:56', NULL),
(1025, 3, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-14 15:44:56', NULL),
(1026, 4, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-14 15:44:56', NULL),
(1027, 5, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-14 15:44:56', NULL),
(1028, 6, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-14 15:44:56', NULL),
(1029, 7, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-14 15:44:56', NULL),
(1030, 8, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-14 15:44:56', NULL),
(1031, 9, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-14 15:44:56', NULL),
(1032, 10, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-14 15:44:56', NULL),
(1033, 11, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-14 15:44:56', NULL),
(1034, 12, 56, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-10-14 15:44:56', 1),
(1035, 12, 56, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-10-14 15:44:56', 2),
(1036, 12, 56, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-10-14 15:44:56', 3),
(1051, 1, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 08:40:19', NULL),
(1052, 2, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-10-27 08:40:19', NULL),
(1053, 3, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 08:40:19', NULL),
(1054, 4, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 08:40:19', NULL),
(1055, 5, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 08:40:19', NULL),
(1056, 6, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 08:40:19', NULL),
(1057, 7, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 08:40:19', NULL),
(1058, 8, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 08:40:19', NULL),
(1059, 9, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 08:40:19', NULL),
(1060, 10, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 08:40:19', NULL),
(1061, 11, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 08:40:19', NULL),
(1062, 12, 57, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-10-27 08:40:19', 1),
(1063, 12, 57, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-10-27 08:40:19', 2),
(1064, 12, 57, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-10-27 08:40:19', 3),
(1247, 1, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 11:44:10', NULL),
(1248, 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-10-27 11:44:10', NULL),
(1249, 3, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 11:44:10', NULL),
(1250, 4, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 11:44:10', NULL),
(1251, 5, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 11:44:10', NULL),
(1252, 6, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 11:44:10', NULL),
(1253, 7, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 11:44:10', NULL),
(1254, 8, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 11:44:10', NULL),
(1255, 9, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 11:44:10', NULL),
(1256, 10, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 11:44:10', NULL),
(1257, 11, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-10-27 11:44:10', NULL),
(1258, 12, 64, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-10-27 11:44:10', 1),
(1259, 12, 64, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-10-27 11:44:10', 2),
(1260, 12, 64, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-10-27 11:44:10', 3),
(1275, 1, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-15 11:50:21', NULL),
(1276, 2, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-11-15 11:50:21', NULL),
(1277, 3, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-15 11:50:21', NULL),
(1278, 4, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-15 11:50:21', NULL),
(1279, 5, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-15 11:50:21', NULL),
(1280, 6, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-15 11:50:21', NULL),
(1281, 7, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-15 11:50:21', NULL),
(1282, 8, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-15 11:50:21', NULL),
(1283, 9, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-15 11:50:21', NULL),
(1284, 10, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-15 11:50:21', NULL),
(1285, 11, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-15 11:50:21', NULL),
(1286, 12, 65, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-11-15 11:50:21', 1),
(1287, 12, 65, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-11-15 11:50:21', 2),
(1288, 12, 65, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-11-15 11:50:21', 3),
(1303, 1, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:01:29', NULL),
(1304, 2, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-11-20 17:01:29', NULL),
(1305, 3, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:01:29', NULL),
(1306, 4, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:01:29', NULL),
(1307, 5, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:01:29', NULL),
(1308, 6, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:01:29', NULL),
(1309, 7, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:01:29', NULL),
(1310, 8, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:01:29', NULL),
(1311, 9, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:01:29', NULL),
(1312, 10, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:01:29', NULL),
(1313, 11, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:01:29', NULL),
(1314, 12, 66, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-11-20 17:01:29', 1),
(1315, 12, 66, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-11-20 17:01:29', 2),
(1316, 12, 66, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-11-20 17:01:29', 3),
(1331, 1, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:05:37', NULL),
(1332, 2, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-11-20 17:05:37', NULL),
(1333, 3, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:05:37', NULL),
(1334, 4, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:05:37', NULL),
(1335, 5, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:05:37', NULL),
(1336, 6, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:05:37', NULL),
(1337, 7, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:05:37', NULL),
(1338, 8, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:05:37', NULL),
(1339, 9, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:05:37', NULL),
(1340, 10, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:05:37', NULL),
(1341, 11, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-20 17:05:37', NULL),
(1342, 12, 67, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-11-20 17:05:37', 1),
(1343, 12, 67, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-11-20 17:05:37', 2),
(1344, 12, 67, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-11-20 17:05:37', 3),
(1415, 1, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:18:59', NULL),
(1416, 2, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-11-26 16:18:59', NULL),
(1417, 3, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:18:59', NULL),
(1418, 4, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:18:59', NULL),
(1419, 5, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:18:59', NULL),
(1420, 6, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:18:59', NULL),
(1421, 7, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:18:59', NULL),
(1422, 8, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:18:59', NULL),
(1423, 9, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:18:59', NULL),
(1424, 10, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:18:59', NULL),
(1425, 11, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:18:59', NULL),
(1426, 12, 71, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-11-26 16:18:59', 1),
(1427, 12, 71, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-11-26 16:18:59', 2),
(1428, 12, 71, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-11-26 16:18:59', 3),
(1443, 1, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:20:15', NULL),
(1444, 2, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-11-26 16:20:15', NULL),
(1445, 3, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:20:15', NULL),
(1446, 4, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:20:15', NULL),
(1447, 5, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:20:15', NULL),
(1448, 6, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:20:15', NULL),
(1449, 7, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:20:15', NULL),
(1450, 8, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:20:15', NULL),
(1451, 9, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:20:15', NULL),
(1452, 10, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:20:15', NULL),
(1453, 11, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-26 16:20:15', NULL),
(1454, 12, 72, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-11-26 16:20:15', 1),
(1455, 12, 72, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-11-26 16:20:15', 2),
(1456, 12, 72, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-11-26 16:20:15', 3),
(1793, 1, 94, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-28 16:37:24', NULL),
(1794, 2, 94, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-11-28 16:37:24', NULL),
(1795, 3, 94, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-28 16:37:24', NULL),
(1796, 4, 94, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-28 16:37:24', NULL),
(1797, 5, 94, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-28 16:37:24', NULL),
(1798, 6, 94, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-28 16:37:24', NULL),
(1799, 7, 94, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-28 16:37:24', NULL),
(1800, 8, 94, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-28 16:37:24', NULL),
(1801, 9, 94, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-28 16:37:24', NULL),
(1802, 10, 94, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-28 16:37:24', NULL),
(1803, 11, 94, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-28 16:37:24', NULL),
(1804, 12, 94, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-11-28 16:37:24', 1),
(1805, 12, 94, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-11-28 16:37:24', 2),
(1806, 12, 94, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-11-28 16:37:24', 3),
(1807, 1, 95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-30 16:24:35', NULL),
(1808, 2, 95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-11-30 16:24:35', NULL),
(1809, 3, 95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-30 16:24:35', NULL),
(1810, 4, 95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-30 16:24:35', NULL),
(1811, 5, 95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-30 16:24:35', NULL),
(1812, 6, 95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-30 16:24:35', NULL),
(1813, 7, 95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-30 16:24:35', NULL),
(1814, 8, 95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-30 16:24:35', NULL),
(1815, 9, 95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-30 16:24:35', NULL),
(1816, 10, 95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-30 16:24:35', NULL),
(1817, 11, 95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-11-30 16:24:35', NULL),
(1818, 12, 95, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-11-30 16:24:35', 1),
(1819, 12, 95, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-11-30 16:24:35', 2),
(1820, 12, 95, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-11-30 16:24:35', 3),
(1821, 1, 96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-01 16:22:36', NULL),
(1822, 2, 96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-12-01 16:22:36', NULL),
(1823, 3, 96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-01 16:22:36', NULL),
(1824, 4, 96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-01 16:22:36', NULL),
(1825, 5, 96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-01 16:22:36', NULL),
(1826, 6, 96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-01 16:22:36', NULL),
(1827, 7, 96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-01 16:22:36', NULL),
(1828, 8, 96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-01 16:22:36', NULL),
(1829, 9, 96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-01 16:22:36', NULL),
(1830, 10, 96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-01 16:22:36', NULL),
(1831, 11, 96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-01 16:22:36', NULL),
(1832, 12, 96, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-12-01 16:22:36', 1),
(1833, 12, 96, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-12-01 16:22:36', 2),
(1834, 12, 96, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-12-01 16:22:36', 3),
(1835, 1, 97, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:53:02', NULL),
(1836, 2, 97, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-12-07 16:53:02', NULL),
(1837, 3, 97, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:53:02', NULL),
(1838, 4, 97, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:53:02', NULL),
(1839, 5, 97, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:53:02', NULL),
(1840, 6, 97, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:53:02', NULL),
(1841, 7, 97, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:53:02', NULL),
(1842, 8, 97, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:53:02', NULL),
(1843, 9, 97, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:53:02', NULL),
(1844, 10, 97, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:53:02', NULL),
(1845, 11, 97, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:53:02', NULL),
(1846, 12, 97, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-12-07 16:53:02', 1),
(1847, 12, 97, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-12-07 16:53:02', 2),
(1848, 12, 97, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-12-07 16:53:02', 3),
(1849, 1, 98, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:58:33', NULL),
(1850, 2, 98, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-12-07 16:58:33', NULL),
(1851, 3, 98, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:58:33', NULL),
(1852, 4, 98, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:58:33', NULL),
(1853, 5, 98, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:58:33', NULL),
(1854, 6, 98, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:58:33', NULL),
(1855, 7, 98, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:58:33', NULL),
(1856, 8, 98, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:58:33', NULL),
(1857, 9, 98, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:58:33', NULL),
(1858, 10, 98, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:58:33', NULL),
(1859, 11, 98, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 16:58:33', NULL),
(1860, 12, 98, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-12-07 16:58:33', 1),
(1861, 12, 98, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-12-07 16:58:33', 2),
(1862, 12, 98, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-12-07 16:58:33', 3),
(1863, 1, 99, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:39:29', NULL),
(1864, 2, 99, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-12-07 21:39:29', NULL),
(1865, 3, 99, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:39:29', NULL),
(1866, 4, 99, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:39:29', NULL),
(1867, 5, 99, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:39:29', NULL),
(1868, 6, 99, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:39:29', NULL),
(1869, 7, 99, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:39:29', NULL),
(1870, 8, 99, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:39:29', NULL),
(1871, 9, 99, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:39:29', NULL),
(1872, 10, 99, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:39:29', NULL),
(1873, 11, 99, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:39:29', NULL),
(1874, 12, 99, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-12-07 21:39:29', 1),
(1875, 12, 99, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-12-07 21:39:29', 2),
(1876, 12, 99, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-12-07 21:39:29', 3),
(1877, 1, 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:40:11', NULL),
(1878, 2, 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2020-12-07 21:40:11', NULL),
(1879, 3, 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:40:11', NULL),
(1880, 4, 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:40:11', NULL),
(1881, 5, 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:40:11', NULL),
(1882, 6, 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:40:11', NULL),
(1883, 7, 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:40:11', NULL),
(1884, 8, 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:40:11', NULL),
(1885, 9, 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:40:11', NULL),
(1886, 10, 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:40:11', NULL),
(1887, 11, 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2020-12-07 21:40:11', NULL),
(1888, 12, 100, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2020-12-07 21:40:11', 1),
(1889, 12, 100, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2020-12-07 21:40:11', 2),
(1890, 12, 100, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2020-12-07 21:40:11', 3),
(1891, 1, 101, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-09 23:26:26', NULL),
(1892, 2, 101, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2021-01-09 23:26:26', NULL),
(1893, 3, 101, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-09 23:26:26', NULL),
(1894, 4, 101, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-09 23:26:26', NULL),
(1895, 5, 101, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-09 23:26:26', NULL),
(1896, 6, 101, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-09 23:26:26', NULL),
(1897, 7, 101, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-09 23:26:26', NULL),
(1898, 8, 101, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-09 23:26:26', NULL),
(1899, 9, 101, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-09 23:26:26', NULL),
(1900, 10, 101, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-09 23:26:26', NULL),
(1901, 11, 101, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-09 23:26:26', NULL),
(1902, 12, 101, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2021-01-09 23:26:26', 1),
(1903, 12, 101, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2021-01-09 23:26:26', 2),
(1904, 12, 101, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2021-01-09 23:26:26', 3),
(1905, 1, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-12 10:02:51', NULL),
(1906, 2, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2021-01-12 10:02:51', NULL),
(1907, 3, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-12 10:02:51', NULL),
(1908, 4, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-12 10:02:51', NULL),
(1909, 5, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-12 10:02:51', NULL),
(1910, 6, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-12 10:02:51', NULL),
(1911, 7, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-12 10:02:51', NULL),
(1912, 8, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-12 10:02:51', NULL),
(1913, 9, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-12 10:02:51', NULL),
(1914, 10, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-12 10:02:51', NULL),
(1915, 11, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-12 10:02:51', NULL),
(1916, 12, 102, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2021-01-12 10:02:51', 1),
(1917, 12, 102, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2021-01-12 10:02:51', 2),
(1918, 12, 102, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2021-01-12 10:02:51', 3),
(1989, 1, 108, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-19 14:08:31', NULL),
(1990, 2, 108, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2021-01-19 14:08:31', NULL),
(1991, 3, 108, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-19 14:08:31', NULL),
(1992, 4, 108, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-19 14:08:31', NULL),
(1993, 5, 108, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-19 14:08:31', NULL),
(1994, 6, 108, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-19 14:08:31', NULL),
(1995, 7, 108, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-19 14:08:31', NULL),
(1996, 8, 108, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-19 14:08:31', NULL),
(1997, 9, 108, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-19 14:08:31', NULL),
(1998, 10, 108, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-19 14:08:31', NULL),
(1999, 11, 108, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-19 14:08:31', NULL),
(2000, 12, 108, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2021-01-19 14:08:31', 1),
(2001, 12, 108, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2021-01-19 14:08:31', 2),
(2002, 12, 108, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2021-01-19 14:08:31', 3),
(2045, 1, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:23:53', NULL),
(2046, 2, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2021-01-20 10:23:53', NULL),
(2047, 3, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:23:53', NULL),
(2048, 4, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:23:53', NULL),
(2049, 5, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:23:53', NULL),
(2050, 6, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:23:53', NULL),
(2051, 7, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:23:53', NULL),
(2052, 8, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:23:53', NULL),
(2053, 9, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:23:53', NULL),
(2054, 10, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:23:53', NULL),
(2055, 11, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:23:53', NULL),
(2056, 12, 112, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2021-01-20 10:23:53', 1),
(2057, 12, 112, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2021-01-20 10:23:53', 2),
(2058, 12, 112, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2021-01-20 10:23:53', 3),
(2059, 1, 113, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:26:29', NULL),
(2060, 2, 113, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2021-01-20 10:26:29', NULL),
(2061, 3, 113, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:26:29', NULL),
(2062, 4, 113, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:26:29', NULL),
(2063, 5, 113, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:26:29', NULL),
(2064, 6, 113, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:26:29', NULL),
(2065, 7, 113, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:26:29', NULL),
(2066, 8, 113, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:26:29', NULL);
INSERT INTO `organization_user_option` (`opt_id`, `option_name_ona_id`, `organization_org_id`, `department_dpt_id`, `position_pos_id`, `title_tit_id`, `user_usr_id`, `opt_bool_value`, `opt_int_value`, `opt_int_value_2`, `opt_float_value`, `opt_string_value`, `opt_enabled`, `opt_created_by`, `opt_inserted`, `org_role`) VALUES
(2067, 9, 113, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:26:29', NULL),
(2068, 10, 113, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:26:29', NULL),
(2069, 11, 113, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:26:29', NULL),
(2070, 12, 113, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2021-01-20 10:26:29', 1),
(2071, 12, 113, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2021-01-20 10:26:29', 2),
(2072, 12, 113, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2021-01-20 10:26:29', 3),
(2073, 1, 114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:36:37', NULL),
(2074, 2, 114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2021-01-20 10:36:37', NULL),
(2075, 3, 114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:36:37', NULL),
(2076, 4, 114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:36:37', NULL),
(2077, 5, 114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:36:37', NULL),
(2078, 6, 114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:36:37', NULL),
(2079, 7, 114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:36:37', NULL),
(2080, 8, 114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:36:37', NULL),
(2081, 9, 114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:36:37', NULL),
(2082, 10, 114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:36:37', NULL),
(2083, 11, 114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-20 10:36:37', NULL),
(2084, 12, 114, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2021-01-20 10:36:37', 1),
(2085, 12, 114, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2021-01-20 10:36:37', 2),
(2086, 12, 114, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2021-01-20 10:36:37', 3),
(2395, 1, 137, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-22 11:42:17', NULL),
(2396, 2, 137, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2021-01-22 11:42:17', NULL),
(2397, 3, 137, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-22 11:42:17', NULL),
(2398, 4, 137, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-22 11:42:17', NULL),
(2399, 5, 137, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-22 11:42:17', NULL),
(2400, 6, 137, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-22 11:42:17', NULL),
(2401, 7, 137, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-22 11:42:17', NULL),
(2402, 8, 137, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-22 11:42:17', NULL),
(2403, 9, 137, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-22 11:42:17', NULL),
(2404, 10, 137, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-22 11:42:17', NULL),
(2405, 11, 137, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-22 11:42:17', NULL),
(2406, 12, 137, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2021-01-22 11:42:17', 1),
(2407, 12, 137, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2021-01-22 11:42:17', 2),
(2408, 12, 137, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2021-01-22 11:42:17', 3),
(2409, 1, 138, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-24 10:06:38', NULL),
(2410, 2, 138, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2021-01-24 10:06:38', NULL),
(2411, 3, 138, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-24 10:06:38', NULL),
(2412, 4, 138, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-24 10:06:38', NULL),
(2413, 5, 138, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-24 10:06:38', NULL),
(2414, 6, 138, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-24 10:06:38', NULL),
(2415, 7, 138, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-24 10:06:38', NULL),
(2416, 8, 138, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-24 10:06:38', NULL),
(2417, 9, 138, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-24 10:06:38', NULL),
(2418, 10, 138, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-24 10:06:38', NULL),
(2419, 11, 138, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-24 10:06:38', NULL),
(2420, 12, 138, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2021-01-24 10:06:38', 1),
(2421, 12, 138, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2021-01-24 10:06:38', 2),
(2422, 12, 138, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2021-01-24 10:06:38', 3),
(2423, 1, 139, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-29 11:05:12', NULL),
(2424, 2, 139, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2021-01-29 11:05:12', NULL),
(2425, 3, 139, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-29 11:05:12', NULL),
(2426, 4, 139, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-29 11:05:12', NULL),
(2427, 5, 139, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-29 11:05:12', NULL),
(2428, 6, 139, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-29 11:05:12', NULL),
(2429, 7, 139, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-29 11:05:12', NULL),
(2430, 8, 139, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-29 11:05:12', NULL),
(2431, 9, 139, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-29 11:05:12', NULL),
(2432, 10, 139, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-29 11:05:12', NULL),
(2433, 11, 139, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-29 11:05:12', NULL),
(2434, 12, 139, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2021-01-29 11:05:12', 1),
(2435, 12, 139, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2021-01-29 11:05:12', 2),
(2436, 12, 139, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2021-01-29 11:05:12', 3),
(2479, 1, 145, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:58:28', NULL),
(2480, 2, 145, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2021-01-31 10:58:28', NULL),
(2481, 3, 145, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:58:28', NULL),
(2482, 4, 145, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:58:28', NULL),
(2483, 5, 145, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:58:28', NULL),
(2484, 6, 145, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:58:28', NULL),
(2485, 7, 145, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:58:28', NULL),
(2486, 8, 145, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:58:28', NULL),
(2487, 9, 145, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:58:28', NULL),
(2488, 10, 145, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:58:28', NULL),
(2489, 11, 145, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:58:28', NULL),
(2490, 12, 145, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2021-01-31 10:58:28', 1),
(2491, 12, 145, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2021-01-31 10:58:28', 2),
(2492, 12, 145, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2021-01-31 10:58:28', 3),
(2493, 1, 146, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:59:22', NULL),
(2494, 2, 146, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2021-01-31 10:59:22', NULL),
(2495, 3, 146, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:59:22', NULL),
(2496, 4, 146, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:59:22', NULL),
(2497, 5, 146, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:59:22', NULL),
(2498, 6, 146, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:59:22', NULL),
(2499, 7, 146, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:59:22', NULL),
(2500, 8, 146, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:59:22', NULL),
(2501, 9, 146, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:59:22', NULL),
(2502, 10, 146, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:59:22', NULL),
(2503, 11, 146, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-01-31 10:59:22', NULL),
(2504, 12, 146, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2021-01-31 10:59:22', 1),
(2505, 12, 146, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2021-01-31 10:59:22', 2),
(2506, 12, 146, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2021-01-31 10:59:22', 3),
(2507, 1, 147, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 14:33:52', NULL),
(2508, 2, 147, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2021-02-02 14:33:52', NULL),
(2509, 3, 147, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 14:33:52', NULL),
(2510, 4, 147, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 14:33:52', NULL),
(2511, 5, 147, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 14:33:52', NULL),
(2512, 6, 147, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 14:33:52', NULL),
(2513, 7, 147, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 14:33:52', NULL),
(2514, 8, 147, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 14:33:52', NULL),
(2515, 9, 147, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 14:33:52', NULL),
(2516, 10, 147, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 14:33:52', NULL),
(2517, 11, 147, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 14:33:52', NULL),
(2518, 12, 147, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2021-02-02 14:33:52', 2),
(2519, 12, 147, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2021-02-02 14:33:52', 3),
(2520, 12, 147, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2021-02-02 14:33:52', 4),
(2521, 1, 148, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 16:12:59', NULL),
(2522, 2, 148, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2021-02-02 16:12:59', NULL),
(2523, 3, 148, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 16:12:59', NULL),
(2524, 4, 148, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 16:12:59', NULL),
(2525, 5, 148, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 16:12:59', NULL),
(2526, 6, 148, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 16:12:59', NULL),
(2527, 7, 148, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 16:12:59', NULL),
(2528, 8, 148, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 16:12:59', NULL),
(2529, 9, 148, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 16:12:59', NULL),
(2530, 10, 148, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 16:12:59', NULL),
(2531, 11, 148, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-02-02 16:12:59', NULL),
(2532, 12, 148, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 'none', 1, NULL, '2021-02-02 16:12:59', 2),
(2533, 12, 148, NULL, NULL, NULL, NULL, 1, 2, 2, 0, 'owner', 1, NULL, '2021-02-02 16:12:59', 3),
(2534, 12, 148, NULL, NULL, NULL, NULL, 0, 3, 3, 0, 'participant', 1, NULL, '2021-02-02 16:12:59', 4);

-- --------------------------------------------------------

--
-- Structure de la table `otpuser`
--

CREATE TABLE `otpuser` (
  `otp_id` int(11) NOT NULL,
  `otp_organization` int(11) NOT NULL,
  `otp_type` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_fullname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_tipe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_created_by` int(11) DEFAULT NULL,
  `otp_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `output`
--

CREATE TABLE `output` (
  `otp_id` int(11) NOT NULL,
  `stage_stg_id` int(11) NOT NULL,
  `survey_sur_id` int(11) DEFAULT NULL,
  `otp_startdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `otp_enddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `otp_created_by` int(11) DEFAULT NULL,
  `otp_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `otp_type` int(11) DEFAULT NULL,
  `otp_visibility` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `output`
--

INSERT INTO `output` (`otp_id`, `stage_stg_id`, `survey_sur_id`, `otp_startdate`, `otp_enddate`, `otp_created_by`, `otp_inserted`, `otp_type`, `otp_visibility`) VALUES
(1, 2, NULL, '2020-01-01 00:09:00', '2020-09-17 13:20:24', NULL, '2020-09-17 13:20:24', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `participation`
--

CREATE TABLE `participation` (
  `par_id` int(11) NOT NULL,
  `team_tea_id` int(11) DEFAULT NULL,
  `activity_act_id` int(11) NOT NULL,
  `stage_stg_id` int(11) NOT NULL,
  `criterion_crt_id` int(11) DEFAULT NULL,
  `survey_sur_id` int(11) DEFAULT NULL,
  `user_usr_id` int(11) DEFAULT NULL,
  `external_user_ext_usr_id` int(11) DEFAULT NULL,
  `par_status` int(11) DEFAULT NULL,
  `par_leader` tinyint(1) DEFAULT NULL,
  `par_type` int(11) DEFAULT NULL,
  `par_mWeight` double DEFAULT NULL,
  `par_precomment` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `par_ivp_bonus` double DEFAULT NULL,
  `par_ivp_penalty` double DEFAULT NULL,
  `par_of_bonus` double DEFAULT NULL,
  `par_of_penalty` double DEFAULT NULL,
  `par_mailed` tinyint(1) DEFAULT NULL,
  `par_created_by` int(11) DEFAULT NULL,
  `par_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `par_confirmed` datetime DEFAULT NULL,
  `par_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `participation`
--

INSERT INTO `participation` (`par_id`, `team_tea_id`, `activity_act_id`, `stage_stg_id`, `criterion_crt_id`, `survey_sur_id`, `user_usr_id`, `external_user_ext_usr_id`, `par_status`, `par_leader`, `par_type`, `par_mWeight`, `par_precomment`, `par_ivp_bonus`, `par_ivp_penalty`, `par_of_bonus`, `par_of_penalty`, `par_mailed`, `par_created_by`, `par_inserted`, `par_confirmed`, `par_deleted`) VALUES
(18, NULL, 5, 6, NULL, NULL, 1, NULL, 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2020-08-25 21:17:49', NULL, NULL),
(19, NULL, 2, 2, NULL, NULL, 56, 18, 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2020-08-31 21:16:15', NULL, NULL),
(22, NULL, 5, 6, NULL, NULL, 58, 28, 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2020-08-31 22:25:45', NULL, NULL),
(23, NULL, 1, 1, NULL, NULL, 59, 33, 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2020-08-31 22:31:13', NULL, NULL),
(29, NULL, 16, 18, NULL, NULL, 58, 28, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-09-26 20:25:01', NULL, NULL),
(30, NULL, 16, 18, NULL, NULL, 1, NULL, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-09-26 20:25:01', NULL, NULL),
(35, NULL, 19, 21, NULL, NULL, 1, NULL, 0, 1, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-09-30 19:23:38', NULL, NULL),
(36, NULL, 19, 21, NULL, NULL, 91, 107, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-09-30 19:23:38', NULL, NULL),
(37, NULL, 20, 22, NULL, NULL, 1, NULL, 0, 1, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-10-06 16:48:10', NULL, NULL),
(38, NULL, 20, 22, NULL, NULL, 8, 41, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-10-06 16:48:11', NULL, NULL),
(67, NULL, 35, 37, NULL, NULL, 104, NULL, 0, 1, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-10-14 08:05:18', NULL, NULL),
(68, NULL, 35, 37, NULL, NULL, 109, 124, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-10-14 08:05:18', NULL, NULL),
(73, NULL, 40, 42, NULL, NULL, 118, NULL, 0, 1, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-10-14 15:45:29', NULL, NULL),
(90, NULL, 41, 43, NULL, NULL, 118, NULL, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-10-24 20:18:02', NULL, NULL),
(92, NULL, 41, 43, NULL, NULL, 125, 138, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-10-24 20:44:19', NULL, NULL),
(103, NULL, 47, 49, NULL, NULL, 128, NULL, 0, 1, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-10-27 14:19:44', NULL, NULL),
(104, NULL, 47, 49, NULL, NULL, 145, 222, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-10-27 14:19:44', NULL, NULL),
(272, NULL, 132, 134, NULL, NULL, 128, NULL, 0, 1, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 128, '2020-11-11 09:39:02', NULL, NULL),
(273, NULL, 132, 134, NULL, NULL, 145, 222, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 128, '2020-11-11 09:39:02', NULL, NULL),
(280, NULL, 136, 138, NULL, NULL, 128, NULL, 0, 1, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 128, '2020-11-11 23:51:35', NULL, NULL),
(281, NULL, 136, 138, NULL, NULL, 145, 222, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 128, '2020-11-11 23:51:35', NULL, NULL),
(282, NULL, 137, 139, NULL, NULL, 145, NULL, 0, 1, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 145, '2020-11-14 13:08:55', NULL, NULL),
(283, NULL, 137, 139, NULL, NULL, 128, 223, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 145, '2020-11-14 13:08:55', NULL, NULL),
(284, NULL, 138, 140, NULL, NULL, 128, NULL, 0, 1, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 128, '2020-11-20 15:24:06', NULL, NULL),
(286, NULL, 138, 140, NULL, NULL, 151, 258, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-11-20 17:01:29', NULL, NULL),
(287, NULL, 138, 140, NULL, NULL, 153, 262, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-11-20 17:05:37', NULL, NULL),
(288, NULL, 139, 141, NULL, NULL, 161, NULL, 0, 1, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 161, '2020-11-30 19:08:43', NULL, NULL),
(289, NULL, 139, 141, NULL, NULL, 128, 265, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 161, '2020-11-30 19:08:43', NULL, NULL),
(290, NULL, 20, 22, NULL, NULL, 199, 269, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-05 14:07:47', NULL, NULL),
(292, NULL, 20, 22, NULL, NULL, 215, 271, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-10 14:38:31', NULL, NULL),
(294, NULL, 1, 1, NULL, NULL, 213, NULL, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-17 15:44:58', NULL, NULL),
(298, NULL, 143, 145, NULL, NULL, 1, NULL, 0, 1, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 1, '2020-12-21 10:28:15', NULL, NULL),
(306, NULL, 20, 22, NULL, NULL, 61, 40, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2021-01-14 17:04:07', NULL, NULL),
(314, NULL, 20, 22, NULL, NULL, 210, 338, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2021-01-18 16:16:51', NULL, NULL),
(329, NULL, 152, 154, NULL, NULL, 223, NULL, 0, 1, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 223, '2021-01-25 09:50:28', NULL, NULL),
(330, NULL, 152, 154, NULL, NULL, 226, 446, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 223, '2021-01-25 09:50:28', NULL, NULL),
(332, NULL, 152, 154, NULL, NULL, 199, 442, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2021-01-25 17:39:04', NULL, NULL),
(347, NULL, 152, 154, NULL, NULL, 300, 486, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2021-01-31 23:26:26', NULL, NULL),
(352, NULL, 152, 154, NULL, NULL, 109, 494, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2021-02-02 11:38:22', NULL, NULL),
(353, NULL, 153, 155, NULL, NULL, 223, NULL, 0, 1, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 223, '2021-02-02 16:11:06', NULL, NULL),
(354, NULL, 153, 155, NULL, NULL, 304, 499, 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, 223, '2021-02-02 16:11:06', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `position`
--

CREATE TABLE `position` (
  `pos_id` int(11) NOT NULL,
  `organization_org_id` int(11) NOT NULL,
  `department_dpt_id` int(11) DEFAULT NULL,
  `weight_wgt_id` int(11) DEFAULT NULL,
  `pos_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pos_created_by` int(11) DEFAULT NULL,
  `pos_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pos_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `position`
--

INSERT INTO `position` (`pos_id`, `organization_org_id`, `department_dpt_id`, `weight_wgt_id`, `pos_name`, `pos_created_by`, `pos_inserted`, `pos_deleted`) VALUES
(1, 1, NULL, NULL, 'Chef', 1, '2020-08-31 11:12:07', NULL),
(3, 25, NULL, NULL, 'CEO', NULL, '2020-09-03 18:53:55', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `process`
--

CREATE TABLE `process` (
  `pro_id` int(11) NOT NULL,
  `organization_org_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `icon_ico_id` int(11) DEFAULT NULL,
  `pro_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pro_approvable` tinyint(1) DEFAULT NULL,
  `pro_gradable` tinyint(1) DEFAULT NULL,
  `pro_created_by` int(11) DEFAULT NULL,
  `pro_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pro_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `process`
--

INSERT INTO `process` (`pro_id`, `organization_org_id`, `parent_id`, `icon_ico_id`, `pro_name`, `pro_approvable`, `pro_gradable`, `pro_created_by`, `pro_inserted`, `pro_deleted`) VALUES
(1, 1, NULL, NULL, 'Relations contractuelles', 0, 1, NULL, '2020-09-04 15:33:33', NULL),
(2, 1, 1, NULL, 'Test - POC', 0, 1, NULL, '2020-09-10 22:28:52', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `process_criterion`
--

CREATE TABLE `process_criterion` (
  `crt_id` int(11) NOT NULL,
  `process_stage_stg_id` int(11) DEFAULT NULL,
  `process_pro_id` int(11) DEFAULT NULL,
  `criterion_name_cna_id` int(11) DEFAULT NULL,
  `crt_type` int(11) DEFAULT NULL,
  `crt_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crt_weight` double DEFAULT NULL,
  `crt_forceComment_compare` tinyint(1) DEFAULT NULL,
  `crt_forceComment_value` double DEFAULT NULL,
  `crt_forceComment_sign` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crt_lowerbound` double DEFAULT NULL,
  `crt_upperbound` double DEFAULT NULL,
  `crt_step` double DEFAULT NULL,
  `crt_comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crt_created_by` int(11) DEFAULT NULL,
  `crt_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `process_stage`
--

CREATE TABLE `process_stage` (
  `stg_id` int(11) NOT NULL,
  `process_pro_id` int(11) NOT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `stg_master_usr_id` int(11) NOT NULL,
  `stg_complete` tinyint(1) DEFAULT NULL,
  `stg_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_visibility` int(11) DEFAULT NULL,
  `stg_definite_dates` tinyint(1) DEFAULT NULL,
  `stg_status` double DEFAULT NULL,
  `stg_desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_progress` double DEFAULT NULL,
  `stg_weight` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_dperiod` int(11) DEFAULT NULL,
  `stg_dfrequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_dorigin` int(11) DEFAULT NULL,
  `stg_fperiod` int(11) DEFAULT NULL,
  `stg_ffrequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_forigin` int(11) DEFAULT NULL,
  `stg_startdate` datetime DEFAULT NULL,
  `stg_enddated` datetime DEFAULT NULL,
  `stg_dealine_nbDays` int(11) DEFAULT NULL,
  `stg_deadline_mailSent` tinyint(1) DEFAULT NULL,
  `stg_created_by` int(11) DEFAULT NULL,
  `stg_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stg_isFinalized` datetime DEFAULT NULL,
  `stg_finalized` datetime NOT NULL,
  `stg_deleted` datetime DEFAULT NULL,
  `stg_dcompleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ranking`
--

CREATE TABLE `ranking` (
  `rnk_id` int(11) NOT NULL,
  `rnk_activity` int(11) NOT NULL,
  `rnk_stage` int(11) DEFAULT NULL,
  `rnk_criterion` int(11) DEFAULT NULL,
  `rnk_organization` int(11) NOT NULL,
  `rnk_user_usr_id` int(11) DEFAULT NULL,
  `rnk_dtype` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rnk_wtype` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rnk_abs_result` int(11) DEFAULT NULL,
  `rnk_rel_result` double DEFAULT NULL,
  `rnk_period` int(11) DEFAULT NULL,
  `rnk_freq` int(11) DEFAULT NULL,
  `rnk_value` double DEFAULT NULL,
  `rnk_series_pop` int(11) DEFAULT NULL,
  `rnk_created_by` int(11) DEFAULT NULL,
  `rnk_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rnk_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ranking_history`
--

CREATE TABLE `ranking_history` (
  `rkh_id` int(11) NOT NULL,
  `rkh_activity` int(11) NOT NULL,
  `rkh_stage` int(11) DEFAULT NULL,
  `rkh_criterion` int(11) DEFAULT NULL,
  `rkh_user_usr_id` int(11) DEFAULT NULL,
  `rkh_wtype` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rkh_abs_result` int(11) DEFAULT NULL,
  `rkh_rel_result` double DEFAULT NULL,
  `rkh_period` int(11) DEFAULT NULL,
  `rkh_freq` int(11) DEFAULT NULL,
  `rkh_value` double DEFAULT NULL,
  `rkh_series_pop` int(11) DEFAULT NULL,
  `rkh_createdBy` int(11) DEFAULT NULL,
  `rkh_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ranking_team`
--

CREATE TABLE `ranking_team` (
  `rkt_id` int(11) NOT NULL,
  `rkt_activity` int(11) NOT NULL,
  `rkt_stage` int(11) DEFAULT NULL,
  `rkt_criterion` int(11) DEFAULT NULL,
  `team_tea_id` int(11) NOT NULL,
  `rkt_organization` int(11) NOT NULL,
  `rkt_dtype` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rkt_wtype` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rkt_abs_result` int(11) DEFAULT NULL,
  `rkt_rel_result` double DEFAULT NULL,
  `rkt_period` int(11) DEFAULT NULL,
  `rkt_freq` int(11) DEFAULT NULL,
  `rkt_value` double DEFAULT NULL,
  `rkt_series_pop` int(11) DEFAULT NULL,
  `rkt_created_by` int(11) DEFAULT NULL,
  `rkt_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rkt_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ranking_team_history`
--

CREATE TABLE `ranking_team_history` (
  `rth_id` int(11) NOT NULL,
  `rth_activity` int(11) DEFAULT NULL,
  `rth_stage` int(11) DEFAULT NULL,
  `rth_criterion` int(11) DEFAULT NULL,
  `team_tea_id` int(11) DEFAULT NULL,
  `rth_organization` int(11) NOT NULL,
  `rth_dtype` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rth_wtype` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rth_abs_result` int(11) DEFAULT NULL,
  `rth_rel_result` double DEFAULT NULL,
  `rth_period` int(11) DEFAULT NULL,
  `rth_freq` int(11) DEFAULT NULL,
  `rth_value` double DEFAULT NULL,
  `rth_series_pop` int(11) DEFAULT NULL,
  `rth_created_by` int(11) DEFAULT NULL,
  `rth_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `record`
--

CREATE TABLE `record` (
  `rec_entity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rec_table_id` int(11) DEFAULT NULL,
  `rec_property` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rec_old` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rec_new` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rec_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rec_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `recurring`
--

CREATE TABLE `recurring` (
  `rct_id` int(11) NOT NULL,
  `organization_org_id` int(11) NOT NULL,
  `rec_master_user_id` int(11) DEFAULT NULL,
  `rct_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rct_status` int(11) DEFAULT NULL,
  `rct_timeframe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rct_freq` int(11) DEFAULT NULL,
  `rct_gsd_interval` int(11) DEFAULT NULL,
  `rct_gsd_timeframe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rct_ged_interval` int(11) DEFAULT NULL,
  `rct_ged_timeframe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rct_type` int(11) DEFAULT NULL,
  `rct_lowerbound` double DEFAULT NULL,
  `rct_upperbound` double DEFAULT NULL,
  `rct_step` double DEFAULT NULL,
  `rct_opend_end` tinyint(1) DEFAULT NULL,
  `rct_startdate` datetime DEFAULT NULL,
  `rct_enddate` datetime DEFAULT NULL,
  `rct_same_part` tinyint(1) DEFAULT NULL,
  `rct_created_by` int(11) DEFAULT NULL,
  `rct_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rct_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `result`
--

CREATE TABLE `result` (
  `res_id` int(11) NOT NULL,
  `activity_act_id` int(11) DEFAULT NULL,
  `stage_stg_id` int(11) DEFAULT NULL,
  `criterion_crt_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `res_type` int(11) DEFAULT NULL,
  `res_war` double DEFAULT NULL,
  `res_ear` double DEFAULT NULL,
  `res_wrr` double DEFAULT NULL,
  `res_err` double DEFAULT NULL,
  `res_wsd` double DEFAULT NULL,
  `res_esd` double DEFAULT NULL,
  `res_wdr` double DEFAULT NULL,
  `res_edr` double DEFAULT NULL,
  `res_wsd_max` double NOT NULL,
  `res_esd_max` double NOT NULL,
  `res_win` double NOT NULL,
  `res_ein` double NOT NULL,
  `res_win_max` double NOT NULL,
  `res_ein_max` double NOT NULL,
  `res_wdr_gen` double NOT NULL,
  `res_edr_gen` double NOT NULL,
  `res_created_by` int(11) DEFAULT NULL,
  `res_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `externalUser_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `result_project`
--

CREATE TABLE `result_project` (
  `rsp_id` int(11) NOT NULL,
  `activity_act_id` int(11) DEFAULT NULL,
  `stage_stg_id` int(11) DEFAULT NULL,
  `criterion_crt_id` int(11) DEFAULT NULL,
  `rsp_type` int(11) DEFAULT NULL,
  `rsp_war` double DEFAULT NULL,
  `rsp_ear` double DEFAULT NULL,
  `rsp_wrr` double DEFAULT NULL,
  `rsp_err` double DEFAULT NULL,
  `rsp_wsd` double DEFAULT NULL,
  `rsp_esd` double DEFAULT NULL,
  `rsp_wdr` double DEFAULT NULL,
  `rsp_edr` double DEFAULT NULL,
  `rsp_wsd_max` double NOT NULL,
  `rsp_esd_max` double NOT NULL,
  `rsp_win` double NOT NULL,
  `rsp_ein` double NOT NULL,
  `rsp_win_max` double NOT NULL,
  `rsp_ein_max` double NOT NULL,
  `rsp_wdr_gen` double NOT NULL,
  `rsp_edr_gen` double NOT NULL,
  `rsp_createdBy` int(11) DEFAULT NULL,
  `rsp_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `result_team`
--

CREATE TABLE `result_team` (
  `rst_id` int(11) NOT NULL,
  `activity_act_id` int(11) DEFAULT NULL,
  `stage_stg_id` int(11) DEFAULT NULL,
  `criterion_crt_id` int(11) DEFAULT NULL,
  `team_tea_id` int(11) DEFAULT NULL,
  `rst_type` int(11) DEFAULT NULL,
  `rst_war` double DEFAULT NULL,
  `rst_ear` double DEFAULT NULL,
  `rst_wrr` double DEFAULT NULL,
  `rst_err` double DEFAULT NULL,
  `rst_wsd` double DEFAULT NULL,
  `rst_esd` double DEFAULT NULL,
  `rst_wdr` double DEFAULT NULL,
  `rst_edr` double DEFAULT NULL,
  `rst_wsd_max` double NOT NULL,
  `rst_esd_max` double NOT NULL,
  `rst_win` double NOT NULL,
  `rst_ein` double NOT NULL,
  `rst_win_max` double NOT NULL,
  `rst_ein_max` double NOT NULL,
  `rst_wdr_gen` double NOT NULL,
  `rst_edr_gen` double NOT NULL,
  `rst_createdBy` int(11) NOT NULL,
  `rst_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stage`
--

CREATE TABLE `stage` (
  `stg_id` int(11) NOT NULL,
  `survey_sur_id` int(11) DEFAULT NULL,
  `activity_act_id` int(11) NOT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `stg_complete` tinyint(1) DEFAULT NULL,
  `stg_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_mode` int(11) DEFAULT NULL,
  `stG_visibility` int(11) DEFAULT NULL,
  `stg_access_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_status` int(11) DEFAULT NULL,
  `stg_desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_progress` int(11) DEFAULT NULL,
  `stg_weight` double DEFAULT NULL,
  `stg_definite_dates` tinyint(1) DEFAULT NULL,
  `stg_dperiod` int(11) DEFAULT NULL,
  `stg_dfrequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stg_dorigin` int(11) DEFAULT NULL,
  `stg_fperiod` int(11) DEFAULT NULL,
  `stg_ffrequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `f_origin` int(11) DEFAULT NULL,
  `stg_startdate` datetime DEFAULT NULL,
  `stg_enddate` datetime DEFAULT NULL,
  `stg_dealine_nb_days` int(11) DEFAULT NULL,
  `stg_deadline_mailSent` tinyint(1) DEFAULT NULL,
  `stg_created_by` int(11) DEFAULT NULL,
  `stg_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stg_reopened` tinyint(1) DEFAULT NULL,
  `stg_last_reopened` datetime DEFAULT NULL,
  `stg_unstarted_notif` tinyint(1) DEFAULT NULL,
  `stg_uncompleted_notif` tinyint(1) DEFAULT NULL,
  `stg_unfinished_notif` tinyint(1) DEFAULT NULL,
  `stg_isFinalized` tinyint(1) DEFAULT NULL,
  `stg_finalized` datetime DEFAULT NULL,
  `stg_deleted` datetime DEFAULT NULL,
  `stg_gcompleted` datetime DEFAULT NULL,
  `stg_invit_closed` datetime DEFAULT NULL,
  `stg_invit_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stage`
--

INSERT INTO `stage` (`stg_id`, `survey_sur_id`, `activity_act_id`, `organization_org_id`, `stg_complete`, `stg_name`, `stg_mode`, `stG_visibility`, `stg_access_link`, `stg_status`, `stg_desc`, `stg_progress`, `stg_weight`, `stg_definite_dates`, `stg_dperiod`, `stg_dfrequency`, `stg_dorigin`, `stg_fperiod`, `stg_ffrequency`, `f_origin`, `stg_startdate`, `stg_enddate`, `stg_dealine_nb_days`, `stg_deadline_mailSent`, `stg_created_by`, `stg_inserted`, `stg_reopened`, `stg_last_reopened`, `stg_unstarted_notif`, `stg_uncompleted_notif`, `stg_unfinished_notif`, `stg_isFinalized`, `stg_finalized`, `stg_deleted`, `stg_gcompleted`, `stg_invit_closed`, `stg_invit_status`) VALUES
(1, NULL, 1, 1, 0, 'Test', 1, 3, 'hhhh', 0, NULL, 1, 1, 1, 15, 'D', 0, 7, 'D', 2, '2020-08-20 00:00:00', '2020-11-20 00:00:00', 3, NULL, 1, '2020-08-24 15:38:47', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(2, NULL, 2, 1, 0, 'Randonnée', 1, 3, NULL, 0, NULL, 1, 1, 1, 15, 'D', 0, 7, 'D', 2, '2020-08-31 00:00:00', '2020-09-30 00:00:00', 3, NULL, 1, '2020-08-24 16:11:54', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(6, NULL, 5, 1, 0, 'Obtention', 1, 3, NULL, 0, NULL, 1, 0.5, 1, 15, 'D', 0, 7, 'D', 2, '2020-06-01 00:00:00', '2020-06-24 00:00:00', 3, NULL, 1, '2020-08-25 21:11:58', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(7, NULL, 5, 1, 0, 'Remboursement - 1ère tranche', 1, 1, NULL, 0, NULL, 1, 0.5, 1, 15, 'D', 0, 7, 'D', 2, '2020-07-01 00:00:00', '2020-10-23 00:00:00', 3, NULL, NULL, '2020-08-31 21:41:17', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(18, NULL, 16, 1, 0, 'Reprise 3', 1, 3, NULL, 1, NULL, -1, 0, 1, 15, 'D', 0, 7, 'D', 2, '2020-09-26 00:00:00', '2020-10-23 00:00:00', 3, NULL, 1, '2020-09-26 20:25:01', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(21, NULL, 19, 1, 0, 'Evaluation 2020', 1, 3, NULL, 0, NULL, 1, 0, 1, 15, 'D', 0, 7, 'D', 2, '2021-01-05 00:00:00', '2021-01-20 00:00:00', 3, NULL, 1, '2020-09-30 19:23:38', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(22, NULL, 20, 1, 0, 'Livraison', 1, 3, NULL, 0, NULL, 0, 0, 1, 15, 'D', 0, 7, 'D', 2, '2020-10-19 00:00:00', '2020-12-22 00:00:00', 3, NULL, 1, '2020-10-06 16:48:10', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(37, NULL, 35, 49, 0, 'Livraison VR 2', 1, 3, NULL, 1, NULL, 1, 0, 1, 15, 'D', 0, 7, 'D', 2, '2020-10-14 00:00:00', '2020-10-29 00:00:00', 3, NULL, 104, '2020-10-14 08:05:17', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(42, NULL, 40, 56, 0, 'Activité avec moi', 1, 3, NULL, 1, NULL, 1, 0, 1, 15, 'D', 0, 7, 'D', 2, '2020-10-14 00:00:00', '2020-10-30 00:00:00', 3, NULL, 118, '2020-10-14 15:45:29', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(43, NULL, 41, 56, 0, 'The Office Contract', 1, 3, NULL, 1, NULL, 1, 0, 1, 15, 'D', 0, 7, 'D', 2, '2020-10-16 00:00:00', '2020-11-11 00:00:00', 3, NULL, 118, '2020-10-16 14:59:38', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(49, NULL, 47, 57, 0, 'Pot Commun', 1, 3, NULL, 1, NULL, -2, 0, 1, 15, 'D', 0, 7, 'D', 2, '2020-10-27 00:00:00', '2020-11-11 00:00:00', 3, NULL, 128, '2020-10-27 14:19:44', 1, '2020-10-28 10:27:47', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(134, NULL, 132, 57, 0, 'USA', 1, 3, NULL, 1, NULL, 1, 0, 1, 15, 'D', 0, 7, 'D', 2, '2020-11-11 00:00:00', '2020-12-16 00:00:00', 3, NULL, 128, '2020-11-11 09:39:02', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(138, NULL, 136, 57, 0, 'Top', 1, 3, NULL, 0, NULL, 0, 0, 1, 15, 'D', 0, 7, 'D', 2, '2020-11-12 00:00:00', '2020-11-26 00:00:00', 3, NULL, 128, '2020-11-11 23:51:35', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(139, NULL, 137, 64, 0, 'Clôture Q4', 1, 3, NULL, 0, NULL, 0, 0, 1, 15, 'D', 0, 7, 'D', 2, '2020-12-01 00:00:00', '2020-12-29 00:00:00', 3, NULL, 145, '2020-11-14 13:08:55', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(140, NULL, 138, 57, 0, 'Activité au long cours', 1, 3, NULL, 1, NULL, 1, 0, 1, 15, 'D', 0, 7, 'D', 2, '2020-11-20 00:00:00', '2022-11-17 00:00:00', 3, NULL, 128, '2020-11-20 15:24:06', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(141, NULL, 139, 72, 0, 'Test activité', 1, 3, NULL, 1, NULL, 1, 0, 1, 15, 'D', 0, 7, 'D', 2, '2020-11-30 00:00:00', '2020-12-24 00:00:00', 3, NULL, 161, '2020-11-30 19:08:43', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(145, NULL, 143, 1, 0, 'Stage ouvert', 1, 3, NULL, 1, NULL, 1, 0, 1, 15, 'D', 0, 7, 'D', 2, '2020-12-21 00:00:00', NULL, 3, NULL, 1, '2020-12-21 10:28:15', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(154, NULL, 152, 102, 0, 'Test avec Gertrude', 1, 3, '40c950b695714d0dceaf13dd07c20bde', 1, NULL, -3, 0, 1, 15, 'D', 0, 7, 'D', 2, '2021-01-07 00:00:00', NULL, 3, NULL, 223, '2021-01-25 09:50:28', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0),
(155, NULL, 153, 102, 0, 'Intégration David', 1, 3, '7d78097c5b80a375efde5eaaf21cfbde', 1, NULL, 1, 0, 1, 15, 'D', 0, 7, 'D', 2, '2021-02-01 00:00:00', NULL, 3, NULL, 223, '2021-02-02 16:11:06', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `state`
--

CREATE TABLE `state` (
  `sta_id` int(11) NOT NULL,
  `country_cou_id` int(11) DEFAULT NULL,
  `sta_abbr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sta_fullname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sta_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sta_createdBy` int(11) DEFAULT NULL,
  `sta_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `state`
--

INSERT INTO `state` (`sta_id`, `country_cou_id`, `sta_abbr`, `sta_fullname`, `sta_name`, `sta_createdBy`, `sta_inserted`) VALUES
(1, 129, NULL, NULL, 'Luxembourg', 1, '2020-09-29 13:42:39');

-- --------------------------------------------------------

--
-- Structure de la table `survey`
--

CREATE TABLE `survey` (
  `sur_id` int(11) NOT NULL,
  `stage_stg_id` int(11) DEFAULT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `sur_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sur_created_by` int(11) DEFAULT NULL,
  `sur_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sur_state` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `survey_field`
--

CREATE TABLE `survey_field` (
  `sfi_id` int(11) NOT NULL,
  `criterion_crt_id` int(11) NOT NULL,
  `survey_sur_id` int(11) NOT NULL,
  `sfi_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sfi_isMandatory` tinyint(1) DEFAULT NULL,
  `sfi_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sfi_position` int(11) DEFAULT NULL,
  `sfi_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sfi_created_by` int(11) DEFAULT NULL,
  `sfi_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sfi_upperbound` int(11) DEFAULT NULL,
  `sfi_lowerbound` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `survey_field_parameter`
--

CREATE TABLE `survey_field_parameter` (
  `sfp_id` int(11) NOT NULL,
  `survey_field_sfi_id` int(11) NOT NULL,
  `sfp_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sfp_lowerbound` double DEFAULT NULL,
  `sfp_upperbound` double DEFAULT NULL,
  `sfp_step` double DEFAULT NULL,
  `sfp_createdBy` int(11) DEFAULT NULL,
  `sfp_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `target`
--

CREATE TABLE `target` (
  `tgt_id` int(11) NOT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `department_dpt_id` int(11) DEFAULT NULL,
  `position_pos_id` int(11) DEFAULT NULL,
  `title_tit_id` int(11) DEFAULT NULL,
  `user_usr_id` int(11) DEFAULT NULL,
  `team_tea_id` int(11) DEFAULT NULL,
  `criterion_name_cna_id` int(11) DEFAULT NULL,
  `criterion_crt_id` int(11) DEFAULT NULL,
  `tgt_sign` int(11) DEFAULT NULL,
  `tgt_value` double DEFAULT NULL,
  `tgt_createdBy` int(11) DEFAULT NULL,
  `tgt_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `team`
--

CREATE TABLE `team` (
  `tea_id` int(11) NOT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `tea_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tea_weight_ini` double DEFAULT NULL,
  `tea_picture` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tea_created_by` int(11) DEFAULT NULL,
  `tea_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tea_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `title`
--

CREATE TABLE `title` (
  `tit_id` int(11) NOT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `weight_wgt_id` int(11) DEFAULT NULL,
  `tit_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tit_weight_ini` double DEFAULT NULL,
  `tit_created_by` int(11) DEFAULT NULL,
  `tit_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tit_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `usr_id` int(11) NOT NULL,
  `usr_superior` int(11) DEFAULT NULL,
  `worker_individual_win_id` int(11) DEFAULT NULL,
  `weight_wgt_id` int(11) DEFAULT NULL,
  `position_pos_id` int(11) DEFAULT NULL,
  `department_dpt_id` int(11) DEFAULT NULL,
  `title_tit_id` int(11) DEFAULT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `usr_int` tinyint(1) DEFAULT NULL,
  `usr_firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_birthdate` datetime DEFAULT NULL,
  `usr_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_position_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_weight_ini` double DEFAULT NULL,
  `usr_act_archive_nb_days` int(11) DEFAULT NULL,
  `usr_rm_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_validated` datetime DEFAULT NULL,
  `usr_enabledCreatingUser` tinyint(1) DEFAULT NULL,
  `usr_created_by` int(11) DEFAULT NULL,
  `usr_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usr_last_connected` datetime DEFAULT NULL,
  `usr_deleted` datetime DEFAULT NULL,
  `role_rol_id` int(11) DEFAULT NULL,
  `usr_synth` tinyint(1) DEFAULT NULL,
  `usr_alt_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_global_usg_id` int(11) DEFAULT NULL,
  `usr_sub_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`usr_id`, `usr_superior`, `worker_individual_win_id`, `weight_wgt_id`, `position_pos_id`, `department_dpt_id`, `title_tit_id`, `organization_org_id`, `usr_int`, `usr_firstname`, `usr_lastname`, `usr_username`, `usr_nickname`, `usr_birthdate`, `usr_email`, `usr_password`, `usr_position_name`, `usr_picture`, `usr_token`, `usr_weight_ini`, `usr_act_archive_nb_days`, `usr_rm_token`, `usr_validated`, `usr_enabledCreatingUser`, `usr_created_by`, `usr_inserted`, `usr_last_connected`, `usr_deleted`, `role_rol_id`, `usr_synth`, `usr_alt_email`, `user_global_usg_id`, `usr_sub_id`) VALUES
(1, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, 'Guillaume', 'Chatelain', 'Guillaume Chatelain', '', NULL, 'gchatelain@yopmail.com', '$2y$12$wezt2XSJHHO39OO1kakQZe/.lQZXjkXOcihDx8xSOp43CLFEI3BZ2', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-08-24 15:34:09', '2021-02-04 09:32:26', NULL, 0, NULL, NULL, 1, NULL),
(2, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, 'Guillaume', 'dBdG', 'Guillaume dBdG', '', NULL, 'gdbdg@yopmail.com', '$2y$12$HDvA5F7ACl5EmPuMc1QS6eJ4KxFQGN0XvtmCIK0yo.DOKu4DUgINe', NULL, NULL, '', NULL, 7, NULL, NULL, NULL, NULL, '2020-08-24 15:34:09', NULL, NULL, 4, NULL, NULL, 2, NULL),
(3, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, 'Steve', 'Jobs', 'Steve Jobs', '', NULL, 'sjobs@yopmail.com', '$2y$12$3jZhgm2vDoABvWRBG11AIeavGr2B5Qz0eKoEPRNeg9wbeZTdiUgpe', NULL, NULL, '', NULL, 7, NULL, NULL, NULL, NULL, '2020-08-24 15:34:09', NULL, NULL, 3, NULL, NULL, 3, NULL),
(8, NULL, NULL, NULL, NULL, NULL, NULL, 13, 1, 'ZZ', 'ZZ', NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, 7, NULL, NULL, NULL, NULL, '2020-08-27 14:12:20', NULL, NULL, 4, 1, NULL, NULL, NULL),
(51, NULL, NULL, NULL, NULL, NULL, NULL, 13, 1, 'Pierre', 'Legrand', 'Pierre Legrand', '', NULL, 'p.legrand@yopmail.com', '$2y$12$eqmXz/8/1r9dxMTnKzNfoOl4Lm.gZ5vt.WACNIESZeM62UKO8BvSG', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, 1, '2020-08-27 17:34:40', '2020-08-27 19:44:59', NULL, 4, NULL, NULL, 4, NULL),
(53, NULL, NULL, 1, 1, 1, NULL, 1, 1, 'Pierre', 'Laroche', 'Pierre Laroche', '', NULL, 'p.laroche@yopmail.com', '', NULL, NULL, '2dd057bdaa424867be7314b603c95f8a', 100, 7, NULL, NULL, NULL, 1, '2020-08-31 11:16:01', NULL, NULL, 3, NULL, NULL, 5, NULL),
(56, NULL, NULL, NULL, NULL, NULL, NULL, 16, 1, 'ZZ', 'ZZ', NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, 7, NULL, NULL, NULL, NULL, '2020-08-31 19:22:34', NULL, NULL, 4, 1, NULL, NULL, NULL),
(57, NULL, NULL, NULL, NULL, NULL, NULL, 18, 1, 'ZZ', 'ZZ', NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, 7, NULL, NULL, NULL, NULL, '2020-08-31 21:42:02', NULL, NULL, 4, 1, NULL, NULL, NULL),
(58, NULL, NULL, NULL, NULL, NULL, NULL, 19, 1, 'ZZ', 'ZZ', NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, 7, NULL, NULL, NULL, NULL, '2020-08-31 22:24:40', NULL, NULL, 4, 1, NULL, NULL, NULL),
(59, NULL, NULL, NULL, NULL, NULL, NULL, 20, 1, 'ZZ', 'ZZ', NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, 7, NULL, NULL, NULL, NULL, '2020-08-31 22:30:19', NULL, NULL, 4, 1, NULL, NULL, NULL),
(60, NULL, NULL, NULL, NULL, NULL, NULL, 21, 1, 'ZZ', 'ZZ', NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, 7, NULL, NULL, NULL, NULL, '2020-09-02 12:38:23', NULL, NULL, 4, 1, NULL, NULL, NULL),
(61, NULL, NULL, NULL, NULL, NULL, NULL, 21, 1, 'Floriane', 'Moutet', 'Floriane Moutet', '', NULL, 'f.moutet@yopmail.com', '', NULL, NULL, '9e5c8f21ef4663f63b2ac0d88d67f4d4', NULL, 7, NULL, NULL, NULL, 1, '2020-09-02 12:39:43', NULL, NULL, 4, NULL, NULL, 6, NULL),
(65, NULL, NULL, NULL, NULL, NULL, NULL, 25, 1, 'ZZ', 'ZZ', NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, 7, NULL, NULL, NULL, NULL, '2020-09-03 18:53:55', NULL, NULL, 4, 1, NULL, NULL, NULL),
(66, NULL, NULL, 15, 3, 3, NULL, 25, 1, 'Bruno', 'Esposito', 'Bruno Esposito', '', NULL, 'b.esposito@yopmail.com', '$2y$12$kRaUgxFZuR37BIH3LdiY4.1ZnFnbd8vLwVZSnIp47CSlGGdfEw2mO', NULL, NULL, '', 100, 7, NULL, NULL, NULL, NULL, '2020-09-03 18:53:55', '2020-09-03 21:16:34', NULL, 2, NULL, NULL, 7, NULL),
(68, NULL, NULL, NULL, NULL, NULL, NULL, 13, 1, 'Pierre', 'Laroche', NULL, NULL, NULL, 'p.laroche@yopmail.com', NULL, NULL, NULL, 'cd53eaef417823a9fab6e58665dfc3c6', NULL, 7, NULL, NULL, NULL, 1, '2020-09-25 14:41:47', NULL, NULL, 4, NULL, NULL, 8, NULL),
(70, NULL, NULL, NULL, NULL, NULL, NULL, 25, 1, 'ZZ', 'Serpico', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-09-27 20:56:19', NULL, NULL, 4, 1, NULL, NULL, NULL),
(71, NULL, NULL, NULL, NULL, NULL, NULL, 25, 1, 'Mathias', 'Keune', 'Mathias Keune', NULL, NULL, 'moien.keune@yopmail.com', NULL, NULL, NULL, '49c2ec39c7666b6baf4dee7a426a4451', NULL, 7, NULL, NULL, NULL, 1, '2020-09-27 20:56:19', NULL, NULL, NULL, NULL, NULL, 9, NULL),
(72, NULL, NULL, NULL, NULL, NULL, NULL, 16, 1, 'Thomas', 'Reicher', 'Thomas Reicher', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, 1, '2020-09-28 08:24:01', NULL, NULL, NULL, NULL, NULL, 10, NULL),
(76, NULL, NULL, NULL, NULL, NULL, NULL, 32, 1, 'ZZ', 'Serpico', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-09-30 08:18:03', NULL, NULL, 4, 1, NULL, NULL, NULL),
(78, NULL, NULL, NULL, NULL, NULL, NULL, 32, 1, 'ZZ', NULL, 'Serpico', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-09-30 13:04:31', NULL, NULL, 4, 1, NULL, NULL, NULL),
(90, NULL, NULL, NULL, NULL, NULL, NULL, 39, 1, 'ZZ', NULL, 'Serpico', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-09-30 17:52:55', NULL, NULL, 4, 1, NULL, NULL, NULL),
(91, NULL, NULL, NULL, NULL, NULL, NULL, 39, 1, 'Marin', 'Guérin', 'Marin Guérin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, 1, '2020-09-30 17:52:55', NULL, NULL, 2, NULL, NULL, 11, NULL),
(92, NULL, NULL, NULL, NULL, NULL, NULL, 40, 1, 'ZZ', NULL, 'Serpico', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-10-02 13:02:54', NULL, NULL, 4, 1, NULL, NULL, NULL),
(93, NULL, NULL, NULL, NULL, NULL, NULL, 40, 1, 'Jerome', 'Grandidier', 'Jerome Grandidier', NULL, NULL, 'j.grandidier@yopmail.com', '$2y$12$CXNZaXSoCboD7AZbwy5xyuq8PpsbJ./irP7oaPsAHE7LZP47Goxke', NULL, NULL, '', NULL, 7, NULL, NULL, NULL, 1, '2020-10-02 13:02:55', '2020-10-05 16:52:35', NULL, 2, NULL, NULL, 12, NULL),
(104, NULL, NULL, NULL, NULL, NULL, NULL, 49, 1, 'Gilberto', 'Fernandez', 'Gilberto Fernandez', NULL, NULL, 'g.fernandez@yopmail.com', '$2y$12$Id9P1R6AK5e8C.G.06WlPODhJyByDAc1CTfL7aRRa8zviAsvpQmpK', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-10-13 21:21:07', '2020-10-13 21:25:36', NULL, 2, NULL, NULL, 13, NULL),
(105, NULL, NULL, NULL, NULL, NULL, NULL, 49, 1, 'ZZ', 'SalonKee', 'SalonKee', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-10-13 21:25:06', NULL, NULL, 4, 1, NULL, NULL, NULL),
(108, NULL, NULL, NULL, NULL, NULL, NULL, 51, 1, 'ZZ', NULL, 'Vizz', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-10-13 22:37:08', NULL, NULL, 4, 1, NULL, NULL, NULL),
(109, NULL, NULL, NULL, NULL, NULL, NULL, 51, 1, 'Mathias', 'Keune', 'Mathias Keune', NULL, NULL, 'moien.keune@yopmail.com', NULL, NULL, NULL, '238b64607fe78f51aa3b9de0629ba740', NULL, 7, NULL, NULL, NULL, 104, '2020-10-13 22:37:08', NULL, NULL, 3, NULL, NULL, 9, NULL),
(118, NULL, NULL, NULL, NULL, NULL, NULL, 56, 1, 'Michele', 'Gallo', 'Michele Gallo', NULL, NULL, 'm.gallo@yopmail.com', '$2y$12$d8/gA1DTnaMNvqBOXL48FebS31q59s3EjxWELs5rPhsmIyx1v/c0G', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-10-14 15:42:56', '2020-10-14 15:45:15', NULL, 2, NULL, NULL, 15, NULL),
(119, NULL, NULL, NULL, NULL, NULL, NULL, 56, 1, 'ZZ', 'Ministère de l\'Economie', 'Ministère de l\'Economie', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-10-14 15:44:56', NULL, NULL, 4, 1, NULL, NULL, NULL),
(125, NULL, NULL, NULL, NULL, NULL, NULL, 25, 1, 'Christian', 'Gillot', 'Christian Gillot', NULL, NULL, 'c.gillot@yopmail.com', '$2y$12$hJbOWhRRh50RR6ur/ryzDuV.ENX6OsHzlLJhCIv3GoCXyyh1OLOG2', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, 118, '2020-10-23 15:33:53', '2020-10-24 20:52:40', NULL, 3, NULL, NULL, 16, NULL),
(126, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Tom', 'Wellens', 'Tom Wellens', NULL, NULL, 't.wellens@yopmail.com', NULL, NULL, NULL, 'cac871fb9cc30f0b02a1493ecf5a257a', NULL, 7, NULL, NULL, NULL, NULL, '2020-10-26 15:01:56', NULL, NULL, 2, NULL, NULL, 17, NULL),
(127, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Guillaume', 'Chatelain', 'Guillaume Chatelain', NULL, NULL, 'guillaume.chatelain@outlook.com', '', NULL, NULL, 'ab6992297e93f348909ce874f4a0e04a', NULL, 7, NULL, NULL, NULL, NULL, '2020-10-26 15:59:52', NULL, NULL, 2, NULL, NULL, 18, NULL),
(128, NULL, NULL, NULL, NULL, NULL, NULL, 57, 1, 'Rodrigo', 'Velazquez', 'Rodrigo Velazquez', NULL, NULL, 'r.velazquez@yopmail.com', '$2y$12$jmaRdrxb0ljD8tB8GhDwTuniby3Q6SVnKdDTlT7Ewege3J44bmSSi', NULL, 'Traxys-5fbacf5e75de1.png', 'f7cb295be984d10d971c99be0d0e6875', NULL, 7, NULL, NULL, NULL, NULL, '2020-10-27 08:38:27', '2020-12-02 11:28:58', NULL, 2, NULL, 'gchatelain@serpicoapp.com', 19, NULL),
(129, NULL, NULL, NULL, NULL, NULL, NULL, 57, 1, 'ZZ', 'Velazquez Foundation', 'Velazquez Foundation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-10-27 08:40:19', NULL, NULL, 4, 1, NULL, NULL, NULL),
(144, NULL, NULL, NULL, NULL, NULL, NULL, 64, 1, 'ZZ', 'Tatcher Inc.', 'Tatcher Inc.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-10-27 11:44:10', NULL, NULL, 4, 1, NULL, NULL, NULL),
(145, NULL, NULL, NULL, NULL, NULL, NULL, 64, 1, 'Margret', 'Tatcher', 'Margret Tatcher', NULL, NULL, 'm.tatcher@yopmail.com', '$2y$12$SXVC.afCGN/t.Q3x7KQ/QOmzTs688u0R3xXGL4SDMHBV6Oc1s1KIu', NULL, 'Capture-d-ecran-2020-11-15-a-15-12-31-5fb6fd3fab680.png', NULL, NULL, 7, NULL, NULL, NULL, 128, '2020-10-27 11:44:11', '2021-01-11 18:39:26', NULL, 3, NULL, NULL, 20, NULL),
(147, NULL, NULL, NULL, NULL, NULL, NULL, 16, 1, 'Donald', 'Joe', 'Donald Joe', NULL, NULL, 'd.joe@yopmail.com', NULL, NULL, NULL, '3162e96bbda38d85272d9d1766ee4eee', NULL, 7, NULL, NULL, NULL, 128, '2020-10-27 15:11:43', NULL, NULL, 3, NULL, NULL, 21, NULL),
(148, NULL, NULL, NULL, NULL, NULL, NULL, 65, 1, 'ZZ', 'Metro Goldwin', 'Metro Goldwin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-11-15 11:50:21', NULL, NULL, 4, 1, NULL, NULL, NULL),
(149, NULL, NULL, NULL, NULL, NULL, NULL, 65, 1, 'Sandra', 'Mouget', 'Sandra Mouget', NULL, NULL, 's.mouget@yopmail.com', '$2y$12$VT00qVs4MHKDudqBLicKCuHQRiQ42DrTd/5zhkBvBX.SWocKvi5RO', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, 145, '2020-11-15 11:50:21', '2020-11-15 19:46:20', NULL, 3, NULL, NULL, 22, NULL),
(150, NULL, NULL, NULL, NULL, NULL, NULL, 66, 1, 'ZZ', 'Camille Suteau', 'Camille Suteau', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-11-20 17:01:29', NULL, NULL, 4, 1, NULL, NULL, NULL),
(151, NULL, NULL, NULL, NULL, NULL, NULL, 66, 1, 'Camille', 'Suteau', 'Camille Suteau', NULL, NULL, 'c.suteau@yopmail.com', NULL, NULL, NULL, '56413d9cd67404af5d513c4de8014faf', NULL, 7, NULL, NULL, NULL, 128, '2020-11-20 17:01:29', NULL, NULL, 3, NULL, NULL, 23, NULL),
(152, NULL, NULL, NULL, NULL, NULL, NULL, 67, 1, 'ZZ', 'Fabrice Pincet', 'Fabrice Pincet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-11-20 17:05:37', NULL, NULL, 4, 1, NULL, NULL, NULL),
(153, NULL, NULL, NULL, NULL, NULL, NULL, 67, 1, 'Fabrice', 'Pincet', 'Fabrice Pincet', NULL, NULL, 'f.pincet@yopmail.com', NULL, NULL, NULL, '662d6d49c993e2e0a795d220fbfbbb0e', NULL, 7, NULL, NULL, NULL, 128, '2020-11-20 17:05:37', NULL, NULL, 3, NULL, NULL, 24, NULL),
(154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Gilbert', 'Meunier', 'Gilbert Meunier', NULL, NULL, 'g.meunier@yopmail.com', '$2y$12$Ev6pQMG7sZMDtXrtqGU7xez761nIrYDSOukAN3DFe3qGMs9z3.99m', NULL, NULL, '6e9c7571a31343b2be2d3d33f15a1882', NULL, 7, NULL, NULL, NULL, NULL, '2020-11-24 14:38:30', NULL, NULL, 2, NULL, NULL, 25, NULL),
(160, NULL, NULL, NULL, NULL, NULL, NULL, 71, 1, 'ZZ', 'Lucien Hermenon', 'Lucien Hermenon', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-11-26 16:18:59', NULL, NULL, 4, 1, NULL, NULL, NULL),
(161, NULL, NULL, NULL, NULL, NULL, NULL, 72, 1, 'Lucien', 'Hermenon', 'Lucien Hermenon', NULL, NULL, 'l.hermenon@yopmail.com', '$2y$12$gkBWAsiKb4JDO/iNQNAXT.MInLKdpOL2KJb9ZFu3sJo1YP3InK4ee', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-11-26 16:18:59', '2020-11-30 21:23:23', NULL, 2, NULL, NULL, 26, NULL),
(162, NULL, NULL, NULL, NULL, NULL, NULL, 71, 1, 'Lucien', 'Hermenon', 'Lucien Hermenon', NULL, NULL, 'l.hermenon@yopmail.com', NULL, NULL, NULL, '99ada75605edb3eed1deb5aeb99c891e', NULL, 7, NULL, NULL, NULL, NULL, '2020-11-26 16:20:15', '2020-11-27 20:05:20', NULL, 2, NULL, NULL, 26, NULL),
(191, NULL, NULL, NULL, NULL, NULL, NULL, 94, 1, 'Vincent', 'Tinot', 'Vincent Tinot', NULL, NULL, 'v.tinot@yopmail.com', '$2y$12$Qz3D7MqsRAT7VynL.9g7BuLnyWxwe3bfMDiXBTQ9p1Sv6E7u57Lf.', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-11-28 16:37:24', '2020-12-01 16:23:08', NULL, 2, NULL, NULL, 27, NULL),
(195, NULL, NULL, NULL, NULL, NULL, NULL, 95, 1, 'Xavier', 'Bettel', 'Xavier Bettel', NULL, NULL, 'xav.bettel@yopmail.com', NULL, NULL, NULL, '19337e8d5862b8ef8b92902e44a1173e', NULL, 7, NULL, NULL, NULL, NULL, '2020-11-30 16:24:35', NULL, NULL, 2, NULL, NULL, 28, NULL),
(197, NULL, NULL, NULL, NULL, NULL, NULL, 96, 1, 'Vincent', 'Tinot', 'Vincent Tinot', NULL, NULL, 'v.tinot@yopmail.com', '$2y$12$Qz3D7MqsRAT7VynL.9g7BuLnyWxwe3bfMDiXBTQ9p1Sv6E7u57Lf.', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-12-01 16:22:36', '2020-12-01 16:22:53', NULL, 2, NULL, NULL, 27, NULL),
(198, NULL, NULL, NULL, NULL, NULL, NULL, 96, 1, 'ZZ', 'Le Mosellan', 'Le Mosellan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-12-01 16:22:36', NULL, NULL, 4, 1, NULL, NULL, NULL),
(199, NULL, NULL, NULL, NULL, NULL, NULL, 19, 1, 'George', 'Faventyne', 'George Faventyne', NULL, NULL, 'g.faventyne@gmail.com', '$2y$12$mGDNuK83UMln7y/xkn2FTuUAWWTbWC7/tENFPkOOVWxkvkTEacw0u', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, 1, '2020-12-05 14:07:47', '2021-01-24 10:36:25', NULL, 2, NULL, NULL, 29, NULL),
(200, NULL, NULL, NULL, NULL, NULL, NULL, 97, 1, 'Maya', 'Coumes', 'Maya Coumes', NULL, NULL, 'm.coumes@yopmail.com', '$2y$12$7yJXzW5CQUR1Hhz4W0BEEuJRRMHkC/VcdTKqGitq5IGqxOXCht7y2', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-12-07 16:53:02', '2020-12-07 22:28:49', NULL, 2, NULL, NULL, 36, NULL),
(202, NULL, NULL, NULL, NULL, NULL, NULL, 98, 1, 'ZZ', 'Coumes Inc', 'Coumes Inc', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-12-07 16:58:33', NULL, NULL, 4, 1, NULL, NULL, NULL),
(209, NULL, NULL, NULL, NULL, NULL, NULL, 98, 1, 'Maya', 'Coumes', 'Maya Coumes', NULL, NULL, 'm.coumes@yopmail.com', '$2y$12$7yJXzW5CQUR1Hhz4W0BEEuJRRMHkC/VcdTKqGitq5IGqxOXCht7y2', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-12-07 21:22:09', '2020-12-07 22:29:12', NULL, 2, NULL, NULL, 36, NULL),
(210, NULL, NULL, NULL, NULL, NULL, NULL, 99, 1, 'Clément', 'Garnier', 'Clément Garnier', NULL, NULL, 'c.garnier@yopmail.com', '$2y$12$6bthxT2D1lPTqgT5/tkd/efGzxvh5.Awvpy9ZWvQFBwXAADbT7pea', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-12-07 21:39:29', '2021-01-18 17:52:10', NULL, 2, NULL, NULL, 37, NULL),
(212, NULL, NULL, NULL, NULL, NULL, NULL, 100, 1, 'ZZ', 'Garnier & Co', 'Garnier & Co', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-12-07 21:40:11', NULL, NULL, 4, 1, NULL, NULL, NULL),
(213, NULL, NULL, NULL, NULL, NULL, NULL, 100, 1, 'Clément', 'Garnier', 'Clément Garnier', NULL, NULL, 'c.garnier@yopmail.com', '$2y$12$6bthxT2D1lPTqgT5/tkd/efGzxvh5.Awvpy9ZWvQFBwXAADbT7pea', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2020-12-07 21:43:06', '2021-01-18 18:22:51', NULL, 2, NULL, NULL, 37, NULL),
(214, NULL, NULL, NULL, NULL, NULL, NULL, 21, 1, 'Fabrice', 'Duplantier', 'Fabrice Duplantier', NULL, NULL, 'f.duplantier@yopmail.com', NULL, NULL, NULL, '3355a804a48fcfb48a36605814c6e088', NULL, 7, NULL, NULL, NULL, 1, '2020-12-10 14:21:42', NULL, NULL, 2, NULL, NULL, 40, NULL),
(215, NULL, NULL, NULL, NULL, NULL, NULL, 21, 1, 'Julie', 'Joyeuse', 'Julie Joyeuse', NULL, NULL, 'j.joyeuse@yopmail.com', NULL, NULL, NULL, 'd38247dabfec58bbcd4c19dcea0ca1dd', NULL, 7, NULL, NULL, NULL, 1, '2020-12-10 14:38:31', NULL, NULL, 2, NULL, NULL, 41, NULL),
(220, NULL, NULL, NULL, NULL, NULL, NULL, 100, 1, 'Pierre', 'Antoine', 'Pierre Antoine', NULL, NULL, 'p.antoine@yopmail.com', NULL, NULL, NULL, '817eaab9b6dc2098ca9362788531c889', NULL, 7, NULL, NULL, NULL, NULL, '2021-01-07 22:32:04', NULL, NULL, 2, NULL, NULL, NULL, NULL),
(221, NULL, NULL, NULL, NULL, NULL, NULL, 101, 1, 'ZZ', 'Evernote', 'Evernote', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2021-01-09 23:26:26', NULL, NULL, 4, 1, NULL, NULL, NULL),
(222, NULL, NULL, NULL, NULL, NULL, NULL, 102, 1, 'ZZ', 'Robeco', 'Robeco', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2021-01-12 10:02:51', NULL, NULL, 4, 1, NULL, NULL, NULL),
(223, NULL, NULL, NULL, NULL, NULL, NULL, 102, 1, 'Cristof', 'Rostoff', 'Cristof Rostoff', NULL, NULL, 'c.rostoff@yopmail.com', '$2y$12$mDaIEn7lQ7Sl03EJV57EIujq0TF4SGQsbfqRdjlfZM1gVQEITC5HO', NULL, NULL, NULL, 100, 7, NULL, NULL, NULL, 1, '2021-01-12 11:25:23', '2021-02-03 18:31:18', NULL, 2, NULL, NULL, 42, NULL),
(225, NULL, NULL, NULL, NULL, NULL, NULL, 100, 1, 'Fred', 'Vidal', 'Fred Vidal', NULL, NULL, 'f.vidal@yopmail.com', NULL, NULL, NULL, '2c94dcd0b95d9ea638be674eeb1dc0e1', 100, 7, NULL, NULL, NULL, 1, '2021-01-18 23:19:48', NULL, NULL, 3, NULL, NULL, 44, NULL),
(226, NULL, NULL, NULL, NULL, NULL, NULL, 19, 1, 'Gertrude', 'Bernard', 'Gertrude Bernard', NULL, NULL, 'g.bernard@yopmail.com', '$2y$12$kGjxwhFkz50ATf0p5.2E/u/5xmtzwbI6vPOeyaA/tXT9swjCBFGki', NULL, NULL, NULL, 100, 7, NULL, NULL, NULL, 223, '2021-01-19 11:46:15', '2021-01-29 15:29:44', NULL, 3, NULL, NULL, 67, NULL),
(229, NULL, NULL, NULL, NULL, NULL, NULL, 108, 1, 'ZZ', 'Floyd Aviation', 'Floyd Aviation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2021-01-19 14:08:31', NULL, NULL, 4, 1, NULL, NULL, NULL),
(230, NULL, NULL, NULL, NULL, NULL, NULL, 108, 1, 'Gerard', 'Sinner', 'Gerard Sinner', NULL, NULL, 'g.sinner@yopmail.com', NULL, NULL, NULL, '8acf460e2d6395917d7e6e179304e5d4', 100, 7, NULL, NULL, NULL, 1, '2021-01-19 14:09:01', NULL, NULL, 3, NULL, NULL, 46, NULL),
(231, NULL, NULL, NULL, NULL, NULL, NULL, 108, 1, 'Franck', 'Vincent', 'Franck Vincent', NULL, NULL, NULL, NULL, NULL, NULL, 'e5124c9f6632e9af4b73527a8df855a2', 100, 7, NULL, NULL, NULL, 1, '2021-01-19 14:19:06', NULL, NULL, 3, NULL, NULL, 47, NULL),
(237, NULL, NULL, NULL, NULL, NULL, NULL, 112, 1, 'Jerome', 'Garcin', 'Jerome Garcin', NULL, NULL, 'j.garcin@yopmail.com', '$2y$12$Mtnvbv.RGILpciL9VinFBeNPgjWKaRu.iBt2jje1U..1si6GwC0Zy', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2021-01-20 10:23:53', '2021-01-20 10:24:53', NULL, 2, NULL, NULL, 50, NULL),
(238, NULL, NULL, NULL, NULL, 23, NULL, 102, 1, 'Jerome', 'Garcin', 'Jerome Garcin', NULL, NULL, 'j.garcin@yopmail.com', '$2y$12$Mtnvbv.RGILpciL9VinFBeNPgjWKaRu.iBt2jje1U..1si6GwC0Zy', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2021-01-20 10:24:30', '2021-01-31 15:10:43', NULL, 1, NULL, NULL, 50, NULL),
(239, NULL, NULL, NULL, NULL, NULL, NULL, 113, 1, 'Pierre', 'Garcin', 'Pierre Garcin', NULL, NULL, 'p.garcin@yopmail.com', '$2y$12$MY/GO7q0/sx/VSAFFyWsge/fieZX.AGX6wlRITjs.beTwbZARhs2m', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2021-01-20 10:26:28', '2021-01-20 10:26:51', NULL, 2, NULL, NULL, 51, NULL),
(240, NULL, NULL, NULL, NULL, NULL, NULL, 114, 1, 'Cristof', 'Rostoff', 'Cristof Rostoff', NULL, NULL, 'c.rostoff@yopmail.com', '$2y$12$tjVsByA222.iej43AEkUOepMqNRo5tv4ROZ7Zk1Yv9n8aQMi4JvYO', NULL, NULL, NULL, 100, 7, NULL, NULL, NULL, 1, '2021-01-20 10:36:37', '2021-01-24 21:19:51', NULL, 2, NULL, NULL, 42, NULL),
(278, NULL, NULL, NULL, NULL, NULL, NULL, 137, 1, 'Federico', 'Garcia', 'Federico Garcia', NULL, NULL, 'f.garcia@yopmail.com', NULL, NULL, NULL, '9ac2e83bc2ee239fc128214316d761c4', NULL, 7, NULL, NULL, NULL, NULL, '2021-01-22 11:42:17', NULL, NULL, 4, 1, NULL, 66, NULL),
(281, NULL, NULL, NULL, NULL, NULL, NULL, 138, 1, 'George', 'Faventyne', 'George Faventyne', NULL, NULL, 'g.faventyne@gmail.com', '$2y$12$mGDNuK83UMln7y/xkn2FTuUAWWTbWC7/tENFPkOOVWxkvkTEacw0u', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, 1, '2021-01-24 10:06:38', NULL, NULL, 2, NULL, NULL, 29, NULL),
(290, NULL, NULL, NULL, NULL, NULL, NULL, 139, 1, 'Gertrude', 'Bernard', 'Gertrude Bernard', NULL, NULL, 'g.bernard@yopmail.com', '$2y$12$kGjxwhFkz50ATf0p5.2E/u/5xmtzwbI6vPOeyaA/tXT9swjCBFGki', NULL, NULL, NULL, 100, 7, NULL, NULL, NULL, 223, '2021-01-29 11:05:12', NULL, NULL, 2, NULL, NULL, 67, NULL),
(292, NULL, NULL, NULL, NULL, NULL, NULL, 102, 1, 'Jerome', 'Stumper', 'Jerome Stumper', NULL, NULL, '', NULL, NULL, NULL, '108c9b1e94ff6d7de5ea2558e169312c', NULL, 7, NULL, NULL, NULL, NULL, '2021-01-29 19:18:55', NULL, NULL, 2, NULL, NULL, 69, NULL),
(293, NULL, NULL, NULL, NULL, NULL, NULL, 140, 1, 'Jerome', 'Stumper', 'Jerome Stumper', NULL, NULL, NULL, NULL, NULL, NULL, '5c990b2e66a13efed6d103a59ae3b5ab', NULL, 7, NULL, NULL, NULL, NULL, '2021-01-29 20:17:34', NULL, NULL, 2, NULL, NULL, 69, NULL),
(299, NULL, NULL, NULL, NULL, NULL, NULL, 145, 1, 'Francis', 'Lalanne', 'Francis Lalanne', NULL, NULL, 'f.lalanne@yopmail.com', '$2y$12$FQoQeIhyiFpA1Z7qb5wI4uTvJnBeI4Ham1OZcfMQ07pumpZUcGEnm', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2021-01-31 10:58:28', '2021-01-31 11:43:13', NULL, 1, NULL, NULL, 72, NULL),
(300, NULL, NULL, NULL, NULL, NULL, NULL, 146, 1, 'Francis', 'Lalanne', 'Francis Lalanne', NULL, NULL, 'f.lalanne@yopmail.com', '$2y$12$FQoQeIhyiFpA1Z7qb5wI4uTvJnBeI4Ham1OZcfMQ07pumpZUcGEnm', NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2021-01-31 10:59:22', '2021-01-31 11:42:02', NULL, 1, NULL, NULL, 72, NULL),
(301, NULL, NULL, NULL, NULL, NULL, NULL, 146, 1, 'ZZ', 'Weigand & Co', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2021-01-31 10:59:22', NULL, NULL, 3, 1, NULL, NULL, NULL),
(302, NULL, NULL, NULL, NULL, NULL, NULL, 102, 1, 'Mathias', 'Keune', 'Mathias Keune', NULL, NULL, '', NULL, NULL, NULL, 'bb7292d7e6172d228a41c24d3083cce9', NULL, 7, NULL, NULL, NULL, NULL, '2021-02-02 11:10:53', NULL, NULL, 2, NULL, NULL, 73, NULL),
(303, NULL, NULL, NULL, NULL, NULL, NULL, 147, 1, 'ZZ', 'De la Cruz Co.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, '2021-02-02 14:33:52', NULL, NULL, 4, 1, NULL, NULL, NULL),
(304, NULL, NULL, NULL, NULL, NULL, NULL, 147, 1, 'David', 'Recibo', 'David Recibo', NULL, NULL, 'd.recibo@yopmail.com', '$2y$12$lrOaNEdGz.PuGyIZ1r4CneOrUUV7uchpHsN52Uf6oJeZpUufwbWvG', NULL, NULL, NULL, 100, 7, NULL, NULL, NULL, 223, '2021-02-02 14:40:45', '2021-02-03 12:11:19', NULL, 1, NULL, NULL, 74, NULL),
(305, NULL, NULL, NULL, NULL, NULL, NULL, 148, 1, 'David', 'Recibo', 'David Recibo', NULL, NULL, 'd.recibo@yopmail.com', '$2y$12$lrOaNEdGz.PuGyIZ1r4CneOrUUV7uchpHsN52Uf6oJeZpUufwbWvG', NULL, NULL, NULL, 100, 7, NULL, NULL, NULL, 223, '2021-02-02 16:12:59', NULL, NULL, 2, NULL, NULL, 74, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_global`
--

CREATE TABLE `user_global` (
  `usg_id` int(11) NOT NULL,
  `usg_phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usg_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usg_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_global`
--

INSERT INTO `user_global` (`usg_id`, `usg_phone_number`, `usg_username`, `created_by`, `inserted`, `usg_email`) VALUES
(1, NULL, 'Guillaume Chatelain', NULL, '2020-11-29 15:03:57', NULL),
(2, NULL, 'Guillaume dBdG', NULL, '2020-11-29 15:03:57', NULL),
(3, NULL, 'Steve Jobs', NULL, '2020-11-29 15:03:57', NULL),
(4, NULL, 'Pierre Legrand', NULL, '2020-11-29 15:03:57', NULL),
(5, NULL, 'Pierre Laroche', NULL, '2020-11-29 15:03:57', NULL),
(6, NULL, 'Floriane Moutet', NULL, '2020-11-29 15:03:57', NULL),
(7, NULL, 'Bruno Esposito', NULL, '2020-11-29 15:03:57', NULL),
(8, NULL, 'Pierre Laroche', NULL, '2020-11-29 15:08:36', NULL),
(9, NULL, 'Mathias Keune', NULL, '2020-11-29 15:08:36', NULL),
(10, NULL, 'Thomas Reicher', NULL, '2020-11-29 15:08:36', NULL),
(11, NULL, 'Martin Guérin', NULL, '2020-11-29 15:08:36', NULL),
(12, NULL, 'Jérome Grandidier', NULL, '2020-11-29 15:08:36', NULL),
(13, NULL, 'Gilberto Fernandez', NULL, '2020-11-29 15:08:36', NULL),
(15, NULL, 'Michele Gallo', NULL, '2020-11-29 15:08:36', NULL),
(16, NULL, 'Christian Gillot', NULL, '2020-11-29 15:08:36', NULL),
(17, NULL, 'Tom Wellens', NULL, '2020-11-29 15:08:36', NULL),
(18, NULL, 'Guillaume Chatelain', NULL, '2020-11-29 15:08:36', NULL),
(19, NULL, 'Rodrigo Velazquez', NULL, '2020-11-29 15:08:36', NULL),
(20, NULL, 'Margret Tatcher', NULL, '2020-11-29 15:09:46', NULL),
(21, NULL, 'Donald Joe', NULL, '2020-11-29 15:09:46', NULL),
(22, NULL, 'Sandra Mouget', NULL, '2020-11-29 15:09:46', NULL),
(23, NULL, 'Camille Suteau', NULL, '2020-11-29 15:09:46', NULL),
(24, NULL, 'Fabrice Pincet', NULL, '2020-11-29 15:09:46', NULL),
(25, NULL, 'Gilbert Meunier', NULL, '2020-11-29 15:09:46', NULL),
(26, NULL, 'Lucien Hermenon', NULL, '2020-11-29 15:09:46', NULL),
(27, NULL, 'Vincent Tinot', NULL, '2020-11-29 15:09:46', NULL),
(28, NULL, 'Xavier Bettel', NULL, '2020-11-30 16:24:35', NULL),
(29, NULL, 'George Faventyne', NULL, '2020-12-05 14:07:47', NULL),
(36, NULL, 'Maya Coumes', NULL, '2020-12-07 16:53:02', NULL),
(37, NULL, 'Clément Garnier', NULL, '2020-12-07 21:39:29', NULL),
(40, NULL, 'Fabrice Duplantier', NULL, '2020-12-10 14:21:42', NULL),
(41, NULL, 'Julie Joyeuse', NULL, '2020-12-10 14:38:31', NULL),
(42, NULL, 'Cristof Rostoff', NULL, '2021-01-12 11:25:23', NULL),
(43, NULL, 'Jerome Stumper', NULL, '2021-01-13 11:12:55', NULL),
(44, NULL, 'Fred Vidal', NULL, '2021-01-18 23:19:48', NULL),
(45, NULL, 'Gertrude Bernard', NULL, '2021-01-19 11:46:15', NULL),
(46, NULL, 'Gerard Sinner', NULL, '2021-01-19 14:09:01', NULL),
(47, NULL, 'Franck Vincent', NULL, '2021-01-19 14:19:06', NULL),
(48, NULL, 'Jerome Garcin', NULL, '2021-01-19 23:45:08', NULL),
(49, NULL, 'Jerome Garcin', NULL, '2021-01-20 10:15:43', NULL),
(50, NULL, 'Jerome Garcin', NULL, '2021-01-20 10:23:53', NULL),
(51, NULL, 'Pierre Garcin', NULL, '2021-01-20 10:26:29', NULL),
(66, NULL, 'Federico Garcia', NULL, '2021-01-21 22:32:27', NULL),
(67, NULL, 'Gertrude Bernard', NULL, '2021-01-22 16:26:25', NULL),
(69, NULL, 'Jerome Stumper', NULL, '2021-01-29 19:18:55', NULL),
(70, NULL, 'Francis Lalanne', NULL, '2021-01-31 10:04:28', NULL),
(71, NULL, 'Francis Lalanne', NULL, '2021-01-31 10:48:09', NULL),
(72, NULL, 'Francis Lalanne', NULL, '2021-01-31 10:58:28', NULL),
(73, NULL, 'Mathias Keune', NULL, '2021-02-02 11:10:53', NULL),
(74, NULL, 'David Recibo', NULL, '2021-02-02 14:40:45', NULL),
(75, NULL, 'Valérie Branco', NULL, '2021-02-04 08:17:39', NULL),
(76, NULL, 'Valérie Branco', NULL, '2021-02-04 08:26:15', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_master`
--

CREATE TABLE `user_master` (
  `usm_id` int(11) NOT NULL,
  `organization_org_id` int(11) DEFAULT NULL,
  `department_dpt_id` int(11) DEFAULT NULL,
  `position_pos_id` int(11) DEFAULT NULL,
  `institution_process_inp_id` int(11) DEFAULT NULL,
  `activity_act_id` int(11) DEFAULT NULL,
  `stage_stg_id` int(11) DEFAULT NULL,
  `event_eve_id` int(11) DEFAULT NULL,
  `output_otp_id` int(11) DEFAULT NULL,
  `user_usr_id` int(11) DEFAULT NULL,
  `usm_type` int(11) DEFAULT NULL,
  `usm_created_by` int(11) DEFAULT NULL,
  `usm_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_master`
--

INSERT INTO `user_master` (`usm_id`, `organization_org_id`, `department_dpt_id`, `position_pos_id`, `institution_process_inp_id`, `activity_act_id`, `stage_stg_id`, `event_eve_id`, `output_otp_id`, `user_usr_id`, `usm_type`, `usm_created_by`, `usm_inserted`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2020-11-28 17:08:29'),
(2, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 66, NULL, NULL, '2020-11-28 17:08:29'),
(3, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 161, NULL, NULL, '2020-11-28 17:08:29'),
(5, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2020-11-28 17:10:24'),
(6, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2020-11-28 17:10:24'),
(7, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, NULL, NULL, '2020-11-28 17:24:02'),
(8, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, 1, NULL, NULL, '2020-11-28 17:24:02'),
(9, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, 1, NULL, NULL, '2020-11-28 17:24:02'),
(10, NULL, NULL, NULL, NULL, 16, NULL, NULL, NULL, 1, NULL, NULL, '2020-11-28 17:24:59'),
(11, NULL, NULL, NULL, NULL, 19, NULL, NULL, NULL, 1, NULL, NULL, '2020-11-28 17:24:59'),
(12, NULL, NULL, NULL, NULL, 20, NULL, NULL, NULL, 1, NULL, NULL, '2020-11-28 17:24:59'),
(13, NULL, NULL, NULL, NULL, 35, NULL, NULL, NULL, 104, NULL, NULL, '2020-11-28 17:25:43'),
(14, NULL, NULL, NULL, NULL, 40, NULL, NULL, NULL, 118, NULL, NULL, '2020-11-28 17:25:43'),
(15, NULL, NULL, NULL, NULL, 41, NULL, NULL, NULL, 118, NULL, NULL, '2020-11-28 17:25:43'),
(16, NULL, NULL, NULL, NULL, 46, NULL, NULL, NULL, 128, NULL, NULL, '2020-11-28 17:26:24'),
(17, NULL, NULL, NULL, NULL, 47, NULL, NULL, NULL, 128, NULL, NULL, '2020-11-28 17:26:24'),
(18, NULL, NULL, NULL, NULL, 132, NULL, NULL, NULL, 128, NULL, NULL, '2020-11-28 17:27:01'),
(19, NULL, NULL, NULL, NULL, 136, NULL, NULL, NULL, 128, NULL, NULL, '2020-11-28 17:27:01'),
(20, NULL, NULL, NULL, NULL, 137, NULL, NULL, NULL, 145, NULL, NULL, '2020-11-28 17:28:19'),
(21, NULL, NULL, NULL, NULL, 138, NULL, NULL, NULL, 128, NULL, NULL, '2020-11-28 17:28:19'),
(22, NULL, NULL, NULL, NULL, 139, 141, NULL, NULL, 161, NULL, NULL, '2020-11-30 19:08:43'),
(26, NULL, NULL, NULL, NULL, 143, 145, NULL, NULL, 1, NULL, NULL, '2020-12-21 10:28:15'),
(35, NULL, NULL, NULL, NULL, 152, 154, NULL, NULL, 223, NULL, NULL, '2021-01-25 09:50:28'),
(36, NULL, NULL, NULL, NULL, 153, 155, NULL, NULL, 223, NULL, NULL, '2021-02-02 16:11:07');

-- --------------------------------------------------------

--
-- Structure de la table `weight`
--

CREATE TABLE `weight` (
  `wgt_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `wgt_interval` int(11) DEFAULT NULL,
  `wgt_titleframe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wgt_value` double DEFAULT NULL,
  `wgt_modified` datetime DEFAULT NULL,
  `wgt_createdBy` int(11) DEFAULT NULL,
  `wgt_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `wgt_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `weight`
--

INSERT INTO `weight` (`wgt_id`, `org_id`, `wgt_interval`, `wgt_titleframe`, `wgt_value`, `wgt_modified`, `wgt_createdBy`, `wgt_inserted`, `wgt_deleted`) VALUES
(1, 1, 0, '', 100, NULL, NULL, '2020-08-24 15:34:09', NULL),
(7, 13, 0, '', 100, NULL, NULL, '2020-08-27 14:12:20', NULL),
(10, 16, 0, '', 100, NULL, NULL, '2020-08-31 19:22:34', NULL),
(11, 18, 0, '', 100, NULL, NULL, '2020-08-31 21:42:02', NULL),
(12, 19, 0, '', 100, NULL, NULL, '2020-08-31 22:24:40', NULL),
(13, 20, 0, '', 100, NULL, NULL, '2020-08-31 22:30:19', NULL),
(14, 21, 0, '', 100, NULL, NULL, '2020-09-02 12:38:23', NULL),
(15, 25, 0, '', 100, NULL, NULL, '2020-09-03 18:53:55', NULL),
(16, 25, 0, '', 100, NULL, NULL, '2020-09-27 20:56:19', NULL),
(19, 32, 0, '', 100, NULL, NULL, '2020-09-30 08:18:03', NULL),
(20, 32, 0, '', 100, NULL, NULL, '2020-09-30 13:04:31', NULL),
(26, 39, 0, '', 100, NULL, NULL, '2020-09-30 17:52:55', NULL),
(27, 40, 0, '', 100, NULL, NULL, '2020-10-02 13:02:54', NULL),
(34, 49, 0, '', 100, NULL, NULL, '2020-10-13 21:25:06', NULL),
(36, 51, 0, '', 100, NULL, NULL, '2020-10-13 22:37:08', NULL),
(41, 56, 0, '', 100, NULL, NULL, '2020-10-14 15:44:56', NULL),
(42, 57, 0, '', 100, NULL, NULL, '2020-10-27 08:40:19', NULL),
(49, 64, 0, '', 100, NULL, NULL, '2020-10-27 11:44:10', NULL),
(50, 65, 0, '', 100, NULL, NULL, '2020-11-15 11:50:21', NULL),
(51, 66, 0, '', 100, NULL, NULL, '2020-11-20 17:01:29', NULL),
(52, 67, 0, '', 100, NULL, NULL, '2020-11-20 17:05:37', NULL),
(55, 71, 0, '', 100, NULL, NULL, '2020-11-26 16:18:59', NULL),
(56, 72, 0, '', 100, NULL, NULL, '2020-11-26 16:20:15', NULL),
(78, 94, 0, '', 100, NULL, NULL, '2020-11-28 16:37:24', NULL),
(79, 95, 0, '', 100, NULL, NULL, '2020-11-30 16:24:35', NULL),
(80, 96, 0, '', 100, NULL, NULL, '2020-12-01 16:22:36', NULL),
(81, 97, 0, '', 100, NULL, NULL, '2020-12-07 16:53:02', NULL),
(82, 98, 0, '', 100, NULL, NULL, '2020-12-07 16:58:33', NULL),
(83, 99, 0, '', 100, NULL, NULL, '2020-12-07 21:39:29', NULL),
(84, 100, 0, '', 100, NULL, NULL, '2020-12-07 21:40:11', NULL),
(85, 101, 0, '', 100, NULL, NULL, '2021-01-09 23:26:26', NULL),
(86, 102, 0, '', 100, NULL, NULL, '2021-01-12 10:02:51', NULL),
(92, 108, 0, '', 100, NULL, NULL, '2021-01-19 14:08:31', NULL),
(96, 112, 0, '', 100, NULL, NULL, '2021-01-20 10:23:53', NULL),
(97, 113, 0, '', 100, NULL, NULL, '2021-01-20 10:26:29', NULL),
(98, 114, 0, '', 100, NULL, NULL, '2021-01-20 10:36:37', NULL),
(121, 137, 0, '', 100, NULL, NULL, '2021-01-22 11:42:17', NULL),
(122, 138, 0, '', 100, NULL, NULL, '2021-01-24 10:06:38', NULL),
(123, 139, 0, '', 100, NULL, NULL, '2021-01-29 11:05:12', NULL),
(127, 145, 0, '', 100, NULL, NULL, '2021-01-31 10:58:28', NULL),
(128, 146, 0, '', 100, NULL, NULL, '2021-01-31 10:59:22', NULL),
(129, 147, 0, '', 100, NULL, NULL, '2021-02-02 14:33:52', NULL),
(130, 148, 0, '', 100, NULL, NULL, '2021-02-02 16:12:59', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `worker_experience`
--

CREATE TABLE `worker_experience` (
  `wex_id` int(11) NOT NULL,
  `worker_individual_wid` int(11) NOT NULL,
  `worker_firm_wfi` int(11) DEFAULT NULL,
  `wex_active` tinyint(1) DEFAULT NULL,
  `wex_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wex_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wex_startdate` datetime DEFAULT NULL,
  `wex_enddate` datetime DEFAULT NULL,
  `wex_createdBy` int(11) DEFAULT NULL,
  `wex_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `worker_firm`
--

CREATE TABLE `worker_firm` (
  `wfi_id` int(11) NOT NULL,
  `worker_firm_sector_wfs_id` int(11) DEFAULT NULL,
  `city_cit_id` int(11) DEFAULT NULL,
  `state_sta_id` int(11) DEFAULT NULL,
  `country_cou_id` int(11) DEFAULT NULL,
  `wfi_hq_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfi_hq_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfi_hq_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfi_hq_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfi_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfi_website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfi_creation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfi_firm_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfi_size` int(11) DEFAULT NULL,
  `wfi_nb_lk_followers` int(11) DEFAULT NULL,
  `wfi_nb_lk_employees` int(11) DEFAULT NULL,
  `wfi_active` tinyint(1) DEFAULT NULL,
  `wfi_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfi_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfi_common_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfi_mail_prefix` int(11) DEFAULT NULL,
  `wfi_mail_suffix` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfi_nb_active_exp` int(11) DEFAULT NULL,
  `wfi_created` int(11) DEFAULT NULL,
  `wfi_creation_date` datetime DEFAULT NULL,
  `wfi_created_by` int(11) DEFAULT NULL,
  `wfi_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `worker_firm`
--

INSERT INTO `worker_firm` (`wfi_id`, `worker_firm_sector_wfs_id`, `city_cit_id`, `state_sta_id`, `country_cou_id`, `wfi_hq_location`, `wfi_hq_city`, `wfi_hq_state`, `wfi_hq_country`, `wfi_logo`, `wfi_website`, `wfi_creation`, `wfi_firm_type`, `wfi_size`, `wfi_nb_lk_followers`, `wfi_nb_lk_employees`, `wfi_active`, `wfi_url`, `wfi_name`, `wfi_common_name`, `wfi_mail_prefix`, `wfi_mail_suffix`, `wfi_nb_active_exp`, `wfi_created`, `wfi_creation_date`, `wfi_created_by`, `wfi_inserted`, `parent_id`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Serpico', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-09-14 14:42:17', NULL),
(6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Welkin & Meraki', 'Welkin & Meraki', NULL, NULL, NULL, NULL, NULL, NULL, '2020-08-27 14:12:20', NULL),
(7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'creos.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 'Creos', 'Creos', NULL, NULL, 1, NULL, NULL, NULL, '2020-08-31 13:36:28', NULL),
(8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mcm.png', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 'Ministère des Classes Moyennes', 'Ministère des Classes Moyennes', NULL, NULL, NULL, NULL, NULL, NULL, '2020-08-31 21:42:02', NULL),
(9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'bnp.png', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 'BGL BNP Paribas', 'BGL BNP Paribas', NULL, NULL, NULL, NULL, NULL, NULL, '2020-08-31 22:24:40', NULL),
(10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'nvision.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 'NVision', 'NVision', NULL, NULL, NULL, NULL, NULL, NULL, '2020-08-31 22:30:19', NULL),
(11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '11.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DuPont & Nemours', 'DuPont & Nemours', NULL, NULL, NULL, NULL, NULL, NULL, '2020-09-02 12:38:23', NULL),
(21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'nvision.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Landifirm', 'Landifirm', NULL, NULL, NULL, NULL, NULL, NULL, '2020-09-03 18:53:55', NULL),
(22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Thomas Reicher', 'Thomas Reicher', NULL, NULL, NULL, NULL, NULL, 1, '2020-09-28 08:23:31', NULL),
(23, 3, 1, 1, 129, NULL, 'Luxembourg', 'Luxembourg', 'LU', 'nvision.jpg', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 'Landifirm 2', 'Landifirm 2', 1, NULL, NULL, 1, '2020-01-01 00:00:00', NULL, '2020-09-29 13:42:39', NULL),
(56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'nvision.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Luxembourg City Incubator', 'Luxembourg City Incubator', NULL, NULL, NULL, NULL, NULL, 1, '2020-09-29 20:21:40', NULL),
(57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'nvision.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Focus', 'Focus', NULL, NULL, NULL, NULL, NULL, 1, '2020-09-30 08:18:03', NULL),
(58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'nvision.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Luxfactory', 'Luxfactory', NULL, NULL, NULL, NULL, NULL, 1, '2020-10-02 13:02:54', NULL),
(59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SalonKee', 'SalonKee', NULL, NULL, NULL, NULL, NULL, 96, '2020-10-12 16:03:59', NULL),
(60, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Castorama', 'Castorama', NULL, NULL, NULL, NULL, NULL, 100, '2020-10-13 08:36:54', NULL),
(62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vizz', 'Vizz', NULL, NULL, NULL, NULL, NULL, 104, '2020-10-13 21:26:43', NULL),
(63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ministère de l\'Economie', 'Ministère de l\'Economie', NULL, NULL, NULL, NULL, NULL, 110, '2020-10-14 13:10:53', NULL),
(64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Velazquez Foundation', 'Velazquez Foundation', NULL, NULL, NULL, NULL, NULL, 128, '2020-10-27 08:40:19', NULL),
(65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tatcher Inc.', 'Tatcher Inc.', NULL, NULL, NULL, NULL, NULL, 128, '2020-10-27 09:20:36', NULL),
(66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Metro Goldwin', 'Metro Goldwin', NULL, NULL, NULL, NULL, NULL, 145, '2020-11-15 11:48:07', NULL),
(67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Metro Goldwin', 'Metro Goldwin', NULL, NULL, NULL, NULL, NULL, 145, '2020-11-15 11:50:21', NULL),
(68, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Camille Suteau', 'Camille Suteau', NULL, NULL, NULL, NULL, NULL, 128, '2020-11-20 17:01:29', NULL),
(69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Fabrice Pincet', 'Fabrice Pincet', NULL, NULL, NULL, NULL, NULL, 128, '2020-11-20 17:05:37', NULL),
(70, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Hermenon Foundation', 'Hermenon Foundation', NULL, NULL, NULL, NULL, NULL, 161, '2020-11-26 16:20:15', NULL),
(71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, 172, '2020-11-27 21:36:24', NULL),
(72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, 175, '2020-11-27 21:48:59', NULL),
(75, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Le Mosellan', 'Le Mosellan', NULL, NULL, NULL, NULL, NULL, 191, '2020-12-01 16:22:36', NULL),
(76, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Coumes Inc', 'Coumes Inc', NULL, NULL, NULL, NULL, NULL, 200, '2020-12-07 16:58:33', NULL),
(77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Garnier & Co', 'Garnier & Co', NULL, NULL, NULL, NULL, NULL, 210, '2020-12-07 21:40:11', NULL),
(78, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Evernote', 'Evernote', NULL, NULL, NULL, NULL, NULL, 210, '2021-01-09 23:19:33', NULL),
(79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Evernote', 'Evernote', NULL, NULL, NULL, NULL, NULL, 210, '2021-01-09 23:22:11', NULL),
(80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Evernote', 'Evernote', NULL, NULL, NULL, NULL, NULL, 210, '2021-01-09 23:25:33', NULL),
(81, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Evernote', 'Evernote', NULL, NULL, NULL, NULL, NULL, 210, '2021-01-09 23:26:26', NULL),
(82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Robeco', 'Robeco', NULL, NULL, NULL, NULL, NULL, 1, '2021-01-12 10:02:51', NULL),
(83, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Floyd Aviation', 'Floyd Aviation', NULL, NULL, NULL, NULL, NULL, 1, '2021-01-19 14:06:40', NULL),
(84, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Garcin Constructions', 'Garcin Constructions', NULL, NULL, NULL, NULL, NULL, 232, '2021-01-20 10:06:32', NULL),
(85, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, 223, '2021-01-20 10:59:26', NULL),
(107, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Weigand & Co', 'Weigand & Co', NULL, NULL, NULL, NULL, NULL, 295, '2021-01-31 10:05:46', NULL),
(108, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'De la Cruz Co.', 'De la Cruz Co.', NULL, NULL, NULL, NULL, NULL, 223, '2021-02-02 14:33:51', NULL),
(114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Branco Inc.', 'Branco Inc.', NULL, NULL, NULL, NULL, NULL, 1, '2021-02-04 08:27:09', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `worker_firm_competency`
--

CREATE TABLE `worker_firm_competency` (
  `wfc_id` int(11) NOT NULL,
  `worker_firm_wfi_id` int(11) DEFAULT NULL,
  `wfc_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfc_created_by` int(11) DEFAULT NULL,
  `wfc_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `worker_firm_location`
--

CREATE TABLE `worker_firm_location` (
  `wfl_id` int(11) NOT NULL,
  `wfl_hq_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfl_hq_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfl_hq_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfl_created_by` int(11) DEFAULT NULL,
  `wfl_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `worker_firm_sector`
--

CREATE TABLE `worker_firm_sector` (
  `wfs_id` int(11) NOT NULL,
  `icon_ico_id` int(11) DEFAULT NULL,
  `wfs_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wfs_createdBy` int(11) DEFAULT NULL,
  `wfs_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `worker_firm_sector`
--

INSERT INTO `worker_firm_sector` (`wfs_id`, `icon_ico_id`, `wfs_name`, `wfs_createdBy`, `wfs_inserted`) VALUES
(1, NULL, 'Administration publique', NULL, '2020-09-29 15:21:44'),
(2, NULL, 'Administration scolaire et universitaire', NULL, '2020-09-29 15:21:44'),
(3, NULL, 'Aéronautique et aérospatiale', NULL, '2020-09-29 15:21:44'),
(4, NULL, 'Affaires étrangères', NULL, '2020-09-29 15:21:44'),
(5, NULL, 'Agriculture', NULL, '2020-09-29 16:19:06'),
(6, NULL, 'Agro-alimentaire', NULL, '2020-09-29 16:19:06'),
(7, NULL, 'Architecture et urbanisme', NULL, '2020-09-29 16:19:06'),
(8, NULL, 'Armée', NULL, '2020-09-29 16:19:06'),
(9, NULL, 'Articles de luxe et bijouterie', NULL, '2020-09-29 16:19:06'),
(10, NULL, 'Articles de sport', NULL, '2020-09-29 16:19:06'),
(11, NULL, 'Arts', NULL, '2020-09-29 16:19:06'),
(12, NULL, 'Arts et artisanat', NULL, '2020-09-29 16:19:06'),
(13, NULL, 'Arts vivants', NULL, '2020-09-29 16:19:06'),
(14, NULL, 'Associations et organisations sociales et syndicales', NULL, '2020-09-29 16:19:06'),
(15, NULL, 'Assurances', NULL, '2020-09-29 16:20:20'),
(16, NULL, 'Automatismes industriels', NULL, '2020-09-29 16:20:20'),
(17, NULL, 'Avocats', NULL, '2020-09-29 16:20:20'),
(18, NULL, 'Banques', NULL, '2020-09-29 16:20:20'),
(19, NULL, 'Bibliothèques', NULL, '2020-09-29 16:20:20'),
(20, NULL, 'Biens de consommation', NULL, '2020-09-29 16:20:20'),
(21, NULL, 'Biens et équipements pour les entreprises', NULL, '2020-09-29 16:20:20'),
(22, NULL, 'Biotechnologie', NULL, '2020-09-29 16:20:20'),
(23, NULL, 'Capital-risque et fonds LBO', NULL, '2020-09-29 16:20:20'),
(24, NULL, 'Centres de recherches', NULL, '2020-09-29 16:20:20'),
(25, NULL, 'Chantiers navals', NULL, '2020-09-29 16:20:20'),
(26, NULL, 'Chimie', NULL, '2020-09-29 16:20:20'),
(27, NULL, 'Collectivités publiques et territoriales', NULL, '2020-09-29 16:20:20'),
(28, NULL, 'Commerce de détail', NULL, '2020-09-29 16:22:43'),
(29, NULL, 'Commerce de gros', NULL, '2020-09-29 16:22:43'),
(30, NULL, 'Commerce et développement international', NULL, '2020-09-29 16:22:43'),
(31, NULL, 'Compagnie aérienne/Aviation', NULL, '2020-09-29 16:22:43'),
(32, NULL, 'Comptabilité', NULL, '2020-09-29 16:22:43'),
(33, NULL, 'Confection et mode', NULL, '2020-09-29 16:22:43'),
(34, NULL, 'Conseil en management', NULL, '2020-09-29 16:22:43'),
(35, NULL, 'Construction', NULL, '2020-09-29 16:22:43'),
(36, NULL, 'Contenus rédactionnels', NULL, '2020-09-29 16:22:43'),
(37, NULL, 'Cosmétiques', NULL, '2020-09-29 16:22:43'),
(38, NULL, 'Défense et espace', NULL, '2020-09-29 16:22:43'),
(39, NULL, 'Design', NULL, '2020-09-29 16:22:43'),
(40, NULL, 'Design graphique', NULL, '2020-09-29 16:22:43'),
(41, NULL, 'Divertissements', NULL, '2020-09-29 16:22:43'),
(42, NULL, 'Édition', NULL, '2020-09-29 16:22:43'),
(43, NULL, 'Élaboration de programmes', NULL, '2020-09-29 16:22:43'),
(44, NULL, 'Élevage', NULL, '2020-09-29 16:22:43'),
(45, NULL, 'Emballages et conteneurs', NULL, '2020-09-29 16:22:43'),
(46, NULL, 'Enseignement supérieur', NULL, '2020-09-29 16:22:43'),
(47, NULL, 'Entreposage, stockage', NULL, '2020-09-29 16:22:43'),
(48, NULL, 'Environnement et énergies renouvelables', NULL, '2020-09-29 16:22:43'),
(49, NULL, 'Équipements collectifs', NULL, '2020-09-29 16:22:43'),
(50, NULL, 'Équipements et services de loisirs', NULL, '2020-09-29 16:26:42'),
(51, NULL, 'Équipements ferroviaires', NULL, '2020-09-29 16:26:42'),
(52, NULL, 'Équipements médicaux', NULL, '2020-09-29 16:26:42'),
(53, NULL, 'Études de marché', NULL, '2020-09-29 16:26:42'),
(54, NULL, 'Études/recherche', NULL, '2020-09-29 16:26:42'),
(55, NULL, 'Externalisation/délocalisation', NULL, '2020-09-29 16:26:42'),
(56, NULL, 'Films d’animation', NULL, '2020-09-29 16:26:42'),
(57, NULL, 'Formation à distance', NULL, '2020-09-29 16:26:42'),
(58, NULL, 'Formation primaire/secondaire', NULL, '2020-09-29 16:26:42'),
(59, NULL, 'Formation professionnelle et coaching', NULL, '2020-09-29 16:26:42'),
(60, NULL, 'Génie civil', NULL, '2020-09-29 16:26:42'),
(61, NULL, 'Gestion de portefeuilles', NULL, '2020-09-29 16:26:42'),
(62, NULL, 'Gestion des associations et fondations', NULL, '2020-09-29 16:26:42'),
(63, NULL, 'Grande distribution', NULL, '2020-09-29 16:26:42'),
(64, NULL, 'Hôpitaux et centres de soins', NULL, '2020-09-29 16:26:42'),
(65, NULL, 'Hôtellerie et hébergement', NULL, '2020-09-29 16:26:42'),
(66, NULL, 'Humanitaire', NULL, '2020-09-29 16:26:42'),
(67, NULL, 'Immobilier', NULL, '2020-09-29 16:26:42'),
(68, NULL, 'Immobilier commercial', NULL, '2020-09-29 16:26:42'),
(69, NULL, 'Import et export', NULL, '2020-09-29 16:26:42'),
(70, NULL, 'Imprimerie, reproduction', NULL, '2020-09-29 16:26:42'),
(71, NULL, 'Industrie automobile', NULL, '2020-09-29 16:26:42'),
(72, NULL, 'Industrie bois et papiers', NULL, '2020-09-29 16:26:42'),
(73, NULL, 'Industrie composants électriques/électroniques', NULL, '2020-09-29 16:26:42'),
(74, NULL, 'Industrie du cinéma', NULL, '2020-09-29 16:26:42'),
(75, NULL, 'Industrie pharmaceutique', NULL, '2020-09-29 16:26:42'),
(76, NULL, 'Industrie textile', NULL, '2020-09-29 16:26:42'),
(77, NULL, 'Ingénierie du mécénat', NULL, '2020-09-29 16:26:42'),
(78, NULL, 'Ingénierie mécanique ou industrielle', NULL, '2020-09-29 16:26:42'),
(79, NULL, 'Institutions judiciaires', NULL, '2020-09-29 16:26:42'),
(80, NULL, 'Institutions religieuses', NULL, '2020-09-29 16:26:42'),
(81, NULL, 'Internet', NULL, '2020-09-29 19:10:49'),
(82, NULL, 'Jeux d’argent et casinos', NULL, '2020-09-29 19:10:49'),
(83, NULL, 'Jeux électroniques', NULL, '2020-09-29 19:10:49'),
(84, NULL, 'Logiciels informatiques', NULL, '2020-09-29 19:10:49'),
(85, NULL, 'Logistique et chaîne d’approvisionnement', NULL, '2020-09-29 19:10:49'),
(86, NULL, 'Loisirs, voyages et tourisme', NULL, '2020-09-29 19:10:49'),
(87, NULL, 'Machines et équipements', NULL, '2020-09-29 19:10:49'),
(88, NULL, 'Mandat législatif', NULL, '2020-09-29 19:10:49'),
(89, NULL, 'Mandat politique', NULL, '2020-09-29 19:10:49'),
(90, NULL, 'Marchés des capitaux', NULL, '2020-09-29 19:10:49'),
(91, NULL, 'Marketing et publicité', NULL, '2020-09-29 19:10:49'),
(92, NULL, 'Matériaux de construction', NULL, '2020-09-29 19:10:49'),
(93, NULL, 'Matériel informatique', NULL, '2020-09-29 19:10:49'),
(94, NULL, 'Matières premières', NULL, '2020-09-29 19:10:49'),
(95, NULL, 'Médecines alternatives', NULL, '2020-09-29 19:10:49'),
(96, NULL, 'Médias en ligne', NULL, '2020-09-29 19:10:49'),
(97, NULL, 'Médias radio et télédiffusés', NULL, '2020-09-29 19:10:49'),
(98, NULL, 'Messageries et fret', NULL, '2020-09-29 19:10:49'),
(99, NULL, 'Meubles', NULL, '2020-09-29 19:10:49'),
(100, NULL, 'Mines et métaux', NULL, '2020-09-29 19:10:49'),
(101, NULL, 'Musées et institutions culturelles', NULL, '2020-09-29 19:10:49'),
(102, NULL, 'Musique', NULL, '2020-09-29 19:10:49'),
(103, NULL, 'Nanotechnologies', NULL, '2020-09-29 19:10:49'),
(104, NULL, 'Organisation', NULL, '2020-09-29 19:10:49'),
(105, NULL, 'Parti politique', NULL, '2020-09-29 19:10:49'),
(106, NULL, 'Pêche', NULL, '2020-09-29 19:10:49'),
(107, NULL, 'Pétrole et énergie', NULL, '2020-09-29 19:10:49'),
(108, NULL, 'Photographie', NULL, '2020-09-29 19:10:49'),
(109, NULL, 'Plastiques', NULL, '2020-09-29 19:10:49'),
(110, NULL, 'Police/gendarmerie', NULL, '2020-09-29 19:10:49'),
(111, NULL, 'Politiques publiques', NULL, '2020-09-29 19:13:08'),
(112, NULL, 'Presse écrite', NULL, '2020-09-29 19:13:08'),
(113, NULL, 'Production audiovisuelle', NULL, '2020-09-29 19:13:08'),
(114, NULL, 'Produits électroniques grand public', NULL, '2020-09-29 19:13:08'),
(115, NULL, 'Professions médicales', NULL, '2020-09-29 19:13:08'),
(116, NULL, 'Recrutement', NULL, '2020-09-29 19:13:08'),
(117, NULL, 'Règlement extrajudiciaire de conflits', NULL, '2020-09-29 19:13:08'),
(118, NULL, 'Relations publiques et communication', NULL, '2020-09-29 19:13:08'),
(119, NULL, 'Réseaux informatiques', NULL, '2020-09-29 19:13:08'),
(120, NULL, 'Ressources humaines', NULL, '2020-09-29 19:13:08'),
(121, NULL, 'Restaurants', NULL, '2020-09-29 19:13:08'),
(122, NULL, 'Restauration collective', NULL, '2020-09-29 19:13:08'),
(123, NULL, 'Santé, forme et bien-être', NULL, '2020-09-29 19:13:08'),
(124, NULL, 'Secteur laitier', NULL, '2020-09-29 19:13:08'),
(125, NULL, 'Secteur médico-psychologique', NULL, '2020-09-29 19:13:08'),
(126, NULL, 'Sécurité civile', NULL, '2020-09-29 19:13:08'),
(127, NULL, 'Sécurité et enquêtes', NULL, '2020-09-29 19:13:08'),
(128, NULL, 'Sécurité informatique et des réseaux', NULL, '2020-09-29 19:13:08'),
(129, NULL, 'Semi-conducteurs', NULL, '2020-09-29 19:13:08'),
(130, NULL, 'Services à la personne', NULL, '2020-09-29 19:13:08'),
(131, NULL, 'Services aux consommateurs', NULL, '2020-09-29 19:13:08'),
(132, NULL, 'Services d’information', NULL, '2020-09-29 19:13:08'),
(133, NULL, 'Services d’investissement', NULL, '2020-09-29 19:13:08'),
(134, NULL, 'Services financiers', NULL, '2020-09-29 19:13:08'),
(135, NULL, 'Services juridiques', NULL, '2020-09-29 19:13:08'),
(136, NULL, 'Services pour l’environnement', NULL, '2020-09-29 19:13:08'),
(137, NULL, 'Sports', NULL, '2020-09-29 19:13:08'),
(138, NULL, 'Tabac', NULL, '2020-09-29 19:13:08'),
(139, NULL, 'Technologies et services de l’information', NULL, '2020-09-29 19:13:08'),
(140, NULL, 'Technologies sans fil', NULL, '2020-09-29 19:13:08'),
(141, NULL, 'Télécommunications', NULL, '2020-09-29 19:13:47'),
(142, NULL, 'Traduction et adaptation', NULL, '2020-09-29 19:13:47'),
(143, NULL, 'Transports maritimes', NULL, '2020-09-29 19:13:47'),
(144, NULL, 'Transports routiers et ferroviaires', NULL, '2020-09-29 19:13:47'),
(145, NULL, 'Verres, céramiques et ciments', NULL, '2020-09-29 19:13:47'),
(146, NULL, 'Vétérinaire', NULL, '2020-09-29 19:13:47'),
(147, NULL, 'Vins et spiritueux', NULL, '2020-09-29 19:13:47');

-- --------------------------------------------------------

--
-- Structure de la table `worker_individual`
--

CREATE TABLE `worker_individual` (
  `win_id` int(11) NOT NULL,
  `win_lk_country` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `win_lk_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `win_lk_fullName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `win_lk_male` tinyint(1) DEFAULT NULL,
  `win_created` int(11) DEFAULT NULL,
  `win_firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `win_lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `win_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `win_gdpr` datetime DEFAULT NULL,
  `win_lk_nbConnections` int(11) DEFAULT NULL,
  `win_lk_contacted` tinyint(1) DEFAULT NULL,
  `win_createdBy` int(11) DEFAULT NULL,
  `win_inserted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`act_id`),
  ADD KEY `IDX_AC74095A95B7A1D` (`process_pro_id`),
  ADD KEY `IDX_AC74095A98B600AE` (`institution_process_id`),
  ADD KEY `IDX_AC74095AF10DACEE` (`organization_org_id`);

--
-- Index pour la table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`asw_id`),
  ADD KEY `IDX_DADD4A25AFBE78D8` (`survey_field_sfi_id`),
  ADD KEY `IDX_DADD4A257D7E0D2A` (`survey_sur_id`),
  ADD KEY `IDX_DADD4A252D58F79B` (`activity_user_par_id`);

--
-- Index pour la table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`cit_id`),
  ADD KEY `IDX_2D5B02342378CB28` (`state_sta_id`),
  ADD KEY `IDX_2D5B0234A1599B34` (`country_cou_id`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`cli_id`),
  ADD KEY `IDX_C7440455F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_C7440455A12537A8` (`client_org_id`),
  ADD KEY `IDX_C7440455A8C102B2` (`worker_firm_wfi_id`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`con_id`);

--
-- Index pour la table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`cou_id`);

--
-- Index pour la table `criterion`
--
ALTER TABLE `criterion`
  ADD PRIMARY KEY (`crt_id`),
  ADD KEY `IDX_7C822271935EEA40` (`stage_stg_id`),
  ADD KEY `IDX_7C822271F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_7C822271CE87AAFC` (`criterion_name_cna_id`),
  ADD KEY `IDX_7C822271D0D26293` (`output_out_id`);

--
-- Index pour la table `criterion_group`
--
ALTER TABLE `criterion_group`
  ADD PRIMARY KEY (`cgp_id`),
  ADD KEY `IDX_863CE5DDF10DACEE` (`organization_org_id`),
  ADD KEY `IDX_863CE5DDB232D839` (`department_dpt_id`);

--
-- Index pour la table `criterion_name`
--
ALTER TABLE `criterion_name`
  ADD PRIMARY KEY (`cna_id`),
  ADD KEY `IDX_ACB459C3E2C46BF8` (`icon_ico_id`),
  ADD KEY `IDX_ACB459C3F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_ACB459C3B232D839` (`department_dpt_id`),
  ADD KEY `IDX_ACB459C33FC278E3` (`criterion_group_cgp_id`);

--
-- Index pour la table `decision`
--
ALTER TABLE `decision`
  ADD PRIMARY KEY (`dec_id`),
  ADD KEY `IDX_84ACBE48F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_84ACBE4883D3BB28` (`activity_act_id`),
  ADD KEY `IDX_84ACBE48935EEA40` (`stage_stg_id`);

--
-- Index pour la table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`dpt_id`),
  ADD KEY `IDX_CD1DE18AF10DACEE` (`organization_org_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `document_author`
--
ALTER TABLE `document_author`
  ADD PRIMARY KEY (`dau_id`),
  ADD KEY `IDX_3CD69AEC59C8305` (`event_document_evd_id`),
  ADD KEY `IDX_3CD69AE693BC8C6` (`user_usr_id`);

--
-- Index pour la table `dynamic_translation`
--
ALTER TABLE `dynamic_translation`
  ADD PRIMARY KEY (`dtr_id`),
  ADD KEY `IDX_72ECA172F10DACEE` (`organization_org_id`);

--
-- Index pour la table `element_update`
--
ALTER TABLE `element_update`
  ADD PRIMARY KEY (`upd_id`),
  ADD KEY `IDX_11323CECB232D839` (`department_dpt_id`),
  ADD KEY `IDX_11323CECB9E63123` (`position_pos_id`),
  ADD KEY `IDX_11323CEC7834077D` (`institution_process_inp_id`),
  ADD KEY `IDX_11323CEC83D3BB28` (`activity_act_id`),
  ADD KEY `IDX_11323CEC935EEA40` (`stage_stg_id`),
  ADD KEY `IDX_11323CEC38084664` (`event_eve_id`),
  ADD KEY `IDX_11323CECC59C8305` (`event_document_evd_id`),
  ADD KEY `IDX_11323CEC3AAD808B` (`event_comment_evc_id`),
  ADD KEY `IDX_11323CEC62D0DC74` (`output_otp_id`),
  ADD KEY `IDX_11323CECD26A20E6` (`criterion_crt_id`),
  ADD KEY `IDX_11323CEC3CE1008F` (`participation_par_id`),
  ADD KEY `IDX_11323CECA2EFDD58` (`result_res_id`),
  ADD KEY `IDX_11323CEC693BC8C6` (`user_usr_id`);

--
-- Index pour la table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`eve_id`),
  ADD KEY `IDX_3BAE0AA783D3BB28` (`activity_act_id`),
  ADD KEY `IDX_3BAE0AA7935EEA40` (`stage_stg_id`),
  ADD KEY `IDX_3BAE0AA7F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_3BAE0AA76A6AB974` (`event_type_evt_id`);

--
-- Index pour la table `event_comment`
--
ALTER TABLE `event_comment`
  ADD PRIMARY KEY (`evc_id`),
  ADD KEY `IDX_1123FBC338084664` (`event_eve_id`),
  ADD KEY `IDX_1123FBC3D0695A70` (`evc_author`),
  ADD KEY `IDX_1123FBC3727ACA70` (`parent_id`),
  ADD KEY `IDX_1123FBC3F10DACEE` (`organization_org_id`);

--
-- Index pour la table `event_document`
--
ALTER TABLE `event_document`
  ADD PRIMARY KEY (`evd_id`),
  ADD KEY `IDX_9E8563A638084664` (`event_eve_id`),
  ADD KEY `IDX_9E8563A6F10DACEE` (`organization_org_id`);

--
-- Index pour la table `event_group`
--
ALTER TABLE `event_group`
  ADD PRIMARY KEY (`evg_id`),
  ADD KEY `IDX_2CDBF5E9F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_2CDBF5E9B232D839` (`department_dpt_id`),
  ADD KEY `IDX_2CDBF5E93649DEE8` (`event_group_name_egn_id`);

--
-- Index pour la table `event_group_name`
--
ALTER TABLE `event_group_name`
  ADD PRIMARY KEY (`egn_id`);

--
-- Index pour la table `event_name`
--
ALTER TABLE `event_name`
  ADD PRIMARY KEY (`evn_id`),
  ADD KEY `IDX_41E832AD3649DEE8` (`event_group_name_egn_id`),
  ADD KEY `IDX_41E832ADE2C46BF8` (`icon_ico_id`);

--
-- Index pour la table `event_type`
--
ALTER TABLE `event_type`
  ADD PRIMARY KEY (`evt_id`),
  ADD KEY `IDX_93151B82E2C46BF8` (`icon_ico_id`),
  ADD KEY `IDX_93151B82F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_93151B829E0D2B9E` (`event_group_evg_id`),
  ADD KEY `IDX_93151B827061F6FA` (`event_name_evn_id`);

--
-- Index pour la table `external_user`
--
ALTER TABLE `external_user`
  ADD PRIMARY KEY (`ext_id`),
  ADD KEY `IDX_188CB665693BC8C6` (`user_usr_id`),
  ADD KEY `IDX_188CB665E9E8A903` (`client_cli_id`);

--
-- Index pour la table `generated_error`
--
ALTER TABLE `generated_error`
  ADD PRIMARY KEY (`err_id`);

--
-- Index pour la table `generated_image`
--
ALTER TABLE `generated_image`
  ADD PRIMARY KEY (`gim_id`),
  ADD UNIQUE KEY `UNIQ_6E67FC40CE87AAFC` (`criterion_name_cna_id`);

--
-- Index pour la table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`grd_id`),
  ADD KEY `IDX_595AAE34517AF523` (`activity_user_team_tea_id`),
  ADD KEY `IDX_595AAE342463E031` (`activity_user_user_usr_id`),
  ADD KEY `IDX_595AAE3483D3BB28` (`activity_act_id`),
  ADD KEY `IDX_595AAE34D26A20E6` (`criterion_crt_id`),
  ADD KEY `IDX_595AAE34935EEA40` (`stage_stg_id`);

--
-- Index pour la table `icon`
--
ALTER TABLE `icon`
  ADD PRIMARY KEY (`ico_id`);

--
-- Index pour la table `institution_process`
--
ALTER TABLE `institution_process`
  ADD PRIMARY KEY (`inp_id`),
  ADD KEY `IDX_E2E3EE04F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_E2E3EE0495B7A1D` (`process_pro_id`),
  ADD KEY `IDX_E2E3EE04727ACA70` (`parent_id`);

--
-- Index pour la table `iprocess_criterion`
--
ALTER TABLE `iprocess_criterion`
  ADD PRIMARY KEY (`crt_id`),
  ADD UNIQUE KEY `UNIQ_57F78ED4CE87AAFC` (`criterion_name_cna_id`),
  ADD KEY `IDX_57F78ED46E3003B6` (`iprocess_stage_stg_id`),
  ADD KEY `IDX_57F78ED4B2CC3CA8` (`iprocess_inp_id`);

--
-- Index pour la table `iprocess_participation`
--
ALTER TABLE `iprocess_participation`
  ADD PRIMARY KEY (`par_id`),
  ADD KEY `IDX_FF76DB8F1C22DDD4` (`team_tea_id`),
  ADD KEY `IDX_FF76DB8FB2CC3CA8` (`iprocess_inp_id`),
  ADD KEY `IDX_FF76DB8F6E3003B6` (`iprocess_stage_stg_id`),
  ADD KEY `IDX_FF76DB8FEF264A3A` (`iprocess_criterion_crt_id`),
  ADD KEY `IDX_FF76DB8F693BC8C6` (`user_usr_id`),
  ADD KEY `IDX_FF76DB8F293D81A2` (`external_useR_ext_usr_id`);

--
-- Index pour la table `iprocess_stage`
--
ALTER TABLE `iprocess_stage`
  ADD PRIMARY KEY (`stg_id`),
  ADD KEY `IDX_6864F1B4B2CC3CA8` (`iprocess_inp_id`),
  ADD KEY `IDX_6864F1B4F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_6864F1B4E7127ABC` (`stg_master_user_id`);

--
-- Index pour la table `mail`
--
ALTER TABLE `mail`
  ADD PRIMARY KEY (`mail_id`),
  ADD KEY `IDX_5126AC48693BC8C6` (`user_usr_id`),
  ADD KEY `IDX_5126AC48B1623D83` (`worker_individual_win_id`),
  ADD KEY `IDX_5126AC48F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_5126AC48A8C102B2` (`worker_firm_wfi_id`),
  ADD KEY `IDX_5126AC4883D3BB28` (`activity_act_id`),
  ADD KEY `IDX_5126AC48935EEA40` (`stage_stg_id`);

--
-- Index pour la table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`mem_id`),
  ADD KEY `IDX_70E4FA781C22DDD4` (`team_tea_id`),
  ADD KEY `IDX_70E4FA78693BC8C6` (`user_usr_id`),
  ADD KEY `IDX_70E4FA78310C5A5A` (`external_user_ext_usr_id`);

--
-- Index pour la table `option_name`
--
ALTER TABLE `option_name`
  ADD PRIMARY KEY (`ona_id`);

--
-- Index pour la table `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`org_id`),
  ADD UNIQUE KEY `UNIQ_C1EE637C2951486D` (`payment_usr_id`),
  ADD KEY `IDX_C1EE637CA8C102B2` (`worker_firm_wfi_id`);

--
-- Index pour la table `organization_payment_method`
--
ALTER TABLE `organization_payment_method`
  ADD PRIMARY KEY (`opm_id`),
  ADD KEY `IDX_8B734245F10DACEE` (`organization_org_id`);

--
-- Index pour la table `organization_user_option`
--
ALTER TABLE `organization_user_option`
  ADD PRIMARY KEY (`opt_id`),
  ADD KEY `IDX_F88BFF654B493BD` (`option_name_ona_id`),
  ADD KEY `IDX_F88BFF6F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_F88BFF6B232D839` (`department_dpt_id`),
  ADD KEY `IDX_F88BFF6B9E63123` (`position_pos_id`),
  ADD KEY `IDX_F88BFF66AFC588` (`title_tit_id`),
  ADD KEY `IDX_F88BFF6693BC8C6` (`user_usr_id`);

--
-- Index pour la table `otpuser`
--
ALTER TABLE `otpuser`
  ADD PRIMARY KEY (`otp_id`),
  ADD KEY `IDX_2524453FA99EEA35` (`otp_organization`);

--
-- Index pour la table `output`
--
ALTER TABLE `output`
  ADD PRIMARY KEY (`otp_id`),
  ADD UNIQUE KEY `UNIQ_CCDE149E7D7E0D2A` (`survey_sur_id`),
  ADD KEY `IDX_CCDE149E935EEA40` (`stage_stg_id`);

--
-- Index pour la table `participation`
--
ALTER TABLE `participation`
  ADD PRIMARY KEY (`par_id`),
  ADD KEY `IDX_AB55E24F1C22DDD4` (`team_tea_id`),
  ADD KEY `IDX_AB55E24F83D3BB28` (`activity_act_id`),
  ADD KEY `IDX_AB55E24F935EEA40` (`stage_stg_id`),
  ADD KEY `IDX_AB55E24FD26A20E6` (`criterion_crt_id`),
  ADD KEY `IDX_AB55E24F7D7E0D2A` (`survey_sur_id`),
  ADD KEY `IDX_AB55E24F693BC8C6` (`user_usr_id`),
  ADD KEY `IDX_AB55E24F310C5A5A` (`external_user_ext_usr_id`);

--
-- Index pour la table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`pos_id`),
  ADD KEY `IDX_462CE4F5F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_462CE4F5B232D839` (`department_dpt_id`),
  ADD KEY `IDX_462CE4F53AF408B7` (`weight_wgt_id`);

--
-- Index pour la table `process`
--
ALTER TABLE `process`
  ADD PRIMARY KEY (`pro_id`),
  ADD KEY `IDX_861D1896F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_861D1896727ACA70` (`parent_id`),
  ADD KEY `IDX_861D1896E2C46BF8` (`icon_ico_id`);

--
-- Index pour la table `process_criterion`
--
ALTER TABLE `process_criterion`
  ADD PRIMARY KEY (`crt_id`),
  ADD UNIQUE KEY `UNIQ_E6CB6099CE87AAFC` (`criterion_name_cna_id`),
  ADD KEY `IDX_E6CB6099F9E0BF71` (`process_stage_stg_id`),
  ADD KEY `IDX_E6CB609995B7A1D` (`process_pro_id`);

--
-- Index pour la table `process_stage`
--
ALTER TABLE `process_stage`
  ADD PRIMARY KEY (`stg_id`),
  ADD KEY `IDX_9420BE9895B7A1D` (`process_pro_id`),
  ADD KEY `IDX_9420BE98F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_9420BE98AFCB9C11` (`stg_master_usr_id`);

--
-- Index pour la table `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`rnk_id`),
  ADD KEY `IDX_80B839D0FCBCC329` (`rnk_activity`),
  ADD KEY `IDX_80B839D08D0A8B3D` (`rnk_stage`),
  ADD KEY `IDX_80B839D0B5DECA3D` (`rnk_criterion`),
  ADD KEY `IDX_80B839D04C14732A` (`rnk_organization`),
  ADD KEY `IDX_80B839D09B266BD9` (`rnk_user_usr_id`);

--
-- Index pour la table `ranking_history`
--
ALTER TABLE `ranking_history`
  ADD PRIMARY KEY (`rkh_id`),
  ADD KEY `IDX_2F6B262166DCD468` (`rkh_activity`),
  ADD KEY `IDX_2F6B2621F4029FC4` (`rkh_stage`),
  ADD KEY `IDX_2F6B2621B49FDB2C` (`rkh_criterion`),
  ADD KEY `IDX_2F6B2621A24F697A` (`rkh_user_usr_id`);

--
-- Index pour la table `ranking_team`
--
ALTER TABLE `ranking_team`
  ADD PRIMARY KEY (`rkt_id`),
  ADD KEY `IDX_6DDD29955B4EB50E` (`rkt_activity`),
  ADD KEY `IDX_6DDD2995547C697E` (`rkt_stage`),
  ADD KEY `IDX_6DDD299510738D20` (`rkt_criterion`),
  ADD KEY `IDX_6DDD29951C22DDD4` (`team_tea_id`),
  ADD KEY `IDX_6DDD29954FB5453B` (`rkt_organization`);

--
-- Index pour la table `ranking_team_history`
--
ALTER TABLE `ranking_team_history`
  ADD PRIMARY KEY (`rth_id`),
  ADD KEY `IDX_A1B5EBE15BD2E465` (`rth_activity`),
  ADD KEY `IDX_A1B5EBE15199BC43` (`rth_stage`),
  ADD KEY `IDX_A1B5EBE1CA13A9A1` (`rth_criterion`),
  ADD KEY `IDX_A1B5EBE11C22DDD4` (`team_tea_id`),
  ADD KEY `IDX_A1B5EBE11318B4A8` (`rth_organization`);

--
-- Index pour la table `record`
--
ALTER TABLE `record`
  ADD PRIMARY KEY (`rec_id`);

--
-- Index pour la table `recurring`
--
ALTER TABLE `recurring`
  ADD PRIMARY KEY (`rct_id`),
  ADD KEY `IDX_6C6C02EDF10DACEE` (`organization_org_id`),
  ADD KEY `IDX_6C6C02ED4C86D2E4` (`rec_master_user_id`);

--
-- Index pour la table `result`
--
ALTER TABLE `result`
  ADD PRIMARY KEY (`res_id`),
  ADD KEY `IDX_136AC11383D3BB28` (`activity_act_id`),
  ADD KEY `IDX_136AC113935EEA40` (`stage_stg_id`),
  ADD KEY `IDX_136AC113D26A20E6` (`criterion_crt_id`),
  ADD KEY `IDX_136AC113A76ED395` (`user_id`),
  ADD KEY `IDX_136AC113E5639263` (`externalUser_id`);

--
-- Index pour la table `result_project`
--
ALTER TABLE `result_project`
  ADD PRIMARY KEY (`rsp_id`),
  ADD KEY `IDX_4D8A520A83D3BB28` (`activity_act_id`),
  ADD KEY `IDX_4D8A520A935EEA40` (`stage_stg_id`),
  ADD KEY `IDX_4D8A520AD26A20E6` (`criterion_crt_id`);

--
-- Index pour la table `result_team`
--
ALTER TABLE `result_team`
  ADD PRIMARY KEY (`rst_id`),
  ADD KEY `IDX_54DBBD5183D3BB28` (`activity_act_id`),
  ADD KEY `IDX_54DBBD51935EEA40` (`stage_stg_id`),
  ADD KEY `IDX_54DBBD51D26A20E6` (`criterion_crt_id`),
  ADD KEY `IDX_54DBBD511C22DDD4` (`team_tea_id`);

--
-- Index pour la table `stage`
--
ALTER TABLE `stage`
  ADD PRIMARY KEY (`stg_id`),
  ADD UNIQUE KEY `UNIQ_C27C93697D7E0D2A` (`survey_sur_id`),
  ADD KEY `IDX_C27C936983D3BB28` (`activity_act_id`),
  ADD KEY `IDX_C27C9369F10DACEE` (`organization_org_id`);

--
-- Index pour la table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`sta_id`),
  ADD KEY `IDX_A393D2FBA1599B34` (`country_cou_id`);

--
-- Index pour la table `survey`
--
ALTER TABLE `survey`
  ADD PRIMARY KEY (`sur_id`),
  ADD UNIQUE KEY `UNIQ_AD5F9BFC935EEA40` (`stage_stg_id`),
  ADD KEY `IDX_AD5F9BFCF10DACEE` (`organization_org_id`);

--
-- Index pour la table `survey_field`
--
ALTER TABLE `survey_field`
  ADD PRIMARY KEY (`sfi_id`),
  ADD KEY `IDX_5785B760D26A20E6` (`criterion_crt_id`),
  ADD KEY `IDX_5785B7607D7E0D2A` (`survey_sur_id`);

--
-- Index pour la table `survey_field_parameter`
--
ALTER TABLE `survey_field_parameter`
  ADD PRIMARY KEY (`sfp_id`),
  ADD KEY `IDX_6740C5AEAFBE78D8` (`survey_field_sfi_id`);

--
-- Index pour la table `target`
--
ALTER TABLE `target`
  ADD PRIMARY KEY (`tgt_id`),
  ADD UNIQUE KEY `UNIQ_466F2FFCCE87AAFC` (`criterion_name_cna_id`),
  ADD UNIQUE KEY `UNIQ_466F2FFCD26A20E6` (`criterion_crt_id`),
  ADD KEY `IDX_466F2FFCF10DACEE` (`organization_org_id`),
  ADD KEY `IDX_466F2FFCB232D839` (`department_dpt_id`),
  ADD KEY `IDX_466F2FFCB9E63123` (`position_pos_id`),
  ADD KEY `IDX_466F2FFC6AFC588` (`title_tit_id`),
  ADD KEY `IDX_466F2FFC693BC8C6` (`user_usr_id`),
  ADD KEY `IDX_466F2FFC1C22DDD4` (`team_tea_id`);

--
-- Index pour la table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`tea_id`),
  ADD KEY `IDX_C4E0A61FF10DACEE` (`organization_org_id`);

--
-- Index pour la table `title`
--
ALTER TABLE `title`
  ADD PRIMARY KEY (`tit_id`),
  ADD KEY `IDX_2B36786BF10DACEE` (`organization_org_id`),
  ADD KEY `IDX_2B36786B3AF408B7` (`weight_wgt_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`usr_id`),
  ADD UNIQUE KEY `UNIQ_8D93D649B1623D83` (`worker_individual_win_id`),
  ADD KEY `IDX_8D93D649E38ABCB8` (`usr_superior`),
  ADD KEY `IDX_8D93D6493AF408B7` (`weight_wgt_id`),
  ADD KEY `IDX_8D93D649B9E63123` (`position_pos_id`),
  ADD KEY `IDX_8D93D649B232D839` (`department_dpt_id`),
  ADD KEY `IDX_8D93D6496AFC588` (`title_tit_id`),
  ADD KEY `IDX_8D93D649F10DACEE` (`organization_org_id`),
  ADD KEY `IDX_8D93D6496AB1CC56` (`user_global_usg_id`);

--
-- Index pour la table `user_global`
--
ALTER TABLE `user_global`
  ADD PRIMARY KEY (`usg_id`);

--
-- Index pour la table `user_master`
--
ALTER TABLE `user_master`
  ADD PRIMARY KEY (`usm_id`),
  ADD KEY `IDX_485BB8EBF10DACEE` (`organization_org_id`),
  ADD KEY `IDX_485BB8EBB232D839` (`department_dpt_id`),
  ADD KEY `IDX_485BB8EBB9E63123` (`position_pos_id`),
  ADD KEY `IDX_485BB8EB7834077D` (`institution_process_inp_id`),
  ADD KEY `IDX_485BB8EB83D3BB28` (`activity_act_id`),
  ADD KEY `IDX_485BB8EB935EEA40` (`stage_stg_id`),
  ADD KEY `IDX_485BB8EB38084664` (`event_eve_id`),
  ADD KEY `IDX_485BB8EB62D0DC74` (`output_otp_id`),
  ADD KEY `IDX_485BB8EB693BC8C6` (`user_usr_id`);

--
-- Index pour la table `weight`
--
ALTER TABLE `weight`
  ADD PRIMARY KEY (`wgt_id`),
  ADD KEY `IDX_7CD5541F4837C1B` (`org_id`);

--
-- Index pour la table `worker_experience`
--
ALTER TABLE `worker_experience`
  ADD PRIMARY KEY (`wex_id`),
  ADD KEY `IDX_81AC8070D4203719` (`worker_individual_wid`),
  ADD KEY `IDX_81AC80708780C6FF` (`worker_firm_wfi`);

--
-- Index pour la table `worker_firm`
--
ALTER TABLE `worker_firm`
  ADD PRIMARY KEY (`wfi_id`),
  ADD KEY `IDX_9DC037AC2391AC72` (`city_cit_id`),
  ADD KEY `IDX_9DC037AC2378CB28` (`state_sta_id`),
  ADD KEY `IDX_9DC037ACA1599B34` (`country_cou_id`),
  ADD KEY `IDX_9DC037AC727ACA70` (`parent_id`),
  ADD KEY `IDX_9DC037AC255DBC04` (`worker_firm_sector_wfs_id`);

--
-- Index pour la table `worker_firm_competency`
--
ALTER TABLE `worker_firm_competency`
  ADD PRIMARY KEY (`wfc_id`),
  ADD KEY `IDX_9E94D7EEA8C102B2` (`worker_firm_wfi_id`);

--
-- Index pour la table `worker_firm_location`
--
ALTER TABLE `worker_firm_location`
  ADD PRIMARY KEY (`wfl_id`);

--
-- Index pour la table `worker_firm_sector`
--
ALTER TABLE `worker_firm_sector`
  ADD PRIMARY KEY (`wfs_id`),
  ADD KEY `IDX_8DDB61C6E2C46BF8` (`icon_ico_id`);

--
-- Index pour la table `worker_individual`
--
ALTER TABLE `worker_individual`
  ADD PRIMARY KEY (`win_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activity`
--
ALTER TABLE `activity`
  MODIFY `act_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT pour la table `answer`
--
ALTER TABLE `answer`
  MODIFY `asw_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `city`
--
ALTER TABLE `city`
  MODIFY `cit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `cli_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=253;

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `con_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `country`
--
ALTER TABLE `country`
  MODIFY `cou_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT pour la table `criterion`
--
ALTER TABLE `criterion`
  MODIFY `crt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `criterion_group`
--
ALTER TABLE `criterion_group`
  MODIFY `cgp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=269;

--
-- AUTO_INCREMENT pour la table `criterion_name`
--
ALTER TABLE `criterion_name`
  MODIFY `cna_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=811;

--
-- AUTO_INCREMENT pour la table `decision`
--
ALTER TABLE `decision`
  MODIFY `dec_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `department`
--
ALTER TABLE `department`
  MODIFY `dpt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `document_author`
--
ALTER TABLE `document_author`
  MODIFY `dau_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `dynamic_translation`
--
ALTER TABLE `dynamic_translation`
  MODIFY `dtr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `element_update`
--
ALTER TABLE `element_update`
  MODIFY `upd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=293;

--
-- AUTO_INCREMENT pour la table `event`
--
ALTER TABLE `event`
  MODIFY `eve_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT pour la table `event_comment`
--
ALTER TABLE `event_comment`
  MODIFY `evc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT pour la table `event_document`
--
ALTER TABLE `event_document`
  MODIFY `evd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `event_group`
--
ALTER TABLE `event_group`
  MODIFY `evg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=622;

--
-- AUTO_INCREMENT pour la table `event_group_name`
--
ALTER TABLE `event_group_name`
  MODIFY `egn_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `event_name`
--
ALTER TABLE `event_name`
  MODIFY `evn_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `event_type`
--
ALTER TABLE `event_type`
  MODIFY `evt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2803;

--
-- AUTO_INCREMENT pour la table `external_user`
--
ALTER TABLE `external_user`
  MODIFY `ext_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=512;

--
-- AUTO_INCREMENT pour la table `generated_error`
--
ALTER TABLE `generated_error`
  MODIFY `err_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `generated_image`
--
ALTER TABLE `generated_image`
  MODIFY `gim_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `grade`
--
ALTER TABLE `grade`
  MODIFY `grd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `icon`
--
ALTER TABLE `icon`
  MODIFY `ico_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT pour la table `institution_process`
--
ALTER TABLE `institution_process`
  MODIFY `inp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `iprocess_criterion`
--
ALTER TABLE `iprocess_criterion`
  MODIFY `crt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `iprocess_participation`
--
ALTER TABLE `iprocess_participation`
  MODIFY `par_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `iprocess_stage`
--
ALTER TABLE `iprocess_stage`
  MODIFY `stg_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `mail`
--
ALTER TABLE `mail`
  MODIFY `mail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=478;

--
-- AUTO_INCREMENT pour la table `member`
--
ALTER TABLE `member`
  MODIFY `mem_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `option_name`
--
ALTER TABLE `option_name`
  MODIFY `ona_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `organization`
--
ALTER TABLE `organization`
  MODIFY `org_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT pour la table `organization_payment_method`
--
ALTER TABLE `organization_payment_method`
  MODIFY `opm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `organization_user_option`
--
ALTER TABLE `organization_user_option`
  MODIFY `opt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2619;

--
-- AUTO_INCREMENT pour la table `otpuser`
--
ALTER TABLE `otpuser`
  MODIFY `otp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `output`
--
ALTER TABLE `output`
  MODIFY `otp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `participation`
--
ALTER TABLE `participation`
  MODIFY `par_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=355;

--
-- AUTO_INCREMENT pour la table `position`
--
ALTER TABLE `position`
  MODIFY `pos_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `process`
--
ALTER TABLE `process`
  MODIFY `pro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `process_criterion`
--
ALTER TABLE `process_criterion`
  MODIFY `crt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `process_stage`
--
ALTER TABLE `process_stage`
  MODIFY `stg_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ranking`
--
ALTER TABLE `ranking`
  MODIFY `rnk_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ranking_history`
--
ALTER TABLE `ranking_history`
  MODIFY `rkh_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ranking_team`
--
ALTER TABLE `ranking_team`
  MODIFY `rkt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ranking_team_history`
--
ALTER TABLE `ranking_team_history`
  MODIFY `rth_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `record`
--
ALTER TABLE `record`
  MODIFY `rec_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `recurring`
--
ALTER TABLE `recurring`
  MODIFY `rct_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `result`
--
ALTER TABLE `result`
  MODIFY `res_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `result_project`
--
ALTER TABLE `result_project`
  MODIFY `rsp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `result_team`
--
ALTER TABLE `result_team`
  MODIFY `rst_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `stage`
--
ALTER TABLE `stage`
  MODIFY `stg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT pour la table `state`
--
ALTER TABLE `state`
  MODIFY `sta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `survey`
--
ALTER TABLE `survey`
  MODIFY `sur_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `survey_field`
--
ALTER TABLE `survey_field`
  MODIFY `sfi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `survey_field_parameter`
--
ALTER TABLE `survey_field_parameter`
  MODIFY `sfp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `target`
--
ALTER TABLE `target`
  MODIFY `tgt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `team`
--
ALTER TABLE `team`
  MODIFY `tea_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `title`
--
ALTER TABLE `title`
  MODIFY `tit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=314;

--
-- AUTO_INCREMENT pour la table `user_global`
--
ALTER TABLE `user_global`
  MODIFY `usg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT pour la table `user_master`
--
ALTER TABLE `user_master`
  MODIFY `usm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `weight`
--
ALTER TABLE `weight`
  MODIFY `wgt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT pour la table `worker_experience`
--
ALTER TABLE `worker_experience`
  MODIFY `wex_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `worker_firm`
--
ALTER TABLE `worker_firm`
  MODIFY `wfi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT pour la table `worker_firm_competency`
--
ALTER TABLE `worker_firm_competency`
  MODIFY `wfc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `worker_firm_location`
--
ALTER TABLE `worker_firm_location`
  MODIFY `wfl_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `worker_firm_sector`
--
ALTER TABLE `worker_firm_sector`
  MODIFY `wfs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT pour la table `worker_individual`
--
ALTER TABLE `worker_individual`
  MODIFY `win_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `FK_AC74095A95B7A1D` FOREIGN KEY (`process_pro_id`) REFERENCES `process` (`pro_id`),
  ADD CONSTRAINT `FK_AC74095A98B600AE` FOREIGN KEY (`institution_process_id`) REFERENCES `institution_process` (`inp_id`),
  ADD CONSTRAINT `FK_AC74095AF10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `FK_DADD4A252D58F79B` FOREIGN KEY (`activity_user_par_id`) REFERENCES `participation` (`par_id`),
  ADD CONSTRAINT `FK_DADD4A257D7E0D2A` FOREIGN KEY (`survey_sur_id`) REFERENCES `survey` (`sur_id`),
  ADD CONSTRAINT `FK_DADD4A25AFBE78D8` FOREIGN KEY (`survey_field_sfi_id`) REFERENCES `survey_field` (`sfi_id`);

--
-- Contraintes pour la table `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `FK_2D5B02342378CB28` FOREIGN KEY (`state_sta_id`) REFERENCES `state` (`sta_id`),
  ADD CONSTRAINT `FK_2D5B0234A1599B34` FOREIGN KEY (`country_cou_id`) REFERENCES `country` (`cou_id`);

--
-- Contraintes pour la table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `FK_C7440455A12537A8` FOREIGN KEY (`client_org_id`) REFERENCES `organization` (`org_id`),
  ADD CONSTRAINT `FK_C7440455A8C102B2` FOREIGN KEY (`worker_firm_wfi_id`) REFERENCES `worker_firm` (`wfi_id`),
  ADD CONSTRAINT `FK_C7440455F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `criterion`
--
ALTER TABLE `criterion`
  ADD CONSTRAINT `FK_7C822271935EEA40` FOREIGN KEY (`stage_stg_id`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_7C822271CE87AAFC` FOREIGN KEY (`criterion_name_cna_id`) REFERENCES `criterion_name` (`cna_id`),
  ADD CONSTRAINT `FK_7C822271D0D26293` FOREIGN KEY (`output_out_id`) REFERENCES `output` (`otp_id`),
  ADD CONSTRAINT `FK_7C822271F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `criterion_group`
--
ALTER TABLE `criterion_group`
  ADD CONSTRAINT `FK_863CE5DDB232D839` FOREIGN KEY (`department_dpt_id`) REFERENCES `department` (`dpt_id`),
  ADD CONSTRAINT `FK_863CE5DDF10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `criterion_name`
--
ALTER TABLE `criterion_name`
  ADD CONSTRAINT `FK_ACB459C33FC278E3` FOREIGN KEY (`criterion_group_cgp_id`) REFERENCES `criterion_group` (`cgp_id`),
  ADD CONSTRAINT `FK_ACB459C3B232D839` FOREIGN KEY (`department_dpt_id`) REFERENCES `department` (`dpt_id`),
  ADD CONSTRAINT `FK_ACB459C3E2C46BF8` FOREIGN KEY (`icon_ico_id`) REFERENCES `icon` (`ico_id`),
  ADD CONSTRAINT `FK_ACB459C3F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `decision`
--
ALTER TABLE `decision`
  ADD CONSTRAINT `FK_84ACBE4883D3BB28` FOREIGN KEY (`activity_act_id`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `FK_84ACBE48935EEA40` FOREIGN KEY (`stage_stg_id`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_84ACBE48F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `FK_CD1DE18AF10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `document_author`
--
ALTER TABLE `document_author`
  ADD CONSTRAINT `FK_3CD69AE693BC8C6` FOREIGN KEY (`user_usr_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_3CD69AEC59C8305` FOREIGN KEY (`event_document_evd_id`) REFERENCES `event_document` (`evd_id`);

--
-- Contraintes pour la table `dynamic_translation`
--
ALTER TABLE `dynamic_translation`
  ADD CONSTRAINT `FK_72ECA172F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `element_update`
--
ALTER TABLE `element_update`
  ADD CONSTRAINT `FK_11323CEC38084664` FOREIGN KEY (`event_eve_id`) REFERENCES `event` (`eve_id`),
  ADD CONSTRAINT `FK_11323CEC3AAD808B` FOREIGN KEY (`event_comment_evc_id`) REFERENCES `event_comment` (`evc_id`),
  ADD CONSTRAINT `FK_11323CEC3CE1008F` FOREIGN KEY (`participation_par_id`) REFERENCES `participation` (`par_id`),
  ADD CONSTRAINT `FK_11323CEC62D0DC74` FOREIGN KEY (`output_otp_id`) REFERENCES `output` (`otp_id`),
  ADD CONSTRAINT `FK_11323CEC693BC8C6` FOREIGN KEY (`user_usr_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_11323CEC7834077D` FOREIGN KEY (`institution_process_inp_id`) REFERENCES `institution_process` (`inp_id`),
  ADD CONSTRAINT `FK_11323CEC83D3BB28` FOREIGN KEY (`activity_act_id`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `FK_11323CEC935EEA40` FOREIGN KEY (`stage_stg_id`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_11323CECA2EFDD58` FOREIGN KEY (`result_res_id`) REFERENCES `result` (`res_id`),
  ADD CONSTRAINT `FK_11323CECB232D839` FOREIGN KEY (`department_dpt_id`) REFERENCES `department` (`dpt_id`),
  ADD CONSTRAINT `FK_11323CECB9E63123` FOREIGN KEY (`position_pos_id`) REFERENCES `position` (`pos_id`),
  ADD CONSTRAINT `FK_11323CECC59C8305` FOREIGN KEY (`event_document_evd_id`) REFERENCES `event_document` (`evd_id`),
  ADD CONSTRAINT `FK_11323CECD26A20E6` FOREIGN KEY (`criterion_crt_id`) REFERENCES `criterion` (`crt_id`);

--
-- Contraintes pour la table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `FK_3BAE0AA76A6AB974` FOREIGN KEY (`event_type_evt_id`) REFERENCES `event_type` (`evt_id`),
  ADD CONSTRAINT `FK_3BAE0AA783D3BB28` FOREIGN KEY (`activity_act_id`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `FK_3BAE0AA7935EEA40` FOREIGN KEY (`stage_stg_id`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_3BAE0AA7F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `event_comment`
--
ALTER TABLE `event_comment`
  ADD CONSTRAINT `FK_1123FBC338084664` FOREIGN KEY (`event_eve_id`) REFERENCES `event` (`eve_id`),
  ADD CONSTRAINT `FK_1123FBC3727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `event_comment` (`evc_id`),
  ADD CONSTRAINT `FK_1123FBC3D0695A70` FOREIGN KEY (`evc_author`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_1123FBC3F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `event_document`
--
ALTER TABLE `event_document`
  ADD CONSTRAINT `FK_9E8563A638084664` FOREIGN KEY (`event_eve_id`) REFERENCES `event` (`eve_id`),
  ADD CONSTRAINT `FK_9E8563A6F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `event_group`
--
ALTER TABLE `event_group`
  ADD CONSTRAINT `FK_2CDBF5E93649DEE8` FOREIGN KEY (`event_group_name_egn_id`) REFERENCES `event_group_name` (`egn_id`),
  ADD CONSTRAINT `FK_2CDBF5E9B232D839` FOREIGN KEY (`department_dpt_id`) REFERENCES `department` (`dpt_id`),
  ADD CONSTRAINT `FK_2CDBF5E9F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `event_name`
--
ALTER TABLE `event_name`
  ADD CONSTRAINT `FK_41E832AD3649DEE8` FOREIGN KEY (`event_group_name_egn_id`) REFERENCES `event_group_name` (`egn_id`),
  ADD CONSTRAINT `FK_41E832ADE2C46BF8` FOREIGN KEY (`icon_ico_id`) REFERENCES `icon` (`ico_id`);

--
-- Contraintes pour la table `event_type`
--
ALTER TABLE `event_type`
  ADD CONSTRAINT `FK_93151B827061F6FA` FOREIGN KEY (`event_name_evn_id`) REFERENCES `event_name` (`evn_id`),
  ADD CONSTRAINT `FK_93151B829E0D2B9E` FOREIGN KEY (`event_group_evg_id`) REFERENCES `event_group` (`evg_id`),
  ADD CONSTRAINT `FK_93151B82E2C46BF8` FOREIGN KEY (`icon_ico_id`) REFERENCES `icon` (`ico_id`),
  ADD CONSTRAINT `FK_93151B82F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `external_user`
--
ALTER TABLE `external_user`
  ADD CONSTRAINT `FK_188CB665693BC8C6` FOREIGN KEY (`user_usr_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_188CB665E9E8A903` FOREIGN KEY (`client_cli_id`) REFERENCES `client` (`cli_id`);

--
-- Contraintes pour la table `generated_image`
--
ALTER TABLE `generated_image`
  ADD CONSTRAINT `FK_6E67FC40CE87AAFC` FOREIGN KEY (`criterion_name_cna_id`) REFERENCES `criterion_name` (`cna_id`);

--
-- Contraintes pour la table `grade`
--
ALTER TABLE `grade`
  ADD CONSTRAINT `FK_595AAE342463E031` FOREIGN KEY (`activity_user_user_usr_id`) REFERENCES `participation` (`par_id`),
  ADD CONSTRAINT `FK_595AAE34517AF523` FOREIGN KEY (`activity_user_team_tea_id`) REFERENCES `team` (`tea_id`),
  ADD CONSTRAINT `FK_595AAE3483D3BB28` FOREIGN KEY (`activity_act_id`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `FK_595AAE34935EEA40` FOREIGN KEY (`stage_stg_id`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_595AAE34D26A20E6` FOREIGN KEY (`criterion_crt_id`) REFERENCES `criterion` (`crt_id`);

--
-- Contraintes pour la table `institution_process`
--
ALTER TABLE `institution_process`
  ADD CONSTRAINT `FK_E2E3EE04727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `institution_process` (`inp_id`),
  ADD CONSTRAINT `FK_E2E3EE0495B7A1D` FOREIGN KEY (`process_pro_id`) REFERENCES `process` (`pro_id`),
  ADD CONSTRAINT `FK_E2E3EE04F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `iprocess_criterion`
--
ALTER TABLE `iprocess_criterion`
  ADD CONSTRAINT `FK_57F78ED46E3003B6` FOREIGN KEY (`iprocess_stage_stg_id`) REFERENCES `iprocess_stage` (`stg_id`),
  ADD CONSTRAINT `FK_57F78ED4B2CC3CA8` FOREIGN KEY (`iprocess_inp_id`) REFERENCES `institution_process` (`inp_id`),
  ADD CONSTRAINT `FK_57F78ED4CE87AAFC` FOREIGN KEY (`criterion_name_cna_id`) REFERENCES `criterion_name` (`cna_id`);

--
-- Contraintes pour la table `iprocess_participation`
--
ALTER TABLE `iprocess_participation`
  ADD CONSTRAINT `FK_FF76DB8F1C22DDD4` FOREIGN KEY (`team_tea_id`) REFERENCES `team` (`tea_id`),
  ADD CONSTRAINT `FK_FF76DB8F293D81A2` FOREIGN KEY (`external_useR_ext_usr_id`) REFERENCES `external_user` (`ext_id`),
  ADD CONSTRAINT `FK_FF76DB8F693BC8C6` FOREIGN KEY (`user_usr_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_FF76DB8F6E3003B6` FOREIGN KEY (`iprocess_stage_stg_id`) REFERENCES `iprocess_stage` (`stg_id`),
  ADD CONSTRAINT `FK_FF76DB8FB2CC3CA8` FOREIGN KEY (`iprocess_inp_id`) REFERENCES `institution_process` (`inp_id`),
  ADD CONSTRAINT `FK_FF76DB8FEF264A3A` FOREIGN KEY (`iprocess_criterion_crt_id`) REFERENCES `iprocess_criterion` (`crt_id`);

--
-- Contraintes pour la table `iprocess_stage`
--
ALTER TABLE `iprocess_stage`
  ADD CONSTRAINT `FK_6864F1B4B2CC3CA8` FOREIGN KEY (`iprocess_inp_id`) REFERENCES `institution_process` (`inp_id`),
  ADD CONSTRAINT `FK_6864F1B4E7127ABC` FOREIGN KEY (`stg_master_user_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_6864F1B4F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `mail`
--
ALTER TABLE `mail`
  ADD CONSTRAINT `FK_5126AC48693BC8C6` FOREIGN KEY (`user_usr_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_5126AC4883D3BB28` FOREIGN KEY (`activity_act_id`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `FK_5126AC48935EEA40` FOREIGN KEY (`stage_stg_id`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_5126AC48A8C102B2` FOREIGN KEY (`worker_firm_wfi_id`) REFERENCES `worker_firm` (`wfi_id`),
  ADD CONSTRAINT `FK_5126AC48B1623D83` FOREIGN KEY (`worker_individual_win_id`) REFERENCES `worker_individual` (`win_id`),
  ADD CONSTRAINT `FK_5126AC48F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `FK_70E4FA781C22DDD4` FOREIGN KEY (`team_tea_id`) REFERENCES `team` (`tea_id`),
  ADD CONSTRAINT `FK_70E4FA78310C5A5A` FOREIGN KEY (`external_user_ext_usr_id`) REFERENCES `external_user` (`ext_id`),
  ADD CONSTRAINT `FK_70E4FA78693BC8C6` FOREIGN KEY (`user_usr_id`) REFERENCES `user` (`usr_id`);

--
-- Contraintes pour la table `organization`
--
ALTER TABLE `organization`
  ADD CONSTRAINT `FK_C1EE637C2951486D` FOREIGN KEY (`payment_usr_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_C1EE637CA8C102B2` FOREIGN KEY (`worker_firm_wfi_id`) REFERENCES `worker_firm` (`wfi_id`);

--
-- Contraintes pour la table `organization_payment_method`
--
ALTER TABLE `organization_payment_method`
  ADD CONSTRAINT `FK_8B734245F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `organization_user_option`
--
ALTER TABLE `organization_user_option`
  ADD CONSTRAINT `FK_F88BFF654B493BD` FOREIGN KEY (`option_name_ona_id`) REFERENCES `option_name` (`ona_id`),
  ADD CONSTRAINT `FK_F88BFF6693BC8C6` FOREIGN KEY (`user_usr_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_F88BFF66AFC588` FOREIGN KEY (`title_tit_id`) REFERENCES `title` (`tit_id`),
  ADD CONSTRAINT `FK_F88BFF6B232D839` FOREIGN KEY (`department_dpt_id`) REFERENCES `department` (`dpt_id`),
  ADD CONSTRAINT `FK_F88BFF6B9E63123` FOREIGN KEY (`position_pos_id`) REFERENCES `position` (`pos_id`),
  ADD CONSTRAINT `FK_F88BFF6F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `otpuser`
--
ALTER TABLE `otpuser`
  ADD CONSTRAINT `FK_2524453FA99EEA35` FOREIGN KEY (`otp_organization`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `output`
--
ALTER TABLE `output`
  ADD CONSTRAINT `FK_CCDE149E7D7E0D2A` FOREIGN KEY (`survey_sur_id`) REFERENCES `survey` (`sur_id`),
  ADD CONSTRAINT `FK_CCDE149E935EEA40` FOREIGN KEY (`stage_stg_id`) REFERENCES `stage` (`stg_id`);

--
-- Contraintes pour la table `participation`
--
ALTER TABLE `participation`
  ADD CONSTRAINT `FK_AB55E24F1C22DDD4` FOREIGN KEY (`team_tea_id`) REFERENCES `team` (`tea_id`),
  ADD CONSTRAINT `FK_AB55E24F310C5A5A` FOREIGN KEY (`external_user_ext_usr_id`) REFERENCES `external_user` (`ext_id`),
  ADD CONSTRAINT `FK_AB55E24F693BC8C6` FOREIGN KEY (`user_usr_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_AB55E24F7D7E0D2A` FOREIGN KEY (`survey_sur_id`) REFERENCES `survey` (`sur_id`),
  ADD CONSTRAINT `FK_AB55E24F83D3BB28` FOREIGN KEY (`activity_act_id`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `FK_AB55E24F935EEA40` FOREIGN KEY (`stage_stg_id`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_AB55E24FD26A20E6` FOREIGN KEY (`criterion_crt_id`) REFERENCES `criterion` (`crt_id`);

--
-- Contraintes pour la table `position`
--
ALTER TABLE `position`
  ADD CONSTRAINT `FK_462CE4F53AF408B7` FOREIGN KEY (`weight_wgt_id`) REFERENCES `weight` (`wgt_id`),
  ADD CONSTRAINT `FK_462CE4F5B232D839` FOREIGN KEY (`department_dpt_id`) REFERENCES `department` (`dpt_id`),
  ADD CONSTRAINT `FK_462CE4F5F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `process`
--
ALTER TABLE `process`
  ADD CONSTRAINT `FK_861D1896727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `process` (`pro_id`),
  ADD CONSTRAINT `FK_861D1896E2C46BF8` FOREIGN KEY (`icon_ico_id`) REFERENCES `icon` (`ico_id`),
  ADD CONSTRAINT `FK_861D1896F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `process_criterion`
--
ALTER TABLE `process_criterion`
  ADD CONSTRAINT `FK_E6CB609995B7A1D` FOREIGN KEY (`process_pro_id`) REFERENCES `process` (`pro_id`),
  ADD CONSTRAINT `FK_E6CB6099CE87AAFC` FOREIGN KEY (`criterion_name_cna_id`) REFERENCES `criterion_name` (`cna_id`),
  ADD CONSTRAINT `FK_E6CB6099F9E0BF71` FOREIGN KEY (`process_stage_stg_id`) REFERENCES `process_stage` (`stg_id`);

--
-- Contraintes pour la table `process_stage`
--
ALTER TABLE `process_stage`
  ADD CONSTRAINT `FK_9420BE9895B7A1D` FOREIGN KEY (`process_pro_id`) REFERENCES `process` (`pro_id`),
  ADD CONSTRAINT `FK_9420BE98AFCB9C11` FOREIGN KEY (`stg_master_usr_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_9420BE98F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `ranking`
--
ALTER TABLE `ranking`
  ADD CONSTRAINT `FK_80B839D04C14732A` FOREIGN KEY (`rnk_organization`) REFERENCES `organization` (`org_id`),
  ADD CONSTRAINT `FK_80B839D08D0A8B3D` FOREIGN KEY (`rnk_stage`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_80B839D09B266BD9` FOREIGN KEY (`rnk_user_usr_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_80B839D0B5DECA3D` FOREIGN KEY (`rnk_criterion`) REFERENCES `criterion` (`crt_id`),
  ADD CONSTRAINT `FK_80B839D0FCBCC329` FOREIGN KEY (`rnk_activity`) REFERENCES `activity` (`act_id`);

--
-- Contraintes pour la table `ranking_history`
--
ALTER TABLE `ranking_history`
  ADD CONSTRAINT `FK_2F6B262166DCD468` FOREIGN KEY (`rkh_activity`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `FK_2F6B2621A24F697A` FOREIGN KEY (`rkh_user_usr_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_2F6B2621B49FDB2C` FOREIGN KEY (`rkh_criterion`) REFERENCES `criterion` (`crt_id`),
  ADD CONSTRAINT `FK_2F6B2621F4029FC4` FOREIGN KEY (`rkh_stage`) REFERENCES `stage` (`stg_id`);

--
-- Contraintes pour la table `ranking_team`
--
ALTER TABLE `ranking_team`
  ADD CONSTRAINT `FK_6DDD299510738D20` FOREIGN KEY (`rkt_criterion`) REFERENCES `criterion` (`crt_id`),
  ADD CONSTRAINT `FK_6DDD29951C22DDD4` FOREIGN KEY (`team_tea_id`) REFERENCES `team` (`tea_id`),
  ADD CONSTRAINT `FK_6DDD29954FB5453B` FOREIGN KEY (`rkt_organization`) REFERENCES `organization` (`org_id`),
  ADD CONSTRAINT `FK_6DDD2995547C697E` FOREIGN KEY (`rkt_stage`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_6DDD29955B4EB50E` FOREIGN KEY (`rkt_activity`) REFERENCES `activity` (`act_id`);

--
-- Contraintes pour la table `ranking_team_history`
--
ALTER TABLE `ranking_team_history`
  ADD CONSTRAINT `FK_A1B5EBE11318B4A8` FOREIGN KEY (`rth_organization`) REFERENCES `organization` (`org_id`),
  ADD CONSTRAINT `FK_A1B5EBE11C22DDD4` FOREIGN KEY (`team_tea_id`) REFERENCES `team` (`tea_id`),
  ADD CONSTRAINT `FK_A1B5EBE15199BC43` FOREIGN KEY (`rth_stage`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_A1B5EBE15BD2E465` FOREIGN KEY (`rth_activity`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `FK_A1B5EBE1CA13A9A1` FOREIGN KEY (`rth_criterion`) REFERENCES `criterion` (`crt_id`);

--
-- Contraintes pour la table `recurring`
--
ALTER TABLE `recurring`
  ADD CONSTRAINT `FK_6C6C02ED4C86D2E4` FOREIGN KEY (`rec_master_user_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_6C6C02EDF10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `result`
--
ALTER TABLE `result`
  ADD CONSTRAINT `FK_136AC11383D3BB28` FOREIGN KEY (`activity_act_id`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `FK_136AC113935EEA40` FOREIGN KEY (`stage_stg_id`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_136AC113A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_136AC113D26A20E6` FOREIGN KEY (`criterion_crt_id`) REFERENCES `criterion` (`crt_id`),
  ADD CONSTRAINT `FK_136AC113E5639263` FOREIGN KEY (`externalUser_id`) REFERENCES `external_user` (`ext_id`);

--
-- Contraintes pour la table `result_project`
--
ALTER TABLE `result_project`
  ADD CONSTRAINT `FK_4D8A520A83D3BB28` FOREIGN KEY (`activity_act_id`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `FK_4D8A520A935EEA40` FOREIGN KEY (`stage_stg_id`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_4D8A520AD26A20E6` FOREIGN KEY (`criterion_crt_id`) REFERENCES `criterion` (`crt_id`);

--
-- Contraintes pour la table `result_team`
--
ALTER TABLE `result_team`
  ADD CONSTRAINT `FK_54DBBD511C22DDD4` FOREIGN KEY (`team_tea_id`) REFERENCES `team` (`tea_id`),
  ADD CONSTRAINT `FK_54DBBD5183D3BB28` FOREIGN KEY (`activity_act_id`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `FK_54DBBD51935EEA40` FOREIGN KEY (`stage_stg_id`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_54DBBD51D26A20E6` FOREIGN KEY (`criterion_crt_id`) REFERENCES `criterion` (`crt_id`);

--
-- Contraintes pour la table `stage`
--
ALTER TABLE `stage`
  ADD CONSTRAINT `FK_C27C93697D7E0D2A` FOREIGN KEY (`survey_sur_id`) REFERENCES `survey` (`sur_id`),
  ADD CONSTRAINT `FK_C27C936983D3BB28` FOREIGN KEY (`activity_act_id`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `FK_C27C9369F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `state`
--
ALTER TABLE `state`
  ADD CONSTRAINT `FK_A393D2FBA1599B34` FOREIGN KEY (`country_cou_id`) REFERENCES `country` (`cou_id`);

--
-- Contraintes pour la table `survey`
--
ALTER TABLE `survey`
  ADD CONSTRAINT `FK_AD5F9BFC935EEA40` FOREIGN KEY (`stage_stg_id`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_AD5F9BFCF10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `survey_field`
--
ALTER TABLE `survey_field`
  ADD CONSTRAINT `FK_5785B7607D7E0D2A` FOREIGN KEY (`survey_sur_id`) REFERENCES `survey` (`sur_id`),
  ADD CONSTRAINT `FK_5785B760D26A20E6` FOREIGN KEY (`criterion_crt_id`) REFERENCES `criterion` (`crt_id`);

--
-- Contraintes pour la table `survey_field_parameter`
--
ALTER TABLE `survey_field_parameter`
  ADD CONSTRAINT `FK_6740C5AEAFBE78D8` FOREIGN KEY (`survey_field_sfi_id`) REFERENCES `survey_field` (`sfi_id`);

--
-- Contraintes pour la table `target`
--
ALTER TABLE `target`
  ADD CONSTRAINT `FK_466F2FFC1C22DDD4` FOREIGN KEY (`team_tea_id`) REFERENCES `team` (`tea_id`),
  ADD CONSTRAINT `FK_466F2FFC693BC8C6` FOREIGN KEY (`user_usr_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_466F2FFC6AFC588` FOREIGN KEY (`title_tit_id`) REFERENCES `title` (`tit_id`),
  ADD CONSTRAINT `FK_466F2FFCB232D839` FOREIGN KEY (`department_dpt_id`) REFERENCES `department` (`dpt_id`),
  ADD CONSTRAINT `FK_466F2FFCB9E63123` FOREIGN KEY (`position_pos_id`) REFERENCES `position` (`pos_id`),
  ADD CONSTRAINT `FK_466F2FFCCE87AAFC` FOREIGN KEY (`criterion_name_cna_id`) REFERENCES `criterion_name` (`cna_id`),
  ADD CONSTRAINT `FK_466F2FFCD26A20E6` FOREIGN KEY (`criterion_crt_id`) REFERENCES `criterion` (`crt_id`),
  ADD CONSTRAINT `FK_466F2FFCF10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `FK_C4E0A61FF10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `title`
--
ALTER TABLE `title`
  ADD CONSTRAINT `FK_2B36786B3AF408B7` FOREIGN KEY (`weight_wgt_id`) REFERENCES `weight` (`wgt_id`),
  ADD CONSTRAINT `FK_2B36786BF10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D6493AF408B7` FOREIGN KEY (`weight_wgt_id`) REFERENCES `weight` (`wgt_id`),
  ADD CONSTRAINT `FK_8D93D6496AB1CC56` FOREIGN KEY (`user_global_usg_id`) REFERENCES `user_global` (`usg_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8D93D6496AFC588` FOREIGN KEY (`title_tit_id`) REFERENCES `title` (`tit_id`),
  ADD CONSTRAINT `FK_8D93D649B1623D83` FOREIGN KEY (`worker_individual_win_id`) REFERENCES `worker_individual` (`win_id`),
  ADD CONSTRAINT `FK_8D93D649B232D839` FOREIGN KEY (`department_dpt_id`) REFERENCES `department` (`dpt_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_8D93D649B9E63123` FOREIGN KEY (`position_pos_id`) REFERENCES `position` (`pos_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_8D93D649E38ABCB8` FOREIGN KEY (`usr_superior`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_8D93D649F10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_master`
--
ALTER TABLE `user_master`
  ADD CONSTRAINT `FK_485BB8EB38084664` FOREIGN KEY (`event_eve_id`) REFERENCES `event` (`eve_id`),
  ADD CONSTRAINT `FK_485BB8EB62D0DC74` FOREIGN KEY (`output_otp_id`) REFERENCES `output` (`otp_id`),
  ADD CONSTRAINT `FK_485BB8EB693BC8C6` FOREIGN KEY (`user_usr_id`) REFERENCES `user` (`usr_id`),
  ADD CONSTRAINT `FK_485BB8EB7834077D` FOREIGN KEY (`institution_process_inp_id`) REFERENCES `institution_process` (`inp_id`),
  ADD CONSTRAINT `FK_485BB8EB83D3BB28` FOREIGN KEY (`activity_act_id`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `FK_485BB8EB935EEA40` FOREIGN KEY (`stage_stg_id`) REFERENCES `stage` (`stg_id`),
  ADD CONSTRAINT `FK_485BB8EBB232D839` FOREIGN KEY (`department_dpt_id`) REFERENCES `department` (`dpt_id`),
  ADD CONSTRAINT `FK_485BB8EBB9E63123` FOREIGN KEY (`position_pos_id`) REFERENCES `position` (`pos_id`),
  ADD CONSTRAINT `FK_485BB8EBF10DACEE` FOREIGN KEY (`organization_org_id`) REFERENCES `organization` (`org_id`);

--
-- Contraintes pour la table `weight`
--
ALTER TABLE `weight`
  ADD CONSTRAINT `FK_7CD5541F4837C1B` FOREIGN KEY (`org_id`) REFERENCES `organization` (`org_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `worker_experience`
--
ALTER TABLE `worker_experience`
  ADD CONSTRAINT `FK_81AC80708780C6FF` FOREIGN KEY (`worker_firm_wfi`) REFERENCES `worker_firm` (`wfi_id`),
  ADD CONSTRAINT `FK_81AC8070D4203719` FOREIGN KEY (`worker_individual_wid`) REFERENCES `worker_individual` (`win_id`);

--
-- Contraintes pour la table `worker_firm`
--
ALTER TABLE `worker_firm`
  ADD CONSTRAINT `FK_9DC037AC2378CB28` FOREIGN KEY (`state_sta_id`) REFERENCES `state` (`sta_id`),
  ADD CONSTRAINT `FK_9DC037AC2391AC72` FOREIGN KEY (`city_cit_id`) REFERENCES `city` (`cit_id`),
  ADD CONSTRAINT `FK_9DC037AC255DBC04` FOREIGN KEY (`worker_firm_sector_wfs_id`) REFERENCES `worker_firm_sector` (`wfs_id`),
  ADD CONSTRAINT `FK_9DC037AC727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `worker_firm` (`wfi_id`),
  ADD CONSTRAINT `FK_9DC037ACA1599B34` FOREIGN KEY (`country_cou_id`) REFERENCES `country` (`cou_id`);

--
-- Contraintes pour la table `worker_firm_competency`
--
ALTER TABLE `worker_firm_competency`
  ADD CONSTRAINT `FK_9E94D7EEA8C102B2` FOREIGN KEY (`worker_firm_wfi_id`) REFERENCES `worker_firm` (`wfi_id`);

--
-- Contraintes pour la table `worker_firm_sector`
--
ALTER TABLE `worker_firm_sector`
  ADD CONSTRAINT `FK_8DDB61C6E2C46BF8` FOREIGN KEY (`icon_ico_id`) REFERENCES `icon` (`ico_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
