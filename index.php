<?php
include_once('datastorage.php');
include_once('userstorage.php');
include_once('auth.php');
$dataStorage = new DataStorage();
$datas = $dataStorage->findAll();
$user_storage = new UserStorage();
$auth = new Auth($user_storage);

function redirect($page) {
    header("Location: ${page}");
    exit();
}

//admin létrehozása
$sA = $user_storage->findOne(['email' => 'admin@nemkovid.hu']);
if(!is_null($sA))
{}else{
    $id = uniqid();
    $id = $user_storage->add([
      'email' => 'admin@nemkovid.hu',
      'pw1'  => password_hash('admin', PASSWORD_DEFAULT),
      'role' => "admin",
    ]);
}

session_start();
if(isset($_SESSION['user']) && !empty($_SESSION['user']))
{
    if (isset($_GET['logout']))
    {
        unset($_SESSION['user']);
        $thisuser = 0;
        $colid = 0;
        $reserved = 0;
        redirect("index.php");
    }
}

$users = array_column($datas, 'users');
$ids = array_column($datas, 'id');

$count=0;
$colid = 0;
$reserved = null;
$thisuser;
foreach ($users as $user) {
    if(isset($_SESSION['user']) && !empty($_SESSION['user']))
    {
        $thisuser = $_SESSION['user'];
        if($thisuser['role'] == "admin")
        {
            break;
        }else{
        if(in_array($thisuser['id'],$user))
        {
            $colid = $count;
            $realid = $ids[$colid];
            $reserved = $dataStorage->findOne(['id' => $realid]);
            break;
        }else{
            $count++;
        }
        }
    } 
}

$count2=0;
$colid2 = 0;
$reserved2 = null;
$realid2 = 0;
$thisuser2;

if(isset($_SESSION['user']) && !empty($_SESSION['user']))
{
    if (isset($_GET['cancel']))
    {
        foreach ($users as $user) 
        {
            if(isset($_SESSION['user']) && !empty($_SESSION['user']))
            {
                //thisuser = bejelentkezett felhasználó és annak adatai
                $thisuser = $_SESSION['user'];
                if($thisuser['role'] == "admin")
                {
                    break;
                }else{
                if(in_array($thisuser['id'],$user))
                {
                    $key = array_search($thisuser['id'], $user);
                    $realid = $ids[$colid];
                    $reserved = $dataStorage->findOne(['id' => $realid]);
                    $reserved['taken'] -= 1;
                    $usersArray = $reserved['users'];
                    unset($usersArray[$key]);
                    $reserved['users'] = $usersArray;
                    $dataStorage->update($realid, $reserved);
                    redirect('index.php');
                    $count2=0;
                    $colid2 = 0;
                    $reserved2 = null;
                    $realid2 = 0;
                    break;
                }
                }
            }
            
        }
    }
}

asort($datas);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NemKoViD - Kezdőlap</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="all">


    <div class="title"><h1>Nemzeti Koronavírus Depó</h1>
    <h3>NemKoViD - Mondj nemet a koronavírusra!</h3>
    </div>
    <?php
    if(isset($_SESSION['user']) && !empty($_SESSION['user']))
    {
        echo '<a href="?logout" class="login">Kijelentkezés</a>';
    }else{
        echo '<a href="login.php" class="login">Bejelentkezés / Regisztráció</a>';
    }
    ?>


    <div class="reserved">
        <?php if (isset($_SESSION['user']) && !empty($_SESSION['user'])) 
        { 
            if($reserved == null)
            {}else {
                echo '<br>';
                echo '<hr>';
                echo '<p class="appointment"><b>Foglalt időpont:</b><br>';
                echo $reserved['date'] . " " . $reserved['time'] ;
                echo '<br>';
                echo '<br><a href="?cancel">Jelentkezés lemondása</a></p>';
                echo '<hr>';
               
            }
        }
        ?>
    </div>
<br>
    <p>
        <h3>Tisztelt Látogatónk! </h3>
        Magyarországra hivatalosan is megérkezett COVID-19 elleni vakcina, és a Nemzeti Koronavírus Depó 
        (NemKoViD - Mondj nemet a koronavírusra!) központi épületében az oltásokat hamarosan elkezdjük.<br>
        Az oltás beadatása jelentkezéshez kötött! <br><br>
        <b>Jelentkezés menete:</b>
        <ol>
            <li>Az alábbi táblázatban az Önnek megfelelő időpont megtalálása után a kattintson a "Jelentkezés" linkre.</li>
            <li>Ezután a bejelentkezési oldalon találja magát. Ha még nem regisztrált, a "Regisztráció"-ra kattintva megteheti ezt.</li>
            
        </ol>
    </p>
    <br>


    <?php 
    if(isset($_SESSION['user']) && !empty($_SESSION['user']))
    {
        if($thisuser['role'] == "admin")
        {
            echo '<a href="newAppointment.php">Új időpont meghirdetése</a><br><br>';
        }
    }
    ?>

    <form action="" method="post">
    <table class="center">
        <?php foreach($datas as $data) : ?>
            <tr>
                <td style="<?php if($data['taken'] == $data['total']) {echo 'background-color:#ff3030';} else {echo 'background-color:#b1ff96';} ?>">
                <?= $data['date'] ?> <?= $data['time'] ?> <?= $data['taken'] ?>/<?= $data['total'] ?> 
                <a href="<?php if (isset($_SESSION['user']) && !empty($_SESSION['user'])) 
                                { echo "appointment.php?id=" . $data['id']; }
                                else {
                                    echo "login.php?id=" . $data['id'];
                                }
                                    ?>">
                                    
                                    <?php if ($reserved !== NULL || $data['taken'] == $data['total'])
                                    {
                                        echo "";
                                    }else {
                                        echo "Jelentkezés";
                                    }
                                    ?></a> </td>
                
            </tr>
        <?php endforeach ?>
    </table>
    </form>
    <a href="">&lt;&lt;Előző hónap</a> || <a href="">Következő hónap&gt;&gt;</a>

<br><br>
    <?php 
    if(isset($_SESSION['user']) && !empty($_SESSION['user']))
    {
        if($thisuser['role'] == "admin")
        {
            echo '<h2>Időpontok részletei:</h2>';
            ?>
            <hr>
            
            <dl>
            <?php foreach($datas as $data) : ?>
                    <dt><b><?= $data['date'] ?> <?= $data['time'] ?> </b></dt>
                    
                    <?php
                        if(count($data['users'])>0)
                        {
                            $str = $data['users'];

                            for ($i = 0; $i < count($str); $i++) 
                            {
                                
                                $ember = $user_storage->findOne(['id' => $str[$i]]);
                                echo '<li>' . $ember['fullname'];
                                echo '<br>';
                                echo $ember['tajnum'];
                                echo '<br>';
                                echo $ember['email'] . '</li>';
                                echo '<br>';
                            }
                        }else{

                            echo "erre az időpontra senki sem jelentkezett";
                            echo '<p></p>';
                        }
                        echo '<hr>';
                    ?>  
            <?php endforeach ?>
            <br>
            </dl>
           <?php 
        }
    }
    ?>
    </div>
</body>
</html>