create table flouss.occupation_data
(
    onetsoc_code char(10)      not null
        primary key,
    title        varchar(150)  not null,
    description  varchar(1000) not null,
    created_at   timestamp     null,
    updated_at   timestamp     null
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

