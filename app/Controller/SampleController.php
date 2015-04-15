<?php
namespace your\namepsace\Controller;

class SampleController extends AppController
{
    protected $filters = array("someFilter");
    protected $models = array("sampleModel");

    // Process get request
    protected function get($imageName)
    {

    }

}
