CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(255) NOT NULL,
    parentId INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- CREATE TABLE `categories` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `category` varchar(255) NOT NULL,
--   `parentId` int(11) NOT NULL DEFAULT '0',
--   PRIMARY KEY (`id`),
--   KEY `parentId` (`parentId`),
--   CONSTRAINT `fk_categories_parentId` FOREIGN KEY (`parentId`) REFERENCES `categories` (`id`) ON DELETE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` text,
  `image` varchar(255), -- Column to store image file path or name
  PRIMARY KEY (`id`),
  KEY `fk_products_category_id` (`category_id`),
  CONSTRAINT `fk_products_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;