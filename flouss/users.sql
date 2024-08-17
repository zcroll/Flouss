create table flouss.users
(
    id                        bigint unsigned auto_increment
        primary key,
    name                      varchar(255)    not null,
    email                     varchar(255)    not null,
    email_verified_at         timestamp       null,
    password                  varchar(255)    not null,
    two_factor_secret         text            null,
    two_factor_recovery_codes text            null,
    two_factor_confirmed_at   timestamp       null,
    remember_token            varchar(100)    null,
    current_team_id           bigint unsigned null,
    profile_photo_path        varchar(2048)   null,
    created_at                timestamp       null,
    updated_at                timestamp       null,
    constraint users_email_unique
        unique (email)
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

