<?php

$product_liftings = "
CREATE OR REPLACE VIEW view_liftings AS
SELECT
`liftings`.`company_id` as `company_id`,
`liftings`.`store_id` as `store_id`,
`liftings`.`lifting_date` as `date`,
`liftings`.`product_type` as `product_type`,
`lifting_products`.`product_id` as `product_id`,
`products`.`name` as `name`,
`products`.`code` as `code`,
`products`.`shared_profit` as `shared_profit`,
`lifting_products`.`variant_id` as `variant_id`,
`attributes`.`name` as `attribute_name`,
`categories`.`id` as `category_id`,
`categories`.`name` as `category_name`,
`product_prices`.`sale_price` as `sale_price`,
`product_prices`.`online_price` as `online_price`,
`product_skus`.`id` as `sku_id`,
`product_skus`.`sku` as `sku`,
`product_skus`.`price` as `variant_price`,
`lifting_products`.`qty` as `qty`,
`lifting_products`.`total_amount` - `lifting_products`.`discount` as `amount`
FROM `lifting_products`
LEFT JOIN `products` ON `products`.`id` = `lifting_products`.`product_id`
LEFT JOIN `product_prices` ON `product_prices`.`product_id` = `products`.`id`
LEFT JOIN `attributes` ON `attributes`.`id` = `products`.`attribute_id`
LEFT JOIN `categories` ON `categories`.`id` = `products`.`category_id`
LEFT JOIN `product_skus` ON `product_skus`.`id` = `lifting_products`.`variant_id`
LEFT JOIN `liftings` ON `liftings`.`id` = `lifting_products`.`lifting_id` WHERE `liftings`.`deleted_at` IS NULL AND `products`.`deleted_at` IS NULL AND `products`.`status` = 1
";

$product_lifting_returns = "
CREATE OR REPLACE VIEW view_lifting_returns AS
SELECT
`lifting_returns`.`company_id` as `company_id`,
`lifting_returns`.`store_id` as `store_id`,
`lifting_returns`.`date` as `date`,
`lifting_returns`.`product_type` as `product_type`,
`lifting_return_lists`.`product_id` as `product_id`,
`product_skus`.`id` as `sku_id`,
`product_skus`.`sku` as `sku`,
`lifting_return_lists`.`qty` as `qty`,
`lifting_return_lists`.`amount` - `lifting_return_lists`.`lifting_discount` as `amount`
FROM `lifting_return_lists`
LEFT JOIN `products` ON `products`.`id` = `lifting_return_lists`.`product_id`
LEFT JOIN `product_skus` ON `product_skus`.`id` = `lifting_return_lists`.`variant_id`
LEFT JOIN `lifting_returns` ON `lifting_returns`.`id` = `lifting_return_lists`.`lifting_return_id` WHERE `lifting_returns`.`deleted_at` IS NULL AND `products`.`deleted_at` IS NULL AND `products`.`status` = 1
";

$product_sales = "
CREATE OR REPLACE VIEW view_sales AS
SELECT
`sales`.`company_id` as `company_id`,
`sales`.`store_id` as `store_id`,
`sales`.`date` as `date`,
`sales`.`product_type` as `product_type`,
`sales`.`sales_type` as `sales_type`,
`sales_lists`.`product_id` as `product_id`,
`products`.`name` as `name`,
`categories`.`id` as `category_id`,
`categories`.`name` as `category_name`,
`product_skus`.`id` as `sku_id`,
`product_skus`.`sku` as `sku`,
`sales_lists`.`qty` as `qty`,
`sales_lists`.`amount` - `sales_lists`.`discount` as `amount`,
`sales_lists`.`returned_qty` as `returned_qty`,
`sales_lists`.`returned_amount` as `returned_amount`
FROM `sales_lists`
LEFT JOIN `products` ON `products`.`id` = `sales_lists`.`product_id`
LEFT JOIN `categories` ON `categories`.`id` = `products`.`category_id`
LEFT JOIN `product_skus` ON `product_skus`.`id` = `sales_lists`.`variant_id`
LEFT JOIN `sales` ON `sales`.`id` = `sales_lists`.`sales_id` WHERE `sales`.`deleted_at` IS NULL AND `products`.`deleted_at` IS NULL AND `products`.`status` = 1
";

