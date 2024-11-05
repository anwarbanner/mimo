<script>
    $(document).ready(function() {
        var SITEURL = "{{ url('/') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var calendar = $('#calendar').fullCalendar({
            editable: true,
            events: SITEURL + "/fullcalender",
            displayEventTime: false,
            minTime: "07:00:00",
            maxTime: "19:00:00",
            eventRender: function(event, element) {
                var displayTitle = event.title;
                element.find('.fc-title').text(displayTitle);
            },
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                var title = prompt('Event Title:');
                var patientId = prompt('Patient ID:');
                if (title && patientId) {
                    var startDate = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                    var endDate = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                    $.ajax({
                        url: SITEURL + "/fullcalenderAjax",
                        data: {
                            title: title,
                            patient_id: patientId,
                            start: startDate,
                            end: endDate,
                            allDay: allDay,
                            type: 'add'
                        },
                        type: "POST",
                        success: function(data) {
                            displayMessage("Event Created Successfully");
                            calendar.fullCalendar('renderEvent', {
                                id: data.id,
                                title: title,
                                patient_id: patientId,
                                start: startDate,
                                end: endDate,
                                allDay: allDay
                            }, true);
                            calendar.fullCalendar('unselect');
                        }
                    });
                }
            },
            eventDrop: function(event) {
                var newStart = event.start ? event.start.format("YYYY-MM-DD HH:mm:ss") : null;
                var newEnd = event.end ? event.end.format("YYYY-MM-DD HH:mm:ss") : newStart;
                console.log("Event ID:", event.id, "New Start:", newStart, "New End:", newEnd);
                $.ajax({
                    url: SITEURL + '/fullcalenderAjax',
                    data: {
                        id: event.id,
                        title: event.title,
                        start: newStart,
                        end: newEnd,
                        allDay: event.allDay,
                        type: 'update'
                    },
                    type: "POST",
                    success: function(response) {
                        displayMessage("Event Updated Successfully");
                    },
                    error: function(xhr, status, error) {
                        console.error("Update failed:", xhr.responseText);
                    }
                });
            },
            eventClick: function(event) {
                var deleteConfirm = confirm("Do you really want to delete?");
                if (deleteConfirm) {
                    $.ajax({
                        type: "POST",
                        url: SITEURL + '/fullcalenderAjax',
                        data: {
                            id: event.id,
                            type: 'delete'
                        },
                        success: function(response) {
                            calendar.fullCalendar('removeEvents', event.id);
                            displayMessage("Event Deleted Successfully");
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
    function displayMessage(message) {
        toastr.success(message, 'Event');
    }
</script>
