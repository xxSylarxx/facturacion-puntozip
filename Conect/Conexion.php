<?php

namespace Conect;

use Exception;
use PDO;

date_default_timezone_set('America/Lima');
class Conexion
{

    const HOST = 'localhost';
    const USER = 'root';
    const PASSWORD = '';
    const BDNAME = 'db_puntozip';
    public static function conectar()
    {
        try {
            $link = new PDO(
                "mysql:host=" . self::HOST . "; dbname=" . self::BDNAME . ";",
                self::USER,
                self::PASSWORD
            );
            $link->exec("set names utf8");
            return $link;
            //    } catch (\Throwable $th) {
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
