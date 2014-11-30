<?php

class twig
{
    /*
        Twig - sablony
        - sablony jsou ulozene s priponou .htm
        - nachazeji se ve slozce /view/sablony/
    */
    public function view($template_params, $tempPom)
    {
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem("view/sablony");
        $twig = new Twig_Environment($loader);
        $template = $twig->loadTemplate($tempPom);
        echo $template->render($template_params,$tempPom);
    }
}

?>