-- CREATE TABLE categories (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     category VARCHAR(255) NOT NULL,
--     parentId INT,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
-- );

CREATE TABLE `shopcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopcategory` varchar(255) NOT NULL,
  `parentId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `techcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `techcategory` varchar(255) NOT NULL,
  `parentId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `shopproducts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopproduct_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `shopcategory_id` int(11) NOT NULL,
  `description` text,
  `image` varchar(255), -- Column to store image file path or name
  PRIMARY KEY (`id`),
  KEY `fk_shopproducts_category_id` (`shopcategory_id`),
  CONSTRAINT `fk_shopproducts_category_id` FOREIGN KEY (`shopcategory_id`) REFERENCES `shopcategories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `techproducts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `techproduct_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `techcategory_id` int(11) NOT NULL,
  `description` text,
  `image` varchar(255), -- Column to store image file path or name
  PRIMARY KEY (`id`),
  KEY `fk_techproducts_category_id` (`techcategory_id`),
  CONSTRAINT `fk_techproducts_category_id` FOREIGN KEY (`techcategory_id`) REFERENCES `techcategories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;