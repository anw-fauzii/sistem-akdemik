<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            font-size: 13px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 750px;
            margin: auto;
            padding: 30px;
        }

        .logo {
            width: 150px;
        }

        .invoice-info {
            text-align: right;
        }

        .highlight {
            background-color: #ffeeca;
            padding: 6px 12px;
            display: inline-block;
            border-radius: 6px;
            font-weight: bold;
            margin: 10px 0;
        }

        .section {
            margin: 20px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
        }

        .totals {
            margin-top: 20px;
            float: right;
            width: 300px;
        }

        .totals table {
            width: 100%;
        }

        .totals td {
            padding: 8px;
        }

        .footer {
            clear: both;
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

            .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        font-size: 12px;
    }

    .invoice-table th,
    .invoice-table td {
        padding: 6px 5px;
        border-bottom: 1px solid #d1d5db; /* abu tipis */
    }

    .invoice-table th {
        text-align: center;
        font-weight: bold;
    }

    .invoice-table td:last-child,
    .invoice-table td:nth-child(3),
    .invoice-table td:nth-child(2) {
        text-align: right;
    }

    .totals-table {
        width: 100%;
        font-size: 12px;
        border-collapse: collapse;
    }

    .totals-table td {
        padding: 3px 5px;
    }

    .totals-table tr:not(:last-child) td {
        border-bottom: 1px solid #d1d5db;
    }

    .totals-table td:last-child {
        text-align: right;
    }
    </style>
</head>
<body>
@php
    $status = strtoupper($tagihan_tahunan->keterangan);

    $statusColor = [
        'LUNAS' => ['bg' => '#d1fae5', 'text' => '#065f46', 'border' => '#065f46'],
        'PENDING' => ['bg' => '#fef3c7', 'text' => '#92400e', 'border' => '#92400e'],
        'EXPIRED' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'border' => '#991b1b'],
        'DIBATALKAN' => ['bg' => '#f3f4f6', 'text' => '#374151', 'border' => '#9ca3af'],
    ];

    $color = $statusColor[$status] ?? ['bg' => '#e5e7eb', 'text' => '#000', 'border' => '#9ca3af'];
@endphp
<div class="container">
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
        <tr>
            <td align="left" style="vertical-align: middle;">
                <img src="{{ storage_path('app/public/logo/logo.png') }}" alt="Logo" style="height: 80px;">
            </td>
            <td align="right" style="vertical-align: middle;">
                <h3 style="margin: 0;">INVOICE</h3>
                <div>{{ $tagihan_tahunan->order_id }}</div>
                <div>Invoice date: {{ \Carbon\Carbon::parse($tagihan_tahunan->created_at)->format('d/m/Y') }}</div>

                <div style="
                    display: inline-block;
                    background-color: {{ $color['bg'] }};
                    color: {{ $color['text'] }};
                    border: 2px solid {{ $color['border'] }};
                    padding: 4px 10px;
                    font-weight: bold;
                    margin-top: 8px;
                    border-radius: 6px;
                    font-size: 14px;
                ">
                    {{ $status }}
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="highlight">Due date: {{ \Carbon\Carbon::parse($tagihan_tahunan->created_at)->addDay()->translatedFormat('d F Y') }}</div>
        <table width="100%">
            <tr valign="top">
                <td width="40%">
                    <strong>From:</strong><br>
                    Yayasan Prima Insani<br>
                    Jln. Ciledug No. 281<br>
                    +62 818 04340547<br>
                    it.primainsani@gmail.com<br>
                    NPWP: 1000000000000001
                </td>
                <td width="30%">
                    <strong>Bill to:</strong><br>
                    {{$tagihan_tahunan->anggotaKelas->siswa_nis}}<br>
                    {{$tagihan_tahunan->anggotaKelas->siswa->nama_lengkap}}
                    ({{$tagihan_tahunan->anggotaKelas->kelas->nama_kelas}})
                </td>
                <td width="30%">
                    <div style="background: #f2f4f6; border-radius: 10px; padding: 10px;">
                        <strong>Pay via:</strong><br>
                        BCA Virtual Account<br>
                        12345685458
                    </div>

                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Tagihan</th>
                    <th>Jumlah Bayar</th>
                    <th>Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$tagihan_tahunan->tagihanTahunan->jenis}}</td>
                    <td>Rp. {{ number_format($tagihan_tahunan->jumlah_bayar) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td><strong>Subtotal</strong></td>
                <td><strong>Rp. {{ number_format($tagihan_tahunan->jumlah_bayar) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Transaction Fee</strong></td>
                <td><strong>Rp. {{ number_format(4500) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Total Amount</strong></td>
                <td><strong>Rp. {{ number_format($tagihan_tahunan->jumlah_bayar + 4500) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Terima kasih telah melakukan pembayaran.<br>
        Dokumen ini dihasilkan secara otomatis dan tidak memerlukan tanda tangan.
    </div>
</div>
</body>
</html>