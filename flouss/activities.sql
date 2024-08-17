create table flouss.activities
(
    id            bigint unsigned auto_increment
        primary key,
    activity      longtext      not null,
    category      text          not null,
    scale         decimal(8, 2) not null,
    interest_type text          not null,
    created_at    timestamp     null,
    updated_at    timestamp     null
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

