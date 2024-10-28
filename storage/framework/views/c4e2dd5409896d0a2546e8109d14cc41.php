<!DOCTYPE html>
<html>

<head>
    <title>Rekap Aktivitas</title>
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
        <img src="<?php echo e(public_path('img/marcologo.png')); ?>" alt="Company Logo">
        <h1>Marcopolo Spa</h1>
        <p>Jl. Dr. Angka No.No, Glempang, Bancarkembar, Kec. Purwokerto Utara, Kabupaten Banyumas, Jawa Tengah 53115</p>
        <p>Email: info@company.com | Telepon: +62895411511551</p>
    </div>

    <!-- Title -->
    <h2 style="text-align: center;">Rekap Aktivitas Keseluruhan</h2>

    <!-- Table with Rekap Data -->
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Card Name</th>
                <th>Staff</th>
                <th>Customer</th>
                <th>Session</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $rekaps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rekap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($loop->iteration); ?></td>
                <td><?php echo e($rekap->timerCard->card_name); ?></td> <!-- Access the timer card name via relation -->
                <td><?php echo e($rekap->therapist_name); ?></td> <!-- therapist_name from rekaps table -->
                <td><?php echo e($rekap->customer); ?></td> <!-- customer from rekaps table -->
                <td><?php echo e($rekap->time); ?></td> <!-- session time from rekaps table -->
                <td><?php echo e(\Carbon\Carbon::parse($rekap->created_at)->setTimezone('Asia/Jakarta')->translatedFormat('j F Y')); ?>

                </td> <!-- formatted date -->
                <td><?php echo e(\Carbon\Carbon::parse($rekap->created_at)->setTimezone('Asia/Jakarta')->translatedFormat('H:i:s')); ?>

                    WIB</td> <!-- formatted time -->
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!-- Footer Section -->
    <div class="footer">
        <p>Generated on: <?php echo e(\Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->translatedFormat('j F Y H:i:s')); ?> WIB
        </p>
        <p>Marcopolo Spa Purwokerto</p>
        <p>Provided by Techno Net Software - All Rights Reserved © <?php echo e(\Carbon\Carbon::now()->year); ?></p>
    </div>

</body>

</html><?php /**PATH D:\laragon\www\spa-counter-rev-ver-1\resources\views/pdf/rekaps.blade.php ENDPATH**/ ?>