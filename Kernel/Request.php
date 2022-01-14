<?php

class Request {


    /**
     * Retrieve an input item from the request.
     *
     * @param  string|null  $key
     * @return mixed
     */
    public function input($key = null) {

        if(is_null($key)) {

            return null;
        }

        if(isset($_POST[$key]) && $_POST[$key]) {

            return trim($_POST[$key]);
        }
        else {

            return null;
        }
    }
}
