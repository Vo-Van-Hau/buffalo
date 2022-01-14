<?php

/**
 * Retrieve an input item from the request.
 *
 * @param  string|null  $key
 * @return mixed
 */
function input($key = null) {

    global $request;

    return $request->input($key);
}