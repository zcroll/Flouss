create table flouss.scales_reference
(
    scale_id   varchar(3)  not null
        primary key,
    scale_name varchar(50) not null,
    minimum    decimal(1)  not null,
    maximum    decimal(3)  not null,
    created_at timestamp   null,
    updated_at timestamp   null
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

