CREATE TABLE `{ticket}` (
  `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `subject`     VARCHAR(255)        NOT NULL DEFAULT '',
  `message`     TEXT,
  `uid`         INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `time_create` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `time_update` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `ip`          CHAR(15)            NOT NULL DEFAULT '',
  `mid`         INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `label`       INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`),
  KEY `time_create` (`time_create`),
  KEY `mid` (`mid`),
  KEY `label` (`label`),
  KEY `mid_status` (`mid`, `status`),
  KEY `mid_label` (`mid`, `label`),
  KEY `mid_uid` (`mid`, `uid`),
  KEY `mid_status_uid` (`mid`, `status`, `uid`),
  KEY `mid_status_label` (`mid`, `status`, `label`),
  KEY `time_create_id` (`time_create`, `id`)
);

CREATE TABLE `{user}` (
  `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket`      INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `reply`       INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `time_update` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ticket` (`ticket`),
  KEY `reply` (`reply`),
  KEY `time_update` (`time_update`)
);

CREATE TABLE `{label}` (
  `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255)        NOT NULL DEFAULT '',
  `ticket`      INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `color`       VARCHAR(8)          NOT NULL DEFAULT '',
  `time_update` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ticket` (`ticket`),
  KEY `status` (`status`),
  KEY `time_update` (`time_update`)
);

