<?php
require 'vendor/autoload.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

// Configuración
$connectionString = getenv("AZURE_STORAGE_CONNECTION_STRING");
$containerName = "comprimidos";  // Cambia esto por el nombre de tu contenedor

$blobClient = BlobRestProxy::createBlobService($connectionString);

// Eliminar archivo si se solicita
if (isset($_GET['delete'])) {
    $blobToDelete = $_GET['delete'];
    try {
        $blobClient->deleteBlob($containerName, $blobToDelete);
        echo "<p style='color:green;'>Archivo $blobToDelete eliminado correctamente.</p>";
    } catch (ServiceException $e) {
        echo "<p style='color:red;'>Error al eliminar: " . $e->getMessage() . "</p>";
    }
}

// Subida de archivo ZIP
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["zipfile"])) {
    $uploadedFile = $_FILES["zipfile"];
    $extension = strtolower(pathinfo($uploadedFile["name"], PATHINFO_EXTENSION));
    $mime = mime_content_type($uploadedFile["tmp_name"]);
    if ($extension !== "zip") {
        echo "<p style='color:red;'>Solo se permiten archivos ZIP.<br>";
        echo "Archivo subido: " . htmlspecialchars($uploadedFile["name"]) . "<br>";
        echo "Tipo detectado: " . htmlspecialchars($mime) . "</p>";
    } else {
        $blobName = basename($uploadedFile["name"]);
        $content = fopen($uploadedFile["tmp_name"], "r");

        try {
            $blobClient->createBlockBlob($containerName, $blobName, $content);
            echo "<p style='color:green;'>Archivo $blobName subido correctamente.</p>";
        } catch (ServiceException $e) {
            echo "<p style='color:red;'>Error al subir: " . $e->getMessage() . "</p>";
        }
    }
}

// Listar archivos
try {
    $listOptions = new ListBlobsOptions();
    $blobList = $blobClient->listBlobs($containerName, $listOptions);
    $blobs = $blobList->getBlobs();
} catch (ServiceException $e) {
    die("Error al listar archivos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestor de archivos ZIP en Azure Blob</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            color: #333;
            padding: 30px;
        }

        h1, h2 {
            color: #0066cc;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        li {
            background-color: #ffffff;
            padding: 12px 16px;
            margin-bottom: 10px;
            border-left: 4px solid #0066cc;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        a {
            color: #0066cc;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        button {
            background-color: #0066cc;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #004b99;
        }

        .success {
            color: green;
            margin-bottom: 10px;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Archivos ZIP en el contenedor '<?= htmlspecialchars($containerName) ?>'</h1>

    <ul>
        <?php foreach ($blobs as $blob): ?>
            <li>
                <a href="<?= htmlspecialchars($blob->getUrl()) ?>" target="_blank">
                    <?= htmlspecialchars($blob->getName()) ?>
                </a>
                [<a href="?delete=<?= urlencode($blob->getName()) ?>" onclick="return confirm('¿Eliminar este archivo?')">Eliminar</a>]
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Subir nuevo archivo ZIP</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="zipfile" accept=".zip" required><br>
        <button type="submit">Subir</button>
    </form>
</body>
</html>

