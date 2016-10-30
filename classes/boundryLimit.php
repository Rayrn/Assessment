<?php
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

class BoundryLimit
{
    public $id;
    public $boundry_id;
    public $year_id;
    public $lower_limit;
    public $upper_limit;
    public $status;
    public $statusDesc;
    public $createBy;

    /**
     * Create a new BoundryLimit object
     * @return void
     */
    public function __construct($boundry_id, $year_id, $lower_limit, $upper_limit, $status, $id = '', $create_by = '') {
        $this->boundry_id = $boundry_id;
        $this->year_id = $year_id;
        $this->lower_limit = $lower_limit;
        $this->upper_limit = $upper_limit;
        $this->status = $status;

        // Optional
        $this->id = $id;
        $this->createBy = $create_by;

        // Derived
        $this->statusDesc = $this->getStatus($status);

        // Return Boundry object
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
     * Get the cards details (array)
     * @return array representation of the Boundry object
     */
    public function toArray() {
        return array(
            'id' => $this->id,
            'grouping' => $this->grouping,
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
            case 'lower_limit':
                $this->lower_limit = $val;
                break;
            case 'upper_limit':
                $this->upper_limit = $val;
                break;
        }
    }
}