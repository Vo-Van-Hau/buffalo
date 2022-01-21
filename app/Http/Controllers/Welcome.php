<?php

class Welcome {

    public function __index() {

        return view('pages/welcome.view.php', [
        
            "name" => "Buffalo Framework"
        ]);      
    }

    public function __home() {

        echo "This is home page";
    }
}