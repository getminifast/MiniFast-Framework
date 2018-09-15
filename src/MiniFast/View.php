<?php

namespace MiniFast;

class View
{
    protected $twig;
    protected $template;
    
    public function __construct($twig)
    {
        $this->twig = $twig;
    }
    
    public function render(string $template)
    {
        // Get storage
        $c = new Container();
        $storage = $c->getStorage();
        
        // Determine if extension is set
        $extension = explode('.', $template);
        $extension = $extension[sizeof($extension) - 1];
        
        // Is the extension .twig or .html?
        if ($extension === 'twig' or $extension === 'html') {
            $this->template = $this->twig->load($template);
        } else {
            $this->template = $this->twig->load($template . '.twig');
        }
        
        if ($storage->isset('route.storage')) {
            echo $this->template->render($storage->getAttribute('route.storage'));
        } else {
            echo $this->template->render([]);
        }
    }
}