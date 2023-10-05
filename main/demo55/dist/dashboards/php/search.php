<?php
include('\laragon\www\RFIDPLAY\main\conexion.php');
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $sql = $mysqli->query("SELECT *, CONCAT(Nombre1, ' ', Nombre2, ' ', Ape1, ' ', Ape2) AS nombreCompleto
FROM documentos
WHERE Nombre1 LIKE '%$searchTerm%' OR Nombre2 LIKE '%$searchTerm%'
      OR Ape1 LIKE '%$searchTerm%' OR Ape2 LIKE '%$searchTerm%'
      OR numero_documento = '$searchTerm';");

    if ($sql->num_rows != 0) {
        echo '<h3 class="fs-5 text-muted m-0 pb-5" data-kt-search-element="category-title">PERSONAS</h3>';
        while ($datos = $sql->fetch_object()) {
            $nombreCompleto = $datos->nombreCompleto;
            $mail = $datos->numero_documento;
            echo '<a href="#" class="d-flex text-dark text-hover-primary align-items-center mb-5">';
            echo '    <!--begin::Symbol-->';
            echo '    <div class="symbol symbol-40px me-4">';
            if (!empty($datos->documentosfoto)) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($datos->documentosfoto) . '" alt="" />';
            } else {
                echo '<img src="../../demo55/dist/assets/media\avatars\blank.png" alt="Foto por defecto" />';
            }
            echo '    </div>';
            echo '    <!--end::Symbol-->';
            echo '    <!--begin::Title-->';
            echo '    <div class="d-flex flex-column justify-content-start fw-semibold">';
            echo '        <span class="fs-6 fw-semibold">' . $nombreCompleto . '</span>';
            echo '        <span class="fs-7 fw-semibold text-muted">' . $mail . '</span>';
            echo '    </div>';
            echo '    <!--end::Title-->';
            echo '</a>';

        }
    }

    $sqle = $mysqli->query("SELECT * FROM escuelasdefutbol WHERE nombre_escuela LIKE '%$searchTerm%'");
    if ($sqle->num_rows != 0) {
        echo '	<h3 class="fs-5 text-muted m-0 pb-5" data-kt-search-element="category-title">ESCUELAS</h3>';
        while ($datose = $sqle->fetch_object()) {

            echo '<a href="../../demo55/dist/pages/user-profile/followers.php?datosescue='.$datose->id_escuela .'" class="d-flex text-dark text-hover-primary align-items-center mb-5">';

            echo '    <!--begin::Symbol-->';
            echo '    <div class="symbol symbol-40px me-4">';
            if (!empty($datose->fotoes)) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($datose->fotoes) . '" alt="" />';
            } else {
                echo '<img src="../../demo55/dist/assets/media\avatars\blank.png" alt="Foto por defecto" />';
            }
            echo '    </div>';
            echo '    <!--end::Symbol-->';
            echo '    <!--begin::Title-->';
            echo '    <div class="d-flex flex-column justify-content-start fw-semibold">';
            echo '        <span class="fs-6 fw-semibold">' . $datose->nombre_escuela . '</span>';
            echo '        <span class="fs-7 fw-semibold text-muted">' . $datose->ciudad . '</span>';
            echo '    </div>';
            echo '    <!--end::Title-->';
            echo '</a>';

        }

        $sqlei = $mysqli->query("SELECT nombre_equipo,nombre_escuela,e.id_escuela AS NAME FROM equipos
         INNER JOIN rfidplay.escuelasdefutbol e on equipos.id_escuela = e.id_escuela where equipos.nombre_equipo LIKE '%$searchTerm%'");
        if ($sqlei->num_rows != 0) {
            echo '	<h3 class="fs-5 text-muted m-0 pb-5" data-kt-search-element="category-title">EQUIPOS</h3>';
            while ($datosei = $sqlei->fetch_object()) {
                echo '    <div class="d-flex flex-column justify-content-start fw-semibold">';
                echo '        <span class="fs-6 fw-semibold">' . $datosei->nombre_equipo . '</span>';
                echo '        <span class="fs-7 fw-semibold text-muted">' . $datosei->nombre_escuela . '</span>';
                echo '    </div>';
                echo '</a>';
            }
        }
    }
}


?>
<script>
    $(document).ready(function () {
        $(".search-result").click(function () {
            var clickedTerm = $(this).find(".fs-6").text().trim();

            $.ajax({
                url: "../../demo55/dist/dashboards/php/store_search_term.php",
                method: "POST",
                data: {term: clickedTerm},
                success: function (storeResponse) {
                    console.log("Término almacenado en sesión: " + clickedTerm);
                }
            });
        });
    });
</script>
