<?php
header('Content-Type: application/json; charset=utf-8');

$db = new PDO('sqlite:books.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'list':
            $stmt = $db->query("SELECT * FROM books ORDER BY created_at DESC");
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $books]);
            break;

        case 'get':
            $id = $_GET['id'] ?? 0;
            $stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
            $stmt->execute([$id]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($book) {
                echo json_encode(['success' => true, 'data' => $book]);
            } else {
                echo json_encode(['success' => false, 'message' => '書籍が見つかりません']);
            }
            break;

        case 'create':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("
                INSERT INTO books (title, author, publisher, published_year, isbn, description)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['title'],
                $data['author'],
                $data['publisher'] ?? null,
                $data['published_year'] ?? null,
                $data['isbn'] ?? null,
                $data['description'] ?? null
            ]);
            echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
            break;

        case 'update':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("
                UPDATE books 
                SET title = ?, author = ?, publisher = ?, published_year = ?, isbn = ?, description = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $data['title'],
                $data['author'],
                $data['publisher'] ?? null,
                $data['published_year'] ?? null,
                $data['isbn'] ?? null,
                $data['description'] ?? null,
                $data['id']
            ]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = $_GET['id'] ?? 0;
            $stmt = $db->prepare("DELETE FROM books WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => '無効なアクション']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
