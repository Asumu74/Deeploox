
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $EMPRESA_NOMBRE3 ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="js/main.js">
    <link rel="stylesheet" href="img/2X3.jpg">
    <link rel="icon" type="image/x-icon" href="img/2X3.jpg" style="border-radius: 4%;">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
</head>
<!-- Header y Navegación -->
<header>
    <div class="container">
        <nav>
            <span href="index.php" class="logo"><?= $EMPRESA_NOMBRE ?><span><?= $EMPRESA_NOMBRE2 ?></span></span>

            <div class="nav-links">
                <a href="index.php?vistas=portada" 
                    class="<?php echo (!isset($_GET['vistas']) || $_GET['vistas'] == 'portada') ? 'active' : ''; ?>">
                    Inicio
                </a>
                <a href="index.php?vistas=servicios" 
                    class="<?php echo (isset($_GET['vistas']) && $_GET['vistas'] == 'servicios') ? 'active' : ''; ?>">
                    Servicios
                </a>
                <a href="index.php?vistas=proyectos" 
                    class="<?php echo (isset($_GET['vistas']) && $_GET['vistas'] == 'proyectos') ? 'active' : ''; ?>">
                    Proyectos
                </a>
                <a href="index.php?vistas=contacto1" 
                    class="<?php echo (isset($_GET['vistas']) && $_GET['vistas'] == 'contacto1') ? 'active' : ''; ?>">
                    Contacto
                </a>
            </div>

            <div class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </div>
</header>
<a class="whatsapp" href="https://wa.me/+240555011022" target="_blank">💬</a>