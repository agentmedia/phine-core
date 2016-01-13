
ALTER TABLE `pc_core_site` ADD `SitemapActive` BOOLEAN NOT NULL DEFAULT TRUE , ADD `SitemapCacheLifetime` INT(10) NOT NULL DEFAULT '86400' COMMENT 'sitemap cache lifetime; in seconds' ;
ALTER TABLE `pc_core_page` ADD `SitemapRelevance` FLOAT UNSIGNED NOT NULL DEFAULT '0.7' , ADD INDEX (`SitemapRelevance`);
ALTER TABLE `pc_core_page` ADD `SitemapChangeFrequency` VARCHAR(32) NOT NULL DEFAULT 'weekly', ADD INDEX (`SitemapChangeFrequency`);