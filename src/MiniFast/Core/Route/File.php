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

    private function FileExists(string $path): bool
    {
        return \file_exists($path);
    }

    private function getContent($path): string
    {
        if ($this->FileExists($path)) {
            return \file_get_contents($path);
        }

        throw new \Exception("File ${$path} does not exists.");
    }

    public function exists(): bool
    {
        return $this->exists;
    }

    public function getRaw(): string
    {
        return $this->content;
    }

    public function getArray(): array
    {
        return json_decode($this->content, true);
    }
}
