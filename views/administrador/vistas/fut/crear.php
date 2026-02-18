    <h1 class="h3 mb-4 text-gray-800">Crear fut</h1>
<head>
    <meta charset="UTF-8">
    <title>Formato Único de Trámite (FUT)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        .row {
            display: flex;
            gap: 15px;
        }
        .row .form-group {
            flex: 1;
        }
        .btn {
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #34495e;
        }
    </style>
</head>
<body>

<h2>FORMATO ÚNICO DE TRÁMITE (FUT)</h2>

<form action="#" method="post">

    <div class="form-group">
        <label>Dependencia o autoridad a la que se dirige:</label>
        <input type="text" name="dependencia" required>
    </div>

    <hr>

    <h3>1. Datos del Solicitante</h3>

    <div class="row">
        <div class="form-group">
            <label>Nombres:</label>
            <input type="text" name="nombres" required>
        </div>
        <div class="form-group">
            <label>Apellidos:</label>
            <input type="text" name="apellidos" required>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label>Tipo de Documento:</label>
            <select name="tipo_documento" required>
                <option value="">Seleccione</option>
                <option value="dni">DNI</option>
                <option value="ce">Carné de Extranjería</option>
                <option value="pasaporte">Pasaporte</option>
            </select>
        </div>
        <div class="form-group">
            <label>N° Documento:</label>
            <input type="text" name="numero_documento" required>
        </div>
    </div>

    <div class="form-group">
        <label>Dirección:</label>
        <input type="text" name="direccion">
    </div>

    <div class="row">
        <div class="form-group">
            <label>Teléfono:</label>
            <input type="text" name="telefono">
        </div>
        <div class="form-group">
            <label>Correo Electrónico:</label>
            <input type="email" name="correo">
        </div>
    </div>

    <hr>

    <h3>2. Detalle de la Solicitud</h3>

    <div class="form-group">
        <label>Asunto:</label>
        <input type="text" name="asunto" required>
    </div>

    <div class="form-group">
        <label>Descripción de la solicitud:</label>
        <textarea name="descripcion" rows="6" required></textarea>
    </div>

    <hr>

    <div class="form-group">
        <label>Fecha:</label>
        <input type="date" name="fecha">
    </div>

    <div class="form-group">
        <label>Firma del solicitante:</label>
        <input type="text" name="firma" placeholder="Escriba su nombre completo">
    </div>

    <br>
    <button type="submit" class="btn">Enviar Solicitud</button>

</form>

</body>
