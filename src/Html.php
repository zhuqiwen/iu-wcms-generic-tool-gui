<?php

namespace Edu\IU\Framework\GenericUpdaterGUI;



use Twig\Environment;

class Html extends Environment {
    use CommonTrait;


    public function init(string $siteName, string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->siteName = $siteName;

    }

    public function get(string $viewName, $parameter = null)
    {
        $method = 'view' . $viewName;
        if(!method_exists($this, $method))
        {
            throw new \RuntimeException('view method: ' . $method . 'not found in Html.php');
        }
        return $this->$method($parameter);
    }

    /**
     * Basic parts
     */
    private function head(): string
    {
        return <<<HEAD
<head>
    <meta charset="UTF-8">
    <link href="https://assets.iu.edu/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <meta content="CodePen" name="apple-mobile-web-app-title">
    <title>IU Framework update tool</title>
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="https://unpkg.com/rivet-core@2.0.0-alpha.7/css/rivet.min.css" rel="stylesheet">
    <link href="https://v2.rivet.iu.edu/css/rivet-v2-rfc-site.css" rel="stylesheet">
    <style>
    /*Eliminates padding, centers the thumbnail */
 body, html {
	 padding: 0;
	 margin: 0;
}
/* Styles the thumbnail */
 a.lightbox img {
	 height: 150px;
	 border: 3px solid white;
	 box-shadow: 0px 0px 8px rgba(0,0,0,.3);
	 margin: 94px 20px 20px 20px;
}
/* Styles the lightbox, removes it from sight and adds the fade-in transition */
 .lightbox-target {
	 position: fixed;
	 top: -100%;
	 width: 100%;
	 background: rgba(0,0,0,.7);
	 width: 100%;
	 opacity: 0;
	 -webkit-transition: opacity .5s ease-in-out;
	 -moz-transition: opacity .5s ease-in-out;
	 -o-transition: opacity .5s ease-in-out;
	 transition: opacity .5s ease-in-out;
	 overflow: hidden;
	 z-index:999;
}
/* Styles the lightbox image, centers it vertically and horizontally, adds the zoom-in transition and makes it responsive using a combination of margin and absolute positioning */
 .lightbox-target img {
	 margin: auto;
	 position: absolute;
	 top: 0;
	 left:0;
	 right:0;
	 bottom: 0;
	 max-height: 0%;
	 max-width: 0%;
	 border: 3px solid white;
	 box-shadow: 0px 0px 8px rgba(0,0,0,.3);
	 box-sizing: border-box;
	 -webkit-transition: .5s ease-in-out;
	 -moz-transition: .5s ease-in-out;
	 -o-transition: .5s ease-in-out;
	 transition: .5s ease-in-out;
}
/* Styles the close link, adds the slide down transition */
 a.lightbox-close {
	 display: block;
	 width:50px;
	 height:50px;
	 box-sizing: border-box;
	 background: white;
	 color: black;
	 text-decoration: none;
	 position: absolute;
	 top: -80px;
	 right: 0;
	 -webkit-transition: .5s ease-in-out;
	 -moz-transition: .5s ease-in-out;
	 -o-transition: .5s ease-in-out;
	 transition: .5s ease-in-out;
}
/* Provides part of the "X" to eliminate an image from the close link */
 a.lightbox-close:before {
	 content: "";
	 display: block;
	 height: 30px;
	 width: 1px;
	 background: black;
	 position: absolute;
	 left: 26px;
	 top:10px;
	 -webkit-transform:rotate(45deg);
	 -moz-transform:rotate(45deg);
	 -o-transform:rotate(45deg);
	 transform:rotate(45deg);
}
/* Provides part of the "X" to eliminate an image from the close link */
 a.lightbox-close:after {
	 content: "";
	 display: block;
	 height: 30px;
	 width: 1px;
	 background: black;
	 position: absolute;
	 left: 26px;
	 top:10px;
	 -webkit-transform:rotate(-45deg);
	 -moz-transform:rotate(-45deg);
	 -o-transform:rotate(-45deg);
	 transform:rotate(-45deg);
}
/* Uses the :target pseudo-class to perform the animations upon clicking the .lightbox-target anchor */
 .lightbox-target:target {
	 opacity: 1;
	 top: 0;
	 bottom: 0;
	 overflow:scroll;
}
 .lightbox-target:target img {
	 max-height: 100%;
	 max-width: 100%;
}
 .lightbox-target:target a.lightbox-close {
	 top: 0;
}
 
</style>
    

    <script>
        // window.console = window.console || function(t) {};
    </script>   
</head>
HEAD;

    }

