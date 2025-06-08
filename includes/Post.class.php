<?php
class Post extends Data {
    private MySql $mysql;
    public ?int $id = null; 
    public int $user_id;
    public string $title;
    public string $preview;
    public string $content;
    public string $created_at;
    public int $comments_count = 0;
    public string $author_login = '';
    
    public string $validate_title = '';
    public string $validate_preview = '';
    public string $validate_content = '';

    public function __construct(MySql $mysql) {
        $this->mysql = $mysql;
    }

    public function validate(): bool {
        $this->validate_title = '';
        $this->validate_preview = '';
        $this->validate_content = '';
        
        if (empty($this->title)) {
            $this->validate_title = 'Заголовок обязателен';
        }
        
        if (empty($this->preview)) {
            $this->validate_preview = 'Превью обязательно';
        }
        
        if (empty($this->content)) {
            $this->validate_content = 'Содержание обязательно';
        }
        
        return empty($this->validate_title) && empty($this->validate_preview) && empty($this->validate_content);
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
            $query = "UPDATE posts SET 
                     title = '{$this->mysql->real_escape_string($this->title)}', 
                     preview = '{$this->mysql->real_escape_string($this->preview)}', 
                     content = '{$this->mysql->real_escape_string($content)}',
                     created_at = NOW()
                     WHERE id = {$this->id}";
        } else {
            $query = "INSERT INTO posts 
                     (user_id, title, preview, content, created_at) 
                     VALUES 
                     ({$this->user_id}, 
                     '{$this->mysql->real_escape_string($this->title)}', 
                     '{$this->mysql->real_escape_string($this->preview)}', 
                     '{$this->mysql->real_escape_string($content)}', 
                     NOW())";
        }
        
        $result = $this->mysql->query($query);
        
        if ($this->id === null && $result) {
            $this->id = $this->mysql->insert_id;
            $this->created_at = date('Y-m-d H:i');
        }
        
        return $result;
    }

    public function findOne(int $id): bool {
        $query = "SELECT p.*, u.login as author_login, COUNT(c.id) as comments_count 
                 FROM posts p 
                 LEFT JOIN user u ON p.user_id = u.id
                 LEFT JOIN comments c ON p.id = c.post_id 
                 WHERE p.id = $id 
                 GROUP BY p.id";
        
        $result = $this->mysql->query($query);
        
        if ($result && $result->num_rows > 0) {
            $this->load($result->fetch_assoc());
            return true;
        }
        
        return false;
    }

    public function post_datetime(): string {
        return self::formatDateTime($this->created_at);
    }

    public function posts_list(int $limit = null, int $offset = 0): array {
        $query = "SELECT p.*, u.login as author_login, COUNT(c.id) as comments_count 
                 FROM posts p 
                 LEFT JOIN user u ON p.user_id = u.id
                 LEFT JOIN comments c ON p.id = c.post_id 
                 GROUP BY p.id 
                 ORDER BY p.created_at DESC";
        
        if ($limit !== null) {
            $query .= " LIMIT $offset, $limit";
        }
        
        $result = $this->mysql->query($query);
        $posts = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $post = new Post($this->mysql);
                $post->load($row);
                $posts[] = $post;
            }
        }
        
        return $posts;
    }

    public function post_feed(): array {
        return $this->posts_list(10);
    }

    public function delete(): bool {
        if (empty($this->id)) {
            return false;
        }
        
        $query = "SELECT COUNT(*) as count FROM comments WHERE post_id = {$this->id}";
        $result = $this->mysql->query($query);
        $count = $result->fetch_assoc()['count'];
        
        if ($count > 0) {
            return false;
        }
        
        $query = "DELETE FROM posts WHERE id = {$this->id}";
        return $this->mysql->query($query);
    }
}
?>