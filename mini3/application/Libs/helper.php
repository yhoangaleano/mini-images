<?php

namespace Mini\Libs;

class Helper
{
    /**
     * debugPDO
     *
     * Shows the emulated SQL query in a PDO statement. What it does is just extremely simple, but powerful:
     * It combines the raw query and the placeholders. For sure not really perfect (as PDO is more complex than just
     * combining raw query and arguments), but it does the job.
     *
     * @author Panique
     * @param string $raw_sql
     * @param array $parameters
     * @return string
     */
    public static function debugPDO($raw_sql, $parameters)
    {
        $keys = array();
        $values = $parameters;

        foreach ($parameters as $key => $value) {

            // check if named parameters (':param') or anonymous parameters ('?') are used
            if (is_string($key)) {
                $keys[] = '/' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

            // bring parameter into human-readable format
            if (is_string($value)) {
                $values[$key] = "'" . $value . "'";
            } elseif (is_array($value)) {
                $values[$key] = implode(',', $value);
            } elseif (is_null($value)) {
                $values[$key] = 'NULL';
            }
        }

        /*
        echo "<br> [DEBUG] Keys:<pre>";
        print_r($keys);

        echo "\n[DEBUG] Values: ";
        print_r($values);
        echo "</pre>";
        */

        $raw_sql = preg_replace($keys, $values, $raw_sql, 1, $count);

        return $raw_sql;
    }

    public static function uploadOnlyPhotos($folderSave, $files, $key)
    {
        $infoUpload = array('uploadOk' => 1, 'message' => '', 'pathSave' => '');
        $targetDir = UPLOADS_FOLDER.$folderSave."/";
        $pathSave = "uploads/".$folderSave."/";

        if (isset($files[$key]['name']) && $files[$key]['error'] == 0) {
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = time().'_'.$files[$key]["name"];

            $targetFile = $targetDir . basename($fileName);
            $pathSave =  $pathSave .  basename($fileName);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            
            // Compruebe si el archivo de imagen es una imagen real o una imagen falsa
            $check = getimagesize($files[$key]["tmp_name"]);
            if ($check == false) {
                $infoUpload['message'] .= "El archivo que ha elegido no es una imagen. ";
                $infoUpload['uploadOk'] = 0;
            }
            
            // Comprueba si la imagen existe --- Para nuestro caso no funcionara
            if (file_exists($targetFile)) {
                $infoUpload['message'] .= "La imagen ya existe dentro de la carpeta en que se almacenara. ";
                $infoUpload['uploadOk'] = 0;
            }

            // Comprobar el tamaño del archivo 5MB (5 * 1000 * 1000 o 5 * 1024 * 1024)
            /**
             *
             * define('KB', 1024);
             * define('MB', 1048576);
             * define('GB', 1073741824);
             * define('TB', 1099511627776);
             *
             */
            if ($files[$key]["size"] > 5000000) {
                $infoUpload['message'] .= "Imagen demasiado grande. ";
                $infoUpload['uploadOk'] = 0;
            }

            // Limitar los formatos de archivo permitidos
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $infoUpload['message'] .= "Sólo se permiten archivos JPG, JPEG, PNG y GIF. ";
                $infoUpload['uploadOk'] = 0;
            }

            // Comprueba si $infoUpload['uploadOk'] es igual a 0 (Tiene errores)
            if ($infoUpload['uploadOk'] == 0) {
                return $infoUpload;
            // Si se pasan todas las comprobaciones, se carga la imagen
            } else {
                if (move_uploaded_file($files[$key]["tmp_name"], $targetFile)) {
                    $infoUpload['message'] = "El archivo ". basename($files[$key]["name"]). " fue subido. ";
                    $infoUpload['uploadOk'] = 1;
                    $infoUpload['pathSave'] = $pathSave;
                    return $infoUpload;
                } else {
                    $infoUpload['message'] .= "Se ha producido un error en la carga. ";
                    $infoUpload['uploadOk'] = 0;
                    return $infoUpload;
                }
            }
        } else {
            $infoUpload['uploadOk'] = 0;
            $infoUpload['message'] .= 'No existen imagenes disponibles para subir. ';
            return $infoUpload;
        }
    }

