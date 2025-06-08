<?php
class Response {
    private User $user;
    private Request $request;
    
    public function __construct(User $user, Request $request) {
        $this->user = $user;
        $this->request = $request;
        if ($request->getToken() && $user->isGuest) {
            $this->redirect('index.php');
        }
    }
    
    public function getLink(string $url, array $params = []): string {
        if (!$this->user->isGuest && !isset($params['token'])) {
            $params['token'] = $this->user->token;
        }
        
        if (!empty($params)) {
            $queryString = http_build_query($params);
            $url .= (strpos($url, '?') === false ? '?' : '&');
            $url .= $queryString;
        }
        
        return $url;
    }
    
    public function redirect(string $url, array $params = []): void {
        $fullUrl = $this->getLink($url, $params);
        header("Location: $fullUrl");
        exit;
    }
}
?>