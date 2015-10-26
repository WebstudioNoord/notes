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

        if (($template==='admin/header.php') || ($template==='header.php') || (explode("/",$template)[0]==='partial') || ($template==='footer.php')) {
			require $templatePathname;
		} else {
			($_SESSION[LoggedIn] && ($_SESSION[Username]==='admin')) ? $admin='admin/' : $admin='';
            require $this->getTemplatePathname($admin.'header.php');
	        require $templatePathname;
            require $this->getTemplatePathname('footer.php');
	    }
	    
        return ob_get_clean();
    }
    
    private function trans($str,$parameter=[]) {
		$app = \Slim\Slim::getInstance();
		return $app->translator->trans($str,$parameter);
	}
    
}