    private function body(string $content, string $issueNumber = null): string
    {
        $header = $this->header();
        $script = $this->customScript();
        return <<< BODY
<body>
<noscript>
	<iframe height="0" src="https://www.googletagmanager.com/ns.html?id=GTM-WJFT899" width="0"></iframe>
</noscript>
<header class="rvt-header-wrapper">
  <!-- Hidden link for screen reader users to skip to main content -->
  <a class="rvt-header-wrapper__skip-link" href="#main-content">Skip to main content</a>
  <div class="rvt-header-global">
    <div class="rvt-container-xl">
      <div class="rvt-header-global__inner">
        <div class="rvt-header-global__logo-slot">
          <a class="rvt-lockup" href="/">  
            <!-- Trident logo -->
            <div class="rvt-lockup__tab">
              <svg xmlns="http://www.w3.org/2000/svg" class="rvt-lockup__trident" viewBox="0 0 28 34">
                <path d="M-3.34344e-05 4.70897H8.83308V7.174H7.1897V21.1426H10.6134V2.72321H8.83308V0.121224H18.214V2.65476H16.2283V21.1426H19.7889V7.174H18.214V4.64047H27.0471V7.174H25.0614V23.6761L21.7746 26.8944H16.2967V30.455H18.214V33.8787H8.76463V30.592H10.6819V26.8259H5.20403L1.91726 23.6077V7.174H-3.34344e-05V4.70897Z" fill="currentColor"></path>
              </svg>
            </div>
            <!-- Website or application title -->
            <div class="rvt-lockup__body">
              <span class="rvt-lockup__title">Web Framework</span>
              <span class="rvt-lockup__subtitle">Indiana University</span>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
  $header
</header>
<main class="rvt-layout__wrapper">
    <div class="rvt-bg-black-000 rvt-p-tb-sm">
        <div class="rvt-container-lg">
            <div class="rvt-card rvt-card--raised [ rvt-p-all-lg rvt-p-all-xxl-md-up ]">
                <div class="rvt-prose rvt-flow">
                    $content
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://unpkg.com/rivet-core@2.0.0-alpha.7/js/rivet.min.js"></script>
<script id="rendered-js">
    Rivet.init();
</script>
$script
</body>

BODY;

    }

    private function header(): string
    {
        $link = "";
        return <<< HEADER
  <div class="rvt-header-local">
    <div class="rvt-container-xl">
      <div class="rvt-header-local__inner" data-rvt-disclosure="local-header-menu">
        <!-- Secondary navigation title -->
        <a href="./index.php" class="rvt-header-local__title">Update Tool (Beta)</a>
        <!-- Menu button that shows/hides secondary navigation on smaller screens -->
        <button aria-expanded="false" class="rvt-global-toggle rvt-global-toggle--menu rvt-hide-lg-up" data-rvt-disclosure-toggle="local-header-menu">
          <span class="rvt-sr-only">Toggle local menu</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
            <path fill="currentColor" d="M8,12.46a2,2,0,0,1-1.52-.7L1.24,5.65a1,1,0,1,1,1.52-1.3L8,10.46l5.24-6.11a1,1,0,0,1,1.52,1.3L9.52,11.76A2,2,0,0,1,8,12.46Z" />
          </svg>
        </button>
        $link
      </div>
    </div>
  </div>

HEADER;

    }

