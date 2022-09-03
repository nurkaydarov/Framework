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
/*        echo '<pre>';
        var_dump($this->routes);
        echo '</pre>';*/
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;

    }

    public function resolve()
    {
/*        echo '<pre>';
        var_dump($_SERVER);
        echo '</pre>';*/

        /*Получаем путь например '/user' */
        $path = $this->request->getPath();

        //Получаем метод post || get
        $method = $this->request->method();

        // Получаем метод
        $callback = $this->routes[$method][$path] ?? false;

        if($callback === false){

            //Application::$app->response->setStatucCode(404);
            $this->response->setStatucCode(404);
            return $this->renderView('_404');
        }
        if(is_string($callback)){

           return $this->renderView($callback);
        }

        if(is_array($callback)){
            //$app->router->post('/contact', [SiteController::class [0], 'contact'[1]]);

            Application::$app->controller = new $callback[0](); // app\controllers\SiteController()
            $callback[0] = Application::$app->controller;
            //var_dump(Application::$app->controller);

        }
/*        echo "<pre>";
        var_dump($callback);
        echo "</pre>";*/
        return call_user_func($callback, $this->request); // Вызываем колбэк функцию

        /*        echo '<pre>';
        var_dump($this->routes);
        echo '</pre>';*/
    }

    public function renderView(string $view, $params = [])
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent()
    {
        $layout = Application::$app->controller->layout;
        ob_start();
        include_once Application::$ROOT_PATH . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params)
    {
        ob_start();
        foreach ($params as $key => $value)
        {
            // У переменной $key станет имя, которая равняется значение $value
            $$key = $value;
        }
        include_once  Application::$ROOT_PATH . "/views/$view.php";
        return ob_get_clean();
    }

    protected function renderContent(string $viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
}