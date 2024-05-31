<?php
class PuzzleKata
{
    private $papan = [
        ['J','Y','B','Y','Q','V','W','G','B','Q','F','D','D','U','L','H','M'],
        ['D','G','O','C','G','V','N','K','Z','C','R','U','B','A','O','I','K'],
        ['U','G','E','U','H','E','W','J','W','T','O','Y','N','U','M','N','V'],
        ['N','H','U','R','D','B','R','J','I','V','U','I','T','P','G','H','W'],
        ['I','H','H','O','O','I','E','M','Z','W','T','J','M','D','T','S','V'],
        ['T','M','V','O','I','P','O','U','A','N','B','E','D','X','T','W','X'],
        ['E','Z','H','B','B','Q','A','U','E','N','W','C','W','C','B','O','N'],
        ['D','L','U','S','A','D','F','G','N','R','Y','Y','G','W','W','S','R'],
        ['K','H','Y','I','I','R','R','M','N','I','P','H','A','B','R','W','P'],
        ['I','T','Q','M','S','A','H','I','M','I','R','U','N','Z','Y','H','S'],
        ['N','E','T','H','E','R','L','A','N','D','S','H','U','N','K','E','Z'],
        ['G','F','K','L','N','L','A','G','U','T','R','O','P','B','I','U','E'],
        ['D','C','P','G','O','G','G','A','R','U','P','U','E','O','P','K','M'],
        ['O','J','B','T','D','N','T','A','X','O','B','X','Z','M','J','C','C'],
        ['M','J','F','P','N','S','L','L','X','B','V','C','Y','W','T','K','E'],
        ['R','X','I','S','I','I','Z','W','A','M','K','S','L','N','H','V','S'],
        ['A','O','J','O','A','E','G','T','X','M','C','Z','P','C','I','O','U']
    ];

    public function getPapan()
    {
        return $this->papan;
    }

    public function cariKata($kata)
    {
        $koordinat = [];
        $kata = strtoupper($kata);
        for ($i = 0; $i < count($this->papan); $i++) {
            for ($j = 0; $j < count($this->papan[0]); $j++) {
                if ($this->papan[$i][$j] == $kata[0]) {
                    foreach ([[0, 1], [1, 0], [1, 1], [1, -1], [0, -1], [-1, -1], [-1, 0], [-1, 1]] as $dir) {
                        $dx = $dir[0];
                        $dy = $dir[1];
                        if ($this->checkWord($kata, $i, $j, $dx, $dy, 0)) {
                            $koordinat[] = [$i, $j];
                            $this->highlightWord($kata, $i, $j, $dx, $dy, $koordinat);
                            break;
                        }
                    }
                }
            }
        }
        return $koordinat;
    }

    private function checkWord($kata, $row, $col, $dx, $dy, $index)
    {
        if ($index == strlen($kata)) {
            return true; // Word found
        }

        if ($row < 0 || $row >= count($this->papan) || $col < 0 || $col >= count($this->papan[0])) {
            return false; // Out of bounds
        }

        if ($this->papan[$row][$col] != $kata[$index]) {
            return false; // Mismatched character
        }

        // Check in the current direction
        return $this->checkWord($kata, $row + $dx, $col + $dy, $dx, $dy, $index + 1);
    }

    private function highlightWord($kata, $row, $col, $dx, $dy, &$koordinat)
    {
        $length = strlen($kata);
        for ($i = 0; $i < $length; $i++) {
            $koordinat[] = [$row, $col];
            $row += $dx;
            $col += $dy;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puzzle Kata</title>
    <style>
        table {
            border-collapse: collapse;
        }
        td {
            width: 30px;
            height: 30px;
            text-align: center;
            border: 1px solid #000;
            position: relative;
        }
        .highlight {
            background-color: red;
            color: white;
        }
        .animation {
            animation: pulse 0.5s infinite alternate;
        }
        @keyframes pulse {
            from {
                background-color: yellow;
            }
            to {
                background-color: white;
            }
        }
        .line {
            position: absolute;
            z-index: -1;
            border: 2px solid red;
            pointer-events: none;
        }
    </style>
        <!-- Tambahkan link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tambahkan link AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <!-- Tambahkan link DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="checkbox.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <!-- Sertakan CSS Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
    <div class="container">
        <br><br>
        <fieldset>
            <h1>Puzzle Kata</h1>
            <form id="form-cari" method="post">
                <label for="kata">Cari Kata:</label>
                <input type="text" id="kata" name="kata">
                <input type="submit" value="Cari">
                <button id="random-search">Pencarian Acak</button>
            </form>
        </fieldset><br>
        <?php 
            // Create PuzzleKata object
            $puzzle = new PuzzleKata();

            // Check if 'kata' parameter is set in POST request
            if (isset($_POST['kata'])) {
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
        <table id="puzzle" class="display table table-bordered table-striped table-hover responsive nowrap" style="width:100%">
            <?php
            // Render the table
            $puzzleData = $puzzle->getPapan();
            $koordinat = isset($_POST['kata']) ? $puzzle->cariKata($_POST['kata']) : [];
            for ($i = 0; $i < count($puzzleData); $i++) {
                echo '<tr>';
                for ($j = 0; $j < count($puzzleData[0]); $j++) {
                    $highlight = '';
                    foreach ($koordinat as $coord) {
                        if ($coord[0] == $i && $coord[1] == $j) {
                            $highlight = 'highlight';
                            break;
                        }
                    }
                    echo "<td class='$highlight'>" . $puzzleData[$i][$j] . "</td>";
                }
                echo '</tr>';
            }
            ?>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#random-search').click(function(e){
                e.preventDefault();
                var randomWords = ['ARGENTINA', 'ITALY', 'GERMANY', 'NETHERLANDS', 'UNITEDKINGDOM', 'EROPA', 'PORTUGAL', 'AUSTRALIA','INDONESIA'];
                var randomIndex = Math.floor(Math.random() * randomWords.length);
                var randomWord = randomWords[randomIndex];
                $('#kata').val(randomWord);
                $('#form-cari').submit();
            });
        });
    </script>
</body>
</html>