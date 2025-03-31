<?php
error_reporting(E_PARSE);

require_once("../../vendor/autoload.php");

use Conect\Conexion;
//Nombre de usuario de mysql
// const USER = "root";

// //Servidor de mysql
// const SERVER = "127.0.0.1";

// //Nombre de la base de datos
// const BD = "facturacionbmm";

// //Contraseña de myqsl
// const PASS = "1105gvi";

//Carpeta donde se almacenaran las copias de seguridad
const BACKUP_PATH =  "backup/";

/*Configuración de zona horaria de tu país para más información visita
    http://php.net/manual/es/function.date-default-timezone-set.php
    http://php.net/manual/es/timezones.php
*/
date_default_timezone_set('America/Lima');


class SGBD extends Conexion
{
    //Funcion para hacer consultas a la base de datos
    public static function sql($query)
    {
        $conect = Conexion::Conectar();
        $con = mysqli_connect(self::HOST, self::USER, self::PASSWORD, self::BDNAME);
        mysqli_set_charset($con, "utf8");
        if (mysqli_connect_errno()) {
            printf("Conexion fallida: %s\n", mysqli_connect_error());
            exit();
        } else {
            mysqli_autocommit($con, false);
            mysqli_begin_transaction($con, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);
            if ($consul = mysqli_query($con, $query)) {
                if (!mysqli_commit($con)) {
                    print("Falló la consignación de la transacción\n");
                    exit();
                }
            } else {
                mysqli_rollback($con);
                echo "Falló la transacción";
                exit();
            }
            return $consul;
        }
    }

    //Funcion para limpiar variables que contengan inyeccion SQL
    public static function limpiarCadena($valor)
    {
        $valor = addslashes($valor);
        $valor = str_ireplace("<script>", "", $valor);
        $valor = str_ireplace("</script>", "", $valor);
        $valor = str_ireplace("SELECT * FROM", "", $valor);
        $valor = str_ireplace("DELETE FROM", "", $valor);
        $valor = str_ireplace("UPDATE", "", $valor);
        $valor = str_ireplace("INSERT INTO", "", $valor);
        $valor = str_ireplace("DROP TABLE", "", $valor);
        $valor = str_ireplace("TRUNCATE TABLE", "", $valor);
        $valor = str_ireplace("--", "", $valor);
        $valor = str_ireplace("^", "", $valor);
        $valor = str_ireplace("[", "", $valor);
        $valor = str_ireplace("]", "", $valor);
        $valor = str_ireplace("\\", "", $valor);
        $valor = str_ireplace("=", "", $valor);
        return $valor;
    }
}