$product_sales_returns = "
CREATE OR REPLACE VIEW view_sales_returns AS
SELECT
`sales_returns`.`company_id` as `company_id`,
`sales_returns`.`store_id` as `store_id`,
`sales_returns`.`date` as `date`,
`sales_returns`.`product_type` as `product_type`,
`sales_return_lists`.`product_id` as `product_id`,
`products`.`name` as `name`,
`categories`.`id` as `category_id`,
`categories`.`name` as `category_name`,
`product_skus`.`id` as `sku_id`,
`product_skus`.`sku` as `sku`,
`sales_return_lists`.`qty` as `qty`,
`sales_return_lists`.`amount` - `sales_return_lists`.`sales_discount` as `amount`
FROM `sales_return_lists`
LEFT JOIN `products` ON `products`.`id` = `sales_return_lists`.`product_id`
LEFT JOIN `categories` ON `categories`.`id` = `products`.`category_id`
LEFT JOIN `product_skus` ON `product_skus`.`id` = `sales_return_lists`.`variant_id`
LEFT JOIN `sales_returns` ON `sales_returns`.`id` = `sales_return_lists`.`sales_return_id` WHERE `sales_returns`.`deleted_at` IS NULL AND `sales_returns`.`approve` = 1 AND `sales_returns`.`reject` = 0 AND `products`.`deleted_at` IS NULL AND `products`.`status` = 1
";

$product_online_sales = "
CREATE OR REPLACE VIEW view_online_sales AS
SELECT
`order_products`.`order_id` as `order_id`,
`order_products`.`id` as `order_product_id`,
`products`.`company_id` as `company_id`,
`products`.`product_type` as `product_type`,
`order_products`.`product_id` as `product_id`,
`order_products`.`variant_id` as `sku_id`,
`products`.`category_id` as `category_id`,
`products`.`name` as `name`,
`products`.`code` as `code`,
`order_products`.`quantity` as `qty`,
`order_products`.`damaged_quantity` as `damaged_qty`,
`order_products`.`subtotal` - `order_products`.`return_amount` - `order_products`.`discount` as `amount`,
`orders`.`date` as `date`,
`orders`.`collected_at` as `collected_at`,
`orders`.`store_id` as `store_id`,
`orders`.`collected` as `collected`,
`orders`.`status` as `status`,
`orders`.`is_stock` as `is_stock`
FROM `order_products`
LEFT JOIN `products` ON `products`.`id` = `order_products`.`product_id`
LEFT JOIN `orders` ON `orders`.`id` = `order_products`.`order_id` WHERE `orders`.`deleted_at` IS NULL AND `products`.`deleted_at` IS NULL AND `products`.`status` = 1
";

$product_online_returns = "
CREATE OR REPLACE VIEW view_online_returns AS
SELECT
`products`.`company_id` as `company_id`,
`products`.`product_type` as `product_type`,
`order_products`.`product_id` as `product_id`,
`order_products`.`variant_id` as `sku_id`,
`products`.`category_id` as `category_id`,
`products`.`name` as `name`,
`products`.`code` as `code`,
`order_products`.`quantity` as `qty`,
`order_products`.`subtotal` as `amount`,
`orders`.`date` as `date`,
`orders`.`store_id` as `store_id`
FROM `order_products`
LEFT JOIN `products` ON `products`.`id` = `order_products`.`product_id`
LEFT JOIN `orders` ON `orders`.`id` = `order_products`.`order_id` WHERE `orders`.`deleted_at` IS NULL AND `orders`.`status` = 'Returned' OR `orders`.`status` = 'Cancelled' AND `products`.`deleted_at` IS NULL AND `products`.`status` = 1
";

