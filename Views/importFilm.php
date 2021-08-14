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
                <a href="/" class="btn btn-light">
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
            <form method="post" action="/film/import" enctype="multipart/form-data">
                <div class="row mt-3">
                    <div class="col-8">
                        <input class="form-control" type="file" name="file" id="formFile">
                    </div>
                    <button type="submit" class="btn btn-success col-4 mb-3" value="upload">Upload file</button>
                </div>
            </form>
        </div>
    </main>
</body>
