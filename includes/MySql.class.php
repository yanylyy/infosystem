<?php
class MySql extends mysqli
{
    public bool $isConnected;
    public function __construct(array $config)
    {
        parent::__construct(
            $config['host'],
            $config['user'],
            $config['password'],
            $config['database']
        );

        $this->isConnected = !$this->connect_error; 
    }
    public function queryA(string $sql){
        $result = $this->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    } 
    public function Unique(string $table, string $field, $value): bool {
        $sql = "SELECT * FROM $table WHERE $field = \"$value\"";

        return empty($this->queryA($sql)); 
    
    }
}

