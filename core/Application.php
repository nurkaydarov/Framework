<?php
namespace app\core;
class Application
{
    public static Application $app; // App instance for response
    public  Controller $controller;
    public Database $database;

    public static string $ROOT_PATH;
    public Router $router;
    public Request $request;
    public Response $response;
    public function __construct($rootPath, array $config)
    {

        self::$ROOT_PATH = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);

        $this->database = new Database($config['db']);

    }

    public function run()
    {
        echo $this->router->resolve();
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

}