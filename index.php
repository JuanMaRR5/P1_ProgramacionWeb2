<?php 

require_once("conexionDB.php");

$items = [
    [
        "nombre" => "Banana",
        "categoria" => "Fruta", 
        "descripcion" => "Fruta amarilla rica en potasio.", 
        "imagen" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTgJgd41Y_qfBNo0cda1uB2RHt534xEtautRg&s"
    ],
    [
        "nombre" => "Manzana",
        "categoria" => "Fruta", 
        "descripcion" => "Fruta roja, muy rica.", 
        "imagen" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSoRct5MDKg-tnHxN6JIsWFyFw50v1vOuYPfw&s"
    ],
    [
        "nombre" => "Oreo",
        "categoria" => "Galletita", 
        "descripcion" => "Galletita con relleno de crema.", 
        "imagen" => "https://media.cnn.com/api/v1/images/stellar/prod/cnne-1137482-las-claves-de-oreo-en-su-110-aniversario.jpg?c=16x9&q=w_1280,c_fill"
    ],
    [
        "nombre" => "Rumbas",
        "categoria" => "Galletita", 
        "descripcion" => "Galletitas con chocolate y coco.", 
        "imagen" => "https://cdn11.bigcommerce.com/s-b156nxvmdp/images/stencil/1280x1280/products/470/767/bagley-rumba-galletitas-chocolate-relleno-coco-108g__49696__44836.1691098610.jpg?c=1"
    ],
    [
        "nombre" => "Cepita",
        "categoria" => "Jugo", 
        "descripcion" => "Jugo de frutas natural.", 
        "imagen" => "https://www.lacoopeencasa.coop/media/lcec/publico/articulos/f/5/1/f51aa85e68fb78a79f2c5cf245ad070b"
    ],
    [
        "nombre" => "Baggio",
        "categoria" => "Jugo", 
        "descripcion" => "Jugo de naranja concentrado.", 
        "imagen" => "https://tyna.com.ar/assets/archivos/recortadas/baggio-jugo-naranja-x1000-cc_d94e297534.jpg"
    ],
    [
        "nombre" => "Leche",
        "categoria" => "Lacteo", 
        "descripcion" => "Leche entera", 
        "imagen" => "https://statics.dinoonline.com.ar/imagenes/full_600x600_ma/3262766_f.jpg"
    ],
    [
        "nombre" => "Yogurt",
        "categoria" => "Lacteo", 
        "descripcion" => "Yogur con sabores.", 
        "imagen" => "https://jumboargentina.vtexassets.com/arquivos/ids/868143-800-600?v=638830173739100000&width=800&height=600&aspect=true"
    ]
];
//post
$error = [];
$sugerenciaMostrada = false;
$modoEditar = false;
$itemEditar = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombreNuevo = trim($_POST["nombre_nuevo"] ?? " ");
    $categoriaNuevo = trim($_POST["nombre_nuevo"] ?? " ");
    $DescripcionNuevo = trim($_POST["nombre_nuevo"] ?? " ");
    $ImagenNuevo= trim($_POST["nombre_nuevo"] ?? " ");

    if (empty($nombreNuevo)) {
        $error[] = "Es necesario el Nombre";
    }
    if (empty($categoriaNuevo)) {
        $error[] = "Es Necesario la categoria";
    }
    if (empty($DescripcionNuevo)) {
        $error[] = "Es necesario la descripcion";
    }
    if (empty($ImagenNuevo)) {
        $error[] = "Es necesario la imagen";
    }

        if (empty($error)) {

            $sugerencia = [
            "nombre" => $nombreNuevo,
            "categoria" => $categoriaNuevo,
            "descripcion" => $DescripcionNuevo,
            "imagen" => $ImagenNuevo,
        ];
    }
}
    elseif (isset($_GET["editar"]) && is_numeric($_GET["editar"]) && $_GET["editar"] >= 0 && $_GET["editar"] < count($items)) {
    $modoEditar = true;
    $itemEditar = $items[(int)$_GET["editar"]];
}
//get
$busqueda = isset($_GET["busqueda"]) ? strtolower(trim($_GET["busqueda"])) : " ";
$nombre = isset($_GET["nombre"]) ? strtolower(string: trim(string: $_GET["nombre"])) :" ";
$categoria = isset($_GET["categoria"]) ? $_GET["categoria"] : " ";
$tema = isset($_GET["tema"]) ? $_GET["tema"] : "claro";
$pagina = isset($_GET["pagina"]) ? max(1, (int)$_GET["pagina"]) : 1;
$limitePorPagina = 4;


