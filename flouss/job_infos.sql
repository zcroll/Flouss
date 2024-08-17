create table flouss.job_infos
(
    id          varchar(20)   not null
        primary key,
    title       varchar(150)  not null,
    description varchar(1000) not null,
    created_at  timestamp     null,
    updated_at  timestamp     null
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

