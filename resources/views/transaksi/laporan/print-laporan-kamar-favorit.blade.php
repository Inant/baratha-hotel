<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/argon.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" type="text/css">
    <title>Laporan Kamar Favorit</title>
</head>
<body>
<center>
<h3>Laporan Kamar Favorit</h3>
<h5>Tanggal: {{date('d-m-Y', strtotime($_GET['dari'])).' s/d '.date('d-m-Y', strtotime($_GET['sampai']))}}</h5>
<br>
</center>
<table width="100%" cellspacing="0" cellpadding="5">
    <thead>
      <tr>
        <th>#</th>
        <th>Nomor Kamar</th>
        <th>Jumlah Transaksi</th>
      </tr>
    </thead>
    <tbody>
      @php
        $total = 0;
      @endphp
      @foreach ($laporan as $value)
        @php
          $total = $total + $value->jml;
        @endphp
          <tr>
          <td>{{$loop->iteration}}</td>
          <td>{{$value->no_kamar}}</td>
          <td>{{$value->jml}}</td>
          </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan='2' class='text-center'><b>TOTAL</b></td>
        <td>{{number_format($total,0,',','.')}}</td>
      </tr>
    </tfoot>
</table>    
</body>
</html>

@if (isset($_GET['xls']))
    @php
        $name = 'Laporan Kamar Favorit ' . date('d-m-Y', strtotime($_GET['dari'])).' s/d '.date('d-m-Y', strtotime($_GET['sampai'])).'.xls';
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$name");
    @endphp
@else
    <script>
        window.print()
    </script>
@endif