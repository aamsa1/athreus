<?php
session_start();
include 'inc/conf.php';
function anti_injection($data){
    $filter = mysqli_real_escape_string(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
    return $filter;
}

$username = anti_injection($_POST['usernm']);
$pass     = anti_injection($_POST['passwd']);

// pastikan username dan password adalah berupa huruf atau angka.
if (!ctype_alnum($username) OR !ctype_alnum($pass)){
    echo "Sekarang loginnya tidak bisa di injeksi lho.";
}
else{
    $passwd = md5($pass);
    $login=mysql_query("SELECT * FROM admin WHERE usernm='$username' AND passwd='$passwd'") or die(mysql_error());
    $ketemu=mysql_num_rows($login);


    // Apabila username dan password ditemukan
    if ($ketemu > 0){
        $r=mysql_fetch_array($login);

        //mengatur waktu time out, automatis logout jika sudah tidak menggunakan aplikasi
        include "timeout.php";

        $_SESSION['usernm']         = $r['usernm'];
        $_SESSION['nama_lengkap'] 	= $r['nama_lengkap'];

        // session timeout
        $_SESSION['login'] = 1;
        timer();
        header('location:med.php?mod=dashboard');
    }
    else{
        echo "Gagal login";
    }
}
?>
