<?php

/**
 * Class Person
 * This is a Model class for persons.
 *
 */

namespace Mini\Model;

use Mini\Core\Model;
use \Exception, \PDOException;

class Person extends Model
{
    /**
     * Get all person from database
     */
    public function getAllPersons()
    {
        $sql = "SELECT idPerson, name, surName, photo, status FROM person";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * Add a person to database
     * TODO put this explanation into readme and remove it from here
     * Please note that it's not necessary to "clean" our input in any way. With PDO all input is escaped properly
     * automatically. We also don't use strip_tags() etc. here so we keep the input 100% original (so it's possible
     * to save HTML and JS to the database, which is a valid use case). Data will only be cleaned when putting it out
     * in the views (see the views for more info).
     * @param string $name Name of person
     * @param string $surName SurName of person
     */
    public function addPerson($name, $surName)
    {
        try {
            $sql = "INSERT INTO person (name, surName) VALUES (:name, :surName)";
            $query = $this->db->prepare($sql);
            $parameters = array(':name' => $name, ':surName' => $surName);
            return ($query->execute($parameters) ? $this->db->lastInsertId() : false);
        } catch (PDOException $ex) {
            return false;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Add a person to database
     * TODO put this explanation into readme and remove it from here
     * Please note that it's not necessary to "clean" our input in any way. With PDO all input is escaped properly
     * automatically. We also don't use strip_tags() etc. here so we keep the input 100% original (so it's possible
     * to save HTML and JS to the database, which is a valid use case). Data will only be cleaned when putting it out
     * in the views (see the views for more info).
     * @param string $name Name of person
     * @param string $surName SurName of person
     */
    public function addPersonWithSP($name, $surName)
    {
        try {
            $sql = "CALL SPCreatePerson(:name, :surName)";
            $query = $this->db->prepare($sql);
            $parameters = array(':name' => $name, ':surName' => $surName);
            if ($query->execute($parameters)) {
                $lastInsertId = $this->db->query("SELECT LAST_INSERT_ID()");
                return $lastInsertId->fetchColumn();
            } else {
                return false;
            }
        } catch (PDOException $ex) {
            return false;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function updatePhotoOfPerson($photo, $idPerson)
    {
        try {
            $sql = "UPDATE person SET photo = :photo WHERE idPerson = :idPerson";
            $query = $this->db->prepare($sql);
            $parameters = array(':photo' => $photo, ':idPerson' => $idPerson);
            return ($query->execute($parameters) ? true : false);
        } catch (PDOException $ex) {
            return false;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function saveGalleryOfPerson($gallery, $idPerson)
    {
        try {

            $this->db->beginTransaction();

            $savedGalleries = array('uploadOk' => 1, 'message' => '', 'paths' => array());

            foreach ($gallery as $path) {
                
                $sql = "INSERT INTO personGallery(idPerson, photo) VALUES (:idPerson, :photo)";

                $query = $this->db->prepare($sql);
                $parameters = array(':idPerson' => $idPerson, ':photo' => $path);
                if ($query->execute($parameters)) {
                    array_push($savedGalleries['paths'], $path);
                } else {
                    $savedGalleries['uploadOk'] = 0;
                    $savedGalleries['message'] = 'Ocurrio un error intentando guardar la imagen ' . $path;
                    return $savedGalleries;
                }
            }

            $this->db->commit();
            return $savedGalleries;

        } catch (PDOException $ex) {

            $this->db->rollBack();
            $savedGalleries['uploadOk'] = 0;
            $savedGalleries['message'] = 'Ocurrio un error intentando guardar la galeria: '. $ex->getMessage();
            return $savedGalleries;

        } catch (Exception $ex) {

            $this->db->rollBack();
            $savedGalleries['uploadOk'] = 0;
            $savedGalleries['message'] = 'Ocurrio un error intentando guardar la galeria: '. $ex->getMessage();
            return $savedGalleries;

        }
    }

    /**
     * Delete a person in the database
     * Please note: this is just an example! In a real application you would not simply let everybody
     * add/update/delete stuff!
     * @param int $idPerson Id of person
     */
    public function deletePerson($idPerson)
    {
        $sql = "DELETE FROM person WHERE idPerson = :idPerson";
        $query = $this->db->prepare($sql);
        $parameters = array(':idPerson' => $idPerson);

        // useful for debugging: you can see the SQL behind above construction by using:
        // echo '[ PDO DEBUG ]: ' . Helper::debugPDO($sql, $parameters);  exit();

        return $query->execute($parameters);
    }

    /**
     * Get a person from database
     * @param integer $idPerson
     */
    public function getPerson($idPerson)
    {
        $sql = "SELECT idPerson, name, surName, photo, status FROM person WHERE idPerson = :idPerson LIMIT 1";
        $query = $this->db->prepare($sql);
        $parameters = array(':idPerson' => $idPerson);

        // useful for debugging: you can see the SQL behind above construction by using:
        // echo '[ PDO DEBUG ]: ' . Helper::debugPDO($sql, $parameters);  exit();

        $query->execute($parameters);

        // fetch() is the PDO method that get exactly one result
        return ($query->rowcount() ? $query->fetch() : false);
    }

    /**
     * Update a person in database
     * // TODO put this explaination into readme and remove it from here
     * Please note that it's not necessary to "clean" our input in any way. With PDO all input is escaped properly
     * automatically. We also don't use strip_tags() etc. here so we keep the input 100% original (so it's possible
     * to save HTML and JS to the database, which is a valid use case). Data will only be cleaned when putting it out
     * in the views (see the views for more info).
     * @param string $name Name
     * @param string $surName SurName
     * @param int $status Status
     * @param int $idPerson Id Person
     */
    public function updatePerson($name, $surName, $status, $idPerson)
    {
        $sql = "UPDATE person SET name = :name, surName = :surName, status = :status WHERE idPerson = :idPerson";
        $query = $this->db->prepare($sql);
        $parameters = array(':name' => $name, ':surName' => $surName, ':status' => $status, ':idPerson' => $idPerson);

        // useful for debugging: you can see the SQL behind above construction by using:
        // echo '[ PDO DEBUG ]: ' . Helper::debugPDO($sql, $parameters);  exit();

        return $query->execute($parameters);
    }

    /**
     * Get simple "stats". This is just a simple demo to show
     * how to use more than one model in a controller (see application/controller/songs.php for more)
     */
    public function getAmountOfPersons()
    {
        $sql = "SELECT COUNT(idPerson) AS amount_of_persons FROM person";
        $query = $this->db->prepare($sql);
        $query->execute();

        // fetch() is the PDO method that get exactly one result
        return $query->fetch()->amount_of_persons;
    }

    /**
    * Get Gallery of person
    * @param integer $idPerson
    */
    public function getGalleryOfPerson($idPerson)
    {
        $sql = "SELECT idGallery, idPerson, photo FROM personGallery WHERE idPerson = :idPerson";
        $query = $this->db->prepare($sql);
        $parameters = array(':idPerson' => $idPerson);
        $query->execute($parameters);
        return $query->fetchAll();
    }
}
