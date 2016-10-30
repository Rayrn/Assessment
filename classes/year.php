<?php
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

class Year
{
    public $id;
    public $title;
    public $status;
    public $statusDesc;
    public $createBy;

    /**
     * Create a new Year object
     * @return void
     */
    public function __construct($title, $status, $id = '', $createBy = '') {
        $this->title = $title;
        $this->status = $status;

        // Optional
        $this->id = $id;
        $this->createBy = $createBy;

        // Derived
        $this->statusDesc = $this->getStatus($status);

        // Return Year object
        return $this;
    }

    /**
     * Get status details
     * @return string text representation status code
     */
    private function getStatus($status) {
        $statusDesc = '';
        switch ($status) {
            case '2': $statusDesc = 'Active'; break;
            case '3': $statusDesc = 'Suspended'; break;
            case '7': $statusDesc = 'Deleted'; break;
            default: $statusDesc = 'Unknown'; break;
        }

        return $statusDesc;
    }

    /**
     * Get the Year details (string)
     * @return string text representation of the User object
     */
    public function toString() {
        return $this->id . '//' . $this->title . '//' . $this->status . '//' . $createBy;
    }

    /**
     * Get the cards details (array)
     * @return array representation of the Year object
     */
    public function toArray() {
        return array(
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'createBy' => $this->createBy
        );
    }

    /**
     * Set a specific property to a specific value
     * @param string $prop 
     * @param Unknown $val 
     * @return void
     */
    public function set($prop, $val) {
        switch ($prop) {
            case 'title':
                $this->title = $val;
                break;
            case 'status':
                $this->status = $val;
                $this->statusDesc = $this->getStatus->$val;
                break;
        }
    }
}