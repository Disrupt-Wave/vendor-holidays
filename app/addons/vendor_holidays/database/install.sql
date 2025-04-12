CREATE TABLE IF NOT EXISTS `?:vendor_holidays` (
    `holiday_id` int(11) NOT NULL AUTO_INCREMENT,
    `vendor_id` int(11) NOT NULL,
    `start_date` date NOT NULL,
    `end_date` date NOT NULL,
    `status` char(1) NOT NULL DEFAULT 'A',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`holiday_id`),
    KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8; 