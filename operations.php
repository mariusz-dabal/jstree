<?php
require_once "db.php";

if (isset($_GET['operation'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $parent = filter_input(INPUT_GET, 'parent', FILTER_SANITIZE_NUMBER_INT);
    $text = trim(filter_input(INPUT_GET, 'text', FILTER_SANITIZE_STRING));
    $result = 'ok';

    if ($_GET['operation'] == 'create_node') {
        try {
            $stmt = $pdo->prepare("SELECT id FROM items WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $row_count = $stmt->rowCount();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        if ($row_count > 0) {
            try {
                $stmt = $pdo->prepare("UPDATE items SET text = :text WHERE id = :id");
                $stmt->execute(['text' => $text, 'id' => $id]);
                $result = $id;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO items VALUES (NULL, :parent, :text)");
                $stmt->execute(['text' => $text, 'parent' => $parent]);
                $result = $pdo->lastInsertId();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
}

    if ($_GET['operation'] == 'delete_node') {
        try {
            $stmt = $pdo->query("SELECT * FROM items");
            $items = $stmt->fetchAll();
        } catch(Exception $e) {
            echo $e->getMessage();
        }

        function delete($array, $currentParent) {
            include "db.php";
            foreach ($array as $items => $item) { 
        
                if ($item['parent'] == $currentParent) {
                    delete($array, $item['id']);
                
                    } else {
                        try {
                            $stmt = $pdo->prepare("DELETE FROM items WHERE id = :id");
                            $stmt->execute(['id' => $currentParent]);
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
            }
             
            }  
        }

        delete($items, $id);
        
    }

    if ($_GET['operation'] == 'paste_node') {
        try {
            $stmt = $pdo->prepare("UPDATE items SET parent = :parent WHERE id = :id");
            $stmt->execute(['parent' => $parent, 'id' => $id]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result);
}