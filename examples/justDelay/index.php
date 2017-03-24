<?php

@ini_set('error_reporting', 1);
@ini_set('display_errors', 1);

include ('../../CallbackForm/iVoiceAPI/autoload.php');

// Set settings
iVoiceAPI::setSettings(__DIR__ . '/settings.ini.php');

$iVoiceAPI = new iVoiceAPI();
$optionsSchedule = $iVoiceAPI->getScheduler();

$errorMsg = '';
$errorFields = [];
$scheduledSuccesfully = false;

if (isset($optionsSchedule['list']['unavailable']) && $optionsSchedule['list']['unavailable'] == true) {
    header('Location: '.iVoiceAPI::getSettings('unavailable_page.1'));
    exit();
}

if (isset($_POST['ScheduleCall'])) {
    $params = strtolower($_SERVER['REQUEST_METHOD']) == 'get' ? $_GET : $_POST;
    $response = $iVoiceAPI->scheduleCallback($params);
    if ($response != false && !empty($response)) {
        if ($response['error'] == 0) {
            $scheduledSuccesfully = true;
        } else {
            $errorFields = isset($response['fields']) ? $response['fields'] : array();
            FormBuilder::setOld($params);
            $errorMsg = $response['msg'];
        }
    } else {
        header('Location: '.iVoiceAPI::getSettings('form_page.1').'?errorMsg='.urlencode('Could not do scheduling, service do not respond.'));
        exit();
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

<h1>This is HTML version of scheduling callback</h1>

<?php if (!empty($errorMsg)) : ?>
<p class="alert alert-danger" role="alert">      
   <?php foreach (explode("\n", $errorMsg) as $errorItem) : ?>
        <?php echo htmlspecialchars($errorItem)?><br/>
   <?php endforeach; ?>       
</p>
<?php elseif (isset($_GET['errorMsg'])) : ?>
    <p class="alert alert-danger" role="alert"><?= htmlspecialchars($_GET['errorMsg']) ?></p>
<?php endif; ?>

<?php if ($scheduledSuccesfully == true) : ?>
    <p class="alert alert-success" role="alert"><?php echo htmlspecialchars($response['msg'])?></p>
<?php elseif ( $optionsSchedule['list']['delay'] == true) : ?>
    <form action="<?= iVoiceAPI::getSettings('form_page.1') ?>" method="post" class="form-horizontal">
    
        <div class="form-group row<?php if (in_array('SurferName', $errorFields)) : ?> has-error<?php endif?>">
            <label for="SurferName" class="col-sm-2 control-label">Surfer Name:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="SurferName" name="SurferName" value="<?php echo htmlspecialchars(FormBuilder::getOld('SurferName')) ?>">
            </div>
        </div>
        
        <div class="form-group<?php if (in_array('TelNumber', $errorFields)) : ?> has-error<?php endif?>">
            <label for="TelNumber" class="col-sm-2 control-label">* Phone Number:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="TelNumber" name="TelNumber" value="<?php echo htmlspecialchars(FormBuilder::getOld('TelNumber')) ?>">
            </div>
        </div>

        <input type="hidden" id="Delay" name="Delay" value="1">    
        <input type="hidden" name="Country" id="Country" value="347">    
        <input type="hidden" class="form-control" id="CallReason" name="CallReason" value="Scheduling Callback" />

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="ScheduleCall" class="btn btn-default">Submit</button>
            </div>
        </div>
    </form>
<?php endif; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</div>

</body>
</html>