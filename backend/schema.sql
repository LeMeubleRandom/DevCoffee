CREATE DATABASE IF NOT EXISTS dev_coffee CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE dev_coffee;

CREATE TABLE IF NOT EXISTS drinks(
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  caffeine_mg INT NOT NULL,
  volume_ml INT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS consumptions(
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  FOREIGN KEY (id) REFERENCES drinks(id) ON DELETE CASCADE,
  developer_name VARCHAR(100) NOT NULL,
  consumed_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO 
drinks(name, caffeine_mg, volume_ml) 
VALUES 
('Espresso', 80, 30),
('Café Allongé', 110, 120),
('Monster Energy', 160, 500);