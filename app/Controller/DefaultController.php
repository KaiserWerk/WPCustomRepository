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
    
    /**
     * @Route("/test", name="test_route")
     * @Method(["GET"])
     */
    public function testAction()
    {
        echo 'test';
    }
    
    /**
     * @Route("/test/[:id]", name="test_route")
     * @Method(["GET"])
     */
    public function testIDAction()
    {
        echo 'test ID';
    }
    
    /**
     * @Route("/admin/[:test]/list", name="admin_test_list")
     * @Method(["GET"])
     */
    public function adminTestListAction()
    {
        echo 'hey';
    }
}
