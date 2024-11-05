<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ $title ?? 'Dashboard' }}</title>

    <link href="admin_assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="admin_assets/img/tab-icon.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    
</head>

<body id="page-top">

    <div id="wrapper">

        

        <x-sidebar />

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <x-navbar />
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">{{ $title ?? '' }}</h1> <!-- Blade syntax to display title -->
                    </div>

                    {{ $slot }}
                </div>
            </div>
            <x-footer />
        </div>
    </div>


    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- JavaScript files -->
    <script src="admin_assets/vendor/jquery/jquery.min.js"></script>
    <script src="admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="admin_assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="admin_assets/js/sb-admin-2.min.js"></script>
    <script src="admin_assets/vendor/chart.js/Chart.min.js"></script>
    <script src="admin_assets/js/demo/chart-area-demo.js"></script>
    <script src="admin_assets/js/demo/chart-pie-demo.js"></script>
</body>

</html>
