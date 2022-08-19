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
}