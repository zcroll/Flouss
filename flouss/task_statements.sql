create table flouss.task_statements
(
    onetsoc_code          char(10)      not null,
    task_id               decimal(8)    not null
        primary key,
    task                  varchar(1000) not null,
    task_type             varchar(12)   null,
    incumbents_responding decimal(4)    null,
    date_updated          date          not null,
    domain_source         varchar(30)   not null,
    created_at            timestamp     null,
    updated_at            timestamp     null,
    constraint task_statements_onetsoc_code_foreign
        foreign key (onetsoc_code) references flouss.occupation_data (onetsoc_code)
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

