<?php


namespace Controllers;


use Models\Film;

class FilmController
{
    public function index()
    {
        $getFilms = new Film();
        if(!array_key_exists('sorting', $_POST)) {
            $films = $getFilms->getAll();
        } else {
            $films = $getFilms->getAll($_POST['sorting']);
        }
        require __DIR__ .'\..\Views\showFilms.php';

    }

    public function add()
    {
        if(!$_POST){
            require_once __DIR__ .'\..\Views\addFilm.php';
        } else {
            $data = $_POST;
            $insert = new Film();
            $insert->insert($data);
            require_once __DIR__ .'\..\Views\addFilm.php';
        }
    }

    public function import()
    {

        $file = $_FILES['file'] ?? '';
        if($file){
            move_uploaded_file($file['tmp_name'], "Storage/" . $file['name']);
            $uploadedFile = fopen("Storage/" . $file['name'], 'r');
            $data = [];
            while(!feof($uploadedFile)){
                $content = fgets($uploadedFile);
                $array = explode('\n', $content);
                if (strpos($array[0], 'Title') !== false) {
                    $array[0] = substr($array[0], 7);

                    $data['title'][] = $array[0];
                }
                if (strpos($array[0], 'Release Year') !== false) {
                    $array[0] = substr($array[0], 14);
                    $array[0] = preg_replace('/\s/', '', $array[0]);

                    $data['release_year'][] = $array[0];
                }
                if (strpos($array[0], 'Format') !== false) {
                    $array[0] = substr($array[0], 8);
                    $array[0] = preg_replace('/\s/', '', $array[0]);

                    $data['format'][] = $array[0];
                }
                if (strpos($array[0], 'Stars') !== false) {
                    $array[0] = substr($array[0], 7);

                    $data['stars'][] = $array[0];
                }
                }
            unlink("Storage/" . $file['name']);

            $films= [];
            foreach ($data['title'] as $key => $value)
            {
                $films[$key]['title'] = $data['title'][$key];
                $films[$key]['release_year'] = $data['release_year'][$key];
                $films[$key]['format'] = $data['format'][$key];
                $films[$key]['stars'] = $data['stars'][$key];
            }
            $insert = new Film();
            $insert->import($films);

            require_once __DIR__ .'\..\Views\addFilm.php';
        } else {
            require __DIR__ .'\..\Views\importFilm.php';
        }
    }

    public function search()
    {
        $getFilms = new Film();
        if(!empty($_POST['title'])) {
            $films = $getFilms->getSome('title', $_POST['title']);
        } else if (!empty($_POST['star'])) {
            $films = $getFilms->getSome('star', $_POST['star']);
        } else {
            header('Location: /');
        }

        require __DIR__ .'\..\Views\searchFilm.php';
    }

    public function delete()
    {
        $filmDelete = new Film;
        $filmDelete->delete($_GET['id']);
        header('Location: /');
    }
}