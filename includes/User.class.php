<?php
class User {
    private string $table = 'user';
    public $id;
    public $name;
    public $surname;
    public $patronymic;
    public $login;
    public $password;
    public $email;
    public $role;
    public $token;
    
    public array $errors = [];
    private Request $request;
    private MySql $mysql;

    public bool $isGuest = true;
    public bool $isAdmin = false;

    public $validate_login = '';
    public $validate_password = '';
    
    public function __construct(Request $request, MySql $mysql) {
        $this->request = $request;
        $this->mysql = $mysql;
        if ($this->request->getToken()) {
            $this->identity();
        }
    }
    
    public function load(array $data): void {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        $this->isAdmin = $this->isAdmin();
    }
    
    public function validateRegister(): bool {
        $this->errors = [];
        if (empty($this->name)) {
            $this->errors['name'] = 'Имя обязательно для заполнения';
        }
        if (empty($this->surname)) {
            $this->errors['surname'] = 'Фамилия обязательна для заполнения';
        }
        if (empty($this->login)) {
            $this->errors['login'] = 'Логин обязателен для заполнения';
        }
        if (empty($this->email)) {
            $this->errors['email'] = 'Email обязателен для заполнения';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Некорректный формат email';
        }
        if (empty($this->password)) {
            $this->errors['password'] = 'Пароль обязателен для заполнения';
        } elseif (strlen($this->password) < 6) {
            $this->errors['password'] = 'Пароль должен содержать минимум 6 символов';
        }
        if ($this->password !== $this->request->post('password_repeat')) {
            $this->errors['password_repeat'] = 'Пароли не совпадают';
        }
        if (!$this->request->post('rules')) {
            $this->errors['rules'] = 'Необходимо согласиться с правилами регистрации';
        }
        return empty($this->errors);
    }
    
    public function validateLogin(): bool {
        $this->validate_login = '';
        $this->validate_password = '';
        if (empty($this->login)) {
            $this->validate_login = 'Логин обязателен для заполнения';
        }
        if (empty($this->password)) {
            $this->validate_password = 'Пароль обязателен для заполнения';
        }
        return empty($this->validate_login) && empty($this->validate_password);
    }
    
    public function login(): bool {
        if (!$this->validateLogin()) {
            return false;
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE login = '{$this->login}' LIMIT 1";
        $result = $this->mysql->query($sql);
        
        if ($result->num_rows === 0) {
            $this->errors['login'] = 'Неверный логин или пароль';
            return false;
        }
        
        $userData = $result->fetch_assoc();
        
        if (!password_verify($this->password, $userData['password'])) {
            $this->errors['password'] = 'Неверный логин или пароль';
            return false;
        }
        
        $this->load($userData);
        $this->isGuest = false;
        $this->isAdmin = $this->isAdmin();
        $this->token = bin2hex(random_bytes(16));
        
        $sql = "UPDATE {$this->table} SET token = '{$this->token}' WHERE id = {$this->id}";
        return $this->mysql->query($sql);
    }
    
    public function identity(): bool {
        $token = $this->request->getToken();
        if (empty($token)) {
            return false;
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE token = '{$token}' LIMIT 1";
        $result = $this->mysql->query($sql);
        
        if ($result->num_rows === 0) {
            $this->logout(false);
            return false;
        }
        
        $userData = $result->fetch_assoc();
        $this->load($userData);
        $this->isGuest = false;
        $this->isAdmin = $this->isAdmin();
        return true;
    }
    
    public function isAdmin(): bool {
        return $this->role === 'admin';
    }
    
    public function logout(): bool {
        if ($this->isGuest) {
            return true;
        }
        
        $sql = "UPDATE {$this->table} SET token = '' WHERE id = {$this->id}";
        if ($this->mysql->query($sql)) {
            $this->isGuest = true;
            $this->isAdmin = false;
            $this->token = null;
            return true;
        }
        return false;
    }
    
    public function save(): bool {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->token = bin2hex(random_bytes(16));
        $this->role = 'author';
        
        $sql = "INSERT INTO user 
                (name, surname, patronymic, login, password, email, role, token) 
                VALUES (
                    '{$this->name}',
                    '{$this->surname}',
                    '{$this->patronymic}',
                    '{$this->login}',
                    '{$this->password}',
                    '{$this->email}',
                    '{$this->role}',
                    '{$this->token}'
                )";
        
        return $this->mysql->query($sql);
    }
}
?>