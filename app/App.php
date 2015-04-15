<?php
namespace your\namespace;

class App
{
    const APP_NAMESPACE = "your\\namespace\\";

    public $config      = null;

    protected $request     = null;
    protected $controller  = null;

    public function __construct()
    {
        $this->initSession();
        $this->config  = new App\Config();
        $this->request = new App\Request();

        $this->config["location.app"] = dirname(__FILE__);

    }

    public function init()
    {
        $this->prepareController();
    }

    protected function prepareController()
    {
        $controllerName = self::APP_NAMESPACE . "Controller\\" . $this->request->getAction() . "Controller";

        if (class_exists($controllerName)) {
            $this->controller = new $controllerName($this->request, $this->config);
        }

    }

    public function initSession()
    {
        session_start();
    }
}