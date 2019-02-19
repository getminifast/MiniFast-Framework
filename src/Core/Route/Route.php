<?php

namespace MiniFast\Core\Route;

use \MiniFast\Config;

class Route
{
    protected $models;
    protected $view;
    protected $response;
    protected $name;

    public function __constrcut(array $content)
    {
        $this->name = $this->setName($content);
    }

    private function arraySetter(array $content, string $index): array
    {
        $items = [];

        if (isset($content[$index])) {
            if (is_array($content[$index])) {
                foreach ($content[$index] as $model) {
                    if (is_string($model)) {
                        $items[] = $model;
                    }
                }
            } elseif (is_string($content[$index])) {
                $items[] = $content[$index];
            }
        }

        return $items;
    }

    private function stringSetter(array $content, string $index): ?string
    {
        $item = null;

        if (isset($content[$index])) {
            if (!empty($content[$index])) {
                if (is_string($content[$index])) {
                    $item = $content[$index];
                } else {
                    throw new \Exception("A ${$index} name must be a string.");
                }
            }
        }

        return $item;
    }

    private function setName(array $content): void
    {
        if (
            $this->name = $this
                ->stringSetter(
                    $content,
                    Config::ROUTER_ROUTENAME_INDEX
                ) === null
        ) {
            throw new \Exception('A route cannot have an empty name.');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function setModels(array $content): void
    {
        $this->models = $this
            ->arraySetter($content, Config::ROUTER_ROUTES_MODELS);
    }

    public function getModels(): array
    {
        return $this->models;
    }

    private function setView(array $content): void
    {
        $this->view = $this
            ->stringSetter($content, Config::ROUTER_ROUTES_VIEW);
    }

    public function getView(): ?string
    {
        return $this->view;
    }
}
