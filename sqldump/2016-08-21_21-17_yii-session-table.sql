
--
-- Table structure for table `yii_session`
--

CREATE TABLE `yii_session` (
  `id` char(64) COLLATE utf8_unicode_ci NOT NULL,
  `expire` int(10) UNSIGNED DEFAULT NULL,
  `data` longblob
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for table `yii_session`
--
ALTER TABLE `yii_session`
  ADD PRIMARY KEY (`id`);
