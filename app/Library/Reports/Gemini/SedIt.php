<?php

namespace App\Library\Reports\Gemini;

use App\Library\Reports\SedItInterface;
use Symfony\Component\Process\Process;

class SedIt implements SedItInterface {


    /**
     * @param string $reportPath
     * @return string
     */
    public function prepareForSql(string $reportPath): string
    {
        return $reportPath;
    }
}
