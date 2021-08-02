<?php

namespace App\Library\Reports;

use App\Library\Reports\Google\SedIt;

/**
 *
 */
class SedItFactory {


    /**
     * @param string $adPlatform
     * @return \App\Library\Reports\SedItInterface
     */
    public static function make(string $adPlatform)
    {
        switch ( $adPlatform )
        {
            case 'google':
                return new SedIt();
            case 'bing':
                return new \App\Library\Reports\Bing\SedIt();
            case 'gemini':
                return new \App\Library\Reports\Gemini\SedIt();


        }
    }

}
