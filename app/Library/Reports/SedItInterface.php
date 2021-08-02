<?php

namespace App\Library\Reports;

interface SedItInterface {


    /**
     * @param string $reportPath
     * @return mixed
     */
    public function prepareForSql(string $reportPath);
}
