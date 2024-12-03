<?php

class Connection
{
    public $pdo = null;

    public function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=diary_app', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            die("ERROR: " . $exception->getMessage());
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    // Fetch all notes for a specific user
    public function getNotes($userId)
    {
        $statement = $this->pdo->prepare("SELECT * FROM notes WHERE user_id = :user_id ORDER BY create_date DESC");
        $statement->bindValue('user_id', $userId);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new note for a specific user
    public function addNote($userId, $note)
    {
        $statement = $this->pdo->prepare("
            INSERT INTO notes (title, user_id, description, create_date) 
            VALUES (:title, :user_id, :description, :date)
        ");
        $statement->bindValue('title', $note['title']);
        $statement->bindValue('user_id', $userId);
        $statement->bindValue('description', $note['description']);
        $statement->bindValue('date', date('Y-m-d H:i:s'));
        
        return $statement->execute();
    }

    // Update a note, ensuring it belongs to the specified user
    public function updateNote($id, $userId, $note)
    {
        $statement = $this->pdo->prepare("
            UPDATE notes 
            SET title = :title, description = :description 
            WHERE id = :id AND user_id = :user_id
        ");
        $statement->bindValue('id', $id);
        $statement->bindValue('user_id', $userId);
        $statement->bindValue('title', $note['title']);
        $statement->bindValue('description', $note['description']);
        return $statement->execute();
    }

    // Delete a note, ensuring it belongs to the specified user
    public function removeNote($id, $userId)
    {
        $statement = $this->pdo->prepare("
            DELETE FROM notes WHERE id = :id AND user_id = :user_id
        ");
        $statement->bindValue('id', $id);
        $statement->bindValue('user_id', $userId);
        return $statement->execute();
    }

    // Fetch a single note by ID, ensuring it belongs to the specified user
    public function getNoteById($id, $userId)
    {
        $statement = $this->pdo->prepare("
            SELECT * FROM notes WHERE id = :id AND user_id = :user_id
        ");
        $statement->bindValue('id', $id);
        $statement->bindValue('user_id', $userId);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}

return new Connection();
