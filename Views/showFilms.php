

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

        .uk-grid-match>*{display:flex;flex-wrap:wrap}
        .uk-child-width-1-1\@s>*{width:100%}.
    </style>

</head>

<body>
<?php $flash = new \Controllers\SessionController();
?>
    <header>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a href="" class="btn btn-light">
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
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md- col-lg-3 d-md-block bg-light sidebar collapse">
                <form method="post" action="/film/search">
                <div class="position-sticky pt-3">
                    <div class="col-12 mb-2">
                        <label for="title" class="form-label">Search by TITLE:</label>
                        <div class="input-group">
                            <input id="title" name="title" type="text" class="form-control" placeholder="Example: 'The Sting'">
                            <button type="submit" class="btn btn-secondary">Search</button>
                        </div>
                    </div>
                    <div class="col-12 mb-2">
                        <label for="star" class="form-label">Search by STAR:</label>
                        <div class="input-group">
                            <input id="star" name="star" type="text" class="form-control" placeholder="Example: 'Robert Redford'">
                            <button type="submit" class="btn btn-secondary">Search</button>
                        </div>
                    </div>
                </div>
                </form>
                <?php foreach ($flash->getFlash('searchError', []) as $error) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error ?>
                    </div>
                <?php endforeach; ?>
            </nav>
    <main class="col-md-9 ms-sm-auto col-lg-9 px-md-4">
        <div class="container mt-4">
            <form method="post" action="/">
            <div>
                <div class=" mb-3">
                    <label for="SortingType" class="visually-hidden">Format</label>
                    <select name="sorting" class="form-control" id="SortingType" required>
                        <option value="">--Please choose films sorting method--</option>
                        <option value="A-Z">Name (A-Z)</option>
                        <option value="Z-A">Name (Z-A)</option>

                    </select>
                </div>
                <button class="w-100 btn btn-lg btn-primary mb-3" type="submit">Sort</button>
            </div>
            </form>
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
            <?php /**
             * @var array $films
             */
            foreach ($films as $key => $value):?>
            <div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                            <div class="col p-3 d-flex flex-column position-static">

                                <h3 class="mb-0">"<?= $films[$key]['title'] ?>"</h3>
                                <div class="mb-1 text-muted"><?= $films[$key]['release_year'] ?></div>
                                <div class="mb-1 text-muted"><?= $films[$key]['format'] ?></div>
                                <strong>STARS:</strong>
                                <p><?= $films[$key]['stars'] ?></p>
                                <div class="mt-2">
                                    <a href="/film/delete?id=<?= $films[$key]['id'] ?>" class="w-25 btn btn-sm btn-danger">Delete</a>
                                </div>
                            </div>
                            <div class="col-auto d-none d-lg-block">
                                <svg class="bd-placeholder-img" width="200" height="100%" role="img" aria-label="Placeholder: IMAGE" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">IMAGE</text></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>
    </main>
        </div>
    </div>
</body>