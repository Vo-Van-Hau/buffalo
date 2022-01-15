<?php

class TemplateEngine {

    private $__directory;
    
    /**
     * 
     * @default null
     * 
     */
    private $__layout;
    
    /**
     * @return array
     */
    private $__sections;
    
    /**
     * 
     * @default null
     * 
     */
    private $__current_section;
    
    public function __construct($directory) {

        $this->__setDirectory($directory);

        $this->__sections = [];

        $this->__layout = null;

        $this->__current_section = null;
    }

    /**
     * 
     * Set path of base directory
     * 
     * @param string $directory
     * 
     */
    private function __setDirectory(string $directory) {

        if (!is_dir($directory)) {

            throw new Exception("$directory is not exist");
        }

        $this->__directory = $directory;
    }

    /**
     * Checking path of file
     * 
     * @param string $path (Extension is .php)
     * 
     * @return string 
     * 
     */
    private function __resolvePath(string $path) {

        // $file = $this->__directory . '/' . $path . '.php';

        $file = $this->__directory . $path;

        if (!file_exists($file)) {

            throw new Exception("$file is not exist");
        }

        return $file;
    }
    
    
    /**
     * 
     * Since the view is stored at, we may load it
     * 
     * @param string $view_name
     * @param array $args
     * 
     * @return string
     * 
     */
    public function __view(string $view_name, array $args = []) {

        if (is_array($args)) {

            extract($args);
        }

        ob_start();

        include_once $this->__resolvePath($view_name);

        $content = ob_get_clean();

        if (empty($this->__layout)) {
            
            echo $content;

            return;
        }

        /**
         *  ob_clean();
         */
        if (ob_get_length() || ob_get_contents()) ob_clean();

        include_once $this->__resolvePath($this->__layout);

        $output = ob_get_contents();

        /**
         *  ob_end_clean();
         */
        if (ob_get_length() || ob_get_contents()) ob_end_clean();

        echo $output;
    }
    
    /**
     * 
     * Views which extend a TemplateEngine layout may inject content into the layout's sections using @__startSection directives
     * 
     * @param string $name
     * 
     */
    public function __startSection(string $name) {

        $this->__current_section = $name;

        ob_start();
    }
    
    /**
     * 
     * End of a section
     * 
     */
    public function __endSection() {

        if (empty($this->__current_section)) {

            throw new Exception("There is not a section start");
        }

        $content = ob_get_contents();

        ob_end_clean();

        $this->__sections[$this->__current_section] = $content;

        $this->__current_section = null;
    }
    
    /**
     * 
     * When defining a child view, use the @__extends TemplateEngine directive to specify which layout the child view should "inherit"
     * 
     * @param string $layout
     * 
     */
    public function __extends(string $layout) {

        $this->__layout = $layout;
    }
    
    /**
     * 
     * The contents of these sections will be displayed in the layout using @__yield
     * 
     * @param string $name
     * 
     * 
     */
    public function __yield(string $name) {

        echo $this->__sections[$name];
    }
}

