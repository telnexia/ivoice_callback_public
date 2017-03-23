<?php
    include "../autoload.php";

    $params = strtolower($_SERVER['REQUEST_METHOD']) == 'get' ? $_GET : $_POST;

    $iVoiceAPI = new iVoiceAPI();

    $response = $iVoiceAPI->scheduleCallback($params);

    if ($response != false && !empty($response)) {
        echo json_encode($response);
    } else {
        echo json_encode(array(
            'error' => true,
            'msg'   => 'Could not do scheduling, service do not respond.'
        ));
    }

?>