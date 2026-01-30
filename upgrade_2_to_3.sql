ALTER TABLE qsos
    CHANGE county location VARCHAR(255),
    ADD COLUMN comment TEXT,
    DROP COLUMN logid;

DROP TABLE loadlog;
DROP TABLE trans;
