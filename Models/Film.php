<?php

namespace Models;

use Controllers\SessionController;

class Film extends Model
{
    protected const TITLE = 'title';
    protected const RELEASE_YEAR = 'release_year';
    protected const FORMAT = 'format';
    protected const STARS = 'stars';

    protected array $validated;

    public function import($data)
    {

        foreach($data as $key => $value)
        {
            $this->insert($data[$key]);
        }

    }


    public function insert($data)
    {



        if($this->validate($data) == false)
        {
            header('Location: /film/add'); exit;
        }

        $stars = explode(', ', $data['stars']);
        $dupe_array = array();
        foreach ($stars as $star) {
            if (++$dupe_array[$star] > 1) {
                $this->setError("You cant enter the same star name twice in one film ('{$this->validated[self::TITLE]}')!");
                header('Location: /film/add'); exit;
            }
        }

        $connection = self::dbConnection();
        $repeat = $this->getSome('title', $this->validated[self::TITLE]);

        foreach ($repeat as $key => $value){
        if ($repeat[$key]['title'] == $this->validated[self::TITLE]
            and $repeat[$key]['release_year'] == $this->validated[self::RELEASE_YEAR]
            and $repeat[$key]['format'] == $this->validated[self::FORMAT]) {
            $this->setError("Film {$this->validated[self::TITLE]} ({$this->validated[self::RELEASE_YEAR]} | {$this->validated[self::FORMAT]}) already exists! If you want create film with the same name, release year and format - make difference mark in it's name");
            header('Location: /film/add');exit;
        }
        }




        $sql = "INSERT INTO films (title, release_year, format, stars) 
                    VALUES (?, ?, ?, ?) 
                    ";

        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, 'siss',
            $this->validated[self::TITLE],
            $this->validated[self::RELEASE_YEAR],
            $this->validated[self::FORMAT],
            $this->validated[self::STARS]);

        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            $this->setError(mysqli_errno($connection));
        } else {
            $this->setSuccess('Film added to library');
        }
    }

    public function delete($id)
    {
        $connection = self::dbConnection();

        $sql = "DELETE FROM films WHERE id = ?;";

        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $result = mysqli_stmt_execute($stmt);
        $this->setSuccess('Film deleted from library');
    }

    public function pagination(){
        $db = self::dbConnection();

        $sql = 'SELECT COUNT(1) AS count FROM films';
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $count = mysqli_fetch_assoc($result)['count'];

        $pages = ceil($count / 10);

        $currentPage = $_GET['page'] ?? 1;

        $pagesHtml = '';

        for ($page = 1; $page <= $pages; $page++) {
            if($page === (int) $currentPage) {
                $pagesHtml .= <<<HTML
        <option selected>{$currentPage}</option>
        HTML;
                    } else {
                if (array_key_exists('sorting',  $_GET)) {
                    $sorting = $_GET['sorting'];
                } else {
                    $sorting = 'no';
                }

                        $pagesHtml .= <<<HTML
        <option value=/?page={$page}&sorting={$sorting}>{$page}</option>
        HTML;
            }

        }

        $prevClass = $currentPage <= 1 ? 'disabled' : '';
        $prevPage = $currentPage - 1;

        $nextClass = $currentPage >= $pages ? 'disabled' : '';
        $nextPage = $currentPage + 1;

        return <<<HTML
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item {$prevClass}">
                    <a class="page-link" href="/?page={$prevPage}&sorting={$sorting}">Previous</a>
                </li>
            <select 
            onchange="location = this.value;" 
            class="page-selector form-control" 
            style="width: 70px">
               {$pagesHtml}
            </select>
                <li class="page-item {$nextClass}">
                <a class="page-link" href="/?page={$nextPage}&sorting={$sorting}">Next</a>
                </li>
            </ul>
        </nav>
HTML;
    }

    public function getAll($sorting = null)
    {

        $currentPage = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($currentPage - 1) * $limit;

        $connection = self::dbConnection();
        switch ($sorting) {
            case 'no':
                $stmt = 'SELECT * FROM films LIMIT ? OFFSET ?';
                break;
            case 'A-Z':
                $stmt = 'SELECT * FROM films ORDER BY title  COLLATE  utf8_unicode_ci ASC LIMIT ? OFFSET ?';
                break;
            case 'Z-A':
                $stmt = 'SELECT * FROM films ORDER BY title COLLATE  utf8_unicode_ci DESC LIMIT ? OFFSET ?';
                break;
            default:
                $stmt = 'SELECT * FROM films LIMIT ? OFFSET ?';
                header('Location: /?page=1&sorting=no'); exit;
        }

        $stmt = mysqli_prepare($connection, $stmt);
        mysqli_stmt_bind_param($stmt, 'ii', $limit, $offset);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $films = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $films;
//        $result = mysqli_query($connection, $stmt);
//        $films = [];
//        if (mysqli_num_rows($result) > 0) {
//            while($row = mysqli_fetch_assoc($result)) {
//                $films[] = $row;
//            }
//        }
//
//        return $films;
    }

    public function getSome($searchBy, $data)
    {
        $connection = self::dbConnection();
        if (!$data){
            $this->setSearchError('Enter any data for search');
            header('Location: /'); exit;
        }

        $data = preg_replace("#[@/\\\*$%^,]#i", '', $data);

        switch ($searchBy) {
            case 'title':
                $sql = "SELECT * FROM films WHERE title LIKE '%$data%'";
                break;
            case 'star':
                $sql = "SELECT * FROM films WHERE stars LIKE '%$data%'";
                break;
            default:
                $this->setSearchError('Cant find search parameter');
                header('Location: /'); exit;
        }

        $result = mysqli_query($connection, $sql);
        $films = [];
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $films[] = $row;
            }
        }
            return $films;
    }

    public function validate($data)
    {

        $connection = self::dbConnection();
        if ($data[self::TITLE]) {
            $this->validated[self::TITLE] = mysqli_real_escape_string($connection, $data[self::TITLE]);
            $this->validated[self::TITLE] = str_replace(['\n', '\r', '\\', '<', '>', ';'], '', $this->validated[self::TITLE]);

        } else {
            $this->setError("Field 'Title' is empty");
            return false;
        }

        if ($data[self::RELEASE_YEAR]) {
            if (is_numeric($data[self::RELEASE_YEAR])) {
                $this->validated[self::RELEASE_YEAR] = mysqli_real_escape_string($connection, $data[self::RELEASE_YEAR]);
            } else {
                $this->setError("Enter correct Year");
                return false;
            }
        } else {
            $this->setError("Field 'Year' is empty");
            return false;
        }

        if ($data[self::FORMAT]) {
            if ($data[self::FORMAT] == 'VHS' or 'DVD' or 'Blu-Ray') {
                $this->validated[self::FORMAT] = mysqli_real_escape_string($connection, $data[self::FORMAT]);
            } else {
                $this->setError("Enter correct Format");
                return false;
            }
        } else {
            $this->setError("Field 'Format' is empty");
            return false;
        }
        if ($data[self::STARS]) {
            $this->validated[self::STARS] = mysqli_real_escape_string($connection, $data[self::STARS]);
            $this->validated[self::STARS] = str_replace(['\n', '\r', '\\', '<', '>', ';'], '', $this->validated[self::STARS]);
        } else {
            $this->setError("Field 'Stars' is empty");
            return false;
        }
        return true;
    }

    public function setError($error)
    {
        $errors[] = $error;
        $session = new SessionController();
        $session->setFlash('errors', $errors);
    }

    public function setSuccess($success)
    {
        $successes[] = $success;
        $session = new SessionController();
        $session->setFlash('success', $successes);
    }

    public function setSearchError($error)
    {
        $errors[] = $error;
        $session = new SessionController();
        $session->setFlash('searchError', $errors);
    }
}