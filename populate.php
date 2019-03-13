<?php

require_once "db.php";

$sql = "TRUNCATE TABLE items";
$sql2 =  "INSERT INTO items 
            VALUES(1,'#', 'ROOT'),
                    (2,1,'CATEGORY'),
                    (3,2,'ITEM'),
                    (4,2,'ITEM'),
                    (5,2,'ITEM'),
                    (6,1,'CATEGORY'),
                    (7,6,'ITEM'),
                    (8,7,'ITEM'),
                    (9,6,'ITEM'),
                    (10,6,'ITEM')";
        try {
            $stmt = $pdo->query($sql);
            $stmt = $pdo->query($sql2);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        header("Location: index.php");