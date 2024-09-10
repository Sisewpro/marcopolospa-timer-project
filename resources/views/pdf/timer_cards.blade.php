<!DOCTYPE html>
<html>

<head>
    <title>Timer Cards</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #333;
    }

    /* Header styles */
    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header img {
        max-width: 100px;
        margin-bottom: 10px;
    }

    .header h1 {
        font-size: 18px;
        margin: 0;
        text-transform: uppercase;
    }

    .header p {
        font-size: 12px;
        margin: 5px 0;
    }

    /* Table styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #333;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    /* Footer styles */
    .footer {
        margin-top: 40px;
        text-align: center;
        font-size: 10px;
    }
    </style>
</head>

<body>

    <!-- Letterhead Section -->
    <div class="header">
        <!-- Replace with your company logo -->
        <img src="{{ public_path('img/marcologo.png') }}" alt="Company Logo">
        <h1>Marcopolo Spa</h1>
        <p>Jl. Dr. Angka No.No, Glempang, Bancarkembar, Kec. Purwokerto Utara, Kabupaten Banyumas, Jawa Tengah 53115</p>
        <p>Email: info@company.com | Telepon: +62895411511551</p>
    </div>

    <!-- Title -->
    <h2 style="text-align: center;">Rekap Aktivitas Keseluruhan</h2>

    <!-- Table with Timer Cards Data -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Card Name</th>
                <th>Customer</th>
                <th>Time</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($timerCards as $timerCard)
            <tr>
                <td>{{ $timerCard->id }}</td>
                <td>{{ $timerCard->card_name }}</td>
                <td>{{ $timerCard->customer }}</td>
                <td>{{ $timerCard->time }}</td>
                <td>{{ $timerCard->status }}</td>
                <td>{{ $timerCard->formatted_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer Section -->
    <div class="footer">
        <p>Generated on: {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</p>
        <p>Marcopolo Spa Purwokerto</p>
        <p>Provided by Techno Net Software - All Rights Reserved © {{ \Carbon\Carbon::now()->year }}</p>
    </div>

</body>

</html>