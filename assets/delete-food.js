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

//    $("table#food_pantry tbody tr").each(function () {
//        if ($(this).data('active') === 'Inactive') {
//            $(this).toggle();
//        }
//    });
    
    $("button#btn_status").on('click', function ()
    {
        $(this).closest('tr').css("visibility", "hidden");
        id = $(this).closest('tr').data('foodid');
        $packet = JSON.stringify([id]);
        $.post(document.location.origin + '/food/' + id + '/status', function (response) {
            if (response) {
            }
        });
    }

    );
    
    $("button#btn_edit").on('click', function() {
        now_at = window.location.href;
        id = $(this).closest('tr').data('foodid');
        go_to = now_at + 'edit/' + id;
        window.location.replace(go_to);
    });
    
});
