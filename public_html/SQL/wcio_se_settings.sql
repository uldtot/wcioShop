--
-- Struktur-dump for tabellen `wcio_se_settings`
--

CREATE TABLE `wcio_se_settings` (
  `id` int(20) NOT NULL,
  `columnName` varchar(50) NOT NULL,
  `columnType` varchar(25) NOT NULL,
  `columnValue` varchar(10000) NOT NULL,
  `columnDescription` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `wcio_se_settings`
--

INSERT INTO `wcio_se_settings` (`id`, `columnName`, `columnType`, `columnValue`, `columnDescription`) VALUES
(1, 'storeName', '', 'XXXXXXXXXXXX', 'Name of your shop, this will be used as default name in header'),
(4, 'storeUrl', '', 'XXXXXXXXXXXX', 'Store url, remember https://'),
(8, 'storeSeoShortName', '', 'XXXXXXXXXXXX', 'Name for seo plugin {$storeSeoShortName}'),
(9, 'storeAddress', '', 'XXXXXXXXXXXX', 'Used on invoices'),
(10, 'storeSaleEmail', '', 'XXXXXXXXXXXX', 'E-mail adress used for outgoing e-mails'),
(13, 'storeTermsPage', '', 'XXXXXXXXXXXX', 'Site terms and conditions, is set with a select on pages.'),
(16, 'storeLastInvoice', '', '0', 'Last used invoice number. Do not edit unless you know what you are doing'),
(43, 'storePermalinkStructure', '', '/%postname%/', 'The default permalink structure if nothing is selectec´d'),
(44, 'storeSlogan', '', 'XXXXXXXXXXXX', 'SLogan for your site, this will be used as default name in header'),
(45, 'storeSaleNotificationEmail', '', 'XXXXXXXXXXXX', 'E-mail adress used to get notification about new sales, normally the same as storeSaleEmail'),
(46, 'storeAdminNotificationEmail', '', 'XXXXXXXXXXXX', 'E-mail adress used to get notification if something is wrong or non sales related information.');

--
-- Begrænsninger for dumpede tabeller
--

--
-- Indeks for tabel `wcio_se_settings`
--
ALTER TABLE `wcio_se_settings`
  ADD PRIMARY KEY (`id`);
