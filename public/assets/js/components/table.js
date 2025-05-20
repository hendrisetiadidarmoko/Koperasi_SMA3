document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('show-modal', event => {
        console.log('sdasa');
        var myModal = new bootstrap.Modal(document.getElementById('form-modal'), {
            keyboard: false
        });
        myModal.show();
    });

    window.addEventListener('close-modal', event => {
        var myModalEl = document.getElementById('form-modal');
        var modal = bootstrap.Modal.getInstance(myModalEl);
        if (modal) {
            modal.hide();
        }
    });
});
