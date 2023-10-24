<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'rfidplay';
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die('Error de conexión: ' . mysqli_connect_error());
}
$jsonFile = "data.json";

// Verificar si el archivo JSON existe
if (file_exists($jsonFile)) {
    // Leer el contenido del archivo JSON
    $jsonData = file_get_contents($jsonFile);

    // Decodificar el JSON a un arreglo asociativo
    $data = json_decode($jsonData, true);

    // Verificar si hay datos en el arreglo
    if (!empty($data)) {
        // Obtener el último elemento del arreglo (dato más reciente)
        $latestData = end($data);

        // Obtener el valor del serial del dato más reciente
        $serial = $latestData["serial"];
        $NODEID = $latestData["nodeMCUId"];

        $sql = $conn->query("SELECT * FROM jugadores inner join documentos d on jugadores.id_documento = d.id_documento
               inner join rfidplay.escuelasdefutbol e on jugadores.id_escuela = e.id_escuela WHERE rfidcpde = '$serial'");

        if ($datos = $sql->fetch_object()) {
            $jsonFile = "data.json";

            // Verificar si el archivo JSON existe
            if (file_exists($jsonFile)) {
                // Leer el contenido del archivo JSON
                $jsonData = file_get_contents($jsonFile);

                // Decodificar el JSON a un arreglo asociativo
                $data = json_decode($jsonData, true);

                // Verificar si hay datos en el arreglo
                if (!empty($data)) {
                    // Obtener el último elemento del arreglo (dato más reciente)
                    $latestData = end($data);

                    // Limpiar el arreglo y dejar solo el dato más reciente
                    $data = array($latestData);

                    // Convertir el arreglo a formato JSON
                    $newJsonData = json_encode($data, JSON_PRETTY_PRINT);

                    // Guardar los datos en el archivo JSON
                    file_put_contents($jsonFile, $newJsonData);
                } else {
                }
            } else {
            }

?>

            <div class="card-body pt-9 pb-0 ">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap">
                    <!--begin: Pic-->

                    <div class="me-7 mb-4">
                        <div class="symbol symbol-130px symbol-lg-160px symbol-fixed position-relative">
                            <img src="data:image/jpg;base64,<?php echo base64_encode($datos->documentosfoto) ?>" alt="image" style="width: 332; height: 498;" />

                            <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">

                            </div>
                        </div>
                    </div>
                    <!--end::Pic-->
                    <!--begin::Info-->
                    <div class="flex-grow-1">
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->
                                <div class="d-flex align-items-center mb-2">
                                    <a class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"><?php echo $datos->Nombre1 ?> <?php echo $datos->Nombre2 ?> <?php echo $datos->Ape1 ?> <?php echo $datos->Ape2 ?></a>
                                    <a>
                                        <i class="ki-outline ki-verify fs-1 text-primary"></i>
                                    </a>
                                </div>
                                <!--end::Name-->
                                <!--begin::Info-->
                                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                    <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                        <img src="data:image/jpg;base64,<?php echo base64_encode($datos->fotoes) ?> " width="50px" />
                                        <i class="ki-outline ki-profile-circle fs-4 me-1"></i><?php echo $datos->nombre_escuela ?></a>
                                    <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                        <img src="data:image/jpg;base64,<?php echo base64_encode($datos->fotoes) ?> " width="50px" />
                                        <i class="ki-outline ki-geolocation fs-4 me-1"></i><?php echo $datos->nombre_escuela ?>, <?php echo $datos->ciudad ?></a>
                                    <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                        <i class="ki-outline ki-sms fs-4 me-1"></i><?php echo $datos->numero_documento ?></a>
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::User-->
                            <!--begin::Actions-->

                            <!--end::Actions-->
                        </div>
                        <!--end::Title-->
                        <!--begin::Stats-->
                        <div class="d-flex flex-wrap flex-stack">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap">
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-arrow-up fs-3 text-success me-2"></i>
                                            <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="4500" data-kt-countup-prefix="$">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400">Goles</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-arrow-down fs-3 text-danger me-2"></i>
                                            <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="80">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400">Faltas</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-arrow-up fs-3 text-success me-2"></i>
                                            <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="60" data-kt-countup-prefix="%">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400"> Tasa de Participación</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Wrapper-->
                            <!--begin::Progress-->
                            <div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-4">
                                <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                    <span class="fw-semibold fs-6 text-gray-400"><?php echo $datos->rfidcpde ?></span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-4">
                                <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                    <span class="fw-semibold fs-6 text-gray-400">Sensor RPLAY Trusted <?php echo $NODEID ?></span>
                                </div>
                            </div>
                            <!--end::Progress-->
                        </div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Details-->
                <!--begin::Navs-->

                <!--begin::Navs-->
            </div>
            
<?php
        } else {
            echo "INTENTA CONECTAR EL SENSOR RPLAY";
        }
    } else {
        echo "No hay datos en el JSON.";
    }
} else {
    echo "El archivo JSON no existe.";
}
?>