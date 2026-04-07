<?php
session_start();
require_once __DIR__ . "/../librerias/conexion.php";
// require_once __DIR__ . "/dompdf/autoload.inc.php";
// use Dompdf\Dompdf;

$id_documento = $_GET['id'] ?? 0;
if (!$id_documento) exit("ID no proporcionado.");

$sql = "SELECT d.*, u.nombres_usuario, u.apellidos_usuario, u.numero_documento, 
               u.celular_usuario, u.email_per, u.direccion_usuario, u.url_firma 
        FROM documento d 
        INNER JOIN usuario u ON d.id_usuario_emisor = u.id_usuario 
        WHERE d.id_documento = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_documento);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) exit("Documento no encontrado.");

$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 0.5cm; }
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .table-main { width: 100%; border-collapse: collapse; border: 2px solid black; }
        .table-main td { border: 1px solid black; padding: 5px; vertical-align: top; }
        .header-text { text-align: center; font-weight: bold; font-size: 14px; }
        .sub-header { text-align: center; font-weight: bold; font-size: 10px; }
        .bg-gray { background-color: #f2f2f2; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        .label-num { font-weight: bold; margin-right: 5px; }
        .firma-box { height: 80px; text-align: center; }
        .logo { width: 60px; }
    </style>
</head>
<body>

    <table class="table-main">
        <tr>
            <td width="15%" style="text-align:center;"><img src="../img/LOGIESPACARAN.png" class="logo"></td>
            <td width="70%" class="header-text" height="75px">
                INSTITUTO DE EDUCACIÓN SUPERIOR<br>
                " PACARAN "<br>
                <span style="font-size: 12px;">FORMULARIO ÚNICO DE TRÁMITE (F.U.T.)</span>
            </td>
            <td width="15%" style="text-align:center;"><img src="../img/drelp.jpg" class="logo"></td>
        </tr>

        <tr>
            <td colspan="3" class="bg-gray"><span class="label-num">1.</span> SUMILLA</td>
        </tr>
        <tr>
            <td colspan="3" style="height: 30px; font-size: 12px; font-weight: bold;">' . ($data['sumilla'] ?? $data['asunto']) . '</td>
        </tr>

        <tr>
            <td colspan="3" class="bg-gray"><span class="label-num">2.</span> DEPENDENCIA O AUTORIDAD A QUIEN SE DIRIGE</td>
        </tr>
        <tr>
            <td colspan="3">' . ($data['lugar'] ?? 'DIRECCIÓN GENERAL DEL IESP PACARÁN') . '</td>
        </tr>

        <tr>
            <td colspan="3" class="bg-gray"><span class="label-num">3.</span> DATOS DEL USUARIO (APELLIDOS Y NOMBRES)</td>
        </tr>
        <tr>
            <td colspan="3">' . $data['apellidos_usuario'] . ' ' . $data['nombres_usuario'] . '</td>
        </tr>

        <tr>
            <td width="50%" class="bg-gray"><span class="label-num">5.</span> D.N.I.</td>
            <td colspan="2" class="bg-gray"><span class="label-num">6.</span> CÓDIGO MODULAR</td>
        </tr>
        <tr>
            <td>' . $data['numero_documento'] . '</td>
            <td colspan="2">' . ($data['codigo_modular'] ?? '---') . '</td>
        </tr>

        <tr>
            <td class="bg-gray"><span class="label-num">7.</span> TLFS./CEL.</td>
            <td colspan="2" class="bg-gray"><span class="label-num">8.</span> E-MAIL</td>
        </tr>
        <tr>
            <td>' . $data['celular_usuario'] . '</td>
            <td colspan="2">' . $data['email_per'] . '</td>
        </tr>

        <tr>
            <td colspan="3" class="bg-gray"><span class="label-num">9.</span> DOMICILIO DEL USUARIO (Av., Jr., Cal., Psj., N° Urb., Distrito, Provincia)</td>
        </tr>
        <tr>
            <td colspan="3">' . $data['direccion_usuario'] . '</td>
        </tr>

        <tr>
            <td colspan="3" class="bg-gray"><span class="label-num">10.</span> FUNDAMENTACIÓN DEL PEDIDO</td>
        </tr>
        <tr>
            <td colspan="3" style="height: 180px;">' . nl2br($data['fundamento_pedido'] ?? $data['descripcion']) . '</td>
        </tr>

        <tr>
            <td colspan="3" class="bg-gray"><span class="label-num">11.</span> DOCUMENTOS QUE SE ADJUNTAN</td>
        </tr>
        <tr>
            <td colspan="2" style="height: 80px;">Ver adjunto digital: ' . $data['codigo_documento'] . '.pdf</td>
            <td width="30%"></td>
        </tr>

        <tr>
            <td class="bg-gray"><span class="label-num">12.</span> LUGAR Y FECHA</td>
            <td colspan="2" class="bg-gray"><span class="label-num">13.</span> FIRMA DEL USUARIO</td>
        </tr>
        <tr>
            <td>Pacarán, ' . date("d/m/Y", strtotime($data['fecha_emision'])) . '</td>
            <td colspan="2" class="firma-box">';
                
                // Definimos la ruta física para validar
                $nombre_firma = $data['url_firma'];
                $camino_firma = __DIR__ . "/../uploads/usuarios/" . $nombre_firma;

                if(!empty($nombre_firma) && file_exists($camino_firma)) {
                    // Para el navegador usamos ruta relativa, para DomPDF usaremos la física
                    $html .= '<img src="../uploads/usuarios/'.$nombre_firma.'" style="height:70px; width:auto;">';
                } else {
                    $html .= '<br><em style="color:red; font-size:8px;">Firma no registrada</em>';
                }
$html .= '  </td>
        </tr>
    </table>

    <p style="text-align: center; font-size: 8px;">PARA EL USUARIO</p>
    <table class="table-main" style="border-style: dashed;">
        <tr>
            <td width="70%"><strong>APELLIDOS Y NOMBRES:</strong> ' . $data['apellidos_usuario'] . ' ' . $data['nombres_usuario'] . '</td>
            <td width="30%"><strong>FECHA:</strong> ' . date("d/m/Y") . '</td>
        </tr>
        <tr>
            <td><strong>SUMILLA:</strong> ' . ($data['sumilla'] ?? $data['asunto']) . '</td>
            <td><strong>FIRMA Y SELLO:</strong></td>
        </tr>
    </table>

</body>
</html>';

echo $html;