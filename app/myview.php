<?php

class CustomView extends \Slim\View
{
    public function render($template, $data = NULL)
    {
        $templatePathname = $this->getTemplatePathname($template);
        if (!is_file($templatePathname)) {
            throw new \RuntimeException("View cannot render `$template` because the template does not exist");
        }

        $data = array_merge($this->data->all(), (array) $data);
        extract($data);
        ob_start();
        
        require $this->getTemplatePathname('header.php');
        require $templatePathname;
        require $this->getTemplatePathname('footer.php');
        
        return ob_get_clean();
    }
}
