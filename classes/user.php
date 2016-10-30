<?php
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

class User
{
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $status;

    /**
     * Create a new User object
     * @return void
     */
    public function __construct($first_name, $last_name, $email, $status, $id = '', $password = '') {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->status = $status;

        // Optional
        $this->id = $id;
        $this->password = $password;

        // Return User object
        return $this;
    }

    /**
     * Get the User details (string)
     * @return string text representation of the User object
     */
    public function toString() {
        return $this->id . '//' . $this->fist_name . '//'. $this->last_name . '//' . $this->email . '//' . $this->status;
    }

    /**
     * Get the cards details (array)
     * @return array representation of the User object
     */
    public function toArray() {
        return array(
            'id' => $this->id,
            'fist_name' => $this->fist_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'status' => $this->status
        );
    }
}