    private function customScript(): string
    {
        return <<< SCRIPT
<script>
    function SubmitAndDisable(e) {
        e.preventDefault();
        const currentButton = e.target;
        const form = document.getElementById("ctr_form");
        const buttons = document.getElementsByTagName("button");
        // no need to validate in 2 step
        if(form != null && validate(form))
        {
            form.submit();
            Array.from(form.elements).forEach(
                formElement => formElement.disabled = true
            );
            
            setCurrentButton(currentButton)
        
            Array.from(buttons).forEach(
                button => button.disabled = true
            );            
        }

        return false;
    }
    
    function setCurrentButton(crrntBtn){
        const currentButtonCss = crrntBtn.getAttribute('class');
        const newButtonCss = currentButtonCss + ' rvt-button--loading';
        crrntBtn.setAttribute('class', newButtonCss);       
        let newInnerHTML = '<span class="rvt-button__content">Update settings</span>';
        newInnerHTML += '<div class="rvt-loader rvt-loader--xs" aria-label="Content loading"></div>';
        crrntBtn.innerHTML = newInnerHTML;
    }
    
    function Disable(e){
        e.preventDefault();
        console.log(e.target);
        const currentButton = e.target;
        const buttons = document.getElementsByTagName("button");
        currentButton.form.submit();
        setCurrentButton(currentButton);
        
        for(let i = 0; i < buttons.length; i++){
            buttons[i].disabled = true;
        }   
    }
    
    function validate(f){
        const textInputs = f.getElementsByTagName("input");
        const funcPrefix = "validate_";
        let result = true;
        for(let i = 0; i < textInputs.length; i++){
            let input = textInputs[i];
            let name = input.name;
            let func = funcPrefix + name;
            if(name != "todo"){
                let fieldResult = window[func](input);
                result = result && fieldResult;    
            }
            
        }

        let isUpdateSelected = validate_update_to_apply(f);
        result = result && isUpdateSelected;

        return result;
    }
    
    function validate_update_to_apply(f){
        const dropdown = f.getElementsByTagName('select')[0];
        const inlineAlert = dropdown.nextElementSibling;
        let hasContent = (dropdown.value.trim() != "");
        dropdown.style = hasContent ? "" : "background-color: #ffece5;";  
        inlineAlert.style = hasContent ? "display: none;" : "display: flex;"    
        
        return hasContent;
    }

    function validate_site_name(input){
        const hasContent = isInputEmpty(input);
        
        return hasContent;
    }

    function validate_api_key(input){
        const hasContent = isInputEmpty(input)
        const correctFormat = true;

        return hasContent && correctFormat;
    }

    function isInputEmpty(input){
        const label = input.previousElementSibling;
        const inlineAlert = input.nextElementSibling;
        const val = input.value.trim();
        const hasContent = val == "" ? false : true;
        input.style = hasContent ? "" : "background-color: #ffece5;";
        inlineAlert.style = hasContent ? "display: none;" : "display: flex;"

        return hasContent;
    }
    
    function dismissAlert(e){
        const inlineAlert = e.target.nextElementSibling;
        inlineAlert.style = "display: none;";
        e.target.style = "background-color: none;";
    }
   
// image light box
window.addEventListener('load', function(){
         const imageSelector = "div.with-light-box>img";
         const images = document.querySelectorAll(imageSelector);
         const body = document.body;
         for(let i = 0; i < images.length; i++){
             let image = images.item(i);
             let lightboxId = image.dataset.lightBox;
             let imagePath = image.src;
             let lightBox = document.createElement('div');
             lightBox.className = 'lightbox-target';
             lightBox.id = lightboxId;
             lightBox.innerHTML = '<img src="' + imagePath + '"/><a class="lightbox-close" href="#"></a>';      
             body.append(lightBox);
             console.log(lightboxId);
             console.log(imagePath);    
             image.addEventListener('click', function() {
                 document.location.href = "#" + lightboxId;
             });
             document.getElementById(lightboxId).addEventListener('click', function(e) {
                 document.location.href = "#";
             });
         }      
     });
    
</script>

SCRIPT;

    }




