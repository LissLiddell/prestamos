<?php
    $IsAjax = true;
    require_once "../config/APP.php";

    if(isset($_POST['client_dni_reg'])){
        /*include instance for contoller  */
        require_once "../controller/ClientController.php";
        $ins_Client = new ClientController();

        /* Add a client */
        if(isset($_POST['client_dni_reg']) &&  isset($_POST['client_name_reg'])){
            echo $ins_Client->FAdd_client_controller();
        }


    }else{
        session_start(['name'=>'SPM']);
        session_unset(); 
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }