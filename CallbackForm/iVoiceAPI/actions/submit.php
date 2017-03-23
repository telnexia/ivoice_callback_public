<?php
    include "../autoload.php";

    $params = strtolower($_SERVER['REQUEST_METHOD']) == 'get' ? $_GET : $_POST;
    

    $iVoiceAPI = new iVoiceAPI();
    
    $response = $iVoiceAPI->scheduleCallback($params);
          
    if ($response != false && !empty($response)) {
        if ($response['error'] == 0) {
            header('Location: '.get_settings('success_page.1').'?successMsg='.urlencode($response['msg']));
            exit();
        } else {
            header('Location: '.$settings['form_page'].'?errorMsg='.urlencode($response['msg']).'&old='.urlencode(base64_encode(json_encode($params))));
            exit();
        }
    } else {
        header('Location: '.$settings['form_page'].'?errorMsg='.urlencode('Could not do scheduling, service do not respond.'));
        exit();
    }

?>