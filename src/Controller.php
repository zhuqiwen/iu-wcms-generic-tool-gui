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
    protected $appInfo;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        // viewLoader can be used by childs to set different path and namespace of tempaltes
        // viewLoader->addPath()
        $this->viewLoader = New FilesystemLoader();
        $this->viewLoader->setPaths(__DIR__ . '/view', 'basic');
    }

    public function start(string $routeMode = 'singleRoute')
    {
        $this->initView();


        try {
            $this->$routeMode();
        }catch (\Exception $e){
            $errorMessage = $e->getMessage();
            $appOrg = $this->appOrg;
            $appName = $this->appName;
            echo $this->view->render('@basic/error.twig', compact('errorMessage', 'appOrg', 'appName'));
        }
    }

    //Route modes

    /**
     * singleRoute should be used for apps like framework updater, or campaign template installer
     * where all forms submitted only to the index.php
     * So there is only one route, /index.php, without any query string
     *
     * other route mode should be defined in client codebase
     */
    protected function singleRoute()
    {
        $nextStep = filter_input(INPUT_POST, "next_step") ?? "index";
        $updateToApply = filter_input(INPUT_POST, "update_to_apply") ?? "";
        $nextStep = $nextStep . str_replace(" ", "", ucwords(str_replace("-", " ", $updateToApply)));

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


    protected function initView()
    {
        $this->view = new Html($this->viewLoader, ['debug' => true]);
        $this->view->addGlobal('session', $_SESSION);
        $this->addViewGlobals();
        $this->view->addExtension(new DebugExtension());
        $this->addViewExtensions();
    }

    //Placeholders for view->addGlobal() and view->addExtension()
    protected function addViewGlobals(){}

    protected function addViewExtensions(){}

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

    protected function setAppInfo(string $appOrg, string $appName, string $welcomeMessage, array $extra = [])
    {
        $this->appOrg = $appOrg;
        $this->appName = $appName;
        $this->welcomeMessage = $welcomeMessage;

        foreach ($extra as $k => $v){
            if($k == 'appOrg' || $k == 'appName' || $k == 'welcomeMessage'){
                $msg = 'the key of ' . $k . ' is not allowed in \$extra passed into setAppInfo()';
                throw new \RuntimeException($msg);
            }
        }
        $username = $this->getUsername();
        $firstLetter = empty($username) ? '?' : strtoupper($username[0]);

        $this->appInfo = array_merge(
            $extra,
            compact(
                'appOrg',
                'appName',
                'welcomeMessage',
                'username',
                'firstLetter'
            )
        );

    }

    public function getUsername()
    {
        return $_SESSION['CAS_USER'] ?? '';
    }

    public function getAppInfo()
    {
        return $this->appInfo;
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