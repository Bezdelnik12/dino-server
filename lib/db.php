<?php
try {
    $pdo = new PDO("sqlite:" . PATH_TO_SQLITE_FILE);
    if (!filesize(PATH_TO_SQLITE_FILE))
        throw new Exception('There are no tables in the database!');
} catch (Exception $e) {

}
