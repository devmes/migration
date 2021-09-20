#
# Some Examples how to extend tables with useful fields for migration
#
#
# CREATE TABLE tx_powermail_domain_model_form (
# 	_migrated tinyint(4) unsigned DEFAULT '0' NOT NULL,
# );
# CREATE TABLE tx_news_domain_model_news (
# 	_migrated tinyint(4) unsigned DEFAULT '0' NOT NULL,
# 	_migrated_uid int(11) unsigned DEFAULT '0' NOT NULL,
# 	_migrated_table varchar(255) DEFAULT '' NOT NULL
# );

CREATE TABLE tx_migration_import_records (
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    orig_uid int(11) unsigned DEFAULT '0' NOT NULL,
    local_uid int(11) unsigned DEFAULT '0' NOT NULL,
    tablename varchar(255) DEFAULT '' NOT NULL
);
