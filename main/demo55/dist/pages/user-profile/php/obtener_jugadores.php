<?php

session_start();
include('\laragon\www\RFIDPLAY\main\conexion.php');


$paginaActual = isset($_GET['page']) ? $_GET['page'] : 1;


$sql = "SELECT * FROM jugadores inner join rfidplay.documentos d on jugadores.id_documento = d.id_documento WHERE id_escuela = '1'";
$resultado = $mysqli->query($sql);

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        echo '<div class="col-md-6 col-xxl-4">';
        echo '<!--begin::Card-->';
        echo '<div class="card">';
        echo '<!--begin::Card body-->';
        echo '<div class="card-body d-flex flex-center flex-column py-9 px-5">';
        echo '<!--begin::Avatar-->';
        echo '<div class="symbol symbol-65px symbol-circle mb-5">';
        echo '<img src="assets/media//avatars/300-11.jpg" alt="image" />';
        echo '<div class="bg-success position-absolute rounded-circle translate-middle start-100 top-100 border border-4 border-body h-15px w-15px ms-n3 mt-n3"></div>';
        echo '</div>';
        echo '<!--end::Avatar-->';
        echo '<!--begin::Name-->';
        echo '<a href="#" class="fs-4 text-gray-800 text-hover-primary fw-bold mb-0">'.$row->Nombre1.'</a>';
        echo '<!--end::Name-->';
        echo '<!--begin::Position-->';
        echo '<div class="fw-semibold text-gray-400 mb-6">Equipos a los que pertenece</div>';
        echo '<!--end::Position-->';
        echo '<!--begin::Info-->';
        echo '<div class="d-flex flex-center flex-wrap mb-5">';
        echo '<!--begin::Stats-->';
        echo '<div class="border border-dashed rounded min-w-90px py-3 px-4 mx-2 mb-3">';
        echo '<div class="fs-6 fw-bold text-gray-700">32</div>';
        echo '<div class="fw-semibold text-gray-400">Goles</div>';
        echo '</div>';
        echo '<!--end::Stats-->';
        echo '<!--begin::Stats-->';
        echo '<div class="border border-dashed rounded min-w-90px py-3 px-4 mx-2 mb-3">';
        echo '<div class="fs-6 fw-bold text-gray-700">1</div>';
        echo '<div class="fw-semibold text-gray-400">Faltas</div>';
        echo '</div>';
        echo '<!--end::Stats-->';
        echo '</div>';
        echo '<!--end::Info-->';
        echo '<!--begin::Follow-->';
        echo '<button class="btn btn-sm btn-light-primary btn-flex btn-center" data-kt-follow-btn="true">';
        echo '<i class="ki-outline ki-check following fs-3"></i>';
        echo '<i class="ki-outline ki-plus follow fs-3 d-none"></i>';
        echo '<!--begin::Indicator label-->';
        echo '<span class="indicator-label">Seguir</span>';
        echo '<!--end::Indicator label-->';
        echo '<!--begin::Indicator progress-->';
        echo '<span class="indicator-progress">Añadiendo...';
        echo '<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>';
        echo '<!--end::Indicator progress-->';
        echo '</button>';
        echo '<!--end::Follow-->';
        echo '</div>';
        echo '<!--begin::Card body-->';
        echo '</div>';
        echo '<!--begin::Card-->';
        echo '</div>';
    }
} else {
    // No hay más jugadores para mostrar
    echo 'No hay más jugadores disponibles.';
}

$mysqli->close();
?>