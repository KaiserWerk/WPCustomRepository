<?php

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method(["GET"])
     */
    public function indexAction()
    {
        AuthHelper::init();
        TemplateHelper::renderHTML('/index');
    }
}
