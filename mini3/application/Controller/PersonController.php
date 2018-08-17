<?php

namespace Mini\Controller;

use Mini\Model\Person;
use Mini\Libs\Helper;
use \Exception;

class PersonController
{
    public function index($message = null)
    {
        $Person = new Person();
        $dropDownList = $Person->getAllPersons();
        $allPersons = $Person->getAllPersons();

        require APP . 'view/_templates/header.php';
        require APP . 'view/person/index.php';
        require APP . 'view/_templates/footer.php';
    }

    public function addPerson()
    {
        $Person = new Person();
        $result = $Person->addPerson($_POST["txtName"], $_POST["txtSurName"]);
        $message = $result != false ? "Persona almacenada correctamente" : "La persona no se pudo almacenar correctamente";
        $retorno = array("mensaje" => $message);
        echo json_encode($retorno);
        return;
    }

    public function images()
    {
        require APP . 'view/_templates/header.php';
        require APP . 'view/person/images.php';
        require APP . 'view/_templates/footer.php';
    }

    public function ajaxImages()
    {
        $infoUploads = Helper::uploadOnlyPhotos('person', $_FILES, 'txtPhoto');
        // $json = array('files' => $_FILES, 'data' => $_POST);
        echo json_encode($infoUploads);
        return;
    }

    public function saveImages()
    {
        $Person = new Person();
        $message = '';

        $idPerson = $Person->addPerson($_POST["txtName"], $_POST["txtSurName"]);
        if ($idPerson != false) {

            $message .= "Persona almacenada correctamente.\n";

            //Metodo para subir una sola imagen y asociarla a la persona creada anteriormente.
            $infoUploads = Helper::uploadOnlyPhotos('person/'.$idPerson, $_FILES, 'txtPhoto');
            if ($infoUploads['uploadOk'] != 0) {
                $updatePhoto = $Person->updatePhotoOfPerson($infoUploads['pathSave'], $idPerson);
                if ($updatePhoto == false) {
                    $message .= 'La imagen de la persona no se pudo actualizar correctamente.\n';
                    Helper::deletePhoto(PUBLIC_FOLDER.$infoUploads['pathSave']);
                }
            } else {
                $message .= $infoUploads['message'].'\n';
            }

            //Metodo para subir multiples fotos y asociarlas en una tabla de la persona
            $infoMultipleUploads = Helper::uploadMultiplePhoto('person/' . $idPerson . '/gallery', $_FILES, 'txtMultiple');
            if ($infoMultipleUploads['uploadOk'] != 0) {
                $savedGalleries = $Person->saveGalleryOfPerson($infoMultipleUploads['pathSave'], $idPerson);
                
                if ($savedGalleries['uploadOk'] == 0) {
                    $message .= $savedGalleries['message'].'\n';
                    foreach ($infoMultipleUploads['pathSave'] as $path) {
                        Helper::deletePhoto(PUBLIC_FOLDER.$path);
                    }
                }
            } else {
                $message .= $infoMultipleUploads['message'].'\n';
            }

        } else {
            $message = "La persona no se pudo almacenar correctamente.\n";
        }

        $dropDownList = $Person->getAllPersons();
        $allPersons = $Person->getAllPersons();

        require APP . 'view/_templates/header.php';
        require APP . 'view/person/index.php';
        require APP . 'view/_templates/footer.php';
        
    }
}
