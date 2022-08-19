<?php
namespace app\core;
class Router
{
    protected array $routes = [];
    public Request $request;
    public Response $response;
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
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

            //Application::$app->response->setStatucCode(404);
            $this->response->setStatucCode(404);
            return 'Page not found';
        }
        if(is_string($callback)){

           return $this->renderView($callback);
        }
        return call_user_func($callback); // Вызываем колбэк функцию

        /*        echo '<pre>';
        var_dump($this->routes);
        echo '</pre>';*/
    }

    public function renderView(string $view)
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view);

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent()
    {
        ob_start();
        include_once Application::$ROOT_PATH . "/views/layouts/main.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view)
    {
        ob_start();
        include_once  Application::$ROOT_PATH . "/views/$view.php";
        return ob_get_clean();
    }
}