$product_transfers = "
CREATE OR REPLACE VIEW view_transfers AS
SELECT
`transfers`.`company_id` as `company_id`,
`transfers`.`host_id` as `host_id`,
`transfers`.`destination_id` as `destination_id`,
`transfers`.`date` as `date`,
`transfers`.`product_type` as `product_type`,
`transfer_products`.`product_id` as `product_id`,
`products`.`name` as `name`,
`categories`.`id` as `category_id`,
`categories`.`name` as `category_name`,
`product_skus`.`id` as `sku_id`,
`product_skus`.`sku` as `sku`,
`transfer_products`.`qty` as `qty`
FROM `transfer_products`
LEFT JOIN `products` ON `products`.`id` = `transfer_products`.`product_id`
LEFT JOIN `categories` ON `categories`.`id` = `products`.`category_id`
LEFT JOIN `product_skus` ON `product_skus`.`id` = `transfer_products`.`variant_id`
LEFT JOIN `transfers` ON `transfers`.`id` = `transfer_products`.`transfer_id` WHERE `transfers`.`deleted_at` IS NULL AND `transfers`.`reject` = 0 AND `transfer_products`.`is_back` = 0 AND `products`.`deleted_at` IS NULL AND `products`.`status` = 1
";

$client_sales = "
CREATE OR REPLACE VIEW view_client_sales AS
SELECT
`sales_lists`.`company_id` as `company_id`,
`sales_lists`.`client_id` as `client_id`,
`client_categories`.`id` as `client_category_id`,
`client_categories`.`name` as `client_category_name`,
`clients`.`name` as `client_name`,
`clients`.`phone` as `client_phone`,
`clients`.`code` as `client_code`,
`regions`.`id` as `region_id`,
`regions`.`name` as `region_name`,
`areas`.`id` as `area_id`,
`areas`.`name` as `area_name`,
`territories`.`id` as `territory_id`,
`territories`.`name` as `territory_name`,
`sales`.`date` as `date`,
SUM(`sales_lists`.`amount`) - SUM(`sales_lists`.`discount`) - SUM(`sales_lists`.`returned_amount`) as `amount`,
`sales`.`total_paid` as `total_paid`,
`staff`.`id` as `staff_id`,
`staff`.`name` as `staff_name`
FROM `sales_lists`
LEFT JOIN `sales` ON `sales`.`id` = `sales_lists`.`sales_id`
LEFT JOIN `clients` ON `clients`.`id` = `sales`.`client_id`
LEFT JOIN `staff` ON `staff`.`id` = `sales`.`staff_id` AND `staff`.`deleted_at` IS NULL
LEFT JOIN `client_categories` ON `client_categories`.`id` = `clients`.`client_category_id` AND `client_categories`.`deleted_at` IS NULL
LEFT JOIN `areas` ON `areas`.`id` = `clients`.`area_id` AND `areas`.`deleted_at` IS NULL
LEFT JOIN `regions` ON `regions`.`id` = `areas`.`region_id` AND `regions`.`deleted_at` IS NULL
LEFT JOIN `territories` ON `territories`.`id` = `clients`.`territory_id` AND `territories`.`deleted_at` IS NULL WHERE  `sales`.`deleted_at` IS NULL AND `clients`.`deleted_at` IS NULL GROUP BY `sales_lists`.`sales_id`
";

$client_returns = "
CREATE OR REPLACE VIEW view_client_returns AS
SELECT
`clients`.`company_id` as `company_id`,
`clients`.`id` as `client_id`,
`client_categories`.`id` as `client_category_id`,
`client_categories`.`name` as `client_category_name`,
`sales_returns`.`date` as `date`,
`sales_return_lists`.`amount` - `sales_return_lists`.`sales_discount` as `amount`
FROM `clients`
LEFT JOIN `sales_returns` ON `clients`.`id` = `sales_returns`.`client_id` AND `sales_returns`.`deleted_at` IS NULL
LEFT JOIN `client_categories` ON `client_categories`.`id` = `clients`.`client_category_id` AND `client_categories`.`deleted_at` IS NULL
LEFT JOIN `sales_return_lists` ON `sales_returns`.`id` = `sales_return_lists`.`sales_return_id` WHERE `clients`.`deleted_at` IS NULL
";

$client_collections = "
CREATE OR REPLACE VIEW view_client_collections AS
SELECT
`clients`.`id` as `client_id`,
`collections`.`payment_date` as `payment_date`,
`collections`.`collection_type` as `collection_type`,
`collections`.`amount` as `amount`
FROM `clients`
LEFT JOIN `collections` ON `clients`.`id` = `collections`.`client_id` AND `collections`.`collection_type` != 'adjust' AND `collections`.`deleted_at` IS NULL
";

