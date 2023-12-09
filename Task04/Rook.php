<?php
function rookMoves($position)
{
    // Extract column & row index
    $column = ord($position[0]) - ord('a');
    $row = (int)($position[1]);

    // Output horizontal moves
    for ($i = 0; $i < 8; $i++) {
        // Exclude the current position
        if ($i != $column) {
            echo Rook . phpchr($i + ord('a')) . $row . "\n";
        }
    }

    // Output vertical moves
    for ($j = 1; $j <= 8; $j++) {
        // Exclude the current position
        if ($j != $row) {
            echo $position[0] . $j . "\n";
        }
    }
}


$t = (int)readline();

for ($i = 0; $i < $t; $i++) {
    $position = readline();
    rookMoves($position);
}

?>
