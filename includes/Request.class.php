<?php
class Request {
    public bool $isPost;
    public bool $isGet;
    public function __construct() {
        $method = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->isPost = $method === 'POST';
        $this->isGet = $method === 'GET';
    }
    public function clean($value) {
        return htmlspecialchars(trim($value));
    }
    public function cleanArray($array) {
        $cleaned = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $cleaned[$key] = $this->cleanArray($value);
            } else {
                $cleaned[$key] = $this->clean($value);
            }
        }
        return $cleaned;
    }
    public function post($key = null) {
        if ($key === null) {
            return $this->cleanArray($_POST);
        }
        return isset($_POST[$key]) ? $this->clean($_POST[$key]) : null;
    }
    public function get($key = null) {
        if ($key === null) {
            return $this->cleanArray($_GET);
        }
        return isset($_GET[$key]) ? $this->clean($_GET[$key]) : null;
    }
    public function getHost() {
        return $_SERVER['HTTP_HOST'] ?? '';
    }
    public function getToken() {
        return $this->get('token');
    }
}