$product_sales = "
CREATE OR REPLACE VIEW view_product_sales AS
SELECT
`products`.`company_id` as `company_id`,
`products`.`id` as `product_id`,
`products`.`category_id` as `category_id`,
`categories`.`name` as `category_name`,
`products`.`name` as `name`,
`products`.`code` as `code`,
`sales`.`date` as `date`,
`staff`.`id` as `staff_id`,
`staff`.`name` as `staff_name`,
`sales_lists`.`qty` as `qty`,
`sales_lists`.`amount` - `sales_lists`.`discount` as `amount`
FROM `products`
LEFT JOIN `sales_lists` ON `products`.`id` = `sales_lists`.`product_id`
LEFT JOIN `categories` ON `categories`.`id` = `products`.`category_id`
LEFT JOIN `sales` ON `sales`.`id` = `sales_lists`.`sales_id` AND `sales`.`deleted_at` IS NULL
LEFT JOIN `staff` ON `staff`.`id` = `sales`.`staff_id` AND `staff`.`deleted_at` IS NULL WHERE `products`.`deleted_at` IS NULL AND `products`.`status` = 1
";

$product_returns = "
CREATE OR REPLACE VIEW view_product_returns AS
SELECT
`products`.`company_id` as `company_id`,
`products`.`id` as `product_id`,
`products`.`category_id` as `category_id`,
`products`.`name` as `name`,
`products`.`code` as `code`,
`sales_returns`.`date` as `date`,
`sales_return_lists`.`qty` as `qty`,
`sales_return_lists`.`amount` - `sales_return_lists`.`sales_discount` as `amount`
FROM `products`
LEFT JOIN `sales_return_lists` ON `products`.`id` = `sales_return_lists`.`product_id`
LEFT JOIN `sales_returns` ON `sales_returns`.`id` = `sales_return_lists`.`sales_return_id` AND `sales_returns`.`deleted_at` IS NULL WHERE `products`.`deleted_at` IS NULL AND `products`.`status` = 1
";

$trialBalance = "
    CREATE OR REPLACE VIEW view_trial_balance AS SELECT
    `account_transactions`.`voucher_type` as `voucher_type`,
    `account_transactions`.`voucher_no` as `voucher_no`,
    `account_transactions`.`voucher_date` as `voucher_date`,
    `account_transactions`.`coa_setup_id` as `coa_setup_id`,
    `account_transactions`.`coa_head_code` as `coa_head_code`,
    `account_transactions`.`debit_amount` as `debit_amount`,
    `account_transactions`.`credit_amount` as `credit_amount`,
    `coa_setups`.`transaction` as `transaction`,
    `coa_setups`.`general` as `general`,
    `coa_setups`.`parent_id` as `parent_id`,
    `coa_setups`.`head_type` as `head_type`,
    `coa_setups`.`head_name` as `head_name`
    FROM `account_transactions`
    LEFT JOIN `coa_setups` ON `coa_setups`.`id` = `account_transactions`.`coa_setup_id`
    ";

$sales_history = "
    CREATE OR REPLACE VIEW view_sales_history AS SELECT
    `sales_lists`.`company_id` as `company_id`,
    `sales_lists`.`product_id` as `product_id`,
    `sales_lists`.`sales_id` as `sales_id`,
    `products`.`name` as `product_name`,
    `products`.`code` as `product_code`,
    `products`.`category_id` as `category_id`,
    `vendors`.`name` as `product_vendor_name`,
    `categories`.`name` as `category_name`,
    `attributes`.`name` as `attribute_name`,
    `sales_lists`.`rate` as `rate`,
    `sales_lists`.`qty` as `qty`,
    `sales_lists`.`amount` - `sales_lists`.`discount` as `amount`,
    `sales`.`store_id` as `store_id`,
    `sales`.`staff_id` as `staff_id`,
    `staff`.`name` as `staff_name`,
    `sales`.`client_id` as `client_id`,
    `clients`.`name` as `client_name`,
    `client_categories`.`name` as `client_category_name`,
    `areas`.`region_id` as `region_id`,
    `areas`.`name` as `area_name`,
    `clients`.`area_id` as `area_id`,
    `clients`.`territory_id` as `territory_id`,
    `territories`.`name` as `territory_name`,
    `sales`.`invoice` as `invoice`,
    `sales`.`date` as `date`,
    `sales`.`sales_type` as `sales_type`
    FROM `sales_lists`
    LEFT JOIN `sales` ON `sales`.`id` = `sales_lists`.`sales_id` AND `sales`.`deleted_at` IS NULL
    LEFT JOIN `staff` ON `staff`.`id` = `sales`.`staff_id`
    LEFT JOIN `clients` ON `clients`.`id` = `sales_lists`.`client_id`
    LEFT JOIN `areas` ON `areas`.`id` = `clients`.`area_id`
    LEFT JOIN `products` ON `products`.`id` = `sales_lists`.`product_id`
    LEFT JOIN `attributes` ON `attributes`.`id` = `products`.`attribute_id`
    LEFT JOIN `categories` ON  `categories`.`id` = `products`.`category_id`
    LEFT JOIN `client_categories` ON  `client_categories`.`id` = `clients`.`client_category_id`
    LEFT JOIN `vendors` ON  `vendors`.`id` = `products`.`vendor_id`
    LEFT JOIN `territories` ON  `territories`.`id` = `clients`.`territory_id`
    ";

