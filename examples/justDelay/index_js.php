<?php

@ini_set('error_reporting', 1);
@ini_set('display_errors', 1);

include ('../../CallbackForm/iVoiceAPI/autoload.php');

// Set settings
iVoiceAPI::setSettings(__DIR__ . '/settings.ini.js.php');

if (isset($_POST['ScheduleCall'])) {
    
    $iVoiceAPI = new iVoiceAPI();
    
    $optionsSchedule = $iVoiceAPI->getScheduler();
        
    if (isset($optionsSchedule['list']['unavailable']) && $optionsSchedule['list']['unavailable'] == true) {
        echo json_encode(array('error' => true, 'msg' => 'Scheduling callback is unavailable at the moment'));
        exit;
    }
        
    $response = $iVoiceAPI->scheduleCallback($_POST);
    if ($response != false && !empty($response)) {        
        echo json_encode($response) ;
        exit;  
    } else {
        echo json_encode(array('error' => true, 'msg' => 'Could not do scheduling, service do not respond.'));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Template</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container">

<h1>This is JS version of scheduling callback</h1>

<div id="response-handler"></div>

<form action="<?=iVoiceAPI::getSettings('form_page.1') ?>" method="post" class="form-horizontal" onsubmit="return iconnectelSchedule.scheduleCallback($(this))">

    <div class="form-group row">
        <label for="SurferName" class="col-sm-2 control-label">Surfer Name:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="SurferName" name="SurferName" value="">
        </div>
    </div>
    
    <div class="form-group">
        <label for="TelNumber" class="col-sm-2 control-label">* Phone Number:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="TelNumber" name="TelNumber" value="">
        </div>
    </div>

    <input type="hidden" id="Delay" name="Delay" value="1">    
    <input type="hidden" name="Country" id="Country" value="347">    
    <input type="hidden" class="form-control" id="CallReason" name="CallReason" value="Scheduling Callback" />
    <input type="hidden" name="ScheduleCall" value="Scheduling Callback" />

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="ScheduleCallAction" class="btn btn-default">Submit</button>
        </div>
    </div>
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script src="schedule.js?<?php echo time()?>"></script>

</div>

</body>
</html>