    public static function uploadMultiplePhoto($folderSave, $files, $key)
    {
        $filesToUpload = $files[$key];

        $infoUpload = array('uploadOk' => 1, 'message' => '', 'pathSave' => array());
        $targetDir = UPLOADS_FOLDER.$folderSave."/";
        $pathSave = "uploads/".$folderSave."/";
        $numPhotos = count($filesToUpload["name"]);

        if ($numPhotos > 0) {

            for ($i = 0; $i < $numPhotos; $i++) {

                if ( isset($filesToUpload["name"][$i]) && $filesToUpload["error"][$i] == 0) {
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
    
                    $fileName = time().'_'.$filesToUpload["name"][$i];
                    $targetFile = $targetDir . basename($fileName);
                    $pathSave =  "uploads/".$folderSave."/" .  basename($fileName);
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                
                    // Compruebe si el archivo de imagen es una imagen real o una imagen falsa
                    $check = getimagesize($filesToUpload["tmp_name"][$i]);
                    if ($check == false) {
                        $infoUpload['message'] .= 'La imagen ' . $i . ' en el orden enviado no es una imagen. ';
                        $infoUpload['uploadOk'] = 0;
                    }
                
                    // Comprueba si la imagen existe --- Para nuestro caso no funcionara
                    if (file_exists($targetFile)) {
                        $infoUpload['message'] .= "La imagen ' . $i . ' en el orden enviado ya existe dentro de la carpeta en que se almacenara. ";
                        $infoUpload['uploadOk'] = 0;
                    }
    
                    // Comprobar el tamaño del archivo 5MB (5 * 1000 * 1000 o 5 * 1024 * 1024)
                    /**
                     *
                     * define('KB', 1024);
                     * define('MB', 1048576);
                     * define('GB', 1073741824);
                     * define('TB', 1099511627776);
                     *
                     */
                    if ($filesToUpload["size"][$i] > 5000000) {
                        $infoUpload['message'] .= "Imagen ' . $i . ' en el orden enviado es demasiado grande. ";
                        $infoUpload['uploadOk'] = 0;
                    }
    
                    // Limitar los formatos de archivo permitidos
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        $infoUpload['message'] .= "Sólo se permiten archivos JPG, JPEG, PNG y GIF. Imagen " . $i . " en el orden enviado. ";
                        $infoUpload['uploadOk'] = 0;
                    }
    
                    // Comprueba si $infoUpload['uploadOk'] es igual a 0 (Tiene errores)
                    if ($infoUpload['uploadOk'] == 0) {

                        if( count($infoUpload['pathSave']) > 0 ){

                            foreach ($infoUpload['pathSave'] as $path) {
                                self::deletePhoto(PUBLIC_FOLDER.$path);
                            }

                            $infoUpload['pathSave'] = array();
                        }

                        $infoUpload['message'] .= 'No se almacenarón las imagenes. Por favor, verifique los errores e intente de nuevo. ';

                        return $infoUpload;
                    // Si se pasan todas las comprobaciones, se carga la imagen
                    }else {
                        if (move_uploaded_file($filesToUpload["tmp_name"][$i], $targetFile)) {
                            $infoUpload['message'] .= "El archivo ". basename($filesToUpload["name"][$i]). " fue subido. ";
                            $infoUpload['uploadOk'] = 1;
                            array_push($infoUpload['pathSave'], $pathSave);
                        } else {
                            $infoUpload['message'] .= "Imagen ' . $i . ' en el orden enviado se ha producido un error en la carga. ";
                            $infoUpload['uploadOk'] = 0;
                        }
                    }
                } else {
                    $infoUpload['uploadOk'] = 0;
                    $infoUpload['message'] .= 'La imagen ' . $i . ' en el orden enviado no se puede subir correctamente.';
                }
            }

        } else {
            $infoUpload['uploadOk'] = 0;
            $infoUpload['message'] .= 'No existen imagenes disponibles para subir. ';
        }

        return $infoUpload;
    }

    public static function deletePhoto($path)
    {
        if (file_exists($path)) {
            unlink($path);
            return true;
        } else {
            return false;
        }
    }

}
