create table  IF NOT EXISTS b_sotbit_mailing_event (
    ID int not null auto_increment,
    ACTIVE char(1) not null default 'N',
    NAME text null,
    DESCRIPTION text null,
    SORT INT(18) not null default '100',
    MODE text null,
    SITE_URL text null,
    USER_AUTH char(1) not null default 'N',
    TEMPLATE text null,
    TEMPLATE_PARAMS longtext null,
    EVENT_PARAMS text null,
    COUNT_RUN int(18) not null default '0',
    DATE_LAST_RUN datetime null,
    MAILING_WORK char(1) not null default 'N',
    MAILING_WORK_COUNT int(18) not null default '0',
    MAILING_WORK_PARAM longtext null,
    EVENT_TYPE text null,
    AGENT_ID int null,
    AGENT_TIME_START int null,
    AGENT_TIME_END int null,
    EVENT_SEND_SYSTEM text null,
    EVENT_SEND_SYSTEM_CODE text null,
    EXCLUDE_UNSUBSCRIBED_USER text null,
    EXCLUDE_UNSUBSCRIBED_USER_MORE text null,
    EXCLUDE_HOUR_AGO int null,
    CATEGORY_ID int(18) not null default '0',
    primary key (ID)
);

CREATE TABLE  IF NOT EXISTS b_sotbit_mailing_section
(
    ID int(11) NOT NULL auto_increment,
    TIMESTAMP_X timestamp not null,
    DATE_CREATE DATETIME NULL,
    ACTIVE CHAR(1) DEFAULT 'Y' NOT NULL,
    SORT int(11) DEFAULT 500 NOT NULL,
    NAME VARCHAR(255) NULL,
    DESCRIPTION TEXT NULL,
    CATEGORY_ID INT(11) DEFAULT 0 NOT NULL,
    PRIMARY KEY (ID)
);


create table  IF NOT EXISTS b_sotbit_mailing_message (
    ID int not null auto_increment,
    ID_EVENT int(18) not null default '0',
    DATE_CREATE datetime null,
    COUNT_RUN int(18) not null default '0',
    SEND char(1) not null default 'N',
    DATE_SEND datetime null,
    SEND_SYSTEM text null,
    SEND_ERROR text null,
    SEND_SYSTEM_MESSEGE_CODE text null,
    EMAIL_FROM text null,
    EMAIL_TO text null,
    BCC text null,
    PARAM_MESSEGE text null,
    PARAM_1 text null,
    PARAM_2 text null,
    PARAM_3 text null,
    STATIC_USER_OPEN char(1) not null default 'N',
    STATIC_USER_OPEN_DATE text null,
    STATIC_USER_BACK char(1) not null default 'N',
    STATIC_USER_BACK_DATE text null,
    STATIC_USER_ID text null,
    STATIC_SALE_UID text null,
    STATIC_GUEST_ID text null,
    STATIC_PAGE_START text null,
    STATIC_ORDER_ID text null,
    primary key (ID)
);

create table  IF NOT EXISTS b_sotbit_mailing_message_text (
    ID int not null auto_increment,
    ID_MESSEGE int(18),
    SUBJECT text null,
    MESSEGE_PARAMETR longtext null,
    MESSEGE longtext null,
    primary key (ID)
);

create table  IF NOT EXISTS b_sotbit_mailing_message_template (
    ID int not null auto_increment,
    ID_EVENT int(18),
    COUNT_START int(18),
    COUNT_END int(18),
    TEMPLATE longtext null,
    ARCHIVE char(1) not null default 'N',
    primary key (ID)
);

create table  IF NOT EXISTS b_sotbit_mailing_unsubscribed (
    ID int not null auto_increment,
    ACTIVE char(1) not null default 'Y',
    DATE_CREATE datetime null,
    ID_MESSEGE int(18) not null default '0',
    ID_EVENT int(18) not null default '0',
    EMAIL_TO text null,
    primary key (ID)
);

create table  IF NOT EXISTS b_sotbit_mailing_undelivered (
    ID int not null auto_increment,
    ACTIVE char(1) not null default 'Y',
    DATE_CREATE datetime null,
    ID_EVENT int(18) not null default '0',
    EMAIL_TO text null,
    SPAM char(1) not null default 'N',
    ERROR_CODE text null,
    primary key (ID)
);

create table  IF NOT EXISTS b_sotbit_mailing_categories (
    ID int not null auto_increment,
    ACTIVE char(1) not null default 'Y',
    DATE_CREATE datetime null,
    NAME text null,
    DESCRIPTION text null,
    SUNC_USER char(1) not null default 'N',
    SUNC_USER_MESSAGE char(1) not null default 'N',
    SUNC_USER_GROUP text null,
    SUNC_USER_EVENT text null,
    SUNC_SUBSCRIPTION char(1) not null default 'N',
    SUNC_SUBSCRIPTION_LIST text null,
    SUNC_MAILCHIMP char(1) not null default 'N',
    SUNC_MAILCHIMP_BACK char(1) not null default 'N',
    SUNC_MAILCHIMP_LIST text null,
    SUNC_UNISENDER char(1) not null default 'N',
    SUNC_UNISENDER_BACK char(1) not null default 'N',
    SUNC_UNISENDER_LIST text null,
    USER_CONFIRM varchar(50) null,
    USER_EVENT_CONFIRM varchar(50) null,
    PARAM_1 text null,
    PARAM_2 text null,
    PARAM_3 text null,
    PARAM_INFO text null,
    primary key (ID)
);

create table  IF NOT EXISTS b_sotbit_mailing_subscribers (
    ID int not null auto_increment,
    ACTIVE char(1) not null default 'Y',
    DATE_CREATE datetime null,
    DATE_UPDATE datetime null,
    NAME text null,
    EMAIL_TO text null,
    USER_ID int(18),
    STATIC_PAGE_SIGNED text null,
    STATIC_PAGE_CAME text null,
    SOURCE text null,
      DOUBLE_OPT_IN varchar(50) null,
      DATE_DOUBLE_OPT_IN date null,
      IP_DOUBLE_OPT_IN text null,
      DEVICE_DOUBLE_OPT_IN text null,
      CITY_DOUBLE_OPT_IN text null,
      DATA_DOUBLE_OPT_IN text null,
    primary key (ID)
);


create table IF NOT EXISTS b_sotbit_mailing_subscr_categ
(
    ID int not null auto_increment,    
    SUBSCRIBERS_ID int(18), 
    CATEGORIES_ID int(18), 
    primary key (ID)
);

