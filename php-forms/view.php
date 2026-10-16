<?php
function e($v) { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
$file = __DIR__ . "/data/registrations.csv";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Saved Records</title>
    <style>
        body { font-family: Arial, sans-serif; padding:20px; }
        table { border-collapse:collapse; width:100%; }
        th, td { border:1px solid #999; padding:8px; text-align:left; }
        th { background:#2c3e50; color:#fff; }
    </style>
</head>
<body>
<h2>Registered Students</h2>
<?php if (!file_exists($file)): ?>
    <p>No records yet.</p>
<?php else: ?>
    <table>
    <?php
    $handle = fopen($file, "r");
    $row = 0;
    while (($data = fgetcsv($handle, 1000, ",", '"', "")) !== false) {
        echo "<tr>";
        foreach ($data as $cell) {
            echo ($row === 0 ? "<th>" : "<td>") . e($cell) . ($row === 0 ? "</th>" : "</td>");
        }
        echo "</tr>";
        $row++;
    }
    fclose($handle);
    ?>
    </table>
<?php endif; ?>
<p><a href="register.php">Back to form</a></p>
</body>
</html>