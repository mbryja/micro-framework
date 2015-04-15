<?php
namespace your\namespace\Controller;

use your\namespace;

abstract class AppController
{
    protected $request   = null;
    protected $config    = null;
    protected $template  = false;
    protected $sessionId = null;

    public function __construct(PhotoWidget\App\Request $request, PhotoWidget\App\Config $config)
    {
        $this->request = $request;
        $this->config = $config;
        $parameter = $this->request->getParameter();

        $this->ApiKey = new PhotoWidget\Model\ApiKeyModel();
        $this->Ip = new PhotoWidget\Model\IpModel();

        if (isset($this->models)) {
            $this->loadModels();
        }

        if (!empty($this->filters)) {
            foreach ($this->filters as $filter) {
                $filter .= "Filter";
                $this->$filter();
            }
        }

        $this->validateSession();
        
        $this->sessionId = session_id();
        
        switch($_SERVER['REQUEST_METHOD']) {
            case "GET":
                $this->get($parameter);
                break;
            case "POST":
                $this->post();
                break;
            case "PUT":
                $this->put();
                break;
            case "DELETE":
                $this->get($parameter);
                break;
        }

        if ($this->template) {
            $view = new PhotoWidget\View\View($this->request->getAction(), $this->config, $this->request, $this->sessionId);
        }
    }

    protected function get($id)
    {
        throw new \Exception("Get Operation Not Permitted For Controller " . $this->request->getAction());
    }

    protected function post()
    {
        throw new \Exception("Post Operation Not Permitted For Controller " . $this->request->getAction());
    }

    protected function put()
    {
        throw new \Exception("Put Operation Not Permitted For Controller " . $this->request->getAction());
    }

    protected function delete($id)
    {
        throw new \Exception("Delete Operation Not Permitted For Controller " . $this->request->getAction());
    }

    protected function jsonp($data)
    {
        header("Content-Type: application/json");
        $callback = $this->request->getCallback();

        if (!empty($callback)) {
            return $callback . "(" . json_encode($data) . ")";
        }

        return json_encode($data);
    }

    private function loadModels()
    {
        foreach ($this->models as $modelName) {
            $model = "your\\model\\namespace" . $modelName . "Model";
            $this->$modelName = new $model();
        }
    }

    private function validateSession()
    {
        if ($this->request->getSessionId()) {
            session_id($this->request->getSessionId());
        }
        
        $this->sessionId = session_id();
    }

    private function someFilter()
    {
        //validation filter example

    }
}
