CREATE TABLE IF NOT EXISTS outbox_messages
(
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    type         VARCHAR NOT NULL,
    payload      TEXT    NOT NULL,
    priority     INTEGER NOT NULL,
    created_at   VARCHAR NOT NULL,
    processed_at VARCHAR NULL,
    attempts     INTEGER NOT NULL DEFAULT 0,
    last_error   TEXT    NULL
);

CREATE INDEX IF NOT EXISTS outbox_messages_pending ON outbox_messages (processed_at, id);
