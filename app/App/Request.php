<?php
namespace your\namespace\App;

class Request
{
    protected $action        = null;
    protected $parameter     = null;
    protected $jsonpCallback = null;
    protected $queryVars     = array();
    protected $sessionId     = null;

    public function __construct()
    {
        $this->processQuery();
    }

    protected function processQuery()
    {
        $request = $_REQUEST;
        $queryArr = explode('/', $request["query"]);

        if (is_array($queryArr)) {
            $this->action = ucfirst(array_shift($queryArr));
            
            if (!empty($queryArr)) {
                $this->parameter = current($queryArr);
            }
            unset($request["query"]);
        }

        if (isset($request['callback'])) {
            $this->jsonpCallback = $request['callback'];
            unset($request['callback']);
        }

        if (isset($request['session'])) {
            $this->sessionId = $request['session'];
            unset($request['session']);
        }

        if (empty($request)) {
            return;
        }

        foreach ($request as $key => $value) {
            $this->queryVars[$key] = $value;
        }

    }

    public function getAction()
    {
        if (isset($this->action)) {
            return $this->action;
        }

        return false;
    }

    public function getParameter()
    {
        if (!empty($this->parameter)) {
            return $this->parameter;
        }

        return false;
    }

    public function getCallback()
    {
        if (!empty($this->jsonpCallback)) {
            return $this->jsonpCallback;
        }

        return false;
    }

    public function getSessionId()
    {
        if (!empty($this->sessionId)) {
            return $this->sessionId;
        }

        return false;
    }

    public function __get($key)
    {
        if (in_array($key, array_keys($this->queryVars))) {
            return $this->queryVars[$key];
        }

        return false;
    }

    public function __set($key, $value)
    {
        $this->queryVars[$key] = $value;
    }
}
