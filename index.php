<?php

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Página de Inicio</title>
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

    <h1>Bienvenido a la aplicación de IAW</h1>

    <form action="storage.php" method="get">
        <button type="submit">Ir a gestión de archivos</button>
    </form>
  

</body>
</html>
