<?php

function ensureContractSchema(PDO $conn): void
{
    $conn->exec("CREATE TABLE IF NOT EXISTS `contracts` (
        `contract_id` INT(11) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(100) NOT NULL,
        `contractType` VARCHAR(100) NOT NULL,
        `phone` VARCHAR(20) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `proposal` TEXT NOT NULL,
        PRIMARY KEY (`contract_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

    $columns = $conn->query("SHOW COLUMNS FROM `contracts`")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array("name", $columns, true)) {
        $conn->exec("ALTER TABLE `contracts` ADD COLUMN `name` VARCHAR(100) NULL AFTER `contract_id`");
    }
    if (!in_array("proposal", $columns, true)) {
        $conn->exec("ALTER TABLE `contracts` ADD COLUMN `proposal` TEXT NULL AFTER `email`");
    }
    if (in_array("studentId", $columns, true)) {
        $conn->exec("ALTER TABLE `contracts` MODIFY COLUMN `studentId` VARCHAR(50) NULL");
    }
}
