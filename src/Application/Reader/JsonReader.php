<?php

namespace MVC\Application\Reader;

use Throwable;

class JsonReader implements ReaderInterface
{
    private string $filePath = '';

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->filePath = $path;
    }

    /**
     * @return array
     *
     * @throws Throwable
     */
    public function read(): array
    {
        $file = fopen($this->filePath, 'r+');
        $fileContent = fread($file, filesize($this->filePath));
        fclose($file);

        return json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);
    }
}