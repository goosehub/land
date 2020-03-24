-- Database sql
DROP TABLE IF EXISTS world;
DROP TABLE IF EXISTS tile;
DROP TABLE IF EXISTS unit_type;
DROP TABLE IF EXISTS account;
DROP TABLE IF EXISTS trade_request;
DROP TABLE IF EXISTS agreement_lookup;
DROP TABLE IF EXISTS supply_account_lookup;
DROP TABLE IF EXISTS supply_account_trade_lookup;
DROP TABLE IF EXISTS supply_trade_lookup;
DROP TABLE IF EXISTS supply_industry_lookup;
DROP TABLE IF EXISTS supply;
DROP TABLE IF EXISTS terrain;
DROP TABLE IF EXISTS resource;
DROP TABLE IF EXISTS settlement;
DROP TABLE IF EXISTS industry;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS chat;
DROP TABLE IF EXISTS analytics;
DROP TABLE IF EXISTS ip_request;

DROP TABLE IF EXISTS `world`;
CREATE TABLE IF NOT EXISTS `world` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(256) NOT NULL,
  `tile_size` int(4) NOT NULL,
  `crontab` varchar(256) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `world` ADD PRIMARY KEY (`id`);
ALTER TABLE `world` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `tile`;
CREATE TABLE IF NOT EXISTS `tile` (
  `id` int(10) UNSIGNED NOT NULL,
  `lat` int(4) NOT NULL,
  `lng` int(4) NOT NULL,
  `world_key` int(10) UNSIGNED NOT NULL,
  `account_key` int(10) UNSIGNED NULL,
  `terrain_key` int(10) UNSIGNED NOT NULL,
  `resource_key` int(10) UNSIGNED NULL,
  `settlement_key` int(10) UNSIGNED NULL,
  `industry_key` int(10) UNSIGNED NULL,
  `unit_key` int(10) UNSIGNED NULL, -- Infantry, Tanks, Commandos, none as null
  `unit_owner_key` int(10) UNSIGNED NULL,
  `unit_owner_color` varchar(8) NULL,
  `is_capitol` int(1) NOT NULL,
  `is_base` int(1) NOT NULL,
  `population` int(10) UNSIGNED NULL,
  `tile_name` varchar(512) NULL,
  `tile_desc` text NULL,
  `color` varchar(8) NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `tile` ADD PRIMARY KEY (`id`);
ALTER TABLE `tile` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `unit_type`;
CREATE TABLE IF NOT EXISTS `unit_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(126) NOT NULL,
  `strength_against_key` int(10) UNSIGNED NOT NULL,
  `cost_base` int(4) NOT NULL,
  `color` varchar(8) NULL,
  `character` varchar(8) NULL,
  `can_take_tiles` int(1) NOT NULL,
  `can_take_towns` int(1) NOT NULL,
  `can_take_cities` int(1) NOT NULL,
  `can_take_metros` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `unit_type` ADD PRIMARY KEY (`id`);
ALTER TABLE `unit_type` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

TRUNCATE TABLE `unit_type`;
INSERT INTO `unit_type` (`id`, `slug`, `strength_against_key`, `cost_base`, `color`, `character`,
  `can_take_tiles`, `can_take_towns`, `can_take_cities`, `can_take_metros`) VALUES
(1, 'Infantry', 3, 50, 'FF0000', 'I',
  TRUE, TRUE, FALSE, FALSE),
(2, 'Tanks', 1, 150, '00FF00', 'T',
  TRUE, TRUE, TRUE, TRUE),
(3, 'Commandos', 2, 100, 'BC13FE', 'C',
  TRUE, FALSE, FALSE, FALSE);

DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_key` int(10) UNSIGNED NOT NULL,
  `world_key` int(10) UNSIGNED NOT NULL,
  `is_active` int(1) UNSIGNED NOT NULL,
  `tutorial` int(10) UNSIGNED NOT NULL,
  -- Custom Info
  `nation_name` varchar(256) NOT NULL,
  `nation_flag` varchar(256) NOT NULL,
  `leader_name` varchar(256) NOT NULL,
  `leader_portrait` varchar(256) NOT NULL,
  `color` varchar(8) NOT NULL,
  -- Government Settings
  `government` int(10) UNSIGNED NULL, -- Democracy, Oligarchy, Autocracy, Anarchy
  `tax_rate` int(10) UNSIGNED NOT NULL,
  `ideology` int(10) UNSIGNED NULL, -- Socialism, Free Market
  `last_law_change` timestamp NOT NULL,
  -- meta
  `last_load` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `account` ADD PRIMARY KEY (`id`);
ALTER TABLE `account` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `trade_request`;
CREATE TABLE IF NOT EXISTS `trade_request` (
  `id` int(10) UNSIGNED NOT NULL,
  `request_account_key` int(10) UNSIGNED NOT NULL,
  `receive_account_key` int(10) UNSIGNED NOT NULL,
  `request_message` text NOT NULL,
  `response_message` text NOT NULL,
  `request_seen` int(1) UNSIGNED NOT NULL,
  `response_seen` int(1) UNSIGNED NOT NULL,
  `is_accepted` int(1) UNSIGNED NOT NULL,
  `is_rejected` int(1) UNSIGNED NOT NULL,
  `is_declared` int(1) UNSIGNED NOT NULL,
  `agreement_key` int(10) UNSIGNED NOT NULL, -- War, Peace, Passage
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `trade_request` ADD PRIMARY KEY (`id`);
ALTER TABLE `trade_request` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `agreement_lookup`;
CREATE TABLE IF NOT EXISTS `agreement_lookup` (
  `id` int(10) UNSIGNED NOT NULL,
  `a_account_key` int(10) UNSIGNED NOT NULL,
  `b_account_key` int(10) UNSIGNED NOT NULL,
  `agreement_key` int(10) UNSIGNED NOT NULL, -- War, Peace, Passage
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `agreement_lookup` ADD PRIMARY KEY (`id`);
ALTER TABLE `agreement_lookup` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `supply_trade_lookup`;
CREATE TABLE IF NOT EXISTS `supply_trade_lookup` (
  `id` int(10) UNSIGNED NOT NULL,
  `trade_key` int(10) UNSIGNED NOT NULL,
  `supply_key` int(10) UNSIGNED NOT NULL,
  `amount` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `supply_trade_lookup` ADD PRIMARY KEY (`id`);
ALTER TABLE `supply_trade_lookup` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `supply_industry_lookup`;
CREATE TABLE IF NOT EXISTS `supply_industry_lookup` (
  `id` int(10) UNSIGNED NOT NULL,
  `industry_key` int(10) UNSIGNED NOT NULL,
  `supply_key` int(10) UNSIGNED NOT NULL,
  `amount` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `supply_industry_lookup` ADD PRIMARY KEY (`id`);
ALTER TABLE `supply_industry_lookup` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

TRUNCATE TABLE `supply_industry_lookup`;
INSERT INTO `supply_industry_lookup` (`industry_key`, `supply_key`, `amount`) VALUES
(2, 1, 10), -- Federal
(3, 1, 10), -- Base
(4, 14, 1), -- Biofuel
(5, 15, 1), -- Coal
(6, 16, 1), -- Gas
(7, 17, 1), -- Petroleum
(8, 18, 1), -- Nuclear
(9, 5, 1), -- Manufacturing
(9, 6, 1), -- Manufacturing
(9, 7, 1), -- Manufacturing
(9, 13, 1), -- Manufacturing
(10, 13, 1), -- Chemicals
(11, 28, 1), -- Steel
(11, 13, 1), -- Steel
(12, 29, 1), -- Electronics
(12, 7, 1), -- Electronics
(13, 39, 2), -- Port
(14, 30, 1), -- Machinery
(14, 39, 1), -- Machinery
(14, 40, 1), -- Machinery
(14, 36, 1), -- Machinery
(14, 38, 1), -- Machinery
(15, 31, 1), -- Automotive
(15, 39, 1), -- Automotive
(15, 40, 1), -- Automotive
(15, 38, 1), -- Automotive
(15, 36, 1), -- Automotive
(15, 17, 1), -- Automotive
(16, 31, 1), -- Aerospace
(16, 32, 1), -- Aerospace
(16, 39, 1), -- Aerospace
(16, 40, 1), -- Aerospace
(16, 38, 1), -- Aerospace
(16, 34, 1), -- Aerospace
(16, 36, 1), -- Aerospace
(16, 17, 1), -- Aerospace
(19, 2, 1), -- Gambling
(20, 1, 10), -- University
(21, 33, 1), -- Software
(22, 1, 10); -- Healthcare

DROP TABLE IF EXISTS `supply_account_lookup`;
CREATE TABLE IF NOT EXISTS `supply_account_lookup` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_key` int(10) UNSIGNED NOT NULL,
  `supply_key` int(10) UNSIGNED NOT NULL,
  `amount` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `supply_account_lookup` ADD PRIMARY KEY (`id`);
ALTER TABLE `supply_account_lookup` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `supply_account_trade_lookup`;
CREATE TABLE IF NOT EXISTS `supply_account_trade_lookup` (
  `id` int(10) UNSIGNED NOT NULL,
  `supply_account_lookup_key` int(10) UNSIGNED NOT NULL,
  `trade_key` int(10) UNSIGNED NOT NULL,
  `amount` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `supply_account_trade_lookup` ADD PRIMARY KEY (`id`);
ALTER TABLE `supply_account_trade_lookup` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `supply`;
CREATE TABLE IF NOT EXISTS `supply` (
  `id` int(10) UNSIGNED NOT NULL,
  `label` varchar(256) NOT NULL,
  `slug` varchar(256) NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL, -- core,food,cash_crops,materials,energy,valuables,metals,light,heavy,knowledge
  `suffix` varchar(256) NOT NULL,
  `can_trade` int(1) UNSIGNED NOT NULL,
  `market_price_key` int(10) UNSIGNED NULL,
  `meta` varchar(256) NOT NULL,
  `sort_order` int(10) UNSIGNED NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `supply` ADD PRIMARY KEY (`id`);
ALTER TABLE `supply` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

TRUNCATE TABLE `supply`;
INSERT INTO `supply` (`id`, `category_id`, `label`, `slug`, `suffix`, `can_trade`, `market_price_key`, `meta`) VALUES
(1, 1, 'Cash', 'cash', 'M', TRUE, NULL, 'This rules everything'),
(2, 1, 'Support', 'support', '%', FALSE, NULL, 'Increases every minute depending on government type'),
(3, 1, 'Population', 'population', 'K', FALSE, NULL, 'Census occurs every hour and updates this value'),
(4, 1, 'Territories', 'tiles', '', FALSE, NULL, 'The primary leaderboard stat'),
(5, 2, 'Timber', 'timber', '', TRUE, NULL, ''),
(6, 2, 'Fiber', 'fiber', '', TRUE, NULL, ''),
(7, 2, 'Ore', 'ore', '', TRUE, NULL, ''),
(8, 3, 'Grain', 'grain', '', TRUE, NULL, ''),
(9, 3, 'Fruit', 'fruit', '', TRUE, NULL, ''),
(10, 3, 'Vegetables', 'vegetables', '', TRUE, NULL, ''),
(11, 3, 'Livestock', 'livestock', '', TRUE, NULL, ''),
(12, 3, 'Fish', 'fish', '', TRUE, NULL, ''),
(13, 4, 'Energy', 'energy', '', FALSE, NULL, ''),
(14, 4, 'Biofuel', 'biofuel', '', TRUE, NULL, ''),
(15, 4, 'Coal', 'coal', '', TRUE, NULL, ''),
(16, 4, 'Gas', 'gas', '', TRUE, NULL, ''),
(17, 4, 'Oil', 'oil', '', TRUE, NULL, ''),
(18, 4, 'Uranium', 'uranium', '', TRUE, NULL, ''),
(19, 5, 'Silver', 'silver', '', TRUE, 1, ''),
(20, 5, 'Gold', 'gold', '', TRUE, 2, ''),
(21, 5, 'Platinum', 'platinum', '', TRUE, 3, ''),
(22, 5, 'Gemstones', 'gemstones', '', TRUE, 4, ''),
(23, 6, 'Coffee', 'coffee', '', TRUE, NULL, ''),
(24, 6, 'Tea', 'tea', '', TRUE, NULL, ''),
(25, 6, 'Cannabis', 'cannabis', '', TRUE, NULL, ''),
(26, 6, 'Alcohols', 'alcohol', '', TRUE, NULL, ''),
(27, 6, 'Tobacco', 'tobacco', '', TRUE, NULL, ''),
(28, 7, 'Iron', 'iron', '', TRUE, NULL, ''),
(29, 7, 'Copper', 'copper', '', TRUE, NULL, ''),
(30, 7, 'Zinc', 'zinc', '', TRUE, NULL, ''),
(31, 7, 'Aluminum', 'aluminum', '', TRUE, NULL, ''),
(32, 7, 'Nickle', 'nickle', '', TRUE, NULL, ''),
(33, 8, 'Education', 'education', '', FALSE, NULL, ''),
(34, 8, 'Software', 'software', '', TRUE, NULL, ''),
(35, 8, 'Healthcare', 'healthcare', '', FALSE, NULL, ''),
(36, 8, 'Engineering', 'engineering', '', TRUE, NULL, ''),
(37, 9, 'Merchandise', 'merchandise', '', TRUE, NULL, ''),
(38, 9, 'Chemicals', 'chemicals', '', TRUE, NULL, ''),
(39, 9, 'Steel', 'steel', '', TRUE, NULL, ''),
(40, 9, 'Electronics', 'electronics', '', TRUE, NULL, ''),
(41, 10, 'Shipping Ports', 'port', '', FALSE, NULL, ''),
(42, 10, 'Machinery', 'machinery', '', TRUE, NULL, ''),
(43, 10, 'Automotive', 'automotive', '', TRUE, NULL, ''),
(44, 10, 'Aerospace', 'aerospace', '', TRUE, NULL, '');

DROP TABLE IF EXISTS `market_price`;
CREATE TABLE IF NOT EXISTS `market_price` (
  `id` int(10) UNSIGNED NOT NULL,
  `supply_key` int(10) UNSIGNED NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `starting_price` int(10) UNSIGNED NOT NULL,
  `percent_chance_of_increase` int(10) UNSIGNED NOT NULL,
  `max_increase` int(10) UNSIGNED NOT NULL,
  `max_decrease` int(10) UNSIGNED NOT NULL,
  `min_increase` int(10) UNSIGNED NOT NULL,
  `min_decrease` int(10) UNSIGNED NOT NULL,
  `min_price` int(10) UNSIGNED NOT NULL,
  `max_price` int(10) UNSIGNED NOT NULL,
  `sort_order` int(10) UNSIGNED NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `market_price` ADD PRIMARY KEY (`id`);
ALTER TABLE `market_price` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

TRUNCATE TABLE `market_price`;
INSERT INTO `market_price` (`id`, `supply_key`, `amount`, `starting_price`,
  `percent_chance_of_increase`, `max_increase`, `max_decrease`,
  `min_increase`, `min_decrease`, `min_price`, `max_price`) VALUES
-- Silver
-- Tends low and a little volatile
(1, 19, 1, 1, 
  40, 4, 5,
  1, 1, 1, 1000
),
-- Gold
-- Tends higher and not volatile 
(2, 20, 1, 1, 
  55, 3, 2,
  1, 1, 1, 1000
),
-- Platinum
-- Even but volatile
(3, 21, 1, 1, 
  50, 5, 5,
  1, 1, 1, 1000
),
-- Gemstones
-- Tends higher and a little volatile
(4, 22, 1, 1, 
  50, 2, 1,
  1, 1, 1, 1000
);

DROP TABLE IF EXISTS `terrain`;
CREATE TABLE IF NOT EXISTS `terrain` (
  `id` int(10) UNSIGNED NOT NULL,
  `label` varchar(256) NOT NULL,
  `slug` varchar(256) NOT NULL,
  `meta` varchar(256) NOT NULL,
  `sort_order` int(10) UNSIGNED NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `terrain` ADD PRIMARY KEY (`id`);
ALTER TABLE `terrain` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

TRUNCATE TABLE `terrain`;
INSERT INTO `terrain` (`id`, `label`, `slug`, `meta`) VALUES
(1, 'Fertile', 'fertile', ''),
(2, 'Barren', 'barren', ''),
(3, 'Mountain', 'mountain', ''),
(4, 'Tundra', 'tundra', ''),
(5, 'Coastal', 'coastal', ''),
(6, 'Ocean', 'ocean', '');

DROP TABLE IF EXISTS `resource`;
CREATE TABLE IF NOT EXISTS `resource` (
  `id` int(10) UNSIGNED NOT NULL,
  `label` varchar(256) NOT NULL,
  `slug` varchar(256) NOT NULL,
  `output_supply_key` int(10) UNSIGNED NOT NULL,
  `is_value_resource` int(1) UNSIGNED NOT NULL,
  `is_energy_resource` int(1) UNSIGNED NOT NULL,
  `is_metal_resource` int(1) UNSIGNED NOT NULL,
  `frequency_per_world` int(10) UNSIGNED NOT NULL,
  `spawns_in_barren` int(1) UNSIGNED NOT NULL,
  `spawns_in_mountain` int(1) UNSIGNED NOT NULL,
  `spawns_in_tundra` int(1) UNSIGNED NOT NULL,
  `spawns_in_coastal` int(1) UNSIGNED NOT NULL,
  `meta` varchar(256) NOT NULL,
  `sort_order` int(10) UNSIGNED NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `resource` ADD PRIMARY KEY (`id`);
ALTER TABLE `resource` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

TRUNCATE TABLE `resource`;
INSERT INTO `resource` (`id`, `label`, `slug`, `output_supply_key`,
  `is_value_resource`, `is_energy_resource`, `is_metal_resource`,
  `frequency_per_world`, `spawns_in_barren`, `spawns_in_mountain`, `spawns_in_tundra`, `spawns_in_coastal`
) VALUES
-- value
(
  1, 'Silver', 'silver', 19,
  TRUE, FALSE, FALSE,
  10, TRUE, TRUE, TRUE, FALSE
),
(
  2, 'Gold', 'gold', 20,
  TRUE, FALSE, FALSE,
  5, TRUE, TRUE, TRUE, FALSE
),
(
  3, 'Platinum', 'platinum', 21,
  TRUE, FALSE, FALSE,
  3, TRUE, TRUE, TRUE, FALSE
),
(
  4, 'Gemstones', 'gemstones', 22,
  TRUE, FALSE, FALSE,
  2, TRUE, TRUE, TRUE, FALSE
),
-- Energy
(
  5, 'Coal', 'coal', 15,
  FALSE, TRUE, FALSE,
  15, TRUE, TRUE, TRUE, FALSE
),
(
  6, 'Gas', 'gas', 16,
  FALSE, TRUE, FALSE,
  12, TRUE, TRUE, TRUE, FALSE
),
(
  7, 'Oil', 'oil', 17,
  FALSE, TRUE, FALSE,
  10, TRUE, FALSE, TRUE, TRUE
),
(
  8, 'Uranium', 'uranium', 18,
  FALSE, TRUE, FALSE,
  3, TRUE, TRUE, TRUE, FALSE
),
-- Metals
(
  9, 'Iron', 'iron', 28,
  FALSE, FALSE, TRUE,
  20, TRUE, TRUE, TRUE, FALSE
),
(
  10, 'Copper', 'copper', 29,
  FALSE, FALSE, TRUE,
  5, TRUE, TRUE, TRUE, FALSE
),
(
  11, 'Zinc', 'zinc', 30,
  FALSE, FALSE, TRUE,
  5, TRUE, TRUE, TRUE, FALSE
),
(
  12, 'Aluminum', 'aluminum', 31,
  FALSE, FALSE, TRUE,
  5, TRUE, TRUE, TRUE, FALSE
),
(
  13, 'Nickle', 'nickle', 32,
  FALSE, FALSE, TRUE,
  5, TRUE, TRUE, TRUE, FALSE
);

DROP TABLE IF EXISTS `settlement`;
CREATE TABLE IF NOT EXISTS `settlement` (
  `id` int(10) UNSIGNED NOT NULL,
  `label` varchar(256) NOT NULL,
  `slug` varchar(256) NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL, -- township, food, materials, energy, cash_crops,
  `is_township` int(1) NOT NULL,
  `is_food` int(1) NOT NULL,
  `is_material` int(1) NOT NULL,
  `is_energy` int(1) NOT NULL,
  `is_cash_crop` int(1) NOT NULL,
  `is_allowed_on_fertile` int(1) NOT NULL,
  `is_allowed_on_coastal` int(1) NOT NULL,
  `is_allowed_on_barren` int(1) NOT NULL,
  `is_allowed_on_mountain` int(1) NOT NULL,
  `is_allowed_on_tundra` int(1) NOT NULL,
  `base_population` int(10) UNSIGNED NOT NULL,
  `input_desc` varchar(256) NOT NULL,
  `gdp` int(10) UNSIGNED NULL,
  `output_supply_key` int(10) UNSIGNED NULL,
  `output_supply_amount` int(10) NULL,
  `meta` varchar(256) NOT NULL,
  `sort_order` int(10) UNSIGNED NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `settlement` ADD PRIMARY KEY (`id`);
ALTER TABLE `settlement` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

TRUNCATE TABLE `settlement`;
INSERT INTO `settlement` (
  `label`, `slug`, `category_id`,
  `is_township`, `is_food`, `is_material`, `is_energy`, `is_cash_crop`,
  `is_allowed_on_fertile`, `is_allowed_on_coastal`, `is_allowed_on_barren`, `is_allowed_on_mountain`, `is_allowed_on_tundra`,
  `base_population`, `input_desc`, `output_supply_key`, `output_supply_amount`, `gdp`) VALUES
('Unclaimed', 'unclaimed', 1,
  FALSE, FALSE, FALSE, FALSE, FALSE,
  TRUE, TRUE, TRUE, TRUE, TRUE,
  0, '', NULL, NULL, 0
),
('Uninhabited', 'uninhabited', 1,
  FALSE, FALSE, FALSE, FALSE, FALSE,
  TRUE, TRUE, TRUE, TRUE, TRUE,
  0, '', NULL, NULL, 0
),
('Town', 'town', 1,
  TRUE, FALSE, FALSE, FALSE, FALSE,
  TRUE, TRUE, TRUE, TRUE, TRUE,
  100, '1 food, 1 energy', NULL, NULL, 5
),
('City', 'city', 1,
  TRUE, FALSE, FALSE, FALSE, FALSE,
  TRUE, TRUE, TRUE, TRUE, FALSE,
  1000, '3 food, 3 energy, 1 cash crop, 1 merchandise', NULL, NULL, 10
),
('Metro', 'metro', 1,
  TRUE, FALSE, FALSE, FALSE, FALSE,
  TRUE, TRUE, FALSE, FALSE, FALSE,
  10000, '5 food, 5 energy, 3 cash crop, 3 merchandise, 1 steel, 1 healthcare', NULL, NULL, 20
),
('Grain', 'grain', 2,
  FALSE, TRUE, FALSE, FALSE, FALSE,
  TRUE, TRUE, FALSE, FALSE, FALSE,
  10, '', 8, 3, 1
),
('Fruit', 'fruit', 2,
  FALSE, TRUE, FALSE, FALSE, FALSE,
  TRUE, TRUE, FALSE, FALSE, FALSE,
  10, '', 9, 2, 1
),
('Vegetables', 'vegetables', 2,
  FALSE, TRUE, FALSE, FALSE, FALSE,
  TRUE, TRUE, FALSE, FALSE, FALSE,
  10, '', 10, 2, 1
),
('Livestock', 'livestock', 2,
  FALSE, TRUE, FALSE, FALSE, FALSE,
  TRUE, TRUE, FALSE, FALSE, FALSE,
  10, '', 11, 1, 2
),
('Fish', 'fish', 2,
  FALSE, TRUE, FALSE, FALSE, FALSE,
  FALSE, TRUE, FALSE, FALSE, FALSE,
  10, '', 12, 2, 4
),
('Timber', 'timber', 3,
  FALSE, FALSE, TRUE, FALSE, FALSE,
  TRUE, TRUE, FALSE, FALSE, FALSE,
  10, '', 5, 3, 1
),
('Fiber', 'fiber', 3,
  FALSE, FALSE, TRUE, FALSE, FALSE,
  TRUE, TRUE, FALSE, FALSE, FALSE,
  10, '', 6, 2, 3
),
('Ore', 'ore', 3,
  FALSE, FALSE, TRUE, FALSE, FALSE,
  FALSE, FALSE, TRUE, TRUE, FALSE,
  10, '', 7, 1, 2
),
('Biofuel', 'biofuel', 4,
  FALSE, FALSE, FALSE, TRUE, FALSE,
  TRUE, TRUE, FALSE, FALSE, FALSE,
  10, '', 14, 1, 1
),
('Solar', 'solar', 4,
  FALSE, FALSE, FALSE, TRUE, FALSE,
  TRUE, TRUE, TRUE, TRUE, FALSE,
  10, '', 13, 1, 1
),
('Wind', 'wind', 4,
  FALSE, FALSE, FALSE, TRUE, FALSE,
  TRUE, TRUE, TRUE, TRUE, FALSE,
  10, '', 13, 1, 1
),
('Coffee', 'coffee', 5,
  FALSE, FALSE, FALSE, FALSE, TRUE,
  TRUE, TRUE, FALSE, FALSE, FALSE,
  10, '', 23, 5, 3
),
('Tea', 'tea', 5,
  FALSE, FALSE, FALSE, FALSE, TRUE,
  TRUE, TRUE, FALSE, FALSE, FALSE,
  10, '', 24, 5, 3
),
('Cannabis', 'cannabis', 5,
  FALSE, FALSE, FALSE, FALSE, TRUE,
  TRUE, TRUE, FALSE, FALSE, FALSE,
  10, '', 25, 5, 3
),
('Alcohol', 'alcohol', 5,
  FALSE, FALSE, FALSE, FALSE, TRUE,
  TRUE, TRUE, FALSE, FALSE, FALSE,
  10, '', 26, 5, 3
),
('Tobacco', 'tobacco', 5,
  FALSE, FALSE, FALSE, FALSE, TRUE,
  TRUE, TRUE, FALSE, FALSE, FALSE,
  10, '', 27, 5, 3
);

DROP TABLE IF EXISTS `industry`;
CREATE TABLE IF NOT EXISTS `industry` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(256) NOT NULL,
  `slug` varchar(256) NOT NULL,
  `output_supply_key` int(10) UNSIGNED NULL,
  `output_supply_amount` int(10) NULL,
  `minimum_settlement_size` int(10) UNSIGNED NULL, -- town, city, metro
  `required_terrain_key` int(10) UNSIGNED NULL,
  `gdp` int(10) UNSIGNED NULL,
  `is_stackable` int(1) UNSIGNED NULL,
  `meta` varchar(256) NOT NULL,
  `sort_order` int(10) UNSIGNED NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `industry` ADD PRIMARY KEY (`id`);
ALTER TABLE `industry` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

TRUNCATE TABLE `industry`;
INSERT INTO `industry` (
  `id`, `category_id`, `label`, `slug`, `minimum_settlement_size`, `required_terrain_key`,
  `output_supply_key`, `output_supply_amount`, `gdp`, `is_stackable`, `meta`) VALUES
-- government
(1, 1, 'Capitol', 'capitol', NULL, NULL,
  null, 10, 10, FALSE, 'Spawns units, creates corruption'
),
(2, 1, 'Federal', 'federal', NULL, NULL,
  2, 10, 5, FALSE, ''
),
(3, 1, 'Base', 'base', NULL, NULL,
  null, 1, 3, TRUE, 'Spawns units'
),
-- energy
(4, 2, 'Biofuel', 'biofuel', NULL, NULL,
  13, 2, 1, TRUE, ''
),
(5, 2, 'Coal', 'coal', NULL, NULL,
  13, 3, 1, TRUE, ''
),
(6, 2, 'Gas', 'gas', NULL, NULL,
  13, 4, 2, TRUE, ''
),
(7, 2, 'Petroleum', 'petroleum', NULL, NULL,
  13, 8, 5, TRUE, ''
),
(8, 2, 'Nuclear', 'nuclear', NULL, NULL,
  13, 10, 5, TRUE, ''
),
-- light industry
(9, 3, 'Manufacturing', 'manufacturing', NULL, NULL,
  37, 1, 5, TRUE, ''
),
(10, 3, 'Chemicals', 'chemicals', NULL, NULL,
  38, 3, 5, TRUE, ''
),
(11, 3, 'Steel', 'steel', NULL, NULL,
  39, 5, 3, TRUE, ''
),
(12, 3, 'Electronics', 'electronics', NULL, NULL,
  40, 3, 10, TRUE, ''
),
-- hevvy industry
(13, 4, 'Shipping Port', 'port', 2, 5,
  41, 1, 50, FALSE, 'Having this industry increases National GDP by 100%'
),
(14, 4, 'Machinery', 'machinery', NULL, NULL,
  42, 3, 30, FALSE, 'A supply of machinery increases Cational GDP by 75%'
),
(15, 4, 'Automotive', 'automotive', 2, NULL,
  43, 3, 40, FALSE, 'A supply of automotive increases Cational GDP by 50%'
),
(16, 4, 'Aerospace', 'aerospace', 2, NULL,
  44, 3, 50, FALSE, 'A supply of aerospace increases Cational GDP by 25%'
),
-- tourism
(17, 5, 'Leisure', 'leisure', NULL, 5,
  null, 1, 10, FALSE, ''
),
(18, 5, 'Resort', 'resort', NULL, 3,
  null, 1, 5, FALSE, ''
),
(19, 5, 'Gambling', 'gambling', 2, NULL,
  null, 1, 10, FALSE, ''
),
-- knowledge/quaternary
(20, 6, 'University', 'university', NULL, NULL,
  33, 3, 3, FALSE, ''
),
(21, 6, 'Software', 'software', 2, NULL,
  34, 3, 8, FALSE, ''
),
(22, 6, 'Healthcare', 'healthcare', 2, NULL,
  35, 1, 6, FALSE, ''
),
-- metro
(23, 7, 'Financial & Banking', 'financial_banking', 3, NULL,
  null, 1, 200, FALSE, ''
),
(24, 7, 'Entertainment & Media', 'entertainment_media', 3, NULL,
  NULL, 1, 50, FALSE, 'Having this industry reduces National Corruption by 50%'
),
(25, 7, 'Engineering & Design', 'engineering_design', 3, NULL,
  36, 5, 100, FALSE, ''
);

-- 
-- 
-- 

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `facebook_id` int(16) NOT NULL,
  `email` varchar(256) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `ab_test` varchar(256) NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `user` ADD PRIMARY KEY (`id`);
ALTER TABLE `user` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `chat`;
CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_key` int(10) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `color` varchar(8) NOT NULL,
  `message` text NOT NULL,
  `world_key` int(10) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `chat` ADD PRIMARY KEY (`id`);
ALTER TABLE `chat` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `analytics`;
CREATE TABLE IF NOT EXISTS `analytics` (
  `id` int(10) UNSIGNED NOT NULL,
  `marketing_slug` varchar(64) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `analytics` ADD PRIMARY KEY (`id`);
ALTER TABLE `analytics` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `ip_request`;
CREATE TABLE IF NOT EXISTS `ip_request` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip` varchar(64) NOT NULL,
  `request` varchar(64) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `ip_request` ADD PRIMARY KEY (`id`);
ALTER TABLE `ip_request` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
