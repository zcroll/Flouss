create table flouss.technology_skills
(
    onetsoc_code   char(10)     not null,
    example        varchar(150) not null,
    commodity_code decimal(8)   not null,
    hot_technology char         not null,
    in_demand      char         not null,
    created_at     timestamp    null,
    updated_at     timestamp    null,
    constraint technology_skills_commodity_code_foreign
        foreign key (commodity_code) references flouss.unspsc_reference (commodity_code),
    constraint technology_skills_onetsoc_code_foreign
        foreign key (onetsoc_code) references flouss.occupation_data (onetsoc_code)
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