$collection_history = "
    CREATE OR REPLACE VIEW view_collection_history AS SELECT
    `collections`.`company_id` as `company_id`,
    `collections`.`collection_type` as `collection_type`,
    `collections`.`payment_no` as `payment_no`,
    `collections`.`payment_type` as `payment_type`,
    `collections`.`amount` as `amount`,
    `collections`.`payment_date` as `date`,
    `collections`.`client_id` as `client_id`,
    `clients`.`name` as `client_name`,
    `client_categories`.`name` as `client_category_name`,
    `areas`.`region_id` as `region_id`,
    `clients`.`area_id` as `area_id`,
    `areas`.`name` as `area_name`,
    `clients`.`territory_id` as `territory_id`,
    `territories`.`name` as `territory_name`,
    `staff`.`id` as `staff_id`,
    `staff`.`name` as `staff_name`,
    `collections`.`created_by` as `created_by`,
    `users`.`name` as `entry_name`
    FROM `collections`
    LEFT JOIN `clients` ON `clients`.`id` = `collections`.`client_id`
    LEFT JOIN `client_categories` ON  `client_categories`.`id` = `clients`.`client_category_id`
    LEFT JOIN `areas` ON `areas`.`id` = `clients`.`area_id`
    LEFT JOIN `territories` ON  `territories`.`id` = `clients`.`territory_id`
    LEFT JOIN `staff` ON `staff`.`id` = `collections`.`staff_id`
    LEFT JOIN `users` ON `users`.`id` = `collections`.`created_by` WHERE `collections`.`deleted_at` IS NULL
    ";

$view_collectionable_sales = "
    CREATE OR REPLACE VIEW view_collectionable_sales AS
    SELECT
    `sales`.`id` as `id`,
    `sales`.`company_id` as `company_id`,
    `sales`.`store_id` as `store_id`,
    `sales`.`client_id` as `client_id`,
    `clients`.`name` as `client_name`,
    `sales`.`invoice` as `invoice`,
    `sales`.`date` as `date`,
    `sales`.`total_amount` as `total_amount`,
    `sales`.`total_paid` as `total_paid`,
    `sales`.`discount` as `discount`,
    SUM(`sales_lists`.`returned_amount`) as `returned_amount`,
    `sales`.`total_amount` - SUM(`sales_lists`.`returned_amount`) - `sales`.`discount` - `sales`.`total_paid` as `collectionable_amount`
    FROM `sales_lists`
    LEFT JOIN `clients` ON `clients`.`id` = `sales_lists`.`client_id`
    LEFT JOIN `sales` ON `sales`.`id` = `sales_lists`.`sales_id` WHERE `sales`.`deleted_at` IS NULL GROUP BY `sales`.`id`
    ";

