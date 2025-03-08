<?php
if (isset($_GET['type'])) {
    $type = $_GET['type'];
    
    $furniture_prices = [
        "plastic_chair" => 30.00,
        "wooden_chair" => 80.00,
        "metal_chair" => 100.00,
        "office_chair" => 120.00,
        "recliner" => 250.00,
        "rocking_chair" => 180.00,
        "folding_chair" => 60.00,
        "gaming_chair" => 300.00,
        "bar_stool" => 90.00,
        "bean_bag" => 70.00,

        "small_table" => 50.00,
        "dining_table" => 400.00,
        "coffee_table" => 150.00,
        "conference_table" => 600.00,
        "glass_table" => 250.00,
        "side_table" => 100.00,
        "folding_table" => 120.00,
        "study_table" => 180.00,
        "workstation_table" => 500.00,
        "outdoor_table" => 220.00,

        "wooden_desk" => 200.00,
        "computer_desk" => 350.00,
        "corner_desk" => 400.00,
        "standing_desk" => 450.00,
        "executive_desk" => 800.00,
        "student_desk" => 250.00,
        "drawer_desk" => 320.00,
        "L_shaped_desk" => 600.00,
        "minimalist_desk" => 280.00,
        "floating_desk" => 220.00,

        "small_couch" => 350.00,
        "two_seater_couch" => 500.00,
        "three_seater_couch" => 750.00,
        "sectional_couch" => 1200.00,
        "sofa_bed" => 900.00,
        "leather_couch" => 1300.00,
        "fabric_couch" => 850.00,
        "modular_couch" => 1400.00,
        "recliner_sofa" => 1100.00,
        "luxury_sofa" => 2000.00,

        "bookcase" => 300.00,
        "file_cabinet" => 200.00,
        "wardrobe" => 1000.00,
        "dresser" => 700.00,
        "tv_stand" => 350.00,
        "shoe_rack" => 150.00,
        "nightstand" => 120.00,
        "storage_bench" => 220.00,
        "bar_cabinet" => 450.00,
        "display_shelf" => 280.00
    ];

    $cost = $furniture_prices[$type] ?? 0;

    echo json_encode(["cost" => $cost]);
}
?>
