<?php

namespace app\core;

class Request
{
    public function getPath()
    {
       $path = $_SERVER['REQUEST_URI'] ?? '/';
       $position = strpos($path, '?'); // Находим знак ?

        if($position === false){ // Если знак ? не найден, то возвращаем путь "/"
            return $path;
        }
        $path = substr($path,0, $position); // Иначе обрезаем знак ?
        return $path;
    }
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getBody()
    {
        $body = [];
        if($this->getMethod() === 'get')
        {
            foreach ($_GET as $key => $value)
            {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if($this->getMethod() === 'post')
        {
            foreach ($_POST as $key => $value)
            {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;

    }
}