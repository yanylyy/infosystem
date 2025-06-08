<?php
class Comment extends Data {
    private MySql $mysql;
    public ?int $id = null;
    public int $post_id;
    public int $user_id;
    public ?int $parent_id = null;
    public string $content;
    public string $created_at;
    
    public string $validate_content = '';
    
    public function __construct(MySql $mysql) {
        $this->mysql = $mysql;
    }
    
    public function validate(): bool {
        $this->validate_content = '';
        
        if (empty($this->content)) {
            $this->validate_content = 'Комментарий не может быть пустым';
        }
        
        return empty($this->validate_content);
    }
    
    public function load(array $data): void {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
    
    public function save(): bool {
        if (!$this->validate()) {
            return false;
        }
        
        $content = self::replaceNewlinesToBr($this->content);
        
        if ($this->id !== null) {
            
            return false;
        } else {
            
            $query = "INSERT INTO comments 
                     (post_id, user_id, parent_id, content, created_at) 
                     VALUES 
                     ({$this->post_id}, 
                     {$this->user_id}, 
                     " . ($this->parent_id ? $this->parent_id : 'NULL') . ", 
                     '{$this->mysql->real_escape_string($content)}', 
                     NOW())";
        }
        
        $result = $this->mysql->query($query);
        
        if ($result && $this->id === null) {
            $this->id = $this->mysql->insert_id;
            $this->created_at = date('Y-m-d H:i');
        }
        
        return $result;
    }
    public function findOne(int $id): bool {
        $query = "SELECT * FROM comments WHERE id = $id LIMIT 1";
        $result = $this->mysql->query($query);
        
        if ($result && $result->num_rows > 0) {
            $this->load($result->fetch_assoc());
            return true;
        }
        
        return false;
    }
    
    public function comment_datetime(): string {
        return self::formatDateTime($this->created_at);
    }
    
    public function getCommentsByPost(int $post_id, bool $include_replies = false): array {
        $query = "SELECT c.*, u.login as author_login 
                 FROM comments c 
                 LEFT JOIN user u ON c.user_id = u.id 
                 WHERE c.post_id = $post_id";
        
        if (!$include_replies) {
            $query .= " AND c.parent_id IS NULL";
        }
        
        $query .= " ORDER BY c.created_at DESC";
        
        $result = $this->mysql->query($query);
        $comments = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $comment = new Comment($this->mysql);
                $comment->load($row);
                $comments[] = $comment;
            }
        }
        
        return $comments;
    }
    
    public function getReplies(int $comment_id): array {
        $query = "SELECT c.*, u.login as author_login 
                 FROM comments c 
                 LEFT JOIN user u ON c.user_id = u.id 
                 WHERE c.parent_id = $comment_id 
                 ORDER BY c.created_at ASC";
        
        $result = $this->mysql->query($query);
        $replies = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $comment = new Comment($this->mysql);
                $comment->load($row);
                $replies[] = $comment;
            }
        }
        
        return $replies;
    }
    
    public function delete(): bool {
        if (empty($this->id)) {
            return false;
        }
        
       
        $this->mysql->query("DELETE FROM comments WHERE parent_id = {$this->id}");
        

        $query = "DELETE FROM comments WHERE id = {$this->id}";
        return $this->mysql->query($query);
    }
}
?>