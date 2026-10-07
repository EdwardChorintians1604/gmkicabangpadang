-- Peran BPC baru serta modul inventaris, arsip, keuangan, strategi, dan koordinasi.
ALTER TABLE `users`
MODIFY COLUMN `role` ENUM(
    'admin',
    'ketcab',
    'sekcab',
    'bencab',
    'sekfung_medko',
    'pengawas',
    'operator'
) NOT NULL DEFAULT 'operator';

UPDATE `users` SET `role` = 'ketcab' WHERE `username` = 'Dani_Manik1945';
UPDATE `users` SET `role` = 'sekcab' WHERE `username` = 'Dan_PP12';

CREATE TABLE IF NOT EXISTS `inventory_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(180) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `quantity` DECIMAL(10,2) NOT NULL DEFAULT 1,
    `unit` VARCHAR(40) NOT NULL DEFAULT 'unit',
    `location` VARCHAR(180) NULL,
    `item_condition` VARCHAR(40) NOT NULL DEFAULT 'Baik',
    `notes` TEXT NULL,
    `created_by` INT UNSIGNED NOT NULL,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_inventory_name` (`name`),
    CONSTRAINT `fk_inventory_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `office_documents` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `document_type` ENUM('archive', 'finance') NOT NULL,
    `title` VARCHAR(180) NOT NULL,
    `description` TEXT NULL,
    `period` VARCHAR(40) NULL,
    `amount` DECIMAL(15,2) NULL,
    `stored_name` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NOT NULL,
    `mime_type` VARCHAR(120) NOT NULL,
    `created_by` INT UNSIGNED NOT NULL,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_documents_type_owner` (`document_type`, `created_by`),
    CONSTRAINT `fk_documents_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `organization_strategies` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(180) NOT NULL,
    `objective` TEXT NOT NULL,
    `status` ENUM('Rencana', 'Berjalan', 'Selesai') NOT NULL DEFAULT 'Rencana',
    `target_date` DATE NULL,
    `created_by` INT UNSIGNED NOT NULL,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_strategy_status_date` (`status`, `target_date`),
    CONSTRAINT `fk_strategy_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `coordination_messages` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `parent_id` BIGINT UNSIGNED NULL,
    `sender_id` INT UNSIGNED NOT NULL,
    `sender_role` VARCHAR(40) NOT NULL,
    `recipient_role` VARCHAR(40) NOT NULL,
    `subject` VARCHAR(180) NOT NULL,
    `body` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_messages_recipient_date` (`recipient_role`, `created_at`),
    INDEX `idx_messages_sender_date` (`sender_id`, `created_at`),
    CONSTRAINT `fk_messages_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
    CONSTRAINT `fk_messages_parent` FOREIGN KEY (`parent_id`) REFERENCES `coordination_messages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
