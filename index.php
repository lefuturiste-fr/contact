<?php 
require "mail.php";
require "recaptcha.php";

$captcha = new recaptcha('PUBLIC KEY', 'SECRET KEY');

if (isset($_POST['btn'])) {
        if (empty($_POST['name'])) {
            $errors['empty-name'] = 'Le nom n\'a pas été remplie !' ;
        }
        if (empty($_POST['email'])) {
            $errors['empty-email'] =  'L\'email n\'a pas été remplie !';
        }   
        if (empty($_POST['message'])) {
            $errors['empty-message'] =  'Le message n\'a pas été remplie !';
        }
        if (empty($_POST['object'])) {
            $errors['empty-message'] =  'L\'objet n\'a pas été remplie !';
        }
        if (empty($_POST['g-recaptcha-response'])) {
            $errors['empty-recaptcha'] = 'Veuillez renseigner le ReCaptcha';
        }
        if (!empty($_POST['g-recaptcha-response']) && $captcha->isSuccess($_POST['g-recaptcha-response']) == false) {
            $errors['error-recaptcha'] =  'Le recaptcha est incorect !';
        }
        else{
            if (!isset($errors)) {
                
                $sql->insertLogContact($_POST['name'], $_POST['email'], $_POST['message']);

                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
                    echo "Fatal error";
                    exit();
                }
                else{
                    //success
                    $mail = new mail;
                    $mail->sender = "sender@domain.com";
                    $mail->nameSender = "name of sender";
                    $mail->recipient = "recipient@domain.com";
                    $mail->object = "Object";
                    $mail->htmlMessage = "<h1>New message</h1> <ul><li>Name : ".$_POST['name']."</li><li>Email : ".$_POST['email']."</li><li>Object : ".$_POST['object']."</li><li>Message : <p>".$_POST['message']."</p></li><li>Ip : ".$_SERVER['REMOTE_ADDR']."</li><li>PostTime : ".date('d-m-Y').' // '.date('H:i')."</li></ul>";
                    $mail->sendMail();
                }
                $success = true;
            }        
     
        }
}


if (isset($errors) && !empty($errors))
{
?>
<?= $captcha->getScript() ?>
<div class="alert alert-danger">
    <?= implode('<br>', $errors); ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php
}
if (isset($success) && $success == true) {
?>
<div class="alert alert-success">
    Votre message à bien été envoyé !
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php
}
?>
<?= $captcha->getScript() ?>
<form class="form-contact" action="" method="post">
    <div class="form-group row">
        <div class="col-md-6 form-group">
            <input type="text" class="form-control" placeholder="Votre nom" title="Votre Nom" value="<?php if(isset($errors)){echo $_POST['name'];}?>" name="name" required>
        </div>
        <div class="col-md-6 form-group">
            <input type="email" class="form-control col-md-6" placeholder="Votre email" title="Votre Email" value="<?php if(isset($errors)){echo $_POST['email'];}?>" name="email" required>
        </div>
    </div>    
    <div class="form-group">
        <input type="text" class="form-control col-md-6" placeholder="Objet du message" title="L'objet de votre message" value="<?php if(isset($errors)){echo $_POST['object'];}?>" name="object" required>
    </div>
    <div class="form-group">
        <textarea type="text" class="form-control" placeholder="Votre message" name="message" required><?php if(isset($errors)){echo $_POST['message'];}?></textarea> 
    </div>
    <div class="form-group">
        <?= $captcha->getHtml() ?>
    </div>
    <button class="btn btn-primary" name="btn">
        Envoyer
    </button>
</form>