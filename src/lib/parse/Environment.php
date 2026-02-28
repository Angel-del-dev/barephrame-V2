<?php 

namespace src\lib\parse;

use Exception;
use stdClass;

class Environment {
    private static ?ParsedEnvironment $env = null;

    public static function get():ParsedEnvironment {
        if(is_null(self::$env)) {
            self::$env = new ParsedEnvironment();
        }
        return self::$env;
    }
}


class ParsedEnvironment {
    private string $root;
    private stdClass $variables;
    public function __construct() {
        $this->root = __DIR__.'/../../..';
        $this->variables = new stdClass();
        $this->parse();
    }

    private function parse():void {
        $file_route = $this->root.'/.env'; 
        if(!is_file($file_route)) {
            throw new Exception("Could not find '.env' in project root.\nRun src/Cli/exec.php --scaffold to generate basic structure");
        }

        $definitions = $this->get_definitions();

        $raw_env = explode(PHP_EOL, file_get_contents($file_route));
        
        foreach($raw_env as $variable) {
            $variable = trim($variable);
            if(
                strlen($variable) === 0 ||
                $variable[0] === '#'
            ) continue;
            [$name, $value] = explode('=', $variable);
            $name = trim($name);
            $value = trim($value);
            if($name === '') continue;
            if(isset($definitions->$name)) {
                $value = $this->parse_value($value, $definitions->$name);
            }
            $this->variables->$name = $value;
        }
    }

    private function parse_value(string $value, array $definition):string|int|bool {
        $clone_value = $value;
        switch(strtolower($definition['type'])) {
            case 'integer':
                $clone_value = intval($clone_value);
            break;
            case 'boolean':
                $clone_value = strtolower($value) === 'true' || $value === '1';
            break;
            default:
            // Nothing for strings
            break;
        }
        return $clone_value;
    }

    private function get_definitions():stdClass {
        return (object)[
            "DATABASE_HOST" => ['type' => 'string'],
            "DATABASE_USER" => ['type' => 'string'],
            "DATABASE_PASSWORD" => ['type' => 'string'],
            "DATABASE_PORT" => ['type' => 'integer'],
            "DATABASE_NAME" => ['type' => 'string'],
            "EMAIL_HOST" => ['type' => 'string'],
            "EMAIL_USER" => ['type' => 'string'],
            "EMAIL_PASSWORD" => ['type' => 'string'],
            "EMAIL_PORT" => ['type' => 'integer'],
            "PRODUCTION" =>  ['type' => 'boolean'],
        ];
    }

    public function __get(string $name):string|int|bool {
        if(!isset($this->variables->$name)) {
            throw new Exception("Environment variable '{$name}' not found");
        }
        return $this->variables->$name;
    }
}