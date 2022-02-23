<?php

class Welcome {

    public function __index() {

        $result = DB::table("products")->get();

        var_dump($result);

        return;
        
        return view('pages/welcome.view.php', [
        
            "name" => "Buffalo Framework"
        ]);      
    }

    public function __home() {

        echo "This is home page";
    }
}