    /**
     * Views
     */
    private function viewIndex(): string
    {
        $html = <<< INDEXHTML
<h2>Web Framework Update Tool (Beta)</h2>
<p>This tool applies updates that require changes in your site. This tool is in <strong>beta</strong>. If you have concerns using the tool, please follow the manual update instructions from the <a target="_blank" href="https://framework.iu.edu/help/changelog/index.html#manual">Framework Changelog</a>.</p>
<p><a target="_blank" href="#">Click Here</a> if you want to use the tool to <strong>apply previous manual updates.</strong></p>
<p><strong>API Key</strong> and <strong>site name in WCMS</strong> are required. To learn how to locate your API, see the <a target="_blank" href="https://www.hannonhill.com/cascadecms/latest/cascade-basics/account-settings.html"> Hannon Hill documentation</a>.</p>
<p><strong>You must be a site manager to use this tool.</strong></p>
<form action="index.php" id="ctr_form" method="post">
    <div class="rvt-row">
        <div class=" rvt-cols-12-md rvt-m-top-md">
            <label class="rvt-label" for="site_name">Site name in WCMS (i.e. IU-SITE-WEBS.main) *</label>
            <input class="rvt-input" id="site_name" name="site_name" type="text" oninput="dismissAlert(event)">
            <div class="rvt-inline-alert rvt-inline-alert--standalone rvt-inline-alert--danger" style="display: none">
              <span class="rvt-inline-alert__icon">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                  <g fill="currentColor">
                    <path d="M8,0a8,8,0,1,0,8,8A8,8,0,0,0,8,0ZM8,14a6,6,0,1,1,6-6A6,6,0,0,1,8,14Z" />
                    <path d="M10.83,5.17a1,1,0,0,0-1.41,0L8,6.59,6.59,5.17A1,1,0,0,0,5.17,6.59L6.59,8,5.17,9.41a1,1,0,1,0,1.41,1.41L8,9.41l1.41,1.41a1,1,0,0,0,1.41-1.41L9.41,8l1.41-1.41A1,1,0,0,0,10.83,5.17Z" />
                  </g>
                </svg>
              </span>
              <span class="rvt-inline-alert__message" id="site-name-name-message">Site name is required.</span>
            </div>
        </div>
        
    </div>
    <div class="rvt-row">
        <div class="rvt-cols-12-md rvt-m-top-md">
            <label class="rvt-label" for="api_key">API Key *</label>
            <input class="rvt-input" id="api_key" name="api_key" type="text" oninput="dismissAlert(event)">
            <div class="rvt-inline-alert rvt-inline-alert--standalone rvt-inline-alert--danger" style="display: none">
              <span class="rvt-inline-alert__icon">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                  <g fill="currentColor">
                    <path d="M8,0a8,8,0,1,0,8,8A8,8,0,0,0,8,0ZM8,14a6,6,0,1,1,6-6A6,6,0,0,1,8,14Z" />
                    <path d="M10.83,5.17a1,1,0,0,0-1.41,0L8,6.59,6.59,5.17A1,1,0,0,0,5.17,6.59L6.59,8,5.17,9.41a1,1,0,1,0,1.41,1.41L8,9.41l1.41,1.41a1,1,0,0,0,1.41-1.41L9.41,8l1.41-1.41A1,1,0,0,0,10.83,5.17Z" />
                  </g>
                </svg>
              </span>
              <span class="rvt-inline-alert__message" id="api-key-name-message">API key is required.</span>
            </div>
        </div>
    </div>

    <div class="rvt-row">
        <div class="rvt-cols-12-md rvt-m-top-md">
            <label class="rvt-label" for="update_to_apply">Please select which update to apply *</label>
            <select class="rvt-select" id="update_to_apply" name="update_to_apply" onchange="dismissAlert(event)">
                <option value="" selected>-</option>
                <option value="2022-april-rivet-style">2022 May: Rivet Visual Alignment Update</option>
            </select>
            <div class="rvt-inline-alert rvt-inline-alert--standalone rvt-inline-alert--danger" style="display: none">
              <span class="rvt-inline-alert__icon">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                  <g fill="currentColor">
                    <path d="M8,0a8,8,0,1,0,8,8A8,8,0,0,0,8,0ZM8,14a6,6,0,1,1,6-6A6,6,0,0,1,8,14Z" />
                    <path d="M10.83,5.17a1,1,0,0,0-1.41,0L8,6.59,6.59,5.17A1,1,0,0,0,5.17,6.59L6.59,8,5.17,9.41a1,1,0,1,0,1.41,1.41L8,9.41l1.41,1.41a1,1,0,0,0,1.41-1.41L9.41,8l1.41-1.41A1,1,0,0,0,10.83,5.17Z" />
                  </g>
                </svg>
              </span>
              <span class="rvt-inline-alert__message" id="site-name-name-message">An update must be selected.</span>
            </div>
        </div>
    </div>
    <input type="hidden" name="todo" value="showSummary"/>

    <div class="rvt-m-top-lg">
        <button onclick="SubmitAndDisable(event)" type="submit" form="ctr_form" value="Submit" class="rvt-button rvt-button--link rvt-button--full-width">Next</button>
    </div>
</form>
INDEXHTML;

        return $this->head() . $this->body($html);

    }

