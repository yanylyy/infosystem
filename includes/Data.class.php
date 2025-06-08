<?php
class Data {
    public static function validateData(object $object): bool {
        $properties = get_object_vars($object);
        $hasErrors = false;
        
        foreach ($properties as $key => $value) {
            if (strpos($key, 'validate_') === 0 && !empty($value)) {
                $hasErrors = true;
                break;
            }
        }
        
        return !$hasErrors;
    }
    public static function loadData(array $data, object $object): void {
        foreach ($data as $key => $value) {
            if (property_exists($object, $key)) {
                $object->$key = $value;
            }
        }
    }

public static function replaceNewlinesToBr(string $text): string {
    return preg_replace('/\v+|\\r\\n/ui', '<br/>', $text);
}

public static function replaceBrToNewlines(string $text): string {
    return str_replace('<br/>', "\r\n", $text);
}

public static function formatDateTime(string $datetime): string 
{

    return (new DateTime($datetime))->format('d.m.Y H:i');
}
}
?>