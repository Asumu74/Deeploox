<?php
// enviar-correo.php
header('Content-Type: application/json; charset=utf-8');

// CORREO DESTINO ACTUALIZADO
$destinatario = "ritamangue23@gmail.com";

// Verificación de seguridad
if (!isset($_POST['website']) || $_POST['website'] !== 'https://deeplooxsl.com') {
    echo json_encode([
        'success' => false, 
        'message' => 'Error de seguridad en el formulario.'
    ]);
    exit;
}

// Verificar timestamp (evitar envíos muy rápidos)
if (isset($_POST['timestamp'])) {
    $timestamp = (int)$_POST['timestamp'];
    $current_time = time();
    $diferencia = $current_time - $timestamp;
    
    // Si el formulario fue enviado en menos de 3 segundos, puede ser bot
    if ($diferencia < 3) {
        echo json_encode([
            'success' => false, 
            'message' => 'Por favor, espere unos segundos antes de enviar.'
        ]);
        exit;
    }
}

// Recibir y sanitizar datos
function limpiarTexto($texto) {
    $texto = trim($texto);
    $texto = stripslashes($texto);
    $texto = htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
    return $texto;
}

$nombre = limpiarTexto($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$telefono = limpiarTexto($_POST['phone'] ?? 'No proporcionado');
$servicio = limpiarTexto($_POST['service'] ?? '');
$mensaje = limpiarTexto($_POST['message'] ?? '');

// Validaciones
$errores = [];

if (empty($nombre) || strlen($nombre) < 2) {
    $errores[] = "El nombre debe tener al menos 2 caracteres.";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "Por favor ingrese un email válido.";
}

if (empty($servicio) || $servicio === '') {
    $errores[] = "Debe seleccionar un servicio de interés.";
}

if (empty($mensaje) || strlen($mensaje) < 10) {
    $errores[] = "El mensaje debe tener al menos 10 caracteres.";
}

// Si hay errores, mostrarlos
if (!empty($errores)) {
    echo json_encode([
        'success' => false, 
        'message' => implode('<br>', $errores)
    ]);
    exit;
}

// Mapear servicios a nombres legibles
$servicios_nombres = [
    'construccion' => 'Construcción de edificios',
    'rehabilitacion' => 'Rehabilitación integral',
    'mantenimiento' => 'Mantenimiento de edificios',
    'reformas' => 'Reformas interiores',
    'presupuesto' => 'Solicitud de presupuesto',
    'otros' => 'Otros servicios'
];

$servicio_texto = $servicios_nombres[$servicio] ?? 'Servicio no especificado';

// Información adicional
$fecha_envio = date('d/m/Y H:i:s');
$ip_cliente = $_SERVER['REMOTE_ADDR'] ?? 'No disponible';
$navegador = $_SERVER['HTTP_USER_AGENT'] ?? 'No disponible';
$dominio = $_SERVER['HTTP_HOST'] ?? 'deeplooxsl.com';

// Asunto del correo
$asunto = "📨 Nuevo Contacto Web - " . $nombre . " - " . date('d/m/Y');

// Cuerpo del mensaje en HTML
$cuerpoHTML = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container { 
            max-width: 700px; 
            margin: 0 auto; 
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header { 
            background: linear-gradient(135deg, #007bff, #0056b3); 
            color: white; 
            padding: 30px 20px; 
            text-align: center; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 28px;
            font-weight: 600;
        }
        .header p { 
            margin: 10px 0 0; 
            opacity: 0.9;
            font-size: 16px;
        }
        .content { 
            padding: 30px; 
        }
        .section-title {
            color: #007bff;
            font-size: 20px;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }
        .field { 
            margin-bottom: 20px; 
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .label { 
            font-weight: bold; 
            color: #0056b3; 
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .value {
            font-size: 16px;
            color: #333;
        }
        .message-box {
            background-color: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            white-space: pre-wrap;
            font-size: 16px;
            line-height: 1.5;
        }
        .footer { 
            background-color: #2c3e50; 
            color: #ecf0f1; 
            padding: 20px; 
            text-align: center; 
            font-size: 14px;
        }
        .footer a { 
            color: #3498db; 
            text-decoration: none;
        }
        .footer a:hover { 
            text-decoration: underline;
        }
        .info-note {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>📬 Nuevo Mensaje de Contacto</h1>
            <p>Formulario Web - Deeploox SL</p>
        </div>
        
        <div class='content'>
            <div class='section-title'>📋 Información del Cliente</div>
            
            <div class='field'>
                <span class='label'>👤 Nombre Completo:</span>
                <div class='value'>$nombre</div>
            </div>
            
            <div class='field'>
                <span class='label'>📧 Email de Contacto:</span>
                <div class='value'>
                    <a href="mailto:$email" style="color: #007bff; text-decoration: none;">$email</a>
                </div>
            </div>
            
            <div class='field'>
                <span class='label'>📞 Teléfono:</span>
                <div class='value'>$telefono</div>
            </div>
            
            <div class='field'>
                <span class='label'>🏗️ Servicio de Interés:</span>
                <div class='value'>
                    <strong style="color: #007bff;">$servicio_texto</strong>
                </div>
            </div>
            
            <div class='section-title'>💬 Mensaje del Cliente</div>
            <div class='message-box'>
                $mensaje
            </div>
            
            <div class='section-title'>🔍 Información Técnica</div>
            
            <div class='field'>
                <span class='label'>📅 Fecha y Hora de Envío:</span>
                <div class='value'>$fecha_envio</div>
            </div>
            
            <div class='field'>
                <span class='label'>🌐 Dirección IP:</span>
                <div class='value'>$ip_cliente</div>
                <div class='info-note'>Para fines de seguridad y seguimiento</div>
            </div>
            
            <div class='field'>
                <span class='label'>🔗 Enviado desde:</span>
                <div class='value'>$dominio</div>
            </div>
        </div>
        
        <div class='footer'>
            <p>✉️ Este mensaje fue enviado automáticamente desde el formulario de contacto de <strong>Deeploox SL</strong></p>
            <p>📍 Detrás de Ministerio de cultura, Malabo | 📞 +240 555 011 022</p>
            <p>© " . date('Y') . " Deeploox SL - Todos los derechos reservados</p>
            <p style="margin-top: 10px; font-size: 12px; color: #bdc3c7;">
                ⚠️ Este es un mensaje automático, por favor no responder directamente a este correo.<br>
                Para responder al cliente, utilice el email: <a href="mailto:$email">$email</a>
            </p>
        </div>
    </div>
</body>
</html>
HTML;

// Versión texto plano
$cuerpoTexto = "NUEVO MENSAJE DE CONTACTO - DEEPLOOX SL
" . str_repeat("=", 50) . "

📋 INFORMACIÓN DEL CLIENTE:
" . str_repeat("-", 30) . "

👤 Nombre: $nombre
📧 Email: $email
📞 Teléfono: $telefono
🏗️ Servicio: $servicio_texto

💬 MENSAJE:
" . str_repeat("-", 30) . "

$mensaje

🔍 INFORMACIÓN TÉCNICA:
" . str_repeat("-", 30) . "

📅 Fecha: $fecha_envio
🌐 IP: $ip_cliente
🔗 Dominio: $dominio
🖥️ Navegador: $navegador

" . str_repeat("=", 50) . "

📍 Deeploox SL
📍 Detrás de Ministerio de cultura, Malabo
📞 +240 555 011 022
📧 ritamangue23@gmail.com

© " . date('Y') . " - Mensaje enviado automáticamente desde el formulario web.
";

// Cabeceras del correo
$headers = "From: Formulario Web Deeploox <no-reply@deeploox.com>\r\n";
$headers .= "Reply-To: $nombre <$email>\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "X-Priority: 1 (Highest)\r\n";
$headers .= "X-MSMail-Priority: High\r\n";
$headers .= "Importance: High\r\n";

// Enviar correo
$enviado = mail($destinatario, $asunto, $cuerpoHTML, $headers);

if ($enviado) {
    // Enviar copia en texto plano como respaldo
    $headersTexto = "From: Formulario Web Deeploox <no-reply@deeploox.com>\r\n";
    $headersTexto .= "Content-Type: text/plain; charset=UTF-8\r\n";
    mail($destinatario, $asunto . " [Versión Texto]", $cuerpoTexto, $headersTexto);
    
    // También enviar copia al cliente
    $asuntoCliente = "Confirmación: Hemos recibido su mensaje";
    $mensajeCliente = "Hola $nombre,\n\nHemos recibido su mensaje correctamente. Nos pondremos en contacto con usted en breve.\n\nServicio: $servicio_texto\nFecha: $fecha_envio\n\nGracias por contactar con Deeploox SL.\n\nSaludos cordiales,\nEl equipo de Deeploox SL\nritamangue23@gmail.com";
    
    mail($email, $asuntoCliente, $mensajeCliente, "From: Deeploox SL <ritamangue23@gmail.com>");
    
    echo json_encode([
        'success' => true, 
        'message' => '✅ ¡Mensaje enviado con éxito!<br><br>Hemos enviado su consulta a <strong>ritamangue23@gmail.com</strong><br>También recibirá una copia en su email.'
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => '❌ Error al enviar el mensaje. Por favor:<br>1. Inténtelo de nuevo más tarde<br>2. O envíe un correo directo a: <strong>ritamangue23@gmail.com</strong>'
    ]);
    
    // Log del error
    error_log("Error enviando correo a ritamangue23@gmail.com - " . date('Y-m-d H:i:s'));
}
?>