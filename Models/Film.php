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


        $connection = self::dbConnection();

        $sql = "INSERT INTO films (title, release_year, format, stars) 
                    VALUES (?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE title= ?, release_year = ?, format = ?, stars = ?;";

        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, 'sisssiss',
            $this->validated[self::TITLE],
            $this->validated[self::RELEASE_YEAR],
            $this->validated[self::FORMAT],
            $this->validated[self::STARS],
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

    public function getAll($sorting = null)
    {
        $connection = self::dbConnection();
        switch ($sorting) {
            case null:
                $stmt = 'SELECT * FROM films';
                break;
            case 'A-Z':
                $stmt = 'SELECT * FROM films ORDER BY title ASC ';
                break;
            case 'Z-A':
                $stmt = 'SELECT * FROM films ORDER BY title DESC ';
                break;
            default:
                $this->setError('Sorting method does not exist');
                header('Location: /'); exit;
        }

        $result = mysqli_query($connection, $stmt);
        $films = [];
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $films[] = $row;
            }
        }

        return $films;
    }

    public function getSome($searchBy, $data)
    {
        $connection = self::dbConnection();
        if (!$data){
            $this->setSearchError('Enter any data for search');
            header('Location: /'); exit;
        }

        $data = preg_replace("#[^0-9,a-z ]#i", '', $data);

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

        if (empty($films)){
            $this->setSearchError('No matches');
            header('Location: /'); exit;
        }
            return $films;
    }

    public function validate($data)
    {
        if ($data[self::TITLE]) {
            $this->validated[self::TITLE] = $data[self::TITLE];
        } else {
            $this->setError("Field 'Title' is empty");
            return false;
        }

        if ($data[self::RELEASE_YEAR]) {
            if (is_numeric($data[self::RELEASE_YEAR])) {
                $this->validated[self::RELEASE_YEAR] = $data[self::RELEASE_YEAR];
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
                $this->validated[self::FORMAT] = $data[self::FORMAT];
            } else {
                $this->setError("Enter correct Format");
                return false;
            }
        } else {
            $this->setError("Field 'Format' is empty");
            return false;
        }
        if ($data[self::STARS]) {
            $this->validated[self::STARS] = $data[self::STARS];
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