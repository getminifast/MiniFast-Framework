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
    
    /**
     * Render a template
     * @param string $template The template name
     * @param array  $array    Variables to pass to the template
     */
    public function render(string $template, array $array = [])
    {
        // Get storage
        $c = new Container();
        $s = $c->getStorage();
        
        // If array is not empty, add it to storage
        if (!empty($array)) {
            $s->setAttributes($array);
        }
        
        // Determine if extension is set
        $extension = explode('.', $template);
        $extension = $extension[sizeof($extension) - 1];
        
        // Is the extension .twig or .html?
        if ($extension === 'twig' or $extension === 'html') {
            $this->template = $this->twig->load($template);
        } else {
            $this->template = $this->twig->load($template . '.twig');
        }

        echo $this->template->render($s->getAttributes());
    }
}
