<script>
    $(document).ready(function() {
        var SITEURL = "{{ url('/') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#calendar').fullCalendar({
            locale: 'fr',
            editable: true,
            events: SITEURL + "/fullcalender",
            displayEventTime: false,
            minTime: "07:00:00",
            maxTime: "19:00:00",
            eventRender: function(event, element) {
                element.find('.fc-title').text(event.title);
            },
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                var title = prompt("Titre de l'événement:");
                var patientId = prompt("ID du patient:");
                if (title && patientId) {
                    var startDate = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                    var endDate = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                    $.ajax({
                        url: SITEURL + "/fullcalenderAjax",
                        type: "POST",
                        data: {
                            title: title,
                            patient_id: patientId,
                            start: startDate,
                            end: endDate,
                            allDay: allDay,
                            type: 'add'
                        },
                        success: function(data) {
                            toastr.success("Événement créé avec succès", "Succès");
                            $('#calendar').fullCalendar('renderEvent', {
                                id: data.id,
                                title: title,
                                patient_id: patientId,
                                start: startDate,
                                end: endDate,
                                allDay: allDay
                            }, true);
                            $('#calendar').fullCalendar('unselect');
                        }
                    });
                }
            },
            eventDrop: function(event) {
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
                        toastr.success("Événement mis à jour avec succès", "Succès");
                    },
                    error: function(xhr) {
                        toastr.error("Erreur lors de la mise à jour", "Erreur");
                    }
                });
            },
            eventClick: function(event) {
                if (confirm("Voulez-vous vraiment supprimer cet événement?")) {
                    $.ajax({
                        type: "POST",
                        url: SITEURL + '/fullcalenderAjax',
                        data: {
                            id: event.id,
                            type: 'delete'
                        },
                        success: function(response) {
                            $('#calendar').fullCalendar('removeEvents', event.id);
                            toastr.success("Événement supprimé avec succès", "Succès");
                        }
                    });
                }
            },
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            }
        });
    });
</script>
