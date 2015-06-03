<?php
require_once 'mainmodel.php';
$model = new MainModel();
$alert = false;
$settings = $model->getSettings();
$username = $settings->username;
$password = $settings->password;
$sp_folder = $settings->sp_folder;
$set_language = $settings->language;
$language = isset($_GET['language']) ? $_GET['language'] : $set_language;
$strings = $model->getStrings($language);
if (isset($_POST['request_sender'])) {
    $sender = $_POST['sender'];
    $request = $model->requestSender($username, $password, $sender);
    if ($request == 117 || $request == 110) {
        $alert = true;
        $type = 'success';
        $index = 'sender_' . $request;
        $alert_message = $strings->$index;
    } else {
        $alert = true;
        $type = 'danger';
        $index = 'sender_' . $request;
        $alert_message = $strings->$index;
    }
} elseif (isset($_POST['verify_sender'])) {
    $mobile = $_POST['mobile'];
    $verify_code = $_POST['verify_code'];
    $request = $model->verifyMobile($username, $password, $mobile, $verify_code);
    if ($request == 110) {
        $alert = true;
        $type = 'success';
        $index = 'request_' . $request;
        $alert_message = $strings->$index;
    } else {
        $alert = true;
        $type = 'danger';
        $index = 'request_' . $request;
        $alert_message = $strings->$index;
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
    <head>
        <title><?=$strings->title?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <?php if ($language == 'ar') { ?>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.2.0-rc2/css/bootstrap-rtl.min.css">
        <?php } ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a value="English" class="btn btn-success" href="senders.php?language=<?= $language == 'ar' ? 'en' : 'ar' ?>"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> <?= $language == 'ar' ? 'English' : 'العربية' ?></a>
                </div>
                <div class="panel-body">
                    <?php if ($alert) { ?>
                        <div class="alert alert-<?= $type ?>" role="alert"><?= $alert_message ?></div>
                    <?php } ?>
                    <ul class="nav nav-tabs">
                        <li class=""><a href="index.php?language=<?= $language ?>"><?= $strings->sending ?></a></li>
                        <li><a href="settings.php?language=<?= $language ?>"><?= $strings->settings ?></a></li>
                        <li class="active"><a href="#"><?= $strings->senders ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="senders_tab" class="tab-pane fade in active">
                            <div class="col-md-6">
                                <div class="panel panel-info">
                                    <div class="panel-heading"><?= $strings->senders_form ?></div>
                                    <div class="panel-body">
                                        <form action="" method="post">
                                            <div class="form-group">
                                                <label for=""><?= $strings->sender ?></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="sender" placeholder="<?= $strings->sender ?>">
                                                    <span class="input-group-btn">
                                                        <input type="submit" name="request_sender" class="btn btn-success" value="<?= $strings->request_sender ?>"/>
                                                    </span>
                                                </div>
                                            </div>
                                        </form>
                                        <form action="" method="post">
                                            <div class="panel panel-warning">
                                                <div class="panel-heading">
                                                    <?= $strings->digi_sender_form ?>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <label for=""><?= $strings->mobile ?></label>
                                                        <input type="text" name="mobile" class="form-control" id="" placeholder="<?= $strings->mobile ?>" value="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for=""><?= $strings->verify_code ?></label>
                                                        <input type="text" name="verify_code" class="form-control" id="" placeholder="<?= $strings->verify_code ?>" value="">
                                                    </div>
                                                    <button type="submit" name="verify_sender" class="btn btn-success"><?= $strings->send ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container">
                    <p class="text-muted"><?= $strings->footer ?></p>
                </div>
            </footer>
        </div>
    </body>
</html>
