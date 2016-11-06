<?php
/* General Purpose Function Library */
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

/**
 * Get the input row for desktop/tablet
 * @param Array $criteriaSet[] Criteria the current user has setup
 * @param Array $yearSet[] Years the current user has setup
 * @return string output row
 */
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

/**
 * Get the input row for mobile
 * @param Array $criteriaSet[] Criteria the current user has setup
 * @param Array $yearSet[] Years the current user has setup
 * @return string output row
 */
function get_input_row_mobile($criteriaSet, $yearSet) {
    // Instantiate
    $criteria_string = '';
    $year_options = '';

    // Build criteria select menu's as one block
    foreach($criteriaSet as $criteria) {
        $criteria_string .= "
            <div class='form-group'>
                <label for='Name' class='control-label'>{$criteria->title}</label>
                <select class='form-control' name='{$criteria->id}[]'>
                    <option value='L'>Low</option>
                    <option value='M'>Medium</option>
                    <option value='H'>High</option>
                </select>
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
                    <label for='Name' class='control-label'>Name</label>
                    <div class='input-group'>
                        <span class='input-group-addon'><i class='fa fa-user fa' aria-hidden='true'></i></span>
                        <input type='text' class='form-control' name='name[]'/>
                    </div>
                </div>

                $criteria_string

                <div class='form-group'>
                    <label for='Year' class='control-label'>Year</label>
                    <select class='form-control' name='year[]'>
                        $year_options
                    </select>
                </div>
            </td>
        </tr>";

    // Return output
    return $row_string;                
}

/**
 * Process the child and figure out the boundries
 * @param User $user Current active user
 * @param Array $child Data for the child we need to process
 * @return Array $child
 */
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
        switch ($child[strtolower($criteria->title)]) {
            case 'L': $grouping += 1; break;
            case 'M': $grouping += 2; break;
            case 'H': $grouping += 3; break;
            default: $grouping += 0; break;
        }
    }

    // Fetch Data
    $child['year'] = $yearFactory->getYear($child['year']);
    $child['boundry'] = $boundryFactory->getBoundryByGrouping($user, $grouping);
    $child['limit'] = $boundryLimitFactory->getBoundryLimitByAll($child['boundry'], $child['year'], $user);

    return $child;
}

/**
 * Get the output row for desktop/tablet
 * @param Array $criteriaSet[] Criteria the current user has setup
 * @param Array $child see processChild()
 * @return string output row
 */
function get_output_row_full($criteriaSet, $child) {
    // Instantiate
    $criteria_string = '';

    // Build criteria select menu's as one block
    foreach($criteriaSet as $criteria) {
        $criteria_string .= $child[strtolower($criteria->title)].' ';
    }

    // Add into structure
    $row_string = "
        <tr>
            <td>".htmlentities($child['name'])."</td>
            <td class='boundry-grouping'>{$criteria_string}</td>
            <td>{$child['year']->title}</td>
            <td>{$child['boundry']->title}</td>
            <td>{$child['limit']->lower_limit}</td>
            <td>{$child['limit']->upper_limit}</td>
        </tr>";

    // Return output
    return $row_string;
}

/**
 * Get the output row for mobile
 * @param Array $criteriaSet[] Criteria the current user has setup
 * @param Array $child see processChild()
 * @return string output row
 */
function get_output_row_mobile($criteriaSet, $child) {
    // Instantiate
    $criteria_string = '';

    // Build criteria select menu's as one block
    foreach($criteriaSet as $criteria) {
        $criteria_string .= $child[strtolower($criteria->title)].' ';
    }

    // Add into structure
    $row_string = "
        <tr>
            <td>
                <div class='form-group'>
                    <label class='control-label'>Name</label><br/>
                    <p class='form-control'>".htmlentities($child['name'])."</p>
                </div>

                <div class='form-group'>
                    <label class='control-label'>Results</label><br/>
                    <p class='form-control'>{$criteria_string}</p>
                </div>

                <div class='form-group'>
                    <label class='control-label'>Year</label><br/>
                    <p class='form-control'>{$child['year']->title}</p>
                </div>

                <div class='form-group'>
                    <label class='control-label'>Boundry</label><br/>
                    <p class='form-control'>{$child['boundry']->title}</p>
                </div>

                <div class='form-group'>
                    <label class='control-label'>Lower limit</label><br/>
                    <p class='form-control'>{$child['limit']->lower_limit}</p>
                </div>

                <div class='form-group'>
                    <label class='control-label'>Upper limit</label><br/>
                    <p class='form-control'>{$child['limit']->upper_limit}</p>
                </div>
            </td>
        </tr>";

    // Return output
    return $row_string;
}

/**
 * Download a CSV from the child data
 *  http://code.stephenmorley.org/php/creating-downloadable-csv-files/
 * @param Array $criteriaSet[] Criteria the current user has setup
 * @param Array $children[] Array of children see processChild()
 * @return void
 */
function downloadChildren($criteriaSet, $children) {
    // Create the header data
    $headers = array();
    $headers[] = 'Name';
    foreach($criteriaSet as $criteria) {
        $headers[] = $criteria->title;
    }
    $headers[] = 'Year';
    $headers[] = 'Boundry';
    $headers[] = 'Lower_Limit';
    $headers[] = 'Upper_Limit';

    // Reformat the child data
    $rows = array();
    foreach($children as $child) {
        $row = array();
        $row['Name'] = $child['name'];
        foreach($criteriaSet as $criteria) {
            $row[$criteria->title] = $child[strtolower($criteria->title)];
        }
        $row['Year'] = $child['year']->title;
        $row['Boundry'] = $child['boundry']->title;
        $row['Lower_Limit'] = $child['limit']->lower_limit;
        $row['Upper_Limit'] = $child['limit']->upper_limit;

        $rows[] = $row;
    }

    // output headers so that the file is downloaded rather than displayed
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=children.csv');

    // create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // output the column headings
    fputcsv($output, $headers);

    // loop over the rows, outputting them
    foreach($rows as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
}

/**
 * Process a CSV filled with child data
 * @param $_FILES['XYZ'] $csvInput[] CSV File (with header row)
 * @return Array $CSV
 */
function processCSV($csvInput) {
    // Init
    $csv = array();

    // Open file
    $handle = fopen($csvInput['tmp_name'], "r");

    // Process file  
    while($data = fgetcsv($handle)) {
        $csv[] = $data;
    }

    // Close file
    fclose($handle);

    // Seperate out header row
    $headers = array_map('strtolower', array_shift($csv));
    
    // Convert to associative array
    foreach($csv as $index=>$row) {
        $csv[$index] = array_combine($headers, $row);
    }

    return $csv;
}