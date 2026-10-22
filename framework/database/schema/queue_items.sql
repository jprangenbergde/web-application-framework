CREATE TABLE IF NOT EXISTS queue_items
(
    id       integer
        constraint queue_items_pk
            primary key,
    priority integer,
    type     varchar,
    data     text
);
