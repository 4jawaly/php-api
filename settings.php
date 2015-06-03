<?php
require_once 'mainmodel.php';
$model = new MainModel();
$alert = false;
if (isset($_POST['save_settings'])) {
    $sets = array(
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'language' => $_POST['language'],
        'sp_folder' => $_POST['sp_folder'],
    );
    //set settings
    $request = $model->setSettings($sets);
    if ($request) {
        $alert = true;
        $type = 'success';
        $alert_message = 'Settings Saved!';
    } else {
        $alert = true;
        $type = 'danger';
        $alert_message = 'Save Error!';
    }
}
//get settings
$settings = $model->getSettings();
$username = $settings->username;
$password = $settings->password;
$sp_folder = $settings->sp_folder;
$set_language = $settings->language;
$language = isset($_GET['language']) ? $_GET['language'] : $set_language;
//get languages strings
$strings = $model->getStrings($language);
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
                    <a value="English" class="btn btn-success" href="settings.php?language=<?= $language == 'ar' ? 'en' : 'ar' ?>"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> <?= $language == 'ar' ? 'English' : 'العربية' ?></a>
                </div>
                <div class="panel-body">
                    <?php if ($alert) { ?>
                        <div class="alert alert-<?= $type ?>" role="alert"><?= $alert_message ?></div>
                    <?php } ?>
                    <ul class="nav nav-tabs">
                        <li class=""><a href="index.php?language=<?= $language ?>"><?= $strings->sending ?></a></li>
                        <li class="active"><a href="#"><?= $strings->settings ?></a></li>
                        <li><a href="senders.php?language=<?= $language ?>"><?= $strings->senders ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="send_tab" class="tab-pane fade">
                        </div>
                        <div id="settings_tab" class="tab-pane fade in active">
                            <form action="" method="post">
                                <div class="col-md-6">
                                    <div class="panel panel-info">
                                        <div class="panel-heading"><?= $strings->settings_form ?></div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label for=""><?= $strings->sp_folder ?> "<?= $strings->request_from_support ?>"</label>
                                                <input type="text" name="sp_folder" class="form-control" id="" placeholder="<?= $strings->sp_folder ?>" value="<?= $sp_folder ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for=""><?= $strings->username ?></label>
                                                <input type="text" name="username" class="form-control" id="" placeholder="<?= $strings->username ?>" value="<?= $username ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for=""><?= $strings->password ?></label>
                                                <input type="password" name="password" class="form-control" id="" placeholder="<?= $strings->password ?>" value="<?= $password ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for=""><?= $strings->language ?></label>
                                                <select class="form-control" name="language">
                                                    <option value="ar" <?= $language == 'ar' ? "selected='selected'" : "" ?>>العربية</option>
                                                    <option value="en" <?= $language == 'en' ? "selected='selected'" : "" ?>>English</option>
                                                </select>
                                            </div>
                                            <button type="submit" name="save_settings" class="btn btn-success"><?= $strings->save ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="logs_tab" class="tab-pane fade">
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
