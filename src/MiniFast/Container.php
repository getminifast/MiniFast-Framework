<?php

namespace MiniFast;

class Container
{
    /**
     * Get an instance of *Storage*
     * @return MiniFast\Storage instance of *Storage*
     */
    public function getStorage()
    {
        $storage = Storage::getInstance();

        return $storage;
    }
    
    public function getView($template = __DIR__ . '/templates', $settings = ['cache' => false])
    {
        $twig = $this->getTwig($template, $settings);
        $view = new View($twig);
        
        return $view;
    }

    /**
     * Get all Twig system
     * @param string           $templates The templates directory
     * @param array            $settings  Optionnal settings for Twig
     * @return Twig_Environment All Twig system
     */
    public function getTwig(
        $templates = __DIR__ . '/templates',
        $settings = [
            'cache' => false
        ]
    ) {
        $loader = $this->getTwigLoader($templates);
        $twig = $this->getTwigEnvironment($loader, $settings);

        return $twig;
    }

    /**
     * Get an instance of *Twig_Loader_Filesystem*
     * @param  string                 $templates The directory where templates are stored
     * @return Twig_Loader_Filesystem The Twig loader
     */
    protected function getTwigLoader($templates)
    {
        $loader = new \Twig_Loader_Filesystem($templates);

        return $loader;
    }

    /**
     * Get an instance of *Twig_Environment*
     * @param  Twig_Loader_Filesystem $loader   The Twig loader
     * @param  array                  $settings Optionnal settings for Twig
     * @return Twig_Environment       Twig
     */
    protected function getTwigEnvironment($loader, $settings)
    {
        $twig = new \Twig_Environment($loader, $settings);

        return $twig;
    }
}