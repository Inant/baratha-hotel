<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<center>
<br>
<br>
<br>
<br>
    <table width="50%" style="background:#f8f9fa;box-shadow:0 1px 3px 0 rgba(27, 27, 27, 0.1);-webkit-box-shadow:0 1px 3px 0 rgba(27, 27, 27, 0.1)">
        <tr>
            <td>
                <br>
                <br>
                <center>
                    <img src="{{asset('img/logobaratha2.png')}}" width="20%" alt="">
                    <h2 style="color:#ffc107"><b>Konfirmasi Pembayaran</b></h2>
                    <p style="color  :#b0b0b0">
                        Pemesanan dengan kode <b> {{$kode}} </b><span style="{{$tipe=='Diterima' ? 'color:#aeda54' : 'color:red'}}">{{$tipe}}</span><b>
                        <?php 
                            if($tipe=='Ditolak'){
                                $kodeUrl = str_replace("/","-",$kode);
                        ?>
                        <br>
                        Silahkan lakukan verifikasi dengan menekan tombol dibawah ini.
                        <br>
                        <br>
                        <br>
                        <a href="{{webUrl().'upload-bukti/'.$kodeUrl}}" style="background:#f2404c;color:white;padding:1rem 2rem;font-size:16px;text-decoration:none;box-shadow:0px 4px 10px rgba(0, 0, 0, 0.25);border-radius:.25rem">Upload Ulang</a>
                        <?php } ?>
                    </p>
                </center>
                <br>
                <br>
            </td>
        </tr>
    </table>
    <p style="color : #b0b0b0 ">
        Copyright 2020 Baratha Hotel - Powered by <a href="https://limadigital.id/" target="_blank" style="color:#aeda54"><u>Lima Digital</u></a> 
    </p>
</center>
</body>
</html>