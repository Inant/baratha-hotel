<div class="table-responsive mt-3">
  <table class="table align-items-center table-flush table-striped  table-hover">
    <thead class="thead-light">
        <tr>
        <th>#</th>
        <th>Nomor Kamar</th>
        <th>Jumlah Transaksi</th>
        </tr>
    </thead>
    <tbody class="list">
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
    <tfoot class="bg-dark text-white">
        <td colspan='2' class='text-center'><b>TOTAL</b></td>
        <td>{{number_format($total,0,',','.')}}</td>
    </tfoot>
  </table>
</div>
