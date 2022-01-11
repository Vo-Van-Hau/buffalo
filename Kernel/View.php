<?php 

    class View {

        /**
         * @return Template-Object
         */
        public static function __template() {

            global $template_v1;

            return $template_v1;
        }
    }   

    /**
     *  @param String   $view_path: Path of file
     *  @param Array    $args: Data in view
     *  @return Template 
     */
    function view(string $view_path, array $args = []) {

        global $template_v1;

        $template_v1->__view($view_path, $args);   
    } 

    /**
     * @param String $file_path
     * @return File
     */
    function includeFile($file_path) {

        global $mulViewDir;

        $_lastDir = $mulViewDir["resources.views"] . $file_path;

        return require_once($_lastDir);
    }
?>