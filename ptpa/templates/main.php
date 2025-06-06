<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= base_url(); ?>favicon.ico" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- React 18, ReactDOM 18 e Babel CDN -->
    <script src="https://cdn.jsdelivr.net/npm/react@18/umd/react.development.js" crossorigin></script>
    <script src="https://cdn.jsdelivr.net/npm/react-dom@18/umd/react-dom.development.js" crossorigin></script>
    <script src="https://cdn.jsdelivr.net/npm/@babel/standalone/babel.min.js"></script>

    <title><?= isset($metadata['page_title']) ? ($metadata['page_title']) : ('TITULO NÃO INFORMADO'); ?></title>

    <style>
        @font-face {
            font-weight: bold;
            font-style: normal;
            font-display: swap;
        }

        .myBold {
            font-weight: bold;
        }

        .font-sans {
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }

        body {
            overflow-x: hidden;
            /* Evita rolagem horizontal */
        }
    </style>
</head>

<body>
    <?
    // myPrint('$loadView :: ', $loadView);
    ?>
    <header>
        <!-- <h1>Template Principal</h1> -->
    </header>
    <main>
        <?php if ($loadView !== array()): ?>
            <?php foreach ($loadView as $getView): ?>
                <?php
                echo view($getView);
                ?>
            <?php endforeach ?>
        <?php else: ?>
            <h1>Não foi passado nenhma view!</h1>
        <?php endif ?>
    </main>
    <footer>
        <!-- <h1>Template Principal</h1> -->
    </footer>

</body>

</html>