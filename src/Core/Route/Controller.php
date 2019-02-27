<?php

namespace MiniFast\Core\Route;

use \MiniFast\Config;
use \MiniFast\Core\Route\Route;
use \MiniFast\Core\Route\Section;

class Controller
{
    protected $url;
    protected $section;
    protected $default;

    public function __construct()
    {
        $this->setUrl();
        $this->route = $this->section = null;
        $this->default = new Route(
            [
                Config::ROUTER_ROUTES_NAME_INDEX => 'default'
            ]
        );
    }

    /**
     * Set the current URL
     */
    private function setUrl(): void
    {
        $completeUrl = $_SERVER['REQUEST_URI'];
        $explodedUrl = \explode('?', $completeUrl);
        $this->url = trim($explodedUrl[0], '/'); // Cleaned URL
    }

    /**
     * Get the URL and return it as a string
     * @return string The URL
     */
    public function getUrlAsString(): string
    {
        return $this->url;
    }

    /**
     * Get the route as an array
     * @return array The route as an array
     */
    public function getUrlAsArray(): array
    {
        return \explode('/', $this->url);
    }

    /**
     * Select a file and search for the corresponding route
     * @param mixed  $file      The file to open, can be an array
     * @param string $templates The template directory
     */
    public function fromFiles($files, string $templates): void
    {
        if (is_array($files)) {
            foreach ($files as $file) {
                $this->fromFiles($file, $templates);
            }
        } elseif (\is_string($files)) {
            $file = new File($files);

            if ($file->exists()) {
                $this->section = new Section($file->getArray());
            }
        }

        $this->route();
    }

    /**
     * Parse all route objects to find the good router
     */
    private function route(): void
    {
        $url = $this->getUrlAsArray();
        $size = \sizeof($url);
        $section = $this->section;

        for ($i = 0; $i < $size; $i++) {
            if ($i < ($size - 1)) {
                $section = $section->searchInSections($url[$i]);
                if ($section instanceof Section) {
                }
            } else {
                $route = $section->searchInRoutes($url[$i]);
                // TODO: Show view and use models
            }
        }
    }
}
