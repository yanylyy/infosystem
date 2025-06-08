<?php

class Menu {
    private array $items;
    private Response $response;
    public function __construct(array $items, Response $response) {
        $this->items = $items;
        $this->response = $response;
    }
    public function ren(): string {
        $current_page = basename($_SERVER['SCRIPT_FILENAME']);
        $html = '<ul>';
        
        foreach ($this->items as $item) {
            $active = ($current_page === $item['file']) ? ' class="active"' : '';
            $url = $this->response->getLink($item['file']);
            $html .= "<li{$active}><a href=\"{$url}\">{$item['title']}</a></li>";
        }
        
        $html .= '</ul>';
        return $html;
    }
}
?>