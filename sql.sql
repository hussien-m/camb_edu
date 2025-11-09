-- Database: cambridge_college
-- ----------------------------
-- Table structure and data for course_categories
CREATE TABLE `course_categories` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) UNIQUE NOT NULL,
  `description` TEXT,
  `icon` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO `course_categories` (`name`, `slug`) VALUES
('Accounting, Finance, Banking', 'accounting-finance-banking'),
('Business Studies, Insurance, Law', 'business-studies-insurance-law'),
('Economics, Commerce, Trade', 'economics-commerce-trade'),
('English, Secretarial, Communication', 'english-secretarial-communication'),
('Hotel, Tourism, Travel, Hospitality, Events', 'hotel-tourism-travel-hospitality-events'),
('Management, Administration, Leadership', 'management-administration-leadership'),
('Marketing, Sales, Advertising', 'marketing-sales-advertising'),
('HR, Organisation, Education & Teaching', 'hr-organisation-education-teaching'),
('Stores, Logistics, Purchasing, Materials', 'stores-logistics-purchasing-materials');

-- Table structure and data for course_levels
CREATE TABLE `course_levels` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) UNIQUE NOT NULL,
  `description` TEXT,
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO `course_levels` (`name`, `slug`) VALUES
('Certificate', 'certificate'),
('Diploma', 'diploma'),
('Honours (Higher) Diploma', 'honours-higher-diploma'),
('ABA: Advanced Business Administration', 'aba-advanced-business-administration'),
('Baccalaureate', 'baccalaureate'),
('EBA: Executive Business Administration', 'eba-executive-business-administration'),
('Mastery of Management Graduate Diploma', 'mastery-of-management-graduate-diploma'),
('Executive Mini MBA', 'executive-mini-mba'),
('EMBA: Executive Mastery of Business Administration', 'emba-executive-mastery-of-business-administration'),
('Joint ILM City & Guilds & CIC Awards (Higher, ABA, EBA, EMBA)', 'joint-ilm-city-guilds-cic-awards');

-- Table structure and data for courses
CREATE TABLE `courses` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) UNIQUE NOT NULL,
  `category_id` BIGINT,
  `level_id` BIGINT,
  `short_description` TEXT,
  `description` LONGTEXT,
  `duration` VARCHAR(100),
  `mode` VARCHAR(50),
  `fee` DECIMAL(10,2),
  `image` VARCHAR(255),
  `is_featured` BOOLEAN DEFAULT FALSE,
  `status` ENUM('active','inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `course_categories`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`level_id`) REFERENCES `course_levels`(`id`) ON DELETE SET NULL
);

INSERT INTO `courses` (`title`, `slug`, `category_id`, `level_id`, `duration`) VALUES
('Audit & Assurance Diploma', 'audit-assurance-diploma', 1, 2, '12 months (flexible)'),
('Business Management & Administration Diploma', 'business-management-administration-diploma', 2, 2, '12 months (flexible)'),
('International Business & Trade Diploma', 'international-business-trade-diploma', 2, 2, '12 months (flexible)'),
('Business Administration (EBA)', 'business-administration-eba', 2, 6, '3 years (flexible)'),
('Strategic Management (EMBA)', 'strategic-management-emba', 2, 9, '3 years (flexible)'),
('Health & Safety in the Workplace Diploma', 'health-safety-workplace-diploma', 6, 2, '12 months (flexible)'),
('Leadership & Team Management Diploma', 'leadership-team-management-diploma', 6, 2, '12 months (flexible)'),
('Human Resource & Personnel Management Diploma', 'human-resource-personnel-management-diploma', 8, 2, '12 months (flexible)'),
('Training & Development Diploma', 'training-development-diploma', 8, 2, '12 months (flexible)');

-- Table structure for pages
CREATE TABLE `pages` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) UNIQUE NOT NULL,
  `content` LONGTEXT,
  `meta_title` VARCHAR(255),
  `meta_description` TEXT,
  `is_published` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO `pages` (`title`, `slug`) VALUES
('About Us', 'about-us'),
('Why choose CIC', 'why-choose-cic'),
('Study Advice', 'study-advice'),
('News & Information', 'news-information'),
('Affiliates', 'affiliates'),
('Contact Us', 'contact-us'),
('Terms & Conditions', 'terms-conditions');

-- Table structure for success_stories
CREATE TABLE `success_stories` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `student_name` VARCHAR(255),
  `country` VARCHAR(100),
  `title` VARCHAR(255),
  `story` LONGTEXT NOT NULL,
  `image` VARCHAR(255),
  `is_published` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO `success_stories` (`student_name`, `country`, `story`) VALUES
('Florence Nako', 'Vanuatu', 'I was very happy when I received my Diploma and knew at once that I was taking the right path to what would become a successful life if I continue with CIC. I plan to continue studying with CIC because I find that the approach to learning really suits my daily routine.');

-- Table structure for contacts
CREATE TABLE `contacts` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255),
  `email` VARCHAR(255),
  `phone` VARCHAR(50),
  `subject` VARCHAR(255),
  `message` TEXT NOT NULL,
  `is_read` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for banners
CREATE TABLE `banners` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255),
  `subtitle` VARCHAR(255),
  `image` VARCHAR(255),
  `link` VARCHAR(255),
  `order` INT DEFAULT 0,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

