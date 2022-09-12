<head>
    <title>INVOICE {{ $transaksi->kode_transaksi }}</title>
</head>
<center>
    <img src="{{ asset('img/invoice-header.jpg') }}" alt="">
    <table>
        <tr>
            <td colspan="3"><b>Bill To</b></td>

            <td rowspan="5" width="70px"></td>

            <td colspan="3">
                <div class="invoice">INVOICE</div>
            </td>
        </tr>

        <tr>
            <td>Name</td>
            <td>:</td>
            <td>{{ $transaksi->tamu->nama }}</td>

            <td>Number</td>
            <td>:</td>
            <td>{{ $transaksi->kode_transaksi }}</td>
        </tr>

        <tr>
            <td>Company Name</td>
            <td>:</td>
            <td>{{ $transaksi->tamu->company }}</td>

            <td>Date of Arrival</td>
            <td>:</td>
            <td>{{ date('d-m-Y', strtotime($transaksi->tgl_checkin)) }}</td>
        </tr>

        <tr>
            <td>Street Address</td>
            <td>:</td>
            <td>{{ $transaksi->tamu->street }}</td>

            <td>Date of Departure</td>
            <td>:</td>
            <td>{{ date('d-m-Y', strtotime($transaksi->tgl_checkout)) }}</td>
        </tr>
        <tr>
            <td>City, ST ZIP Code</td>
            <td>:</td>
            <td>{{ $transaksi->tamu->city }}</td>

            {{-- <td>Date of Departure</td>
      <td>:</td>
      <td>{{date('d-m-Y',strtotime($transaksi->tgl_checkout))}}</td> --}}
        </tr>
        <tr>
            <td>Phone</td>
            <td>:</td>
            <td>{{ $transaksi->tamu->phone }}</td>

            {{-- <td>Date of Departure</td>
      <td>:</td>
      <td>{{date('d-m-Y',strtotime($transaksi->tgl_checkout))}}</td> --}}
        </tr>
    </table>
    <br>
    @php
        $kamar = \DB::table(\DB::raw('kamar k'))
            ->select('k.no_kamar', 'kt.harga')
            ->join(\DB::raw('kategori_kamar kt'), 'k.id_kategori_kamar', '=', 'kt.id')
            ->join(\DB::raw('detail_transaksi d'), 'k.id', '=', 'd.id_kamar')
            ->where('d.kode_transaksi', $transaksi->kode_transaksi)
            ->get();

        $diff = strtotime($transaksi->tgl_checkout) - strtotime($transaksi->tgl_checkin);
        $durasi = abs(round($diff / 86400));
        // foreach ($kamar as $key => $value) {
        //   $subtotal = $value->harga * $durasi;
        // }
        // $tax = $subtotal * 10 / 100;

        $pembayaran = \App\Pembayaran::select('total', 'diskon', 'tax', 'charge', 'grandtotal', 'extra_bed')
            ->where('kode_transaksi', $transaksi->kode_transaksi)
            ->get()[0];
        $total = $pembayaran->total;
        $diskon = $pembayaran->diskon;
        $tax = $pembayaran->tax;
        $charge = $pembayaran->charge;
        $grandtotal = $pembayaran->grandtotal;
        $extra_bed = $pembayaran->extra_bed;
    @endphp
    <table id="detail">
        <thead>
            <tr>
                <th>Room Number</th>
                <th># of Night</th>
                <th>Price per Night</th>
                <th>Other Charges</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kamar as $item)
                @php
                    $subtotal = $item->harga * $durasi;
                @endphp
                <tr>
                    <td>{{ $item->no_kamar }}</td>
                    <td>{{ $durasi }}</td>
                    <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>{{ number_format(0, 0, ',', '.') }}</td>
                    <td>{{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <br>
    <table id="total">
        <tr>
            <td><b>Subtotal</b></td>
            <td>:</td>
            <td>{{ number_format($total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><b>Diskon</b></td>
            <td>:</td>
            <td>{{ number_format($diskon, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><b>Sales Tax 10%</b></td>
            <td>:</td>
            <td>{{ number_format($tax, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><b>Down Payment</b></td>
            <td>:</td>
            <td>{{ number_format(0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><b>Charge</b></td>
            <td>:</td>
            <td>{{ number_format($charge, 0, ',', '.') }}</td>
        </tr>
        @if ($extra_bed != null)
            <tr>
                <td><b>Extra Bed</b></td>
                <td>:</td>
                <td>{{ number_format($extra_bed, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr>
            <td><b>Total</b></td>
            <td>:</td>
            <td>{{ number_format($grandtotal, 0, ',', '.') }}</td>
        </tr>
    </table>
    <br><br>
    <img src="{{ asset('img/invoice-footer.jpg') }}" alt="">
</center>
<style>
    table {
        font-family: Arial, Helvetica, sans-serif;
    }

    table .invoice {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 40px;
        font-weight: bold;
        color: #fe0000;
    }

    #detail {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #detail td,
    #detail th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #detail tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #detail tr:hover {
        background-color: #ddd;
    }

    #detail th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: center;
        background-color: #fe0000;
        color: white;
    }

    #total {
        margin-left: 450px;
    }
</style>
<script>
    window.print()
</script>