$lifting_and_returns = "
    CREATE OR REPLACE VIEW view_lifting_and_returns AS
    SELECT
    `liftings`.`id` as `id`,
    `liftings`.`company_id` as `company_id`,
    `liftings`.`lifting_no` as `lifting_no`,
    `liftings`.`lifting_date` as `date`,
    `liftings`.`store_id` as `store_id`,
    `liftings`.`vendor_id` as `vendor_id`,
    SUM(`lifting_products`.`total_amount`) as `total_amount`,
    SUM(`lifting_products`.`discount`) as `discount`,
    SUM(`lifting_products`.`total_amount`) - SUM(`lifting_products`.`discount`) as `payable_amount`,
    ROUND(SUM(((`lifting_products`.`total_amount` - `lifting_products`.`discount`) / `lifting_products`.`qty`) * `lifting_products`.`return_qty`), 2) as `return_amount`,
    `liftings`.`total_paid` as `total_paid`
    FROM `lifting_products`
    LEFT JOIN `liftings` ON `liftings`.`id` = `lifting_products`.`lifting_id` WHERE `liftings`.`deleted_at` IS NULL GROUP BY `lifting_products`.`lifting_id`
    ";

$client_sales = "
    CREATE OR REPLACE VIEW view_product_sales AS
    SELECT
    `sales_lists`.`company_id` as `company_id`,
    `sales_lists`.`client_id` as `client_id`,
    `client_categories`.`id` as `client_category_id`,
    `client_categories`.`name` as `client_category_name`,
    `clients`.`name` as `client_name`,
    `clients`.`phone` as `client_phone`,
    `clients`.`code` as `client_code`,
    `regions`.`id` as `region_id`,
    `regions`.`name` as `region_name`,
    `areas`.`id` as `area_id`,
    `areas`.`name` as `area_name`,
    `territories`.`id` as `territory_id`,
    `territories`.`name` as `territory_name`,
    `sales`.`date` as `date`,
    SUM(`sales_lists`.`amount`) - SUM(`sales_lists`.`discount`) - SUM(`sales_lists`.`returned_amount`) as `amount`,
    `sales`.`total_paid` as `total_paid`,
    `staff`.`id` as `staff_id`,
    `staff`.`name` as `staff_name`
    FROM `sales_lists`
    LEFT JOIN `sales` ON `sales`.`id` = `sales_lists`.`sales_id`
    LEFT JOIN `clients` ON `clients`.`id` = `sales`.`client_id`
    LEFT JOIN `staff` ON `staff`.`id` = `sales`.`staff_id` AND `staff`.`deleted_at` IS NULL
    LEFT JOIN `client_categories` ON `client_categories`.`id` = `clients`.`client_category_id` AND `client_categories`.`deleted_at` IS NULL
    LEFT JOIN `areas` ON `areas`.`id` = `clients`.`area_id` AND `areas`.`deleted_at` IS NULL
    LEFT JOIN `regions` ON `regions`.`id` = `areas`.`region_id` AND `regions`.`deleted_at` IS NULL
    LEFT JOIN `territories` ON `territories`.`id` = `clients`.`territory_id` AND `territories`.`deleted_at` IS NULL WHERE  `sales`.`deleted_at` IS NULL AND `clients`.`deleted_at` IS NULL GROUP BY `sales_lists`.`sales_id`
    ";

$product_online_sales = "
    CREATE OR REPLACE VIEW view_online_all_sales AS
    SELECT
    `order_products`.`order_id` as `order_id`,
    `order_products`.`id` as `order_product_id`,
    `products`.`company_id` as `company_id`,
    `products`.`product_type` as `product_type`,
    `order_products`.`product_id` as `product_id`,
    `order_products`.`variant_id` as `sku_id`,
    `products`.`category_id` as `category_id`,
    `products`.`name` as `name`,
    `products`.`code` as `code`,
    `order_products`.`quantity` as `qty`,
    `order_products`.`subtotal` - `order_products`.`return_amount` - `order_products`.`discount` as `amount`,
    `order_products`.`return_amount` as `return_amount`,
    `orders`.`date` as `date`,
    `orders`.`store_id` as `store_id`,
    `orders`.`status` as `status`,
    `orders`.`area_id` as `area_id`
    FROM `order_products`
    LEFT JOIN `products` ON `products`.`id` = `order_products`.`product_id`
    LEFT JOIN `orders` ON `orders`.`id` = `order_products`.`order_id` WHERE `orders`.`deleted_at` IS NULL AND `products`.`deleted_at` IS NULL AND `products`.`status` = 1
    ";

