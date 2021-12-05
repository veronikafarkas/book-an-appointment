<?php
include_once('datastorage.php');
include_once('userstorage.php');
include_once('auth.php');
$dataStorage = new DataStorage();
$user_storage = new UserStorage();
$auth = new Auth($user_storage);
session_start();

function redirect($page) {
    header("Location: ${page}");
    exit();
}

$id = $_GET['id'];
$appointment = $dataStorage->findById($id);
if (!$appointment) {
  $errors['global'] = 'Nem létező id';
}

$user = $_SESSION['user'];

if (isset($_POST['helper']) && $_POST['helper'] == 'zero') 
{
    $data['helper'] = $_POST['helper'];
}

//main
$data = [];
$errors = [];
if (count($_POST) > 0) 
{
    if (isset($_POST['accept'])) 
    {
        $app = $dataStorage->findById($id);
        array_push($app['users'], $user['id']);
        $app['taken'] += 1;
        $dataStorage->update($id, $app);

        redirect("successful.php");
    } else{
        $errors['accept'] = 'A jelölőnégyzet bepipálása kötelező!';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NemKoViD - Időpont</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="all">
    <div class="title"><h1>Nemzeti Koronavírus Depó</h1>
    <h3>NemKoViD - Mondj nemet a koronavírusra!</h3>
    </div>
    <p><h3>Időpontfoglalás</h3></p>

    <p>A kiválasztott időpont részletei:</p>
    <ul>
        <li><b>Dátum:</b> <?= $appointment['date'] ?></li>
        <li><b>Időpont:</b> <?= $appointment['time'] ?></li>
    </ul>
    <p>A jelentkező adatai:</p>
    <ul>
        <li><b>Név:</b> <?= $user['fullname'] ?></li>
        <li><b>Lakcím:</b> <?= $user['address'] ?></li>
        <li><b>TAJ szám:</b> <?= $user['tajnum'] ?></li>
    </ul>
    <form action="" method="post">
    <?php if (isset($errors['global'])) : ?>
    <p><span class="error"><?= $errors['global'] ?></span></p>
    <?php endif; ?>
    <?php if (isset($errors['accept'])) : ?>
    <span class="error"><?= $errors['accept'] ?></span>
    <?php endif; ?>
    <br>
    
    <input type="checkbox" name="accept" id="accept"> A jelölőnégyzet bepipálásával elfogadja a jelentkezés feltételeit, miszerint 
    kötelező megjelenni az oltáson a megadott időpontban, valamint azt, hogy az oltásnak különféle, előre nem teljesen
    meghatározható mellékhatásai lehetnek. 
    <input id='helper' type='hidden' value='zero' name='helper'>
<br><br>
    <button type="submit">Jelentkezés megerősítése</button><br><br>
    </form>
    </div>
</body>
</html>