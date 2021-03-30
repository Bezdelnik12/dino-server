<?php
try {
    $pdo = new PDO("sqlite:" . PATH_TO_SQLITE_FILE);
    if (!filesize(PATH_TO_SQLITE_FILE))
        throw new Exception('There are no tables in the database!');
} catch (Exception $e) {
    $pdo->exec(<<<_SQL
CREATE TABLE "users" (
	"id"	INTEGER,
	"login"	TEXT UNIQUE,
	"email"	TEXT UNIQUE,
	"password"	TEXT,
	PRIMARY KEY("id" AUTOINCREMENT)
);
_SQL
);
    $pdo->exec(<<<_SQL
CREATE TABLE "records" (
	"id"	INTEGER,
	"user_id"	INTEGER,
	"count"	INTEGER,
	PRIMARY KEY("id" AUTOINCREMENT)
);
_SQL
    );
}
