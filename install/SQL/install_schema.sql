CREATE TABLE `wcioshop_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminEmail` varchar(320) NOT NULL,
  `adminPassword` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attachmentType` varchar(30) NOT NULL,
  `attachmentPostId` int(11) NOT NULL,
  `attachmentValue` varchar(150) NOT NULL,
  `attachmentOrder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(10000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=505 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_corders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) NOT NULL,
  `prd_id` varchar(50) NOT NULL,
  `prd_name` varchar(100) NOT NULL,
  `prd_attribute` varchar(50) NOT NULL,
  `prd_amount` int(11) NOT NULL,
  `prd_price` varchar(30) NOT NULL,
  `prd_weight` int(11) NOT NULL,
  `prd_vat` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3813 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `amount` varchar(20) NOT NULL,
  `ispercent` varchar(20) NOT NULL,
  `IsLimitedUse` int(11) NOT NULL,
  `Uses` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(11) NOT NULL,
  `langname` varchar(50) NOT NULL,
  `shortname` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pageurl` varchar(500) NOT NULL,
  `metatype` varchar(500) NOT NULL,
  `metacontent` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7903 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `content` varchar(10000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=508 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_permalinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postType` varchar(20) NOT NULL,
  `postId` int(11) NOT NULL,
  `url` varchar(100) NOT NULL,
  `templateFile` varchar(100) NOT NULL,
  `SEOtitle` varchar(200) NOT NULL,
  `SEOkeywords` varchar(250) NOT NULL,
  `SEOdescription` varchar(1000) NOT NULL,
  `SEOnoIndex` int(11) NOT NULL,
  `isHomePage` int(11) NOT NULL,
  `smartyCache` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=497 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_porders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) NOT NULL,
  `cart_status` varchar(50) NOT NULL,
  `cart_shipping` varchar(50) NOT NULL,
  `cart_shipping_vat` int(11) NOT NULL,
  `cart_fees` varchar(50) NOT NULL,
  `cart_discount` varchar(50) NOT NULL,
  `cart_discount_used` varchar(20) NOT NULL,
  `cart_total` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `adress` varchar(100) NOT NULL,
  `zip` varchar(50) NOT NULL,
  `city` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `notes` varchar(500) NOT NULL,
  `dateandtime` varchar(50) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `paymentmethod` int(11) NOT NULL,
  `paymentmethodNiceName` varchar(250) NOT NULL,
  `shippingmethod` int(11) NOT NULL,
  `shippingmethodNiceName` varchar(250) NOT NULL,
  `AdminNotes` text NOT NULL,
  `StockUpdated` int(11) NOT NULL DEFAULT 0,
  `Active` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1688 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_product_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productid` varchar(20) NOT NULL,
  `attribute` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prdid` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5762 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_productmeta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productId` int(11) NOT NULL,
  `columnName` varchar(200) NOT NULL,
  `columnNiceName` varchar(200) NOT NULL,
  `columnValue` varchar(10000) NOT NULL,
  `columnDescription` varchar(1000) NOT NULL,
  `columnOrder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=796 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `featured` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=447 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `wcioshop_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `autoload` int(11) NOT NULL,
  `settingOrder` int(11) NOT NULL,
  `columnName` varchar(50) NOT NULL,
  `columnNiceName` varchar(100) NOT NULL,
  `settingMainGroup` varchar(100) NOT NULL,
  `settingSecondaryGroup` varchar(100) NOT NULL,
  `columnType` varchar(25) NOT NULL,
  `columnTypeData` varchar(250) NOT NULL,
  `columnValue` varchar(10000) NOT NULL,
  `columnDescription` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

