<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link href="admin_assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="admin_assets/img/tab-icon.png">
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">


    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script> <!-- Make sure this is included -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body class="bg-gray-100 vh-100">
    <div id="wrapper">
        <x-sidebar />
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <x-navbar />
                <x-container>
                    <div class="flex justify-end mb-6">
                        <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="btn-add-event" href="{{ route('rdvs.create') }}">
                            Créer un Rendez-vous
                        </a>
                    </div>

                    <h1 class="text-2xl font-bold text-center mb-6">Agenda</h1>

                    <div id='calendar' class="bg-white shadow-lg rounded-lg overflow-hidden p-4"></div>
                </x-container>
            </div>
            <x-footer />
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- SB Admin 2 Script -->
    <script src="admin_assets/js/sb-admin-2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/locale/fr.js"></script> <!-- FullCalendar French Locale -->
    <x-fullcalendar-script />
</body>

</html>
