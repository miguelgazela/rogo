<?php
    // Connect to the Database
    $db = new PDO('pgsql:host=vdbm;dbname=lbaw12201', 'username', 'password');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    //$db->exec("SET search_path TO proto"); // the schema is in public, no need for this
?>