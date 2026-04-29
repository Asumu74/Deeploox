<?php

//MÉTODO 1: FUNCIONA BIEN AL 100%.

$EMPRESA_NOMBRE = "DEEP";
$EMPRESA_NOMBRE2 = "LOOK";
$EMPRESA_NOMBRE3 = "DeepLoox";

include "Includes/header.php";

$p = "portada";

if (isset($_GET['vistas'])) {
    $p = $_GET['vistas'];
} else {
}

include "vistas/$p.php";

include "includes/footer.php";
