<?php

class Welcome {

    public function __index() {


        // $result = DB::table("comments")->get();

        $result = DB::table("customers")
            ->where("customer_name", "Hau Ha Vo")
            ->where("customer_id", 60807500)
            ->first();

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