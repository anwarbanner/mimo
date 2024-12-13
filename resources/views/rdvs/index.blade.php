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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

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

        @media (max-width: 768px) {
            .modal-content {
                width: 90%;
                max-width: none;
            }

            .modal-content .space-y-3 {
                flex-direction: column;
            }

            .fc-toolbar .fc-state-active,
            .fc-toolbar .ui-state-active {
                z-index: 0;
            }

        }

        @media (max-width: 1024px) {

            /* Styles for tablets and iPads */
            .modal-content {
                width: 80%;
                max-width: none;
            }

            /* Adjust other elements as needed, e.g., font sizes, spacing */
            .modal-content h3 {
                font-size: 1.5rem;
            }

            .modal-content p {
                font-size: 1rem;
            }

            .fc-toolbar .fc-state-active,
            .fc-toolbar .ui-state-active {
                z-index: 0;
            }

        }

        .fc-toolbar .fc-state-active,
        .fc-toolbar .ui-state-active {
            z-index: 0;
        }

        #wrapper {
            background-color: #244cbf;
        }
    </style>
</head>

<body class="bg-gray-100 vh-100">
    <div id="wrapper">
        <div class="hidden lg:block"> <!-- Sidebar will only appear on large screens and above -->
            <x-sidebar />
        </div>
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

    <div id="eventDetailsModal"
        class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div
            class="modal-content bg-white w-full max-w-lg p-6 rounded-lg shadow-lg transform transition-all sm:scale-100 sm:max-w-md">
            <!-- Close Button -->
            <button id="closeModal" aria-label="Close"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 focus:outline-none focus:ring focus:ring-gray-300 text-2xl">
                &times;
            </button>

            <!-- Modal Header -->
            <h3 class="text-lg font-bold text-gray-800 mb-4">Détails du rendez-vous</h3>

            <!-- Modal Content -->
            <div class="space-y-3 text-gray-600">
                <p><strong>ID du rendez-vous:</strong> <span id="eventId"></span></p>
                <p><strong>Titre:</strong> <span id="eventTitle"></span></p>
                <p><strong>Patient:</strong> <span id="eventPatientId"></span></p>
                <p><strong>Début:</strong> <span id="eventStart"></span></p>
                <p><strong>Fin:</strong> <span id="eventEnd"></span></p>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 space-y-4 sm:space-y-0 sm:flex sm:space-x-4">
                <!-- Confirm via Email -->
                <button id="confirmEmail"
                    class="flex items-center justify-center w-full sm:w-auto px-4 py-2 text-white bg-blue-500 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300">
                    <i class="fas fa-envelope mr-2"></i> Confirmer via Email
                </button>

                <!-- Confirm via WhatsApp -->
                <button id="confirmWhatsApp"
                    class="flex items-center justify-center w-full sm:w-auto px-4 py-2 text-white bg-green-500 rounded-md shadow hover:bg-green-700 focus:outline-none focus:ring focus:ring-green-300">
                    <i class="fab fa-whatsapp mr-2"></i> Confirmer via WhatsApp
                </button>
            </div>

            <!-- Etat Update Form -->
            <form id="updateEtatForm" class="space-y-4">
                <div class="mt-6 p-4 bg-gray-100 rounded-lg shadow">
                    <label for="etat" class="block text-sm font-medium text-gray-700">
                        Modifier l'état:
                    </label>
                    <select id="etat" name="etat" required
                        class="block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="confirmé">Confirmé</option>
                        <option value="annulé">Annulé</option>
                    </select>
                    <button type="submit" id="updateEtatButton"
                        class="w-full px-4 py-2 text-white bg-indigo-600 rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-300">
                        Mettre à jour
                    </button>
            </form>
        </div>

        <!-- Delete Button -->
        <button id="deleteEvent"
            class="mt-4 w-full px-4 py-2 text-white bg-red-500 rounded-md shadow hover:bg-red-700 focus:outline-none focus:ring focus:ring-red-300">
            Supprimer
        </button>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="hidden mt-4 flex items-center justify-center">
            <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
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
