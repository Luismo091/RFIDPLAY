<?php
// Include your database connection
include '\laragon\www\RFIDPLAY\main\conexion.php';

// Fetch the PDF data from the database
$query = "SELECT docfile FROM documentos WHERE id_documento = '1'";
$result = mysqli_query($mysqli, $query);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $pdfData = $row['docfile'];

        // Set appropriate headers for PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="documento.pdf"');
        header('Content-Length: ' . strlen($pdfData));

        // Output the PDF data
        echo $pdfData;
    } else {
        echo "No PDF data found in the database.";
    }
} else {
    echo "Error querying the database: " . mysqli_error($mysqli);
}
?>