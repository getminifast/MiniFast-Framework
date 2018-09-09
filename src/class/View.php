<?php

class View
{
    private $twig;

    public function __construct(string $templatesDir)
    {
        if(!empty($templatesDir))
        {
            // Initializing Twig
            $loader = new Twig_Loader_Filesystem($templatesDir); // Dossier contenant les templates
            $this->twig = new Twig_Environment($loader, [
                'cache' => false // No cache for dev environment
            ]);
            if(isset($_SESSION)) $this->twig->addGlobal('session', $_SESSION); // Allow access to $_SESSION in all templates
            
        }
    }

    public function render(string $template)
    {
        $template = $this->twig->loadTemplate($template . '.twig');
        $container = new Container();
        echo $template->render($container->getStorage()->getAll());
    }
}
