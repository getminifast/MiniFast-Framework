<?php

namespace MiniFast\Core\Route;

class File
{
    protected $content;
    protected $exists;

    public function __construct(string $path)
    {
        $this->content = $this->getContent($path);
        $this->exists = $this->FileExists($path);
    }

    /**
     * Test if this file exists
     * @param  string $path The path of the file
     * @return bool         True if exists
     */
    private function FileExists(string $path): bool
    {
        return \file_exists($path);
    }

    /**
     * Get the content of the file
     * @param  string $path The path of the file
     * @return string       Return the content as as string
     */
    private function getContent(string $path): string
    {
        if ($this->FileExists($path)) {
            return \file_get_contents($path);
        }

        throw new \Exception("File $path does not exists.");
    }

    /**
     * Return exists property
     * @return bool True if the file exists
     */
    public function exists(): bool
    {
        return $this->exists;
    }

    /**
     * Return the content of the fil
     * @return string The content of the file
     */
    public function getRaw(): string
    {
        return $this->content;
    }

    /**
     * Return the JSON-decoded string
     * @return array JSON-decoded string as an array
     */
    public function getArray(): array
    {
        return json_decode($this->content, true);
    }
}
