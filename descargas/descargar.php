<?php
if (isset($_GET['file'])) {
    $filename = basename($_GET['file']);

    if (file_exists($filename)) {
        // Configurar encabezados para la descarga
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        ob_clean();
        flush();
        readfile($filename);
        unlink($filename); // Eliminar el archivo después de la descarga
        exit;
    } else {
        echo "Error: Archivo no encontrado.";
    }
} else {
    echo "Error: No se especificó un archivo.";
}
?>
