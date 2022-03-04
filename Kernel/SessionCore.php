<?php

class SessionCore {

    /**
     * The default session handler is a file system, and it means that PHP stores sessions on the disk. 
     * Basically, it's a small file on the server which is associated with the unique session id. 
     * It's the same id which is stored in a session cookie on the client browser
     */

    /**
     * Has a session started?
     *
     * @return bool
     */
    public function isActive() {

        return \PHP_SESSION_ACTIVE === session_status();
    }

    /**
     * Gets the session ID.
     *
     * @return string|false
     */
    public function getId() {

        /**
         * session_id() returns the session id for the current session or the empty string ("") if there is no current session (no current session id exists). 
         * On failure, false is returned.
         * session_id() needs to be called before session_start() for that purpose.
         */
        return session_id();
    }

    /**
     * Sets the session ID.
     * 
     * @throws \LogicException
     */
    public function setId(string $id = null) {

        if(is_null($id)) return false;

        if ($this->isActive()) {

            throw new \LogicException('Cannot change the ID of an active session.');
        }

        session_id($id);

        return true;
    }

    /**
     * Gets the session name.
     *
     * @return string
     */
    public function getName() {

        return session_name();
    }

    /**
     * Sets the session name.
     *
     * @throws \LogicException
     */
    public function setName(string $name = null) {

        if(is_null($name)) return false;

        if ($this->isActive()) {

            throw new \LogicException('Cannot change the name of an active session.');
        }

        session_name($name);

        return true;
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

            /**
             * For flash session
             */
            if(is_array($_SESSION[$name]) && isset($_SESSION[$name]["type"]) && $_SESSION[$name]["type"] == "flash") {

                $value = $_SESSION[$name]["value"];

                /**
                 * The flashed data will be deleted
                 */
                if($this->remove($name)) {

                    return $value;
                }

                return null;
            }

            /**
             * For normal session
             */
            return $_SESSION[$name];
        }

        return null;
    }

    /**
     * Sets an attribute.
     *
     * @param mixed $value
     * @return boolean
     */
    public function set(string $name = null, $value = null) {
        
        try {

            if(is_null($name) || is_null($value)) return null;

            $_SESSION[$name] = $value;

            return true;
        }
        catch(Exception $error) {

            return false;
        }
    }

    /**
     * Returns attributes.
     *
     * @return array
     */
    public function all() {

        return $_SESSION;
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
     * 
     * @return mixed
     */
    public function replace(array $attributes) {

        if(!is_array($attributes) || is_null($attributes)) return false;
        
        $_SESSION = $attributes;

        return true;
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

            return true;
        }

        return null;
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
     * Returns the number of attributes.
     */
    public function count() {

        return count($this->all());
    }

    /**
     * Sometimes you may wish to store items in the session for the next request. You may do so using the flash method.
     * Data stored in the session using this method will be available immediately and during the subsequent HTTP request. 
     * After the subsequent HTTP request, the flashed data will be deleted. Flash data is primarily useful for short-lived status messages
     * 
     * @param string $name
     * @param string $value
     * 
     * @return boolean
     */
    public function flash($name = null, $value = null) {

        if(is_null($name) && is_null($value)) return false;

        return $this->set($name, [
            "type" => "flash",
            "value" => $value
        ]);
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
