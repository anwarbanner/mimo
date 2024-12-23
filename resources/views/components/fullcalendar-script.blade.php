<script>
    $(document).ready(function() {
        var SITEURL = "{{ url('/') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Configure toastr globally
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "closeMethod": "fadeOut",
            "closeEasing": "linear",
            "closeDuration": 200
        };

        $('#calendar').fullCalendar({
            locale: 'fr',
            editable: true,
            events: SITEURL + "/fullcalender",
            displayEventTime: false,
            minTime: "07:00:00",
            maxTime: "19:00:00",

            // Render event details on the calendar
            eventRender: function(event, element) {
                // Set the event title with patient details
                element.find('.fc-title').html(event.title + "<br>" + event.patient_nom + " " +
                    event.patient_prenom);

                // Assign colors based on the event's etat
                if (event.etat === "annulé") {
                    element.css('background-color', '#ff4d4d'); // Red for annulé
                    element.css('border-color', '#ff4d4d');
                } else if (event.etat === "confirmé") {
                    element.css('background-color', '#28a745'); // Green for confirmé
                    element.css('border-color', '#28a745');
                } else {
                    element.css('background-color', '#007bff'); // Blue for others
                    element.css('border-color', '#007bff');
                }
            },


            selectable: true,
            selectHelper: true,


            // Update event start and end time
            eventDrop: function(event, delta, revertFunc) {
                var newStart = event.start ? event.start.format("YYYY-MM-DD HH:mm:ss") : null;
                var newEnd = event.end ? event.end.format("YYYY-MM-DD HH:mm:ss") : newStart;

                $.ajax({
                    url: SITEURL + '/fullcalenderAjax',
                    type: "POST",
                    data: {
                        id: event.id,
                        title: event.title,
                        start: newStart,
                        end: newEnd,
                        allDay: event.allDay,
                        type: 'update'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success("Événement mis à jour avec succès",
                                "Succès");
                        } else {
                            toastr.error("Erreur lors de la mise à jour: " + response
                                .message, "Erreur");
                            revertFunc(); // Revert event if update fails
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Erreur lors de la mise à jour", "Erreur");
                        revertFunc();
                    }
                });
            },

            // Display event details on click
            eventClick: function(event) {
                $('#eventId').text(event.id);
                $('#eventTitle').text(event.title);
                $('#eventPatientId').text(event.patient_nom + " " + event
                    .patient_prenom);
                $('#eventStart').text(event.start.format("YYYY-MM-DD HH:mm:ss"));
                $('#eventEnd').text((event.end ? event.end.format(
                    "YYYY-MM-DD HH:mm:ss") : "N/A"));

                // Show modal with event details
                $('#eventDetailsModal').show();

                // Close modal on icon click
                $('#closeModal').on('click', function() {
                    $('#eventDetailsModal').hide();
                });

                // Delete event
                $('#deleteEvent').off('click').on('click', function() {
                    if (confirm("Voulez-vous vraiment supprimer cet événement?")) {
                        $.ajax({
                            type: "POST",
                            url: SITEURL + '/fullcalenderAjax',
                            data: {
                                id: event.id,
                                type: 'delete'
                            },
                            success: function(response) {
                                $('#calendar').fullCalendar('removeEvents',
                                    event.id);
                                toastr.success("Événement supprimé avec succès",
                                    "Succès");
                                $('#eventDetailsModal').hide();
                            },
                            error: function(xhr) {
                                toastr.error("Erreur lors de la suppression",
                                    "Erreur");
                            }
                        });
                    }
                });

                // Confirm appointment via WhatsApp
                $('#confirmWhatsApp').off('click').on('click', function() {
                    let patientPhoneNumber = event.telephone; // Patient's phone number
                    let nom = event.patient_nom + " " + event.patient_prenom;
                    let noun = event.sexe === "M" ? "Monsieur" : "Madame";
                    let appointmentDate = event.start.format(
                        "YYYY-MM-DD HH:mm");
                    let cabinetName = "Acupencture Marrakech";

                    let message =
                        `Bonjour ${noun} ${nom},\n\nNous vous contactons de la part de ${cabinetName}. Nous souhaitons confirmer votre rendez-vous prévu le ${appointmentDate}. Veuillez répondre à ce message pour confirmer.\n\nCordialement,\nVotre Équipe de Santé`;
                    // Encode the message for URL
                    let encodedMessage = encodeURIComponent(message);

                    // WhatsApp URL construction
                    let whatsappUrl =
                        `https://wa.me/${patientPhoneNumber}?text=${encodedMessage}`;

                    // Open WhatsApp URL in a new tab
                    window.open(whatsappUrl, '_blank');
                });


                // Confirm appointment via Email (using Axios)
                $('#confirmEmail').off('click').on('click', function() {
                    // Retrieve the appointment ID
                    let appointmentId = event.id; // Use the event ID for the appointment

                    // Send POST request to the server to trigger email sending
                    axios.post(`/appointments/${appointmentId}/send-confirmation-email`)
                        .then(response => {
                            console.log(response
                                .data); // Handle success (e.g., show a success message)
                            toastr.success("Email de confirmation envoyé!", "Succès");
                            $('#eventDetailsModal')
                                .hide(); // Optionally, close the modal
                        })
                        .catch(error => {
                            console.error('Error:', error.response
                                .data); // Handle error
                            toastr.error("Erreur lors de l'envoi de l'email", "Erreur");
                        });
                });
                // commancer la visite
                $('#Createviste').off('click').on('click', function() {
                    // Redirect to the visites/create route with the rdv ID as a parameter
                    let rdvId = event.id; // Assuming the rendezvous ID is the event ID
                    let redirectUrl = `/visites/create?rdv=${rdvId}`;
                    window.location.href = redirectUrl;
                });
                // Handle updating event etat
                $('#updateEtatForm').off('submit').on('submit', function(e) {
                    e.preventDefault();

                    let etat = $('#etat').val(); // Get the selected etat
                    let eventId = event.id; // Use the event ID

                    // AJAX request to update etat
                    $.ajax({
                        url: SITEURL +
                            '/fullcalenderAjax', // Backend endpoint for updates
                        type: 'POST',
                        data: {
                            id: eventId,
                            etat: etat,
                            type: 'update-etat'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(
                                    "L'état de l'événement a été mis à jour avec succès",
                                    "Succès");
                                $('#calendar').fullCalendar(
                                    'refetchEvents'); // Refresh the calendar
                                $('#eventDetailsModal')
                                    .hide(); // Close the modal
                            } else {
                                toastr.error("Erreur lors de la mise à jour: " +
                                    response.message, "Erreur");
                            }
                        },
                        error: function(xhr) {
                            toastr.error("Erreur lors de la mise à jour",
                                "Erreur");
                        }
                    });
                });


            },

            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            }
        });
    });
</script>
