<?php

namespace App\Library\Reports\Bing;

use App\Library\Reports\SedItInterface;
use Symfony\Component\Process\Process;

/**
 *
 */
class SedIt implements SedItInterface {


    /**
     * @param string $reportPath
     * @return string
     */
    public function prepareForSql(string $reportPath): string
    {


        $process = Process::fromShellCommandline("sed -i 's/%//g' $reportPath");
        $process->mustRun();
        $process->wait();

        return $reportPath;
    }


}
