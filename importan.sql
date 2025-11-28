-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2025 at 11:23 AM
-- Server version: 10.6.23-MariaDB-cll-lve
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `camb_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `level_id` bigint(20) DEFAULT NULL,
  `mode` varchar(25) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `fee` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `slug`, `category_id`, `level_id`, `mode`, `short_description`, `description`, `duration`, `fee`, `image`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Audit & Assurance Diploma', 'audit-assurance-diploma', 1, 2, 'online', 'Audit principles, assurance engagements and financial reporting.', NULL, '12 months (flexible)', 3000.00, NULL, 1, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(2, 'Accounting & Finance Diploma', 'accounting-finance-diploma', 1, 2, NULL, 'Fundamentals of accounting and corporate finance.', NULL, '12 months (flexible)', 0.00, NULL, 1, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(3, 'Banking Operations & Services Diploma', 'banking-operations-services-diploma', 1, 2, NULL, 'Banking procedures, retail and corporate banking operations.', NULL, '12 months (flexible)', 0.00, NULL, 1, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(4, 'Financial Management Certificate', 'financial-management-certificate', 1, 1, NULL, 'Basics of financial planning and management.', NULL, '6 months (flexible)', 0.00, NULL, 1, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(5, 'Business Management & Administration Diploma', 'business-management-administration-diploma', 2, 2, NULL, 'Core management and administration skills for business.', NULL, '12 months (flexible)', 0.00, NULL, 1, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(6, 'International Business & Trade Diploma', 'international-business-trade-diploma', 2, 2, NULL, 'International trade, export-import and trade finance.', NULL, '12 months (flexible)', 0.00, NULL, 1, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(7, 'Commercial Practice & Law Diploma', 'commercial-practice-law-diploma', 2, 2, NULL, 'Business law, contracts and commercial practice.', NULL, '12 months (flexible)', 0.00, NULL, 1, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(8, 'Business Administration (EBA)', 'business-administration-eba', 2, 6, NULL, 'Executive Business Administration program.', NULL, '3 years (flexible)', 0.00, NULL, 1, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(9, 'Strategic Management (EMBA)', 'strategic-management-emba', 2, 9, NULL, 'Advanced strategic leadership and management.', NULL, '3 years (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(10, 'Principles of Economics Diploma', 'principles-of-economics-diploma', 3, 2, NULL, 'Micro and macroeconomics fundamentals.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(11, 'International Trade & Commerce Diploma', 'international-trade-commerce-diploma', 3, 2, NULL, 'Trade policy, commerce and global markets.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(12, 'Economic Policy & Development Diploma', 'economic-policy-development-diploma', 3, 2, NULL, 'Economic planning and development strategies.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(13, 'Business English Communication Certificate', 'business-english-communication-certificate', 4, 1, NULL, 'Business writing, presentations and workplace English.', NULL, '3 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(14, 'Journalism, Mass Media & Professional Writing Diploma', 'journalism-mass-media-professional-writing-diploma', 4, 2, NULL, 'Journalism skills, reporting and writing.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(15, 'Secretarial & Office Management Certificate', 'secretarial-office-management-certificate', 4, 1, NULL, 'Office procedures, typing and admin skills.', NULL, '6 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(16, 'Hospitality Management Diploma', 'hospitality-management-diploma', 5, 2, NULL, 'Hotel operations, guest services and hospitality management.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(17, 'Tourism & Travel Management Diploma', 'tourism-travel-management-diploma', 5, 2, NULL, 'Tourism industry, tour ops and travel management.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(18, 'Event Management Certificate', 'event-management-certificate', 5, 1, NULL, 'Planning and managing events.', NULL, '6 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(19, 'Health & Safety in the Workplace Diploma', 'health-safety-in-the-workplace-diploma', 6, 2, NULL, 'Workplace safety practices and legislation.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(20, 'Leadership & Team Management Diploma', 'leadership-team-management-diploma', 6, 2, NULL, 'Leadership skills, team building and supervision.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(21, 'Management & Administration (Honours)', 'management-administration-honours', 6, 3, NULL, 'Advanced management and administration topics.', NULL, '21 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(22, 'Project Management & Administration (EBA)', 'project-management-administration-eba', 6, 6, NULL, 'Project planning and executive administration.', NULL, '3 years (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(23, 'Leadership & Strategic Administration (EBA)', 'leadership-strategic-administration-eba', 6, 6, NULL, 'Executive leadership and strategy.', NULL, '3 years (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(24, 'Digital & Online Marketing Diploma', 'digital-online-marketing-diploma', 7, 2, NULL, 'SEO, social media, email marketing and analytics.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(25, 'Marketing Strategies Diploma', 'marketing-strategies-diploma', 7, 2, NULL, 'Marketing planning, branding and market research.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(26, 'Sales & Negotiation Certificate', 'sales-negotiation-certificate', 7, 1, NULL, 'Sales techniques and negotiation skills.', NULL, '6 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(27, 'Human Resource & Personnel Management Diploma', 'human-resource-personnel-management-diploma', 8, 2, NULL, 'Recruitment, payroll, training and development.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(28, 'Training & Development Diploma', 'training-development-diploma', 8, 2, NULL, 'Designing and delivering workplace training.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(29, 'Human Resource Administration (Honours)', 'human-resource-administration-honours', 8, 3, NULL, 'Advanced HR administration and strategy.', NULL, '21 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(30, 'Organisational Understanding & Development', 'organisational-understanding-development', 8, 7, NULL, 'Organisational development and change management.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(31, 'Human Resource Management (EMBA)', 'human-resource-management-emba', 8, 9, NULL, 'Executive HR management and leadership.', NULL, '3 years (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(32, 'Logistics & Supply Chain Diploma', 'logistics-supply-chain-diploma', 9, 2, NULL, 'Logistics planning, warehousing and distribution.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(33, 'Purchasing & Materials Management Diploma', 'purchasing-materials-management-diploma', 9, 2, NULL, 'Procurement and inventory control.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(34, 'Stores & Warehouse Management Certificate', 'stores-warehouse-management-certificate', 9, 1, NULL, 'Warehouse operations and stock control.', NULL, '6 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(35, 'Corporate Accounting Diploma', 'corporate-accounting-diploma', 1, 2, NULL, 'Financial accounting for corporations.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(36, 'Taxation & VAT Diploma', 'taxation-vat-diploma', 1, 2, NULL, 'Principles of taxation and VAT accounting.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(37, 'Management Accounting Certificate', 'management-accounting-certificate', 1, 1, NULL, 'Costing, budgeting and management accounts.', NULL, '6 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(38, 'Risk Management & Insurance Diploma', 'risk-management-insurance-diploma', 2, 2, NULL, 'Principles of risk management and insurance.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(39, 'Business Ethics & Corporate Governance Diploma', 'business-ethics-corporate-governance-diploma', 2, 2, NULL, 'Ethics, governance and compliance.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(40, 'Development Economics Diploma', 'development-economics-diploma', 3, 2, NULL, 'Economic development theories and practice.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(41, 'Financial Markets & Institutions Diploma', 'financial-markets-institutions-diploma', 3, 2, NULL, 'Structure of financial markets and institutions.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(42, 'English for Academic Purposes Certificate', 'english-for-academic-purposes-certificate', 4, 1, NULL, 'Academic reading, writing and listening skills.', NULL, '3 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(43, 'Business Writing & Report Writing Certificate', 'business-writing-report-writing-certificate', 4, 1, NULL, 'Professional writing and report preparation.', NULL, '3 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(44, 'Front Office Operations Certificate', 'front-office-operations-certificate', 5, 1, NULL, 'Reception, reservations and guest services.', NULL, '6 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(45, 'Food & Beverage Management Diploma', 'food-beverage-management-diploma', 5, 2, NULL, 'F&B operations, menu planning and service.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(46, 'Operations Management Diploma', 'operations-management-diploma', 6, 2, NULL, 'Operations planning and quality management.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(47, 'Strategic HR Management Diploma', 'strategic-hr-management-diploma', 8, 2, NULL, 'Strategic approaches to HR and talent management.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(48, 'Brand Management Diploma', 'brand-management-diploma', 7, 2, NULL, 'Brand strategy, positioning and management.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(49, 'Market Research & Consumer Behaviour Diploma', 'market-research-consumer-behaviour-diploma', 7, 2, NULL, 'Market research methods and consumer insights.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(50, 'Payroll Management Certificate', 'payroll-management-certificate', 1, 1, NULL, 'Payroll processing and statutory deductions.', NULL, '3 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(51, 'Credit Management Diploma', 'credit-management-diploma', 1, 2, NULL, 'Credit control and receivables management.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(52, 'Entrepreneurship & Small Business Management Diploma', 'entrepreneurship-small-business-management-diploma', 2, 2, NULL, 'Starting and managing small businesses.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(53, 'Negotiation & Commercial Sales Diploma', 'negotiation-commercial-sales-diploma', 7, 2, NULL, 'Advanced sales techniques and negotiation.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(54, 'Public Policy & Administration Diploma', 'public-policy-administration-diploma', 3, 2, NULL, 'Public administration and policy design.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(55, 'English Language Teaching (ELT) Certificate', 'english-language-teaching-elt-certificate', 4, 1, NULL, 'Basics of teaching English as a second language.', NULL, '6 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(56, 'Hospitality Supervision Certificate', 'hospitality-supervision-certificate', 5, 1, NULL, 'Supervisory skills for hospitality staff.', NULL, '6 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(57, 'Tour Guiding & Interpretation Certificate', 'tour-guiding-interpretation-certificate', 5, 1, NULL, 'Tour guiding skills and visitor interpretation.', NULL, '6 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(58, 'Business Analytics Diploma', 'business-analytics-diploma', 3, 2, NULL, 'Data analytics for business decisions.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(59, 'Digital Transformation & E-Business Diploma', 'digital-transformation-e-business-diploma', 2, 2, NULL, 'E-business models and digital strategies.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(60, 'Organisational Behaviour Diploma', 'organisational-behaviour-diploma', 8, 2, NULL, 'Individual & group behaviour in organisations.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(61, 'Performance Management Certificate', 'performance-management-certificate', 8, 1, NULL, 'Appraisal systems and performance improvement.', NULL, '6 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(62, 'Supply Chain Management Certificate', 'supply-chain-management-certificate', 9, 1, NULL, 'Basics of supply chain and logistics.', NULL, '6 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(63, 'Inventory Control & Stocktaking Diploma', 'inventory-control-stocktaking-diploma', 9, 2, NULL, 'Stock control techniques and systems.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(64, 'Business Law Certificate', 'business-law-certificate', 2, 1, NULL, 'Fundamental aspects of commercial law.', NULL, '3 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(65, 'Corporate Finance Diploma', 'corporate-finance-diploma', 1, 2, NULL, 'Corporate funding, capital structure, valuation.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(66, 'Customer Service & Relations Certificate', 'customer-service-relations-certificate', 5, 1, NULL, 'Customer care and service excellence.', NULL, '3 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(67, 'Hotel Revenue Management Diploma', 'hotel-revenue-management-diploma', 5, 2, NULL, 'Pricing strategies and revenue optimisation.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(68, 'Advanced Diploma in Marketing', 'advanced-diploma-in-marketing', 7, 3, NULL, 'Advanced concepts in marketing and strategy.', NULL, '18-24 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(69, 'Advanced Diploma in HRM', 'advanced-diploma-in-hrm', 8, 3, NULL, 'Advanced HR theories and practices.', NULL, '18-24 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(70, 'Executive Mini MBA (Leadership)', 'executive-mini-mba-leadership', 8, 8, NULL, 'Short executive program for leadership.', NULL, '6-12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(71, 'Mastery of Management Graduate Diploma (MMGD)', 'mastery-of-management-graduate-diploma-mmgd', 6, 7, NULL, 'Graduate-level management mastery program.', NULL, '12-24 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(72, 'EMBA Strategic Leadership', 'emba-strategic-leadership', 2, 9, NULL, 'Executive MBA in strategic leadership.', NULL, '3 years (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(73, 'Joint ILM & City Guilds Combined Awards', 'joint-ilm-city-guilds-combined-awards', 2, 10, NULL, 'Combined professional awards and accreditation.', NULL, 'Various durations', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(74, 'Professional Communication Skills Certificate', 'professional-communication-skills-certificate', 4, 1, NULL, 'Effective workplace communication and presentation.', NULL, '3 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(75, 'Office Administration & Records Management Diploma', 'office-administration-records-management-diploma', 4, 2, NULL, 'Office systems and records management.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(76, 'Small Business Accounting Certificate', 'small-business-accounting-certificate', 1, 1, NULL, 'Accounting for SMEs and bookkeeping basics.', NULL, '3 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(77, 'Insurance Underwriting Diploma', 'insurance-underwriting-diploma', 2, 2, NULL, 'Principles of insurance underwriting and claims.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(78, 'E-Commerce & Online Business Diploma', 'e-commerce-online-business-diploma', 7, 2, NULL, 'Online selling, platforms and payments.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(79, 'Social Media Marketing Certificate', 'social-media-marketing-certificate', 7, 1, NULL, 'Social media strategies and content planning.', NULL, '3 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(80, 'Workplace Mediation & Conflict Resolution Certificate', 'workplace-mediation-conflict-resolution-certificate', 8, 1, NULL, 'Mediation techniques and conflict management.', NULL, '3-6 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(81, 'Leadership in Organisations Certificate', 'leadership-in-organisations-certificate', 6, 1, NULL, 'Foundations of organisational leadership.', NULL, '3-6 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(82, 'Freight & Customs Procedures Diploma', 'freight-customs-procedures-diploma', 9, 2, NULL, 'Customs, freight forwarding and documentation.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(83, 'Transport Management Diploma', 'transport-management-diploma', 9, 2, NULL, 'Transport planning and fleet operations.', NULL, '12 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(84, 'Personal Development & Study Skills Certificate', 'personal-development-study-skills-certificate', 4, 1, NULL, 'Study skills, time management and personal development.', NULL, '3 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21'),
(85, 'Professional CV & Job Search Skills Certificate', 'professional-cv-job-search-skills-certificate', 4, 1, NULL, 'CV writing and job search strategies.', NULL, '1-2 months (flexible)', 0.00, NULL, 0, 'active', '2025-11-07 16:29:45', '2025-11-08 11:01:21');

-- --------------------------------------------------------

--
-- Table structure for table `course_categories`
--

CREATE TABLE `course_categories` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_categories`
--

