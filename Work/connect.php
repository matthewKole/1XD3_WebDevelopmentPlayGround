<?php
try {
    $dbh = new PDO(
        "mysql:host=localhost;dbname=kolesnm_db;charset=utf8mb4",
        "kolesnm_local",
        "2oHN7n5-3S93jd_1"
    );

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Error connecting to the database: " . $e->getMessage());
}
