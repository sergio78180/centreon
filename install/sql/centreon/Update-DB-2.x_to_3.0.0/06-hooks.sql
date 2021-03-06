-- Table manage hooks
CREATE TABLE `hooks` (
    `hook_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hook_name` VARCHAR(255),
    `hook_description` VARCHAR(255),
    `hook_type` tinyint(1) DEFAULT 0,
    PRIMARY KEY(`hook_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `module_hooks` (
    `module_id` INT UNSIGNED NOT NULL,
    `hook_id` INT UNSIGNED NOT NULL,
    `module_hook_name` VARCHAR(255) NOT NULL,
    `module_hook_description` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`module_id`, `hook_id`),
    CONSTRAINT `fk_module_id` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_hook_id` FOREIGN KEY (`hook_id`) REFERENCES `hooks` (`hook_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
