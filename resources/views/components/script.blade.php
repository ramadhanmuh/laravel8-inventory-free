{{-- Bootstrap core JavaScript --}}
<script defer src="{{ url('template/sb-admin-2') }}/vendor/jquery/jquery.min.js"></script>
<script defer src="{{ url('template/sb-admin-2') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

{{-- Core plugin JavaScript --}}
<script defer src="{{ url('template/sb-admin-2') }}/vendor/jquery-easing/jquery.easing.min.js"></script>

{{-- Custom scripts for all pages --}}
<script defer src="{{ url('template/sb-admin-2') }}/js/sb-admin-2.min.js"></script>
<script>
    var pageLoader = document.getElementById('pageLoader'),
        pageLoaderClass = pageLoader.getAttribute('class').split(' ');

    function disablePageLoader() {
        var popped = pageLoaderClass.pop(),
            nonActivePageLoaderClass = pageLoaderClass.push('d-none'),
            nonActivePageLoaderClass = nonActivePageLoaderClass.toString().replace(/[,]/g, ' ');

        pageLoader.className = nonActivePageLoaderClass + ' d-none';
    }

    function createFormEvent() {
        var form = document.getElementsByTagName('form'),
            popped = pageLoaderClass.pop(),
            activePageLoaderClass = pageLoaderClass.toString(),
            activePageLoaderClass = activePageLoaderClass.replace(/[,]/g, ' '),
            activePageLoaderClass = activePageLoaderClass + ' d-flex';

        for (var index = 0; index < form.length; index++) {
            form[index].addEventListener('submit', function () {
                pageLoader.className = activePageLoaderClass;
            });
        }
    }

    function convertUnixColumnValueToDate() {
        var unixColumns = document.getElementsByClassName('unix-column');

        if (unixColumns.length < 1) {
            return;
        }

        for (var index = 0; index < unixColumns.length; index++) {
            var unixColumn = unixColumns[index],
                unix = parseInt(unixColumn.textContent);

            if (isNaN(unix)) {
                continue;
            }

            var date = new Date(unix * 1000),
                local = date.toLocaleDateString('id-ID', {
                    hour: '2-digit', minute: '2-digit'
                });

            unixColumn.textContent = local;
        }
    }

    var stateCheck = setInterval(function() {
        if (document.readyState === 'complete') {
            clearInterval(stateCheck);

            convertUnixColumnValueToDate();

            createFormEvent();

            disablePageLoader();

            if (window.innerWidth < 992) {
                document.getElementById('sidebarToggleTop').click();
            }
        }
    }, 100);
</script>