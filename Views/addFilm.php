<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width-device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css"
          rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl"
          crossorigin="anonymous">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .container {
            width: auto;
            max-width: 680px;
            padding: 0 15px;
        }

        .brd {
            border: 4px double black; /* Параметры границы */
            background: #E0FFFF; /* Цвет фона */
            padding: 10px; /* Поля вокруг текста */
        }
    </style>

</head>

<body>
    <header>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a href="/?page=1&sorting=no" class="btn btn-light">
                    Home
                </a>
            </div>
            <div class="container">
                <a href="/film/add" class="btn btn-light">
                    Add film
                </a>
            </div>
            <div class="container">
                <a href="/film/import" class="btn btn-light">
                    Import film
                </a>
            </div>
        </div>
    </header>
    <main class="flex-shrink-0">
        <div class="container">
            <?php $flash = new \Controllers\SessionController();
            ?>
            <?php foreach ($flash->getFlash('errors', []) as $error) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            <?php endforeach; ?>
            <?php foreach ($flash->getFlash('success', []) as $error) : ?>
                <div class="alert alert-success" role="alert">
                    <?= $error ?>
                </div>
            <?php endforeach; ?>
            <form method="post" action="/film/add">
                <h1 class="h3 mb-3 mt-4 fw-normal">Add Film</h1>
                <div class="mb-3">
                    <label for="InputTitle" class="visually-hidden">Title</label>
                    <input type="text"
                           name="title"
                           value="<?php $insertData['title'] ?? '' ?>"
                           class="form-control"
                           id="InputTitle"
                           pattern="[^ ][^<>;@#$%^&*]*"
                           placeholder="Title"
                           required>
                </div>
                <div class="mb-3">
                    <label for="InputReleaseYear" class="visually-hidden">Release Year</label>
                    <input type="number"
                           min="1901"
                           max="2021"
                           name="release_year"
                           value="<?php $insertData['releaseYear'] ?? '' ?>"
                           class="form-control"
                           id="InputReleaseYear"
                           placeholder="Release Year"
                           required>
                </div>
                <div class=" mb-3">
                    <label for="InputFormat" class="visually-hidden">Format</label>
                    <select name="format" class="form-control" id="InputFormat" required>
                        <option value="">--Please choose film format--</option>
                        <option value="VHS">VHS</option>
                        <option value="DVD">DVD</option>
                        <option value="Blu-Ray">Blu-Ray</option>
                    </select>
                </div>
                <div class=" mb-3">
                    <label for="InputStars" class="visually-hidden">Stars</label>
                    <input type="text"
                           pattern="[^ ][^0-9<>@#$%^&*/\]*"
                           name="stars"
                           value="<?php $insertData['InputStars'] ?? '' ?>"
                           class="form-control"
                           id="InputStars"
                           placeholder="Stars (input all using comma separator)"
                           required>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Add Film</button>
            </form>
        </div>
    </main>
</body>
