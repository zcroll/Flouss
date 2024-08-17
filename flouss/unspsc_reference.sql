create table flouss.unspsc_reference
(
    commodity_code  decimal(8)   not null
        primary key,
    commodity_title varchar(150) not null,
    class_code      decimal(8)   not null,
    class_title     varchar(150) not null,
    family_code     decimal(8)   not null,
    family_title    varchar(150) not null,
    segment_code    decimal(8)   not null,
    segment_title   varchar(150) not null,
    created_at      timestamp    null,
    updated_at      timestamp    null
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

