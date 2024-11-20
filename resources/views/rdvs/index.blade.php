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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="admin_assets/img/tab-icon.png">
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
        }

        .close,
        .close-button {
            cursor: pointer;
        }

        /* Custom toastr success style */
        .toast-success {
            background-color: #38a169;
            /* Green */
            color: white;
            border-radius: 0.375rem;
            /* Tailwind's rounded-lg */
            padding: 10px;
        }

        /* Custom toastr error style */
        .toast-error {
            background-color: #e53e3e;
            /* Red */
            color: white;
            border-radius: 0.375rem;
            /* Tailwind's rounded-lg */
            padding: 10px;
        }

        /* Custom toastr info style */
        .toast-info {
            background-color: #3182ce;
            /* Blue */
            color: white;
            border-radius: 0.375rem;
            /* Tailwind's rounded-lg */
            padding: 10px;
        }

        /* Optional: Customizing all toastr notifications globally */
        .toast {
            font-size: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-100 vh-100">
    <div id="wrapper">
        <x-sidebar />
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <x-navbar />
                <x-container>
                    <div class="flex justify-end mb-6">
                        <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            id="btn-add-event" href="{{ route('rdvs.create') }}">
                            Créer un Rendez-vous
                        </a>
                    </div>
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Whoops!</strong>
                            <span class="block sm:inline">There were some problems with your submission:</span>
                            <ul class="mt-2 list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


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

    <div id="eventDetailsModal" class="modal" style="display: none;">
        <div class="modal-content relative">
            <span id="closeModal"
                class="absolute top-0 right-0 m-4 cursor-pointer text-gray-500 hover:text-gray-800 text-2xl">&times;</span>
            <h3>Détails du rendez-vous</h3>
            <p id="eventId"></p>
            <p id="eventTitle"></p>
            <p id="eventPatientId"></p>
            <p id="eventStart"></p>
            <p id="eventEnd"></p>

            <!-- Confirmation Buttons Row -->
            <!-- Responsive Confirmation Buttons Row -->
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 mt-4">
                <!-- Confirm via Email Button with Icon -->
                <button id="confirmEmail"
                    class="flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded w-full sm:w-auto">
                    <i class="fas fa-envelope mr-2"></i> Confirmer via Email
                </button>

                <!-- Confirm via WhatsApp Button with Icon -->
                <button id="confirmWhatsApp"
                    class="flex items-center justify-center bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded w-full sm:w-auto">
                    <i class="fab fa-whatsapp mr-2"></i> Confirmer via WhatsApp
                </button>
            </div>

<!-- Separate Row for Créer Viste Button -->
<div class="mt-4">
</div>


            <!-- Delete Event Button -->
            <button id="deleteEvent" class="bg-red-500 text-white px-4 py-2 rounded mt-4">Supprimer</button>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="hidden flex items-center justify-center mt-4">
                <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span class="ml-2 text-gray-500">En attente...</span>
            </div>
        </div>
    </div>

    <!-- SB Admin 2 Script -->
    <script src="admin_assets/js/sb-admin-2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/locale/fr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <x-fullcalendar-script />
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Set CSRF token for Axios globally
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute(
            'content');

        document.addEventListener("DOMContentLoaded", function() {
            const confirmEmailButton = document.getElementById("confirmEmail");
            const loadingSpinner = document.getElementById("loadingSpinner");

            confirmEmailButton.addEventListener("click", function() {
                // Show loading spinner and disable the button
                loadingSpinner.classList.remove("hidden");
                confirmEmailButton.disabled = true;

                // Actual email confirmation request
                axios.post('/send-email-confirmation', {
                        /* request data */
                    })
                    .then(response => {
                        loadingSpinner.classList.add("hidden");
                        confirmEmailButton.disabled = false;
                        toastr.success("Email de confirmation envoyé avec succès !");
                    })
                    .catch(error => {
                        break
                    });
            });
        });
    </script>
</body>

</html>
