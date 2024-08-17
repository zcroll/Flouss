create table flouss.tools_used
(
    onetsoc_code   char(10)     not null,
    example        varchar(150) not null,
    commodity_code decimal(8)   not null,
    created_at     timestamp    null,
    updated_at     timestamp    null,
    constraint tools_used_commodity_code_foreign
        foreign key (commodity_code) references flouss.unspsc_reference (commodity_code),
    constraint tools_used_onetsoc_code_foreign
        foreign key (onetsoc_code) references flouss.occupation_data (onetsoc_code)
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

