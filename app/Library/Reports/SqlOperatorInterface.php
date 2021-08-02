<?php

namespace App\Library\Reports;

interface SqlOperatorInterface {

    public function loadToTemporaryTable(string $reportPath);

}
