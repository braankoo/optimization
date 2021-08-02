<?php

namespace App\Library\Reports\Google;

use App\Library\Reports\SedItInterface;
use Symfony\Component\Process\Process;

class SedIt implements SedItInterface {


    /**
     * @param string $reportPath
     * @return string
     */
    public function prepareForSql(string $reportPath): string
    {
        $process = Process::fromShellCommandline("sed -i -e '1d;$ d' -e 's/%//g' -e 's/< 10/9/g' -e 's/> 90/91/g' -e 's/(//' -e 's/)//' $reportPath");
        $process->mustRun();
        $process->wait();

        return $reportPath;
    }

}
