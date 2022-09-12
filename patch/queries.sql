-- added(07/11/2021)

ALTER TABLE `popular_comparisons` CHANGE `count` `view_count` INT(11) NULL DEFAULT '0';

-- added(07/14/2021)

ALTER TABLE `mobile_images` CHANGE `is_default` `sorting` INT(1) NULL DEFAULT '0';

-- added(07/15/2021)
ALTER TABLE `user_rating` CHANGE `status` `status` ENUM('Approved','Denied','Pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'Pending';

ALTER TABLE `user_rating` CHANGE `status` `status` ENUM('Approved','Pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'Pending';


-- added(08/29/2021)
