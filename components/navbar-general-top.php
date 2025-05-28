
    <?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/styles/header-include.php' ?>
</head>

<body>
    <nav class="pure-g">

        <div class="pure-u-md-1-5 pure-u-1-2" id="logoId">
            <a href="<?= '//'.$_SERVER['SERVER_NAME']?>/msmr"><img src="<?= '//'.$_SERVER['SERVER_NAME']?>/msmr/res/simple-logo.png" class="logo"></a>
        </div>

        <div class="pure-u-md-1-2 pure-u-1-2 menu">
            <a href="#" onclick="openmenu()"><i class="fa-solid fa-bars" id="menuIcon"></i></a>
        </div>

        <div class="pure-u-md-3-5" id="menu-elem">
            <div class="pure-menu pure-menu-horizontal navBtns" id="menuUno">
                <ul class="pure-menu-list">

                    <li class="pure-menu-item ">
                    <a href="<?= '//'.$_SERVER['SERVER_NAME']?>/msmr/?s=#ChiSiamo" class="menu-color pure-menu-link ">Chi siamo</a>
                    </li>

                    <li class="pure-menu-item ">
                    <a href="<?= '//'.$_SERVER['SERVER_NAME']?>/msmr/?s=#Spedire" class="pure-menu-link menu-color">Spedire</a>
                    </li>

                    <li class="pure-menu-item">
                    <a href="<?= '//'.$_SERVER['SERVER_NAME']?>/msmr/?s=#Tracking" class="pure-menu-link menu-color">Tracking</a>
                    </li>

                    <li class="pure-menu-item ">
                        <a href="//<?= $_SERVER['SERVER_NAME'] ?>/msmr/abbonamenti/listino.php" class="pure-menu-link menu-color">Tariffe</a>
                    </li>

                    <?php
                        $oggettoMail = "Piattaforma MSMR - Richiesta di assistenza - Utente: ";
                        if(isset($_SESSION["username"])) 
                            $oggettoMail.=$_SESSION["username"];
                        else 
                            $oggettoMail.="No User";

                        $oggettoMail.=" - ".date('m/d/Y h:i:s a', time());
                        $oggettoMail = htmlentities($oggettoMail);
                    ?>

                    <li class="pure-menu-item ">
                        <a href="mailto:bellamiaantonio06@gmail.com?subject=<?=$oggettoMail?>&body=<?=$oggettoMail?>" class="pure-menu-link menu-color">Assistenza</a>
                    </li>
                </ul>


            </div>



        </div>

        <div class="pure-u-md-1-5" id="menu-elem2">
            <div class="login pure-menu pure-menu-horizontal login" id="menuDue">

                <ul class="pure-menu-list">
                    <li class="pure-menu-item">