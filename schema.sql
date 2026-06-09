-- Library DB schema
CREATE DATABASE IF NOT EXISTS `library` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `library`;

CREATE TABLE IF NOT EXISTS `books` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `author` VARCHAR(255) DEFAULT NULL,
  `isbn` VARCHAR(50) DEFAULT NULL,
  `copies` INT DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL
);

-- simple default admin (username: admin, password: admin)
INSERT IGNORE INTO `users` (`username`, `password`) VALUES ('admin','admin');

CREATE TABLE IF NOT EXISTS `loans` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `book_id` INT NOT NULL,
  `borrower_name` VARCHAR(255) NOT NULL,
  `borrowed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `returned_at` DATETIME DEFAULT NULL,
  `returned` TINYINT(1) DEFAULT 0,
  FOREIGN KEY (`book_id`) REFERENCES `books`(`id`) ON DELETE CASCADE
);
