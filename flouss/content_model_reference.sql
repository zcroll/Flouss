create table flouss.content_model_reference
(
    element_id   varchar(20)   not null
        primary key,
    element_name varchar(150)  not null,
    description  varchar(1500) not null,
    created_at   timestamp     null,
    updated_at   timestamp     null
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

