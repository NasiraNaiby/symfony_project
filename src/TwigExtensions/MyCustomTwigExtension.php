<?php

namespace App\TwigExtensions;

use Symfony\Component\Validator\Constraints\Length;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyCustomTwigExtension extends AbstractExtension{

    public function getFilters()
    {
        return [
            new TwigFilter('defaultImage', [$this, 'defaultImage'])
        ];
    }
    public function defaultImage(string $path ):string {
        if(strlen(trim($path)) == 0){
            return 'p2.jpeg';
        }
        return $path;
    }
}

