<?php 

    /**
     *  @param String   $view_path: Path of file
     *  @param Array    $dataInView: Data in view
     *  @return Template 
     */
    function view($view_path, $dataInView = []) {

        global $mulViewDir;

        foreach($dataInView as $key_DataView => $value_DataView) {

            ${$key_DataView} = $value_DataView;
        }

        $_lastDir = $mulViewDir["resources.views"] . $view_path;

        return include($_lastDir);
    } 

    /**
     * @param String $file_path
     * @return File
     */
    function includeFile($file_path) {

        global $mulViewDir;

        $_lastDir = $mulViewDir["resources.views"] . $file_path;

        return include($_lastDir);
    }

    function __extends($name) {

        includeFile($name);
    }

    function __section(String $name = null) {

        if(null === $name || empty($name)) {

            return;
        }

        global $section_extends;

        array_push($section_extends["sections"], $name);
    }

    function __yield($name = null) {

        if(null === $name || empty($name)) {

            return;
        }

        echo $name;
    }

?>