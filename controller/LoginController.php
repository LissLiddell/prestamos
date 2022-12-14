<?php
    if($IsAjax){
        require_once "../models/LoginModel.php";
    }else{
        require_once "./models/LoginModel.php";
    }

    class LoginController extends LoginModel{
        /*------ login controller */
        public function start_session_controller(){
            $user=VmainModel::Fclean_string($_POST['usuario_log']);
            $key=VmainModel::Fclean_string($_POST['clave_log']);

             /* verify empty fields */
             if($user=="" || $key==""){
                echo '
                <script> 
                    Swal.fire({
                        title: "Ocurrio un inesperado",
                        text: "No has llenado todos los campos requeridos",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>
                ';
                exit();
             }

             /*verify data integrity */
             if(VmainModel::Fcheck_data("[a-zA-Z0-9]{1,35}",$user)){
                echo '
                <script> 
                    Swal.fire({
                        title: "Ocurrio un inesperado",
                        text: "El Nombre de Usuario no coincide con el formato permitido",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>
                ';
                exit();
             }

             if(VmainModel::Fcheck_data("[a-zA-Z0-9$@.-]{7,100}",$key)){
                echo '
                <script> 
                    Swal.fire({
                        title: "Ocurrio un inesperado",
                        text: "La contraseña capturada no coincide con el formato permitido",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>
                ';
                exit();
             }

             $key = VmainModel::encryption($key);

             $data_login = [
                "Usuario"=>$user,
                "Clave"=>$key
             ];

             $count_data = LoginModel::start_session_model($data_login);
             if($count_data->rowCount()==1){
                $row=$count_data->fetch();

                session_start(['name'=>'SPM']);

                $_SESSION['id_spm']=$row['usuario_id'];
                $_SESSION['nombre_spm']=$row['usuario_nombre'];
                $_SESSION['apellido_spm']=$row['usuario_apellido'];
                $_SESSION['usuario_spm']=$row['usuario_usuario'];
                $_SESSION['privilegio_spm']=$row['usuario_privilegio'];
                $_SESSION['token_spm']=md5(uniqid(mt_rand(),true));

                return header("Location: ".SERVERURL."home/");
             }else{
                echo '
                <script> 
                    Swal.fire({
                        title: "Ocurrio un inesperado",
                        text: "El usuario o clave son incorrectos",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>
                ';
             }
        } /* end of controller*/ 

         /*------ force log out controller */
         public function force_log_out_controller(){
            session_unset();
            session_destroy();
            if(headers_sent()){
                return "<script> window.location.href='".SERVERURL ."login/';</script>";
            }else{
                return header("Location: ".SERVERURL."login/");
            }
         } /* end of controller*/ 

         /*------ Log out controller */
         public function Log_out_controller(){
            session_start(['name'=>'SPM']);
            $token=VmainModel::decryption($_POST['token']);
            $usuario=VmainModel::decryption($_POST['usuario']);

            if($token==$_SESSION['token_spm'] && $usuario==$_SESSION['usuario_spm']){
                session_unset();
                session_destroy();
                $alert=[
                    "Alert"=>"redirect",
                    "URL"=>SERVERURL."login/"    
                ];
            }else{
                $alert=[
                    "Alert"=>"simple",
                    "title"=>"Ocurrio un error inesperado",
                    "text"=>"No se pudo cerrar sesion en el sistema",
                    "type"=>"error"
                ];
            }
            echo json_encode($alert);
         } /* end of controller*/ 
    }