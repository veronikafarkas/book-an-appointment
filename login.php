<?php
include_once('datastorage.php');
include_once('userstorage.php');
include_once('auth.php');

// functions
function redirect($page) {
  header("Location: ${page}");
  exit();
}
function validate($post, &$data, &$errors) {
  // username, password not empty

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
    if (!isset($post['password'])) {
        $errors['password'] = ' Jelszó megadása kötelező!';
    }else if (trim($post['password']) === '') {
        $errors['password'] = ' Jelszó megadása kötelező!';
    }else {
        $data['password'] = $post['password'];
    }

  return count($errors) === 0;
}

$dataStorage = new DataStorage();
if($_GET)
{
  $id = $_GET['id'];
  $appointment = $dataStorage->findById($id);
  if (!$appointment) {
    $errors['global'] = 'Nem létező id';
  }
}

//main
session_start();
$user_storage = new UserStorage();
$auth = new Auth($user_storage);
$data = [];
$errors = [];
if ($_POST) {
  if (validate($_POST, $data, $errors)) {
    $auth_user = $auth->authenticate($data['email'], $data['password']);
    if (!$auth_user) {
      $errors['global'] = "Hiba: a beírt adatok nem találhatók meg az adatbázisunkban!";
    } else {
      $auth->login($auth_user);
      if($_GET)
      {
      redirect("appointment.php?id=" . $id);
      }
      else{
        redirect("index.php");
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NemKoViD - Bejelentkezés</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="all">
  <div class="title"><h1>Nemzeti Koronavírus Depó</h1>
    <h3>NemKoViD - Mondj nemet a koronavírusra!</h3>
  </div>
    

    <?php if (isset($errors['global'])) : ?>
    <p><span class="error"><?= $errors['global'] ?></span></p>
    <?php endif; ?>

    <?php if (isset($errors['email'])) : ?>
        <span class="error"><?= $errors['email'] ?></span><br>
    <?php endif; ?>

    <p><h3>Bejelentkezés</h3></p>
    
    <?php if (isset($errors['password'])) : ?>
        <span class="error"><?= $errors['password'] ?></span><br>
    <?php endif; ?>
    <form action="" method="post" novalidate>
    <div>
        <label for="email">E-mail: </label><br>
        <input type="email" name="email" id="email" value="<?= $_POST['email'] ?? "" ?>">
    </div>
    <div>
        <label for="password">Jelszó: </label><br>
        <input type="password" name="password" id="password">
    </div>
    <div>
      <br>
        <button type="submit">Bejelentkezés</button>
    </div>
    </form><br>
    <a href="register.php">Regisztráció</a><br><br>
    <br>

</div>
</body>
</html>