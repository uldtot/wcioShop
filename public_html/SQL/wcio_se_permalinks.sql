
--
-- Struktur-dump for tabellen `wcio_se_permalinks`
--

CREATE TABLE `wcio_se_permalinks` (
  `id` int(10) NOT NULL,
  `postId` int(10) NOT NULL,
  `url` varchar(100) NOT NULL,
  `templateFile` varchar(100) NOT NULL,
  `SEOtitle` varchar(200) NOT NULL,
  `SEOkeywords` varchar(250) NOT NULL,
  `SEOdescription` varchar(1000) NOT NULL,
  `SEOnoIndex` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `wcio_se_permalinks`
--

INSERT INTO `wcio_se_permalinks` (`id`, `postId`, `url`, `templateFile`, `SEOtitle`, `SEOkeywords`, `SEOdescription`, `SEOnoIndex`) VALUES
(1, 0, '', 'index.tpl', 'XXXXXXXXXXXXX', 'XXXXXXXXXXXXX', 'XXXXXXXXXXXXX', 0);

--
-- Begrænsninger for dumpede tabeller
--

--
-- Indeks for tabel `wcio_se_permalinks`
--
ALTER TABLE `wcio_se_permalinks`
  ADD PRIMARY KEY (`id`);
