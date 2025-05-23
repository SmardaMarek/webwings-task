<?php

class FileClass
{
    function getPdfBase64(string $filePath): string
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException("File '$filePath' does not exist.");
        }

        $content = file_get_contents($filePath);

        if ($content === false) {
            throw new RuntimeException("Cannot load file '$filePath'.");
        }

        return base64_encode($content);
    }
}