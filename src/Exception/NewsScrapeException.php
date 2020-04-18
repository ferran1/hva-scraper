<?php

namespace App\Exception;

class NewsScrapeException extends \Exception
{

    public function setLine(int $lineNumber) {
        $this->line = $lineNumber;
    }

    public function setFile(string $fileName) {
        $this->file = $fileName;
    }
}
