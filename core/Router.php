<?php
namespace app\core;
class Router
{
    protected array $routes = [];
    public Request $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function resolve()
    {
/*        echo '<pre>';
        var_dump($_SERVER);
        echo '</pre>';*/

        /*Получаем путь например '/user' */
        $path = $this->request->getPath();

        //Получаем метод post || get
        $method = $this->request->getMethod();

        // Получаем метод
        $callback = $this->routes[$method][$path] ?? false;

        if($callback === false){
            echo 'Page not found';
            exit();
        }
        echo call_user_func($callback); // Вызываем колбэк функцию

        /*        echo '<pre>';
        var_dump($this->routes);
        echo '</pre>';*/
    }
}