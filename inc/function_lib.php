<?php
/* General Purpose Function Library */
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

function get_input_row_full($criteriaSet, $yearSet) {
    // Instantiate
    $criteria_string = '';
    $year_options = '';

    // Build criteria select menu's as one block
    foreach($criteriaSet as $criteria) {
        $criteria_string .= "
            <td>
                <select class='form-control' name='{$criteria->id}[]'>
                    <option value='L'>Low</option>
                    <option value='M'>Medium</option>
                    <option value='H'>High</option>
                </select>
            </td>";
    }

    // Build year options
    foreach($yearSet as $year) {
        $year_options .= "<option value='{$year->id}'>{$year->title}</option>";
    }

    // Add into structure
    $row_string = "
        <tr>
            <td>
                <input type='text' class='form-control' name='name[]'/>
            </td>

            $criteria_string

            <td>
                <select class='form-control' name='year[]'>
                    $year_options
                </select>
            </td>
        </tr>";

    // Return output
    return $row_string;
}

function get_input_row_mobile($criteriaSet, $yearSet) {
    // Instantiate
    $criteria_string = '';
    $year_options = '';

    // Build criteria select menu's as one block
    foreach($criteriaSet as $criteria) {
        $criteria_string .= "
            <div class='form-group'>
                <label for='Name' class='cols-xs-2 control-label'>{$criteria->title}</label>
                <div class='cols-xs-10'>
                    <select class='form-control' name='{$criteria->id}[]'>
                        <option value='L'>Low</option>
                        <option value='M'>Medium</option>
                        <option value='H'>High</option>
                    </select>
                </div>
            </div>";
    }

    // Build year options
    foreach($yearSet as $year) {
        $year_options .= "<option value='{$year->id}'>{$year->title}</option>";
    }

    // Add into structure
    $row_string = "
        <tr>
            <td>
                <div class='form-group'>
                    <label for='Name' class='cols-xs-2 control-label'>Name</label>
                    <div class='cols-xs-10'>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='fa fa-user fa' aria-hidden='true'></i></span>
                            <input type='text' class='form-control' name='name[]'/>
                        </div>
                    </div>
                </div>

                $criteria_string

                <div class='form-group'>
                    <label for='Year' class='cols-xs-2 control-label'>Year</label>
                    <div class='cols-xs-10'>
                        <select class='form-control' name='year[]'>
                            $year_options
                        </select>
                    </div>
                </div>
            </td>
        </tr>";

    // Return output
    return $row_string;                
}

function processChild(User $user, Array $child) {
    // Fetch SQL Connection
    global $pdo;

    // Setup Factories
    $boundryFactory = new BoundryFactory($pdo);
    $criteriaFactory = new CriteriaFactory($pdo);
    $yearFactory = new YearFactory($pdo);
    $boundryLimitFactory = new BoundryLimitFactory($pdo);

    // Fetch Supporting Data
    $criteriaSet = $criteriaFactory->getCriteriaByUser($user);

    // Calculate Grouping Data
    $grouping = 0;
    foreach($criteriaSet as $criteria) {
        switch ($child[$criteria->title]) {
            case 'L': $grouping += 1; break;
            case 'M': $grouping += 2; break;
            case 'H': $grouping += 3; break;
            default: $grouping += 0; break;
        }
    }

    // Fetch Data
    $child['year'] = $yearFactory->getYear($child['Year']);
    $child['boundry'] = $boundryFactory->getBoundryByGrouping($user, $grouping);
    $child['limit'] = $boundryLimitFactory->getBoundryLimitByAll($child['boundry'], $child['year'], $user);

    return $child;
}