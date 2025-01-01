<?php
// Simulate API response based on furniture type
if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $cost = 0;

    // Simulate different costs for each type of furniture
    switch ($type) {
        case 'chair':
            $cost = 50.00;
            break;
        case 'table':
            $cost = 100.00;
            break;
        case 'desk':
            $cost = 150.00;
            break;
        case 'couch':
            $cost = 200.00;
            break;
        default:
            $cost = 0;
            break;
    }

    // Return the simulated cost as JSON
    echo json_encode(["cost" => $cost]);
}
?>
