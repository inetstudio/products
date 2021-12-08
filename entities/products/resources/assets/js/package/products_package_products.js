$(document).ready(function() {
    $('#submit-analytics-filter').on('click', function (event) {
        event.preventDefault();

        $('#dataTableBuilder').DataTable().ajax.reload();
    });
});
