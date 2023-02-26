{{-- Bootstrap core JavaScript --}}
<script defer src="{{ url('template/sb-admin-2') }}/vendor/jquery/jquery.min.js"></script>
<script defer src="{{ url('template/sb-admin-2') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

{{-- Core plugin JavaScript --}}
<script defer src="{{ url('template/sb-admin-2') }}/vendor/jquery-easing/jquery.easing.min.js"></script>

{{-- Custom scripts for all pages --}}
<script defer src="{{ url('template/sb-admin-2') }}/js/sb-admin-2.min.js"></script>
<script>
    function convertUnixColumnValueToDate() {
        var unixColumns = document.getElementsByClassName('unix-column');

        if (unixColumns.length < 1) {
            return;
        }

        for (var index = 0; index < unixColumns.length; index++) {
            var unixColumn = unixColumns[index],
                unix = parseInt(unixColumn.textContent),
                date = new Date(unix * 1000),
                year = date.getFullYear().toString(),
                month = date.getMonth().toString(),
                day = date.getDay().toString(),
                hour = date.getHours().toString(),
                minute = date.getMinutes().toString(),
                second = date.getSeconds().toString();

            month = month.length < 2 ? '0' + month : month;

            day = day.length < 2 ? '0' + day : day;
            
            hour = hour.length < 2 ? '0' + hour : hour;

            minute = minute.length < 2 ? '0' + minute : minute;

            second = second.length < 2 ? '0' + second : second;

            unixColumn.textContent = year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
        }
    }

    var stateCheck = setInterval(function() {
        if (document.readyState === 'complete') {
            clearInterval(stateCheck);

            convertUnixColumnValueToDate();
        }
    }, 100);
</script>