    private function viewSummaryRivetStyle(array $assets): string
    {
        if(empty($assets))
        {
            $html = $this->buildNoNeedToUpdate();
            return  $this->head() . $this->body($html);
        }

        $accordion = $this->buildAccordion($assets);
        $preview = $this->buildImagesComparison();

        $html = <<<SUMMARYRIVET
<div class="rvt-tabs" data-rvt-tabs="tabset-1">
  <div class="rvt-tabs__tablist" role="tablist" aria-label="Rivet tabs">
    <button class="rvt-tabs__tab" role="tab" data-rvt-tab="tab-list" id="t-list">
      What will be updated
    </button>
    <button class="rvt-tabs__tab" role="tab" data-rvt-tab="tab-preview" id="t-preview">
      Preview of changes
    </button>
  </div>
  <div class="rvt-tabs__panel" tabindex="0" role="tabpanel" id="tab-list" aria-labelledby="t-list" data-rvt-tab-panel="tab-list" data-rvt-tab-init>
    <span class="rvt-ts-23 rvt-text-bold">In your site: $this->siteName</span>
    <p>The string of <strong>3.2.x</strong> in the following assets will be replaced with <strong>3.3.x</strong>.</p>
    $accordion
  </div>
  <div class="rvt-tabs__panel" tabindex="0" role="tabpanel" id="tab-preview" aria-labelledby="t-preview" data-rvt-tab-panel="tab-preview">
  <p>click image to enlarge.</p>
    $preview
  </div>
</div>
SUMMARYRIVET;

        $html .= $this->buildUpdateButton();


        return $this->head() . $this->body($html);
        
    }

    private function viewError(string $errorMessage): string
    {
        $error = $this->buildError($errorMessage);

        return $this->head() . $this->body($error);
    }

    private function viewUpdateResult(array $result): string
    {
        $assetsHtml = "";
        foreach ($result as $asset) {

            $assetData = $asset->getNewAsset();
            $url = "https://sites.wcms.iu.edu/entity/open.act?id=" . $assetData->id . "&type=" . strtolower($asset->getAssetTypeDisplay());
            $assetsHtml .= "<li>";
            $assetsHtml .= "<a target='_blank' href='$url'>$assetData->path</a>";
            $assetsHtml .= "</li>";
        }

        $content = <<< RESULT
<div class="rvt-alert rvt-alert--success [ rvt-m-top-md ]" role="alert" aria-labelledby="success-alert-title" data-rvt-alert="success">
  <div class="rvt-alert__title" id="success-alert-title">You Site: $this->siteName has been successfully update! Just one more step!</div>
  <p class="rvt-alert__message">The following assets have been successfully updated:</p>
  <ul class="rvt-list">
    $assetsHtml
  </ul>
  <p class="rvt-alert__message"><strong>It requires a full-site publish to take on Rivet style.</strong></p> 
</div>
RESULT;

        return $this->head() . $this->body($content);
    }


    /**
     * Building parts
     */

    private function buildNoNeedToUpdate(): string
    {
        return <<< NOTHINGTOUPDATE
<div class="rvt-alert rvt-alert--info" role="alert" aria-labelledby="information-alert-title" data-rvt-alert="info">
  <div class="rvt-alert__title" id="information-alert-title">Emmm...</div>
  <p class="rvt-alert__message">Looks like the Site: $this->siteName may have already been updated. </p>
</div>
NOTHINGTOUPDATE;

    }

    private function buildAccordion(array $assets): string
    {
        $accordionItems = "";
        foreach ($assets as $index => $asset) {
            $path = $asset->path;
            if($path == PATH_SETTINGS_BLOCK){
                $header = "Settings Block";
                $url = "https://sites.wcms.iu.edu/entity/open.act?id=$asset->id&type=block";
                $codeOld = $this->buildCode("<brand-version>3.2.x</brand-version>");
                $codeNew = str_replace("3.2.x", "3.3.x", $codeOld);

            }
            if($path == PATH_JAVASCRIPT_INCLUDE_FILE || $path == PATH_GLOBAL_CSS_INCLUDE_FILE){
                $header = $path == PATH_JAVASCRIPT_INCLUDE_FILE ?
                    "Javascript Inclusion File"
                    :
                    "Global CSS inclusion File";
                $url = "https://sites.wcms.iu.edu/entity/open.act?id=$asset->id&type=file";
                $codeOld = $this->buildCode($asset->text);
                $codeNew = str_replace("3.2.x", "3.3.x", $codeOld);
            }
            $data = compact('header', 'url', 'codeOld', 'codeNew', 'path', 'index');
            $accordionItems .= $this->buildAccordionItem($data);
        }

        $html = <<<ACCORDION
<div class="rvt-accordion" data-rvt-accordion="test-accordion">
    $accordionItems
</div>
ACCORDION;

        return $html;
    }

