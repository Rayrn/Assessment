<?php
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

class Boundry
{
    public $id;
    public $grouping;
    public $groupingSets;
    public $title;
    public $status;
    public $statusDesc;
    public $createBy;

    /**
     * Create a new Boundry object
     * @return void
     */
    public function __construct($grouping, $title, $status, $id = '', $createBy = '') {
        $this->grouping = $grouping;
        $this->title = $title;
        $this->status = $status;

        // Optional
        $this->id = $id;
        $this->createBy = $createBy;

        // Derived
        $this->statusDesc = $this->getStatus($status);
        $this->groupingSets = $this->getGroupingSets($grouping);

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
     * Get status details
     * @return string text representation status code
     */
    private function getGroupingSets($grouping) {
        $groupingSets = array();
        switch ($grouping) {
            case '4':
                $groupingSets[] = array('L', 'L', 'L', 'L');
                break;
            case '5':
                $groupingSets[] = array('M', 'L', 'L', 'L');
                break;
            case '6':
                $groupingSets[] = array('H', 'L', 'L', 'L');
                $groupingSets[] = array('M', 'M', 'L', 'L');
                break;
            case '7':
                $groupingSets[] = array('H', 'M', 'L', 'L');
                $groupingSets[] = array('M', 'M', 'M', 'L');
                break;
            case '8':
                $groupingSets[] = array('H', 'H', 'L', 'L');
                $groupingSets[] = array('H', 'M', 'M', 'L');
                $groupingSets[] = array('M', 'M', 'M', 'M');
                break;
            case '9':
                $groupingSets[] = array('H', 'H', 'M', 'L');
                $groupingSets[] = array('H', 'M', 'M', 'M');
                break;
            case '10':
                $groupingSets[] = array('H', 'H', 'H', 'L');
                $groupingSets[] = array('H', 'H', 'M', 'M');
                break;
            case '11':
                $groupingSets[] = array('H', 'H', 'H', 'M');
                break;
            case '12':
                $groupingSets[] = array('H', 'H', 'H', 'H');
                break;
            default: $statusDesc = 'Unknown'; break;
        }

        return $groupingSets;
    }

    /**
     * Get the Boundry details (string)
     * @return string text representation of the User object
     */
    public function toString() {
        return $this->id . '//' . $this->grouping . '//' . $this->title . '//' . $this->status . '//' . $createBy;
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