<?php

namespace MiniFast;

class Config
{
    const ROUTE_NAMESPACE = '\\MiniFast\\Core\\Route\\';

    const ROUTER_DEFAULT_INDEX = 'default';

    const ROUTER_ROUTE_CLASSNAME = self::ROUTE_NAMESPACE . 'Route';
    const ROUTER_ROUTEITERATOR_CLASSNAME = self::ROUTE_NAMESPACE
        . 'RouteIterator';
    const ROUTER_ROUTES_INDEX = 'routes';
    const ROUTER_ROUTES_MODELS = 'model';
    const ROUTER_ROUTES_NAME_INDEX = 'name';
    const ROUTER_ROUTES_RESPONSE = 'response';
    const ROUTER_ROUTES_VIEW = 'view';

    const ROUTER_SECTION_CLASSNAME = self::ROUTE_NAMESPACE . 'Section';
    const ROUTER_SECTIONITERATOR_CLASSNAME = self::ROUTE_NAMESPACE
        . 'SectionIterator';
    const ROUTER_SECTIONS_INDEX = 'sections';
    const ROUTER_SECTIONS_NAME = 'name';
}
