<?php

include_once('userstorage.php');
include_once('auth.php');

// functions
function validate($post, &$data, &$errors) {
    //fullname, tajnum, address, email, pw1, pw2

    //fullname
    if (!isset($post['fullname'])) {
        $errors['fullname'] = ' Teljes név megadása kötelező!';
    }else if (trim($post['fullname']) === '') {
        $errors['fullname'] = ' Teljes név megadása kötelező!';
    }else {
        $data['fullname'] = $post['fullname'];
    }

    //tajnum
    if (!isset($post['tajnum'])) {
        $errors['tajnum'] = ' TAJ szám megadása kötelező!';
    }else if (trim($post['tajnum']) === '') {
        $errors['tajnum'] = ' TAJ szám megadása kötelező!';
    }else if (!filter_var($post['tajnum'], FILTER_VALIDATE_INT)) {
        $errors['tajnum'] = ' A TAJ szám rossz számformátumú!';
    }else {
        $tajnum = (int)$post['tajnum'];

        if(strlen($tajnum)!==9)
        {
            $errors['tajnum'] = ' A TAJ számnak 9 számjegyből kell állnia!';
        } else{
            $data['tajnum'] = $tajnum;
        }
    }

    //address
    if (!isset($post['address'])) {
        $errors['address'] = ' Értesítési cím megadása kötelező!';
    }else if (trim($post['address']) === '') {
        $errors['address'] = ' Értesítési cím megadása kötelező!';
    }else {
        $data['address'] = $post['address'];
    }

    //email
    if (!isset($post['email'])) {
        $errors['email'] = ' E-mail cím megadása kötelező!';
    }else if (trim($post['email']) === '') {
        $errors['email'] = ' E-mail cím megadása kötelező!';
    }else if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ' Az e-mail cím rossz formátumú!';
    }else {
        $data['email'] = $post['email'];
    }

    //pw1
    if (!isset($post['pw1'])) {
        $errors['pw1'] = ' Jelszó megadása kötelező!';
    }else if (trim($post['pw1']) === '') {
        $errors['pw1'] = ' Jelszó megadása kötelező!';
    }else if ($post['pw1'] !== $post['pw2']) {
        $errors['pw1'] = ' A megadott jelszavak nem egyeznek!';
    }else {
        $data['pw1'] = $post['pw1'];
    }

    //pw2
    if (!isset($post['pw2'])) {
        $errors['pw2'] = ' Jelszó megadása kötelező!';
    }else if (trim($post['pw2']) === '') {
        $errors['pw2'] = ' Jelszó megadása kötelező!';
    }else if ($post['pw2'] !== $post['pw1']) {
        $errors['pw2'] = ' A megadott jelszavak nem egyeznek!';
    }else {
        $data['pw2'] = $post['pw2'];
    }

  return count($errors) === 0;
}

function redirect($page) {
    header("Location: ${page}");
    exit();
}

$user_storage = new UserStorage();
$auth = new Auth($user_storage);
$errors = [];
$data = [];
if (count($_POST) > 0) {
  if (validate($_POST, $data, $errors)) {
    if ($auth->user_exists($data['email'])) {
      $errors['global'] = "User already exists";
    } else {
      $auth->register($data);
      redirect('login.php');
    } 
  }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NemKoViD - Regisztráció</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="all">
    <div class="title"><h1>Nemzeti Koronavírus Depó</h1>
    <h3>NemKoViD - Mondj nemet a koronavírusra!</h3>
    </div>
    <p><h3>Regisztráció</h3></p>

    <?php if (isset($errors['global'])) : ?>
    <p><span class="error"><?= $errors['global'] ?></span></p>
    <?php endif; ?>
    <form action="" method="post" novalidate>
    <div>
        <label for="fullname">Teljes név: </label>
        <span class="redspan">*</span><br>
        <input type="text" name="fullname" id="fullname" value="<?= $_POST['fullname'] ?? "" ?>">
        <?php if (isset($errors['fullname'])) : ?>
        <span class="error"><?= $errors['fullname'] ?></span>
        <?php endif; ?>
    </div>

    <div>
        <label for="tajnum">TAJ szám: </label>
        <span class="redspan">*</span><br>
        <input type="number" name="tajnum" id="tajnum" value="<?= $_POST['tajnum'] ?? "" ?>">
        <?php if (isset($errors['tajnum'])) : ?>
        <span class="error"><?= $errors['tajnum'] ?></span>
        <?php endif; ?>
    </div>

    <div>
        <label for="address">Értesítési cím: </label>
        <span class="redspan">*</span><br>
        <input type="text" name="address" id="address" value="<?= $_POST['address'] ?? "" ?>">
        <?php if (isset($errors['address'])) : ?>
        <span class="error"><?= $errors['address'] ?></span>
        <?php endif; ?>
    </div>

    <div>
        <label for="email">E-mail cím: </label>
        <span class="redspan">*</span><br>
        <input type="email" name="email" id="email" value="<?= $_POST['email'] ?? "" ?>">
        <?php if (isset($errors['email'])) : ?>
        <span class="error"><?= $errors['email'] ?></span>
        <?php endif; ?>
    </div>

    <div>
        <label for="pw1">Jelszó: </label>
        <span class="redspan">*</span><br>
        <input type="password" name="pw1" id="pw1">
        <?php if (isset($errors['pw1'])) : ?>
        <span class="error"><?= $errors['pw1'] ?></span>
        <?php endif; ?>
    </div>

    <div>
        <label for="pw2">Jelszó megerősítése: </label>
        <span class="redspan">*</span><br>
        <input type="password" name="pw2" id="pw2">
        <?php if (isset($errors['pw2'])) : ?>
        <span class="error"><?= $errors['pw2'] ?></span>
        <?php endif; ?>
    </div>
    <br>
    <small><span class="redspan">*</span>-gal jelölt mezők kitöltése kötelező!</small>
    <br><br>
    <div>
        <button type="submit">Regisztráció</button><br><br>
    </div>
    </form>
</div>
</body>
</html>