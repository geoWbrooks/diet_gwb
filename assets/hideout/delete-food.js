    const deleteModal = document.getElementById('modal-delete');
    deleteModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const foodName = button.getAttribute('data-bs-foodname')
        const foodId = button.getAttribute('data-bs-foodid')
//        const clickAction = `window.location.replace('../food/${foodName}/delete')`
//        const btns = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button id='food-delete' type="button" class="btn btn-primary" onclick=${clickAction}>Delete</button>`
        const modalBodyInput = deleteModal.querySelector('.modal-body ')
        const modalFooterButtons= deleteModal.querySelector('form')

        modalBodyInput.textContent = `Confirm deletion of ${foodName}`
        modalFooterButtons.setAttribute('action',`../food/${foodId}/delete`)
//        modalFooterButtons.setAttribute = `action='../food/${foodId}/delete'`
    }
);