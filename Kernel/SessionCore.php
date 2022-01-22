<?php

class SessionCore {

    /**
     * The default session handler is a file system, and it means that PHP stores sessions on the disk. 
     * Basically, it's a small file on the server which is associated with the unique session id. 
     * It's the same id which is stored in a session cookie on the client browser
     */

    /**
     * Returns the session ID.
     *
     * @return string
     */
    public function getId() {

        
    }

    /**
     * Sets the session ID.
     */
    public function setId(string $id) {

       
    }
    /**
     * Starts the session.
     *
     * @return bool True if started
     *
     * @throws \RuntimeException if something goes wrong starting the session
     */
    public function start() {
      
        if (!session_start()) {

            throw new \RuntimeException('Failed to start the session.');
        }

        return session_start();
    }

    /**
     * Returns an attribute.
     *
     * @param mixed $default The default value if not found
     *
     * @return mixed
     */
    public function get(string $name = null, $default = null) {

        if(is_null($name)) return null;

        if($this->has($name)) {

            return $_SESSION[$name];
        }

        return null;
    }

    /**
     * Sets an attribute.
     *
     * @param mixed $value
     */
    public function set(string $name = null, $value = null) {
        
        if(is_null($name) || is_null($value)) return null;

        $_SESSION[$name] = $value;
    }

    /**
     * Returns attributes.
     *
     * @return array
     */
    public function all() {

    }

    /**
     * Checks if an attribute is defined.
     *
     * @return bool
     */
    public function has(string $name = null) {

        if(is_null($name)) return false;

        if(isset($_SESSION[$name])) return true;

        return false;
    }

    /**
     * Sets attributes.
     */
    public function replace(array $attributes) {

        $this->getAttributeBag()->replace($attributes);
    }

    /**
     * Removes an attribute.
     *
     * @return mixed The removed value or null when it does not exist
     */
    public function remove(string $name = null) {

        if(is_null($name)) return null;

        if($this->has($name)) {

            unset($_SESSION[$name]);
        }
    }

    /**
     * Clears all attributes.
     */
    public function clear(){

       // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {

            $params = session_get_cookie_params();

            setcookie(session_name(), '', time() - 42000,

                $params["path"], $params["domain"],

                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }

    /**
     * ---------------------------------------Session Handler---------------------------------------
     * 
     * link: https://code.tutsplus.com/tutorials/how-to-use-sessions-and-session-variables-in-php--cms-31839
     * 
     * You might want to manage sessions in a database, Redis, or some other storage. 
     * In this case, you need to implement a custom session handler which overrides the default behavior
     */
}
