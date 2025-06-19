<?php

/**
 * View-specific wrapper.
 * Limits the accessible scope available to templates.
 */
class View{
    /**
     * Template being rendered.
     */
    protected $template = null;


    /**
     * Initialize a new view context.
     */
    public function __construct($template) {
        $this->template = str_replace(".", DIRECTORY_SEPARATOR, $template);
    }

    /**
     * Safely escape/encode the provided data.
     */
    public function h($data) {
        return htmlspecialchars((string) $data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Render the template, returning it's content.
     * @param array $data Data made available to the view.
     * @return string The rendered template.
     */
    public function render(Array $data) {
		
		$ROOT = $_SESSION["ROOT"];
		
        extract($data);
        $sep = DIRECTORY_SEPARATOR;
        ob_start();
        include( $ROOT .  "pages"  . $sep . "default"  . $sep . "includes" . $sep . $this->template . ".php");
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}

?>