//filtro
$itemsFiltrados = array_filter($items, function($item) use ($busqueda, $categoria) {
    $coincideNombre = $busqueda === " " || strpos(strtolower($item["nombre"]), $busqueda) !== false;
    $coincideCategoria = $categoria === " " || $item["categoria"] === $categoria;
    return $coincideNombre && $coincideCategoria;
});

$itemsFiltrados = array_values($itemsFiltrados);
$totalItems = count($itemsFiltrados);
$totalPaginas = max(1, ceil($totalItems / $limitePorPagina));
$offset = ($pagina - 1) * $limitePorPagina;
$itemsPagina = array_slice($itemsFiltrados, $offset, $limitePorPagina);

$estilos = [
    "claro" => [
        "fondo_body" => "#f8f9fa",
        "texto_body" => "#212529",
        "fondo_tarjeta" => "#ffffff",
        "borde_tarjeta" => "#dee2e6",
        "fondo_header" => "#007bff",
        "texto_header" => "#ffffff"
    ],
    "oscuro" => [
        "fondo_body" => "#343a40",
        "texto_body" => "#f8f9fa",
        "fondo_tarjeta" => "#495057",
        "borde_tarjeta" => "#6c757d",
        "fondo_header" => "#343a40",
        "texto_header" => "#f8f9fa"
    ]
];
$estiloActual = $estilos[$tema] ?? $estilos["claro"];
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Precios y Ofertas</title>

    
</head>
<style>
    body {
        background-color: <?= $estiloActual["fondo_body"] ?>;
        color: <?= $estiloActual["texto_body"] ?>;
        font-family: "Arial", sans-serif;
        margin: 0;
        padding: 20px;
        line-height: 1.6;
    }
    header {
        background-color: <?= $estiloActual["fondo_header"] ?>;
        color: <?= $estiloActual["texto_header"] ?>;
        padding: 20px;
        text-align: center;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .filtros, .sugerir-form {
        background-color: <?= $estiloActual["fondo_tarjeta"] ?>;
        border: 1px solid <?= $estiloActual["borde_tarjeta"] ?>;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
    }
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    input, select, textarea, button {
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid <?= $estiloActual["borde_tarjeta"] ?>;
        border-radius: 4px;
        width: 100%;
        max-width: 300px;
    }
    button {
        background-color: <?= $tema === "claro" ? "#007bff" : "#28a745" ?>;
        color: white;
        cursor: pointer;
        border: none;
        width: auto;
        padding: 10px 20px;
    }
    button:hover {
        opacity: 0.8;
    }
    .grid-tarjetas {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    .tarjeta {
        background-color: <?= $estiloActual["fondo_tarjeta"] ?>;
        border: 1px solid <?= $estiloActual["borde_tarjeta"] ?>;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .tarjeta:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .tarjeta img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .tarjeta h3 {
        margin: 10px 0;
        color: <?= $tema === "claro" ? "#007bff" : "#17a2b8" ?>;
    }
    .categoria {
        background-color: <?= $tema === "claro" ? "#e9ecef" : "#6c757d" ?>;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.9em;
        display: inline-block;
        margin-bottom: 10px;
    }
    .errores {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .sugerencia {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .paginacion {
        text-align: center;
        margin-top: 20px;
    }
    .paginacion a {
        display: inline-block;
        padding: 10px 15px;
        margin: 0 5px;
        background-color: <?= $estiloActual["fondo_tarjeta"] ?>;
        color: <?= $estiloActual["texto_body"] ?>;
        text-decoration: none;
        border-radius: 4px;
        border: 1px solid <?= $estiloActual["borde_tarjeta"] ?>;
    }
    .paginacion a:hover {
        background-color: <?= $tema === "claro" ? "#007bff" : "#17a2b8" ?>;
        color: white;
    }
    .actual {
        background-color: <?= $tema === "claro" ? "#007bff" : "#17a2b8" ?>;
        color: white;
        padding: 10px 15px;
        border-radius: 4px;
    }
   
    @media (max-width: 768px) {
        .grid-tarjetas {
            grid-template-columns: 1fr;
        }
        input, select, textarea {
            max-width: 100%;
        }
    }
</style>
<body>
    <h1>TIENDA ONLINE</h1>
    <p>Los mejores precio y ofertas.</p>

    <form method="GET" class="filtros">
        <label for="busqueda">Buscar por nombre:</label>
        <input type="text" id="busqueda" name="busqueda" value="<?= htmlspecialchars($busqueda) ?>" placeholder="Ej: Banana">

        <label for="categoria">Filtrar por categoría:</label>
        <select id="categoria" name="categoria">
            <option value=" ">Todas las categorías</option>
            <option value="Fruta" <?= $categoria === "Fruta" ? "selected" : " " ?>>Fruta</option>
            <option value="Galletita" <?= $categoria === "Galletita" ? "selected" : " " ?>>Galletita</option>
            <option value="Jugo" <?= $categoria === "Jugo" ? "selected" : " " ?>>Jugo</option>
            <option value="Lacteo" <?= $categoria === "Lacteo" ? "selected" : " " ?>>Lacteo</option>
        </select>

        <label for="tema">Tema:</label>
        <select id="tema" name="tema">
            <option value="claro" <?= $tema === "claro" ? "selected" : " " ?>>Claro</option>
            <option value="oscuro" <?= $tema === "oscuro" ? "selected" : " " ?>>Oscuro</option>
        </select>

        <?php if ($modoEditar): ?>
            <input type="hidden" name="editar" value="<?= (int)$_GET["editar"] ?>">
        <?php endif; ?>

        <button type="submit">Filtrar y Buscar</button>
    </form>

        <?php if (empty($itemsFiltrados)): ?>
        <div class="no-resultados">
            <p>No se encontraron ítems que coincidan con los filtros.</p>
        </div>
    <?php else: ?>
        <div class="grid-tarjetas">
            <?php foreach ($itemsFiltrados as $item): ?>
                <div class="tarjeta">
                    <img src="<?= htmlspecialchars($item["imagen"]) ?>" 
                         alt="<?= htmlspecialchars($item["nombre"]) ?>">
                    <span class="categoria"><?= htmlspecialchars($item["categoria"]) ?></span>
                    <h3><?= htmlspecialchars($item["nombre"]) ?></h3>
                    <p><?= htmlspecialchars($item["descripcion"]) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

   <form method="POST" class="sugerir-form" id="form-sugerir">
    <h2><?= $modoEditar ? "Editar Ítem Existente (Simulado - No guarda cambios)" : "Sugerir un Nuevo Producto" ?></h2>
    
    <input type="hidden" name="busqueda" value="<?= htmlspecialchars($busqueda) ?>">
    <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
    <input type="hidden" name="tema" value="<?= htmlspecialchars($tema) ?>">
    <input type="hidden" name="pagina" value="1">
    
    <?php if ($modoEditar): ?>
        <input type="hidden" name="editar" value="<?= htmlspecialchars($itemEditar["id"]) ?>">
    <?php endif; ?>

    <label for="nuevo_nombre">Nombre del producto:</label>
    <input type="text" id="nuevo_nombre" name="nuevo_nombre" 
           value="<?= $modoEditar ? htmlspecialchars($itemEditar["nombre"]) : " " ?>" 
           required placeholder="Ejemplo: Chocolate Milka">

    <label for="nueva_categoria">Categoría:</label>
    <select id="nueva_categoria" name="nueva_categoria" required>
        <option value="">Selecciona una categoría</option>
        <option value="Fruta" <?= ($modoEditar && $itemEditar["categoria"] === "Fruta") ? "selected" : " " ?>>Fruta</option>
        <option value="Galletita" <?= ($modoEditar && $itemEditar["categoria"] === "Galletita") ? "selected" : " " ?>>Galletita</option>
        <option value="Jugo" <?= ($modoEditar && $itemEditar["categoria"] === "Jugo") ? "selected" : " " ?>>Jugo</option>
        <option value="Lacteo" <?= ($modoEditar && $itemEditar["categoria"] === "Lacteo") ? "selected" : " " ?>>Lacteo</option>
    </select>

    <label for="nueva_descripcion">Descripción breve:</label>
    <textarea id="nueva_descripcion" name="nueva_descripcion" rows="3" 
              required placeholder="Ej: Producto delicioso y saludable"><?= $modoEditar ? htmlspecialchars($itemEditar["descripcion"]) : "" ?></textarea>

    <label for="nueva_imagen">URL de imagen (opcional):</label>
    <input type="url" id="nueva_imagen" name="nueva_imagen" 
           value="<?= $modoEditar ? htmlspecialchars($itemEditar["imagen"]) : " " ?>" 
           placeholder="Ejemplo: https://ejemplo.com/imagen.jpg">

    <button type="submit"><?= $modoEditar ? "Actualizar (Simulado)" : "Sugerir Producto" ?></button>
    
    <?php if ($modoEditar): ?>
        <p><small><a href="?busqueda=<?= urlencode($busqueda) ?>&categoria=<?= urlencode($categoria) ?>&tema=<?= urlencode($tema) ?>&pagina=<?= $pagina ?>">Volver a la lista</a></small></p>
    <?php endif; ?>
</form>
</body>
</html>