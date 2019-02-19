<?php

namespace MiniFast\Core\Route;

use \MiniFast\Config;
use \MiniFast\Core\Route;

class Section
{
    protected $routes;
    protected $sections;
    protected $name;
    protected $default;

    public function __construct(array $content)
    {
        $this->routes = $this->findRoutes($content);
        $this->sections = $this->findSections($content);
        $this->name = $this->findName($content);
        $this->default = $this->findDefault($content);
    }

    private function find(array $content, string $index, string $class): array
    {
        $items = [];

        if (isset($content[$index])) {
            foreach ($content[$index] as $key => $item) {
                $items[] = new $class($item);
            }
        }

        return $items;
    }

    private function findRoutes(array $content): array
    {
        return $this->find(
            $content,
            Config::ROUTER_ROUTES_INDEX,
            Config::ROUTER_ROUTE_CLASSNAME
        );
    }

    private function findName(array $content): string
    {
        if (isset($content[Config::ROUTER_ROUTES_SECTIONS])) {
            if (!empty($content[Config::ROUTER_ROUTES_SECTIONS])) {
                return $content[Config::ROUTER_ROUTES_SECTIONS];
            }
        }

        throw new \Exception('A section name cannot be empty.');
    }

    private function findDefault(array $content): ?Route
    {
        if (isset($content[Config::ROUTER_DEFAULT_INDEX])) {
            return new Route(
                $content[Config::ROUTER_DEFAULT_INDEX]
            );
        }

        return null;
    }

    private function findSections($content): array
    {
        return $this->find(
            $content,
            Config::ROUTER_SECTIONS_INDEX,
            Config::ROUTER_SECTION_CLASSNAME
        );
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getSections(): array
    {
        return $this->sections;
    }

    private function getName(): string
    {
        return $this->name;
    }

    public function searchInSections(string $name): ?Section
    {
        foreach ($this->sections as $section) {
            if ($section->getName() === $name) {
                return $section;
            }
        }

        return null;
    }

    public function searchInRoutes(string $name): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->getName() === $name) {
                return $route;
            }
        }

        return null;
    }
}
