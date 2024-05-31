<?php
// Include PuzzleKata class
require_once 'PuzzleKata.php';

// Create PuzzleKata object
$puzzle = new PuzzleKata();

// Check if 'kata' parameter is set in POST request
if(isset($_POST['kata'])) {
    // Get the kata from POST request
    $kata = $_POST['kata'];
    
    // Search for the kata
    $koordinat = $puzzle->cariKata($kata);
    
    // Encode the result as JSON and send it back
    echo json_encode($koordinat);
} else {
    // If 'kata' parameter is not set, send back an empty response
    echo json_encode([]);
}
?>
