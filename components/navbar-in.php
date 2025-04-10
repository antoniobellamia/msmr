

<?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/navbar-general-top.php' ?>

<a href="#" class="pure-menu-link menu-color ">
    <?php 
        if(isset($_SESSION["username"])) 
            echo $_SESSION["username"];
        else echo "Profilo";
    ?>
    <i class="fa-sharp fa-solid fa-circle-user"></i>
</a>

<?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/navbar-general-bott.php' ?>
                   