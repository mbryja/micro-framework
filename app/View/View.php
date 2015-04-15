<?php
namespace CarouselChecks\PhotoWidget\View;

class View
{
    protected $config = null;
    protected $request = null;
    protected $sessionId = null;

    public function __construct($tmpl, $config, $request, $sessionId)
    {
        $this->config = $config;
        $this->request = $request;
        $this->sessionId = $sessionId;

        include(dirname(__FILE__) . "/" . $tmpl . "View.php");
    }
}
