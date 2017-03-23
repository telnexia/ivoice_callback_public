<script type="text/javascript">
    var scheduleTimes = <?= json_encode($optionsSchedule) ?>;

    (function () {
        function loadScheduler() {
            $('#ScheduleDay .option').remove();
            $.each( scheduleTimes.list.day_list, function( key, value ) {
                var prefix = value.wday == 'today' || value.wday == 'tomorrow' ? value.wday.charAt(0).toUpperCase() + value.wday.slice(1) + ', ' : '';

                var option = $("<option></option>")
                    .addClass('option')
                    .attr("value",value.wday)
                    .text(prefix + value.fullname);

                if (value.active = 0) {
                    option.attr("disabled", "disabled");
                }

                $('#ScheduleDay').append(option);
            });
        }

        $('#ScheduleDay').click(function () {
            $('#ScheduleTime .option').remove();
            if ($(this).val()) {
                $.each( scheduleTimes.list.time_list[$(this).val()], function( key, value ) {
                    $('#ScheduleTime').append($("<option></option>")
                        .addClass('option')
                        .attr("value",value.value)
                        .text(value.fullname));
                });
            }
        });

        loadScheduler();

        $('#Delay').click(function(){
            if ($(this).is(':checked')) {
            	$('.hide-delay').hide();
            } else {
            	$('.hide-delay').show();
            }
        });
        
        if ($('#Delay').is(':checked')) {
        	$('.hide-delay').hide();
        } else {
        	$('.hide-delay').show();
        }
        
    })();
</script>