    private function buildAccordionItem(array $data): string
    {
        extract($data);
        return <<< ACCORDIONITEM
<h3 class="rvt-accordion__summary">
    <button class="rvt-accordion__toggle" id="test-accordion-$index-label" data-rvt-accordion-trigger="test-accordion-$index">
      <span class="rvt-accordion__toggle-text">$header</span>
      <div class="rvt-accordion__toggle-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
          <g fill="currentColor">
            <path class="rvt-accordion__icon-bar" d="M8,15a1,1,0,0,1-1-1V2A1,1,0,0,1,9,2V14A1,1,0,0,1,8,15Z" />
            <path d="M14,9H2A1,1,0,0,1,2,7H14a1,1,0,0,1,0,2Z" />
          </g>
        </svg>
      </div>
    </button>
  </h3>
  <div class="rvt-accordion__panel" id="test-accordion-$index" aria-labelledby="test-accordion-$index-label" data-rvt-accordion-panel="test-accordion-$index">
    <ul class="rvt-list">
        <li>Path: <a target="_blank" href="$url">$path</a></li>
        <li>
            Changes:
            $codeOld
            will be replaced with:
            $codeNew
        </li>
    </ul>
  </div>
ACCORDIONITEM;


    }

    private function buildImagesComparison(): string
    {
        return <<< IMGCOMP
    <div class="rvt-row">
      <div class="rvt-cols-6-md">
        <div class="rvt-card">
          <div class="rvt-card__body">
            <h2 class="rvt-card__title">
              Before
            </h2>
          </div>
          <div class="rvt-card__image with-light-box">
            <img src="./lib/img/iuframe-current-style.png" alt="Smiling students sitting outside on a bench" data-light-box="iuframe-current">
          </div>
          
        </div>
      </div>
      <div class="rvt-cols-6-md">
        <div class="rvt-card">
          <div class="rvt-card__body">
            <h2 class="rvt-card__title">
                After
            </h2>
          </div>
          <div class="rvt-card__image with-light-box">
            <img src="./lib/img/iuframe-rivet.png" alt="Replace this value with appropriate alternative text" data-light-box="iuframe-rivet">
          </div>
          
        </div>
      </div>
    </div>
IMGCOMP;

    }

    private function buildError(string $errorMessage): string
    {
        return <<<ERROR
<div class="rvt-alert rvt-alert--danger [ rvt-m-top-md ]" role="alert" aria-labelledby="error-alert-title" data-rvt-alert="error">
  <div class="rvt-alert__title" id="error-alert-title">Oops!</div>
  <p class="rvt-alert__message">$errorMessage</p>
</div>
ERROR;
    }

    private function buildCode(string $content): string
    {
        $html = '<div class="rvt-component-example__code">';
        $html .= '   <pre class="language-html">';
        $html .= '   <code class="language-html">';
        $html .= htmlentities($content);
        $html .= '</code></pre></div>';

        return $html;
    }

    private function buildUpdateButton(): string
    {
        $label = "Update";
        $apiKey = $this->apiKey;
        $siteName =$this->siteName;
        $updateTpApply = $_SESSION['update_to_apply'];

        $html = <<< BUTTONFORM
<form action="index.php" id="form_update" method="post">
    <input name="api_key" value="$apiKey" type="hidden">
    <input name="site_name" value="$siteName" type="hidden">
    <input name="update_to_apply" value="$updateTpApply" type="hidden">
    <input name="todo" value="update" type="hidden">
</form>

<div class="rvt-m-top-lg">
    <button onclick="Disable(event)" type="submit" form="form_update" value="Submit" class="rvt-button rvt-button--link rvt-button--full-width">$label</button>
</div>

BUTTONFORM;

        return $html;
    }



}