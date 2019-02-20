<?php

namespace MiniFast\Core\Route;

use \MiniFast\Config;
use \MiniFast\Core\Route\Route;
use \MiniFast\Core\Route\RouteIterator;
use \MiniFast\Core\Route\SectionIterator;

class Section
{
    protected $routeIterator;
    protected $sectionIterator;
    protected $name;
    protected $default;

    public function __construct(array $content)
    {
        $this->routes = $this->findRoutes($content);
        $this->sections = $this->findSections($content);
        $this->name = $this->findName($content);
        $this->default = $this->findDefault($content);
    }

    /**
     * Find an array at $index in $content
     * @param  array  $content The section
     * @param  string $index   The index to find
     * @param  string $class   The classname of the element
     * @return array           An array filled with objects
     */
    private function find(
        array $content, string $index, string $class, string $iterator
    ): array {
        $items = new $iterator();

        if (isset($content[$index])) {
            foreach ($content[$index] as $key => $item) {
                $items->add(new $class($item));
            }
        }

        return $items;
    }

    /**
     * Find all routes in $content
     * @param  array $content The section content
     * @return array          An array filled with *Route*
     */
    private function findRoutes(array $content): array
    {
        return $this->find(
            $content,
            Config::ROUTER_ROUTES_INDEX,
            Config::ROUTER_ROUTE_CLASSNAME,
            Config::ROUTER_ROUTEITERATOR_CLASSNAME
        );
    }

    /**
     * Find this section name
     * @param  array  $content The section content
     * @return string          The section name
     */
    private function findName(array $content): string
    {
        if (isset($content[Config::ROUTER_ROUTES_SECTIONS])) {
            if (!empty($content[Config::ROUTER_ROUTES_SECTIONS])) {
                return $content[Config::ROUTER_ROUTES_SECTIONS];
            }
        }

        throw new \Exception('A section name cannot be empty.');
    }

    /**
     * Find a default *Route*
     * @param  array  $content The section content
     * @return Route           The default *Route* if found
     */
    private function findDefault(array $content): ?Route
    {
        if (isset($content[Config::ROUTER_DEFAULT_INDEX])) {
            return new Route(
                $content[Config::ROUTER_DEFAULT_INDEX]
            );
        }

        return null;
    }

    /**
     * Find all sections in $content
     * @param  array $content The section content
     * @return array          All found sections
     */
    private function findSections($content): array
    {
        return $this->find(
            $content,
            Config::ROUTER_SECTIONS_INDEX,
            Config::ROUTER_SECTION_CLASSNAME,
            Config::ROUTER_SECTIONITERATOR_CLASSNAME
        );
    }

    /**
     * Return all routes in this section
     * @return array All routes in this section
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Return all sections in this section
     * @return array All sections in this section
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * Get this section name
     * @return string This section name
     */
    private function getName(): string
    {
        return $this->name;
    }

    /**
     * Search for a section with $name
     * @param  string $name The name of the section to find
     * @return Section      The *Section* instance if found
     */
    public function searchInSections(string $name): ?Section
    {
        foreach ($this->sections as $section) {
            if ($section->getName() === $name) {
                return $section;
            }
        }

        return null;
    }

    /**
     * Search for a route with $name
     * @param  string $name The name of the route to find
     * @return Route        The *Route* instance if found
     */
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
