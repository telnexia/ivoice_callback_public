<?php
    include "../autoload.php";

    $iVoiceAPI = new iVoiceAPI();

    echo json_encode($iVoiceAPI->getScheduler());
?>