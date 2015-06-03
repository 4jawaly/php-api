<?php
require_once 'mainmodel.php';
$model = new MainModel();
$alert = false;
$settings = $model->getSettings();
$username = $settings->username;
$password = $settings->password;
$set_language = $settings->language;
$language = isset($_GET['language']) ? $_GET['language'] : $set_language;
$strings = $model->getStrings($language);
if (isset($_POST['send_message'])) {
    $sender = $_POST['sender'];
    $message = $_POST['message'];
    $numbers = $_POST['numbers'];
    $request = $model->sendMessage($username, $password, $sender, $message, $numbers);
    if ($request == 100) {
        $alert = true;
        $type = 'success';
        $index = 'send_' . $request;
        $alert_message = $strings->$index;
    } else {
        $alert = true;
        $type = 'danger';
        $index = 'send_' . $request;
        $alert_message = $strings->$index;
    }
}
$balance = $model->getBalance($username, $password);
$senders = $model->getSenders($username, $password);
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a value="English" class="btn btn-success" href="index.php?language=<?= $language == 'ar' ? 'en' : 'ar' ?>"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> <?= $language == 'ar' ? 'English' : 'العربية' ?></a>
                </div>
                <div class="panel-body">
                    <?php if ($alert) { ?>
                        <div class="alert alert-<?= $type ?>" role="alert"><?= $alert_message ?></div>
                    <?php } ?>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#"><?= $strings->sending ?></a></li>
                        <li><a href="settings.php?language=<?= $language ?>"><?= $strings->settings ?></a></li>
                        <li><a href="senders.php?language=<?= $language ?>"><?= $strings->senders ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="send_tab" class="tab-pane fade in active">
                            <div class="alert alert-info"><?= $strings->your_balance ?> : <?= $balance ?></div>
                            <form action="" method="post" id="validate_form">
                                <div class="col-md-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading"><?= $strings->send_form ?></div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?= $strings->sender ?></label>
                                                <select class="form-control" name="sender">
                                                    <option value=""><?= $strings->choose_sender ?></option>
                                                    <?php if ($senders) { ?>
                                                        <?php foreach ($senders as $s) { ?>
                                                            <?php if ($s->State == 'Yes') { ?>
                                                                <option value="<?= $s->SenderName ?>" ><?= $s->SenderName ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <a href="senders.php" class="btn btn-info"><?= $strings->request_sender ?></a>
                                            </div>
                                            <div class="form-group">
                                                <label for=""><?= $strings->numbers ?></label>
                                                <textarea name="numbers" class="form-control"></textarea>
                                                <p class="text-warning"><?= $strings->comma_numbers ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label for=""><?= $strings->message ?></label>
                                                <textarea name="message" onchange="calcsmsnumbers(this)" onkeyup="calcsmsnumbers(this);" id="txtMessage"  class="form-control"></textarea>
                                                <p class="text-info"><span id="chardone">0</span> <?= $strings->chars_count ?></p>
                                                <p class="text-warning"><span id="charleft">160</span> <?= $strings->remain_count ?></p>
                                                <p class="text-success"><span id="smscount">1</span> <?= $strings->messages_count ?></p>
                                            </div>
                                            <input type="submit" class="btn btn-info" name="send_message" value="<?= $strings->send ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="settings_tab" class="tab-pane fade">
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
        <script>
            $("#validate_form").validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    sender: {
                        required: true
                    },
                    message: {
                        required: true
                    },
                    numbers: {
                        required: true,
                    },
                },
                messages: {// custom messages for radio buttons and checkboxes
                    'sender': {
                        required: '<?= $strings->required_field ?>',
                    },
                    'message': {
                        required: '<?= $strings->required_field ?>',
                    },
                    'numbers': {
                        required: '<?= $strings->required_field ?>',
                    },
                },
                invalidHandler: function (event, validator) { //display error alert on form submit
                },
                highlight: function (element) { // hightlight error inputs
                    $(element)
                            .closest('.form-group').addClass('has-error'); // set error class to the control group
                },
                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                            .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },
                success: function (label, element) {
                    label
                            .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });

            function calcsmsnumbers(me) {
                x = $(me).val().split('\n');
                var chars = $(me).val().length + x.length - 1;
                if (CheckForNonLatinCodepoints($(me).val())) {
                    if (chars > 70) {
                        var IncreaseSize = 67;
                    } else {
                        var IncreaseSize = 70;
                    }
                    $(me).css("direction", "rtl");
                }
                else {
                    $(me).css("direction", "ltr");
                    if (chars > 160) {
                        var IncreaseSize = 153;
                    } else {
                        var IncreaseSize = 160;
                    }
                }
                var max = IncreaseSize * 11;
                setmaxlengh(me, max);
                messages = Math.ceil(chars / IncreaseSize),
                        remaining = messages * IncreaseSize - (chars % (messages * IncreaseSize) || messages * IncreaseSize);
                $("#chardone").text(chars);
                $("#charleft").text(remaining);
                $("#smscount").text(messages);
            }
            function CheckForNonLatinCodepoints(Text) {

                var NumberOfTries = 4;
                var ContainsUnicodeCharacters = false;

                for (i = 0; i < NumberOfTries; i++) {
                    if (!ContainsUnicodeCharacters)
                        ContainsUnicodeCharacters = /[\u0100-\uFFFF]+/g.test(Text);
                }

                return ContainsUnicodeCharacters;
            }
            function setmaxlengh(me, limit) {
                var text = $(me).val();
                var chars = text.length;
                if (chars > limit) {
                    var new_text = text.substr(0, limit);
                    $(me).val(new_text);
                }
            }
        </script>
    </body>
</html>
