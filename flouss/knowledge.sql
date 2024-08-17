create table flouss.knowledge
(
    id                 bigint unsigned auto_increment
        primary key,
    onetsoc_code       char(10)      not null,
    element_id         varchar(20)   not null,
    scale_id           varchar(3)    not null,
    data_value         decimal(5, 2) not null,
    n                  decimal(4)    null,
    standard_error     decimal(7, 4) null,
    lower_ci_bound     decimal(7, 4) null,
    upper_ci_bound     decimal(7, 4) null,
    recommend_suppress char          null,
    not_relevant       char          null,
    date_updated       date          not null,
    domain_source      varchar(30)   not null,
    created_at         timestamp     null,
    updated_at         timestamp     null,
    constraint knowledge_element_id_foreign
        foreign key (element_id) references flouss.content_model_reference (element_id),
    constraint knowledge_onetsoc_code_foreign
        foreign key (onetsoc_code) references flouss.occupation_data (onetsoc_code),
    constraint knowledge_scale_id_foreign
        foreign key (scale_id) references flouss.scales_reference (scale_id)
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

