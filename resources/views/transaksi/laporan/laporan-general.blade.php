<div class="table-responsive mt-3">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Kode Transaksi</th>
                <th>Waktu Pembayaran</th>
                <th>Nama Tamu</th>
                <th>No Kamar</th>
                <th>Lama Menginap</th>
                <th>Diskon</th>
                <th>Total</th>
                <th>Jenis Bayar</th>
                <th>No Kartu</th>
                <th>Tipe Pemesanan</th>
                {{-- <th>PPN</th> --}}
            </tr>
        </thead>
        <tbody class="list">
            <?php
            $total = 0;
            $total_diskon = 0;
            $no = 1;
            ?>
            @foreach ($laporan as $key => $value)
                <?php
                
                $total_diskon += $value->diskon;
                // $total_ppn = $total_ppn + $value->total_ppn;
                $subtotal = $value->grandtotal - $value->charge;
                // if ($value->isTravel=='True') {
                //     $biaya_travel = ($subtotal - $value->total_ppn - $value->room_charge) * 10/100;
                //     $subtotal = $subtotal - $biaya_travel;
                // }
                $total = $total + $subtotal;
                $diff = strtotime($value->tgl_checkout) - strtotime($value->tgl_checkin);
                $durasi = abs(round($diff / 86400));
                
                // $kamar = \App\Kamar::with('transaksi')->where('id', $value->id_kamar)->get()[0];
                $kamar = \DB::table(\DB::raw('kamar k'))
                    ->select('k.no_kamar')
                    ->join(\DB::raw('detail_transaksi d'), 'k.id', '=', 'd.id_kamar')
                    ->where('d.kode_transaksi', $value->kode_transaksi)
                    ->get();
                
                $tanggal = date('d/m/y', strtotime($value->waktu));
                $checkTanggal = $key > 1 ? date('d/m/y', strtotime($laporan[$key - 1]->waktu)) : $tanggal;
                if ($tanggal != $checkTanggal) {
                    $no = 1;
                }
                ?>
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ 'INV/BH/' . sprintf('%04d', $no) . '/' . $tanggal }}</td>
                    <td>{{ date('d-m-Y H:i', strtotime($value->waktu)) }}</td>
                    <td>{{ $value->nama }}</td>
                    <td>
                        @foreach ($kamar as $item)
                            <span class="badge badge-success"> {{ $item->no_kamar }} </span>
                        @endforeach
                    </td>
                    <td>{{ $durasi }}</td>
                    <td>{{ $value->diskon }}</td>
                    <td>{{ number_format($subtotal, 0, ',', '.') }}</td>
                    <td>{{ $value->jenis_pembayaran }}</td>
                    <td>{{ $value->no_kartu }}</td>
                    <td>{{ $value->tipe_pemesanan }}</td>
                    {{-- @if ($value->isTravel == 'True')
              <td>Travel</td>
          @else
              <td>Umum</td>
          @endif --}}
                </tr>
                @php
                    $no++
                @endphp
            @endforeach
        </tbody>
        <tfoot class="bg-dark text-white">
            <tr>
                <td colspan='7' class='text-center'><b>TOTAL</b></td>
                <td>{{ number_format($total, 0, ',', '.') }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