INSERT INTO `course_categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Accounting, Finance, Banking', 'accounting-finance-banking', NULL, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(2, 'Business Studies, Insurance, Law', 'business-studies-insurance-law', NULL, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(3, 'Economics, Commerce, Trade', 'economics-commerce-trade', NULL, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(4, 'English, Secretarial, Communication', 'english-secretarial-communication', NULL, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(5, 'Hotel, Tourism, Travel, Hospitality, Events', 'hotel-tourism-travel-hospitality-events', NULL, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(6, 'Management, Administration, Leadership', 'management-administration-leadership', NULL, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(7, 'Marketing, Sales, Advertising', 'marketing-sales-advertising', NULL, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(8, 'HR, Organisation, Education & Teaching', 'hr-organisation-education-teaching', NULL, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(9, 'Stores, Logistics, Purchasing, Materials', 'stores-logistics-purchasing-materials', NULL, '2025-11-07 16:29:45', '2025-11-07 16:29:45');

-- --------------------------------------------------------

--
-- Table structure for table `course_levels`
--

CREATE TABLE `course_levels` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_levels`
--

INSERT INTO `course_levels` (`id`, `name`, `slug`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Certificate', 'certificate', NULL, 1, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(2, 'Diploma', 'diploma', NULL, 2, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(3, 'Honours (Higher) Diploma', 'honours-higher-diploma', NULL, 3, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(4, 'ABA: Advanced Business Administration', 'aba-advanced-business-administration', NULL, 4, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(5, 'Baccalaureate', 'baccalaureate', NULL, 5, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(6, 'EBA: Executive Business Administration', 'eba-executive-business-administration', NULL, 6, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(7, 'Mastery of Management Graduate Diploma', 'mastery-of-management-graduate-diploma', NULL, 7, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(8, 'Executive Mini MBA', 'executive-mini-mba', NULL, 8, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(9, 'EMBA: Executive Mastery of Business Administration', 'emba-executive-mastery-of-business-administration', NULL, 9, '2025-11-07 16:29:45', '2025-11-07 16:29:45'),
(10, 'Joint ILM City & Guilds & CIC Awards (Higher, ABA, EBA, EMBA)', 'joint-ilm-city-guilds-cic-awards', NULL, 10, '2025-11-07 16:29:45', '2025-11-07 16:29:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_courses_category` (`category_id`),
  ADD KEY `fk_courses_level` (`level_id`);

--
-- Indexes for table `course_categories`
--
ALTER TABLE `course_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `course_levels`
--
ALTER TABLE `course_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `course_categories`
--
ALTER TABLE `course_categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `course_levels`
--
ALTER TABLE `course_levels`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `fk_courses_category` FOREIGN KEY (`category_id`) REFERENCES `course_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_courses_level` FOREIGN KEY (`level_id`) REFERENCES `course_levels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
