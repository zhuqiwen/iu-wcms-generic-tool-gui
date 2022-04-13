<?php

namespace Edu\IU\Framework\GenericUpdaterGUI;


use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class Controller{
    use CommonTrait;
    public $viewLoader;
    public $view;
    protected $model;
    protected $appName;
    protected $appOrg;
    protected $welcomeMessage;

    public function __construct()
    {
        // viewLoader can be used by childs to set different path and namespace of tempaltes
        // viewLoader->addPath()
        $this->viewLoader = New FilesystemLoader();
        $this->viewLoader->setPaths(__DIR__ . '/view', 'basic');
    }

    public function start()
    {
        $nextStep = filter_input(INPUT_POST, "next_step") ?? "index";
        $updateToApply = filter_input(INPUT_POST, "update_to_apply") ?? "";
        $nextStep = $nextStep . str_replace(" ", "", ucwords(str_replace("-", " ", $updateToApply)));

        $this->view = new Html($this->viewLoader, ['debug' => true]);
        $this->view->addExtension(new DebugExtension());

        if ($nextStep != "index")
        {
            $this->init();
            $this->view->init($this->siteName, $this->apiKey);
        }

        try {
            $this->$nextStep();
        }catch (\Error $e){
            $errorMessage = $e->getMessage();
            $appOrg = $this->appOrg;
            $appName = $this->appName;
            echo $this->view->render('@basic/error.twig', compact('errorMessage', 'appOrg', 'appName'));
        }
    }

    protected function init()
    {
        $apiKey = filter_input(INPUT_POST, "api_key");
        $siteName = filter_input(INPUT_POST, "site_name");

        $_SESSION["api_key"] = $apiKey;
        $_SESSION["site_name"] = $siteName;
        $_SESSION["update_to_apply"] = filter_input(INPUT_POST, "update_to_apply");

        $this->siteName = $siteName;
        $this->apiKey = $apiKey;
    }

    protected function setAppInfo(string $appOrg, string $appName, string $welcomeMessage)
    {
        $this->appOrg = $appOrg;
        $this->appName = $appName;
        $this->welcomeMessage = $welcomeMessage;
    }


    //provide a default index with some instrucction
    protected function index()
    {
        //TODO: add detailed instructions
        $appOrg = "Your ORG";
        $appName = "Your APP NAME";
        $welcomeMessage = "Some simple instructions of how to use the lib";
        echo $this->view->render('@basic/index.twig', compact('appOrg', 'appName', 'welcomeMessage'));
    }

}