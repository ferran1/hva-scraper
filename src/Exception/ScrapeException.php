<?php

namespace App\Exception;

class ScrapeException extends \Exception
{

    public function setLine(int $lineNumber) {
        $this->line = $lineNumber;
    }

    public function setFile(string $fileName) {
        $this->file = $fileName;
    }
}