$all_sales = "
   CREATE OR REPLACE VIEW view_all_sales AS
    SELECT
        `order_products`.`product_id` AS `product_id`,
        `products`.`name` AS `product_name`,
        `products`.`code` AS `product_code`,
        `products`.`category_id` AS `category_id`,
        `categories`.`name` AS `category_name`,
        `attributes`.`name` AS `attribute_name`,
        `order_products`.`sale_price` AS `rate`,
        `order_products`.`quantity` AS `qty`,
        (`order_products`.`subtotal` - `order_products`.`return_amount` - `order_products`.`discount`) AS `amount`,
        `orders`.`store_id` AS `store_id`,
        `orders`.`date` AS `date`
    FROM `order_products`
    LEFT JOIN `products` ON `products`.`id` = `order_products`.`product_id`
    LEFT JOIN `attributes` ON `attributes`.`id` = `products`.`attribute_id`
    LEFT JOIN `categories` ON `categories`.`id` = `products`.`category_id`
    LEFT JOIN `orders` ON `orders`.`id` = `order_products`.`order_id`
    WHERE `orders`.`deleted_at` IS NULL
    AND `orders`.`status` IN ('Delivered', 'On Route', 'Collected')
    AND `products`.`deleted_at` IS NULL
    AND `products`.`status` = 1

    UNION ALL

    SELECT
        `sales_lists`.`product_id` AS `product_id`,
        `products`.`name` AS `product_name`,
        `products`.`code` AS `product_code`,
        `products`.`category_id` AS `category_id`,
        `categories`.`name` AS `category_name`,
        `attributes`.`name` AS `attribute_name`,
        `sales_lists`.`rate` AS `rate`,
        `sales_lists`.`qty` AS `qty`,
        (`sales_lists`.`amount` - `sales_lists`.`discount`) AS `amount`,
        `sales`.`store_id` AS `store_id`,
        `sales`.`date` AS `date`
    FROM `sales_lists`
    LEFT JOIN `sales` ON `sales`.`id` = `sales_lists`.`sales_id`
    LEFT JOIN `products` ON `products`.`id` = `sales_lists`.`product_id`
    LEFT JOIN `attributes` ON `attributes`.`id` = `products`.`attribute_id`
    LEFT JOIN `categories` ON `categories`.`id` = `products`.`category_id`
    WHERE `sales`.`deleted_at` IS NULL
    AND `products`.`deleted_at` IS NULL
    AND `products`.`status` = 1;
    ";

$client_realization_sales = "
   CREATE OR REPLACE VIEW view_client_realization_sales AS
    SELECT
    `sales`.`company_id` as `company_id`,
    `sales`.`client_id` as `client_id`,
    `clients`.`client_category_id` as `client_category_id`,
    `client_categories`.`name` as `client_category_name`,
    `clients`.`name` as `client_name`,
    `clients`.`phone` as `client_phone`,
    `clients`.`code` as `client_code`,
    `areas`.`region_id` as `region_id`,
    `regions`.`name` as `region_name`,
    `clients`.`area_id` as `area_id`,
    `areas`.`name` as `area_name`,
    `clients`.`territory_id` as `territory_id`,
    `territories`.`name` as `territory_name`,
    `sales`.`date` as `date`,
    `sales`.`total_amount` - `sales`.`discount`  as `amount`,
    `sales`.`total_paid` as `total_paid`,
    `sales`.`staff_id` as `staff_id`,
    `staff`.`name` as `staff_name`
    FROM `sales`
    LEFT JOIN `clients` ON `clients`.`id` = `sales`.`client_id`
    LEFT JOIN `areas` ON `areas`.`id` = `clients`.`area_id`
    LEFT JOIN `regions` ON `regions`.`id` = `areas`.`region_id`
    LEFT JOIN `territories` ON `territories`.`id` = `clients`.`territory_id`
    LEFT JOIN `staff` ON `staff`.`id` = `sales`.`staff_id`
    LEFT JOIN `client_categories` ON `client_categories`.`id` = `clients`.`client_category_id` WHERE `clients`.`deleted_at` IS NULL AND `sales`.`deleted_at` IS NULL
    ";
