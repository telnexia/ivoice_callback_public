<?php

/* @ini_set('error_reporting', 1);
@ini_set('display_errors', 1); */

include ('iVoiceAPI/autoload.php');
$iVoiceAPI = new iVoiceAPI();
$optionsSchedule = $iVoiceAPI->getScheduler();

$errorMsg = '';
$errorFields = [];

if (isset($optionsSchedule['list']['unavailable']) && $optionsSchedule['list']['unavailable'] == true) {
    header('Location: '.iVoiceAPI::getSettings('unavailable_page.1'));
    exit();
}

if (isset($_POST['ScheduleCall'])) {
    $params = strtolower($_SERVER['REQUEST_METHOD']) == 'get' ? $_GET : $_POST;
    $response = $iVoiceAPI->scheduleCallback($params);
    
    if ($response != false && !empty($response)) {
        if ($response['error'] == 0) {
            header('Location: '.iVoiceAPI::getSettings('success_page.1').'?successMsg='.urlencode($response['msg']));
            exit();
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
    <h1><a href="<?= iVoiceAPI::getSettings('form_page.1') ?>">Test form</a></h1>
    
   <?php if (!empty($errorMsg)) : ?>
   <p class="alert alert-danger" role="alert">      
       <?php foreach (explode("\n", $errorMsg) as $errorItem) : ?>
            <?php echo htmlspecialchars($errorItem)?><br/>
       <?php endforeach; ?>       
   </p>
   <?php elseif (isset($_GET['errorMsg'])) : ?>
        <p class="alert alert-danger" role="alert"><?= htmlspecialchars($_GET['errorMsg']) ?></p>
   <?php endif; ?>
    
    <form action="" method="post" class="form-horizontal">
        <?= FormBuilder::createHidden('Field02', FormBuilder::getOld('Field02')) ?>
        <?= FormBuilder::createHidden('Field03', FormBuilder::getOld('Field03')) ?>
        <?= FormBuilder::createHidden('Field04', FormBuilder::getOld('Field04')) ?>
        <?= FormBuilder::createHidden('Field05', FormBuilder::getOld('Field05')) ?>
        <?= FormBuilder::createHidden('Field06', FormBuilder::getOld('Field06')) ?>
        
        <input type="hidden" name="ReferralID" value="<?php echo htmlspecialchars(FormBuilder::getOld('ReferralID',isset($_GET['id']) ? $_GET['id'] : '')); ?>" />

        <div class="form-group row<?php if (in_array('SurferName', $errorFields)) : ?> has-error<?php endif?>">
            <label for="SurferName" class="col-sm-2 control-label">* Surfer Name:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="SurferName" name="SurferName" value="<?php echo htmlspecialchars(FormBuilder::getOld('SurferName')) ?>">
            </div>
        </div>

        <?php /* Additional fields are optional, because of that validation has to be done internally, before calling API */ ?>
        <div class="form-group row<?php if (in_array('Field01', $errorFields)) : ?> has-error<?php endif?>">
            <label for="Field01" class="col-sm-2 control-label">* Business Name:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="Field01" name="Field01" value="<?php echo htmlspecialchars(FormBuilder::getOld('Field01')) ?>">
                <input type="hidden" class="form-control" id="Field01Required" name="Field01Required" value="on">
            </div>
        </div>

        <div class="form-group row<?php if (in_array('CallReason', $errorFields)) : ?> has-error<?php endif?>">
            <label for="CallReason" class="col-sm-2 control-label">* Reason:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="CallReason" name="CallReason" value="<?php echo htmlspecialchars(FormBuilder::getOld('CallReason')) ?>" />
            </div>
        </div>
        
        <div class="form-group<?php if (in_array('Country', $errorFields)) : ?> has-error<?php endif?>">
            <label for="Country" class="col-sm-2 control-label">* Country:</label>
            <div class="col-sm-10">
                <select class="form-control" name="Country" id="Country">
                    <?= FormBuilder::createCountriesOptions(FormBuilder::getOld('Country', null)) ?>
                </select>
            </div>
        </div>
        
        <div class="form-group<?php if (in_array('TelNumber', $errorFields)) : ?> has-error<?php endif?>">
            <label for="TelNumber" class="col-sm-2 control-label">* Phone Number:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="TelNumber" name="TelNumber" value="<?php echo htmlspecialchars(FormBuilder::getOld('TelNumber')) ?>">
            </div>
        </div>

        <h2>Scheduling Options</h2>

        <?php if ($optionsSchedule['list']['delay'] == true) : ?>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="checkbox" id="Delay" name="Delay" value="0" <?php FormBuilder::getOld('Delay', null) == '0' ? print ' checked="checked" ' : ''?>>
                <label for="Delay" class="control-label">* Ask the next available agent to call me back</label>
            </div>
        </div>
        <h4 class="hide-delay">OR Specify a date and time that suits you.</h4>
        <?php else : ?>
         <h4>Specify a date and time that suits you.</h4>
        <?php endif; ?>

        <div class="form-group hide-delay<?php if (in_array('ScheduleDay', $errorFields)) : ?> has-error<?php endif?>">
            <label for="ScheduleDay" class="col-sm-2 control-label">Day:</label>
            <div class="col-sm-10">
                <select class="form-control" name="ScheduleDay" id="ScheduleDay">
                    <option value="">Please select</option>
                </select>
            </div>
        </div>

        <div class="form-group hide-delay<?php if (in_array('ScheduleTime', $errorFields)) : ?> has-error<?php endif?>">
            <label for="ScheduleTime" class="col-sm-2 control-label">Time:</label>
            <div class="col-sm-10">
                <select class="form-control" name="ScheduleTime" id="ScheduleTime">
                    <option value="">Please select</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="ScheduleCall" class="btn btn-default">Submit</button>
            </div>
        </div>
    </form>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<?php include 'iVoiceAPI/template/sheduler.tpl.php' ?>
</body>
</html>