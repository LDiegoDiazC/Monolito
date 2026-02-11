<!-- Introducción a Microservicios: Arquitectura y Contenedores -->
<?php
include 'db.php';

// Simulación de envío de correo
function simulateEmail($email) {
    // Esto bloquea toda la aplicación por 5 segundos.
    sleep(5); 
    return true;
    //Con error:
    //throw new Exception("Error al enviar correo a $email");
}

$message = "";

// Lógica de Compra (Monolito)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_product_id'])) {
    $product_id = $_POST['buy_product_id'];
    $email = $_POST['email'];

    try {
        // 1. Crear Orden en BD
        $stmt = $pdo->prepare("INSERT INTO orders (product_id, customer_email, status) VALUES (?, ?, 'confirmed')");
        $stmt->execute([$product_id, $email]);

        // 2. Enviar Correo
        //Este sera nuestro nuevo microservicio, pero por ahora lo dejamos aquí para simular el proceso.
        simulateEmail($email);

        // 3. Mostrar Mensaje de Éxito
        $message = "<div class='alert alert-success'>¡Compra exitosa! Correo de confirmación enviado a $email (tardó 5s).</div>";
    } catch (Exception $e) {
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}

// Obtener productos
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ecommerce Monolito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4">📦 Ecommerce <small class="text-muted">(Monolito v1.0)</small></h1>
        
        <?= $message ?>

        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                            <h6 class="card-subtitle mb-2 text-primary">$<?= number_format($product['price'], 2) ?></h6>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <form method="POST">
                                <input type="hidden" name="buy_product_id" value="<?= $product['id'] ?>">
                                <div class="mb-2">
                                    <input type="email" name="email" class="form-control" placeholder="correo@correo.com" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Comprar Ahora</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>