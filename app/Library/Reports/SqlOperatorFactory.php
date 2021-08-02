<?php

namespace App\Library\Reports;

use App\Library\Reports\Google\SqlOperator;

class SqlOperatorFactory {


    /**
     * @param string $adPlatform
     * @return \App\Library\Reports\SqlOperatorInterface
     */
    public static function make(string $adPlatform)
    {
        switch ( $adPlatform )
        {
            case 'google':
                return new SqlOperator();
            case 'bing':
                return new \App\Library\Reports\Bing\SqlOperator();
            case 'gemini':
                return new \App\Library\Reports\Gemini\SqlOperator();
        }
    }

}
