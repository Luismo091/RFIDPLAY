
<?php
include '\laragon\www\RFIDPLAY\main\conexion.php';
echo '';


$query = "SELECT * FROM documentos 
          INNER JOIN rfidplay.jugadores j ON documentos.id_documento = j.id_documento
          INNER JOIN rfidplay.escuelasdefutbol e ON j.id_escuela = e.id_escuela";

$result = mysqli_query($mysqli, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';

        echo '<td>';
        echo '<div class="d-flex align-items-center">';
        if (!empty($row['documentosfoto'])) {
            echo '<a class="symbol symbol-50px">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($row['documentosfoto']) . '" width="50" height="50" />';
            echo '</a>';
        } else {
            echo '<a class="symbol symbol-50px">';
            echo '<img src="../../demo55/dist/assets/media/avatars/blank.png" width="50" height="50" />';
            echo '</a>';
        }
        echo '<div class="ms-8">';
        echo '<a class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $row['Nombre1'] . ' ' . $row['Nombre2'] . ' ' . $row['Ape1'] . ' ' . $row['Ape2'] . '</a>';
        echo '</div>';
        echo '</div>';
        echo '</td>';
        echo '<td class="text-end pe-0">';
        echo '<span class="fw-bold">' . $row['numero_documento'] . '</span>';
        echo '</td>';
        echo '<td class="text-end pe-0">';
        echo '<span class="fw-bold">' . $row['nombre_escuela'] . '</span>';
        echo '</td>';
        echo '<td class="text-end">';
        echo '<button class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan" data-pdf-url="o">Abrir PDF</button>';
        echo '</td>';
        echo '</tr>';


    }
} else {
    echo '<tr><td colspan="3">No hay documentos disponibles.</td></tr>';
}
?>