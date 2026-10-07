CREATE TABLE IF NOT EXISTS `background_jobs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `owner_id` INT UNSIGNED NOT NULL,
    `job_type` VARCHAR(50) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `status` ENUM('queued', 'processing', 'completed', 'failed') NOT NULL DEFAULT 'queued',
    `attempts` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `output_name` VARCHAR(255) NULL,
    `error_message` VARCHAR(1000) NULL,
    `available_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    `reserved_at` DATETIME NULL,
    `finished_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_background_jobs_queue` (`status`, `available_at`, `id`),
    INDEX `idx_background_jobs_owner` (`owner_id`, `created_at`),
    CONSTRAINT `fk_background_jobs_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `inventory_items`
    ADD INDEX `idx_inventory_updated_at_id` (`updated_at`, `id`);

ALTER TABLE `office_documents`
    ADD INDEX `idx_documents_type_created_at` (`document_type`, `created_at`, `id`),
    ADD INDEX `idx_documents_owner_created_at` (`document_type`, `created_by`, `created_at`, `id`);

ALTER TABLE `organization_strategies`
    ADD INDEX `idx_strategies_target_date_id` (`target_date`, `id`);

ALTER TABLE `coordination_messages`
    ADD INDEX `idx_messages_sender_recipient_id` (`sender_role`, `recipient_role`, `id`),
    ADD INDEX `idx_messages_recipient_sender_id` (`recipient_role`, `sender_role`, `id`);
