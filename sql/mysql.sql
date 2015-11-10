CREATE TABLE `{ticket}` (
  `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `subject`     VARCHAR(255)        NOT NULL DEFAULT '',
  `message`     TEXT,
  `uid`         INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `time_create` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `ip`          CHAR(15)            NOT NULL DEFAULT '',
  `main_id`     INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`),
  KEY `time_create` (`time_create`),
  KEY `main_id` (`main_id`),
  KEY `main_id_status` (`main_id`, `status`),
  KEY `main_id_uid` (`main_id`, `uid`),
  KEY `main_id_status_uid` (`main_id`, `status`, `uid`),
  KEY `time_create_id` (`time_create`, `id`)
);