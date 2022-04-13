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

}