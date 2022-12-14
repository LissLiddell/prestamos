<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title><?php echo COMPANY; ?></title>
    <?php include "./view/inc/css.php"; ?>

</head>
<body>	
    <?php 
        $IsAjax=False;
        require_once "./controller/Vcontroller.php";
        $IV = new ViewController();

        $view = $IV->get_view_controller();

        if($view=="login" || $view=="404"){
            require_once "./view/content/".$view."-v.php";
        }else{
            session_start(['name'=>'SPM']);

            $page=explode("/", $_GET['views']);

            require_once "./controller/LoginController.php";
            $lc = new LoginController();
            if(!isset($_SESSION['token_spm']) || !isset($_SESSION['usuario_spm']) || !isset($_SESSION['privilegio_spm']) || !isset($_SESSION['id_spm'])){
                echo $lc->force_log_out_controller();
                exit();
            }
    ?>
	<!-- Main container -->
	<main class="full-box main-container">
    <?php include "./view/inc/LateralNav.php"; ?>
		<!-- Page content -->
		<section class="full-box page-content">
        <?php 
            include "./view/inc/NavBar.php"; 
            include $view;
        ?>
		</section>
	</main>
    <?php 
        include "./view/inc/LogOut.php"; 
        }
    include "./view/inc/script.php"; 
    ?>
</body>
</html>