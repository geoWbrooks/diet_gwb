$(document).ready(function () {
    const deleteModal = document.getElementById('modal-delete');
    deleteModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const foodName = button.getAttribute('data-bs-foodname');
        const foodId = button.getAttribute('data-bs-foodid');
        const modalBodyInput = deleteModal.querySelector('.modal-body ');
        const modalFooterButtons = deleteModal.querySelector('form');

        modalBodyInput.textContent = `Confirm deletion of ${foodName}`;
        modalFooterButtons.setAttribute('action', `../food/${foodId}/delete`);
    }
    );

    $("button").on('click', function (e)
    {
        if ($(this).data('active')) {
            id = $(this).closest('tr').attr('data-foodid');
            $packet = JSON.stringify([id]);
            $.post(document.location.origin + '/food/' + id + '/status', $packet, function (response) {
                if (response) {
                }
            });
            state = $(this).text();
            if (state === 'Inactive') {
                $(this).attr('data-active', 'Active');
                $(this).text('Active');
            } else {
                $(this).attr('data-active', 'Inactive');
                $(this).text('Inactive');
            }
        }
    }

    );

});