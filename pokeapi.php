<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API</title>
    <link rel="stylesheet" href="diseno.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>

<body>
    <h1>Pokédex API</h1>
    <div class="navigation">
        <form action="" method="GET">
            <input type="hidden" name="content"
                value="<?php echo isset($_GET['content']) ? htmlspecialchars($_GET['content']) : 'pokemon'; ?>">
            <input type="text" name="search" placeholder="Buscar"
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <select name="order_by">
                <option value="id_asc" <?php echo isset($_GET['order_by']) && $_GET['order_by'] == 'id_asc' ? 'selected' : ''; ?>>Número menor</option>
                <option value="id_desc" <?php echo isset($_GET['order_by']) && $_GET['order_by'] == 'id_desc' ? 'selected' : ''; ?>>Número mayor</option>
                <option value="name_asc" <?php echo isset($_GET['order_by']) && $_GET['order_by'] == 'name_asc' ? 'selected' : ''; ?>>A-Z</option>
                <option value="name_desc" <?php echo isset($_GET['order_by']) && $_GET['order_by'] == 'name_desc' ? 'selected' : ''; ?>>Z-A</option>
            </select>
            <button type="submit">BUSCAR</button>
            <div>
            <a class="tex" href="?content=pokemon">Pokémones</a>
            <a class="tex" href="?content=trainers">Entrenador</a>
        </div>
        </form>
        
    </div>

    <div class="pokedex">
        <?php
        if (isset($_GET['content'])) {
            $content = $_GET['content'];
            if ($content === 'pokemon') {
                include 'pokemones.php';
            } elseif ($content === 'trainers') {
                include 'entrenador.php';
            } else {
                echo 'Contenido no válido';
            }
        } else {
            include 'pokemones.php';
        }
        ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const images = document.querySelectorAll(".pokemon-image img");

            const options = {
                root: null,
                rootMargin: "0px",
                threshold: 0.1
            };

            const observer = new IntersectionObserver(function (entries, observer) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const src = img.dataset.src;
                        img.src = src;
                        observer.unobserve(img);
                    }
                });
            }, options);

            images.forEach(image => {
                observer.observe(image);
            });
        });
    </script>
</body>

</html>