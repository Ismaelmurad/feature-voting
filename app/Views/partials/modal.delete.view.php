<div class="modal fade"
     id="modal-delete"
     tabindex="-1"
     role="dialog"
     aria-labelledby="delete-category-label"
     aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete-category-label"><?= $title; ?></h5>
            </div>
            <div class="modal-body">
                <p id="modal_body">
                    <?= str_replace('{name}', '<span id="modal-delete-name"></span>', $message); ?>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Abbrechen
                </button>
                <a class="btn btn-danger"
                   id="modal-delete-link"
                   href="">
                    Löschen
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    const modalDelete = new bootstrap.Modal(document.getElementById('modal-delete'))
    const modalTextDelete = document.querySelector('#modal-delete-name');
    const modalLinkDelete = document.querySelector('#modal-delete-link');

    document.querySelectorAll('.btn-delete').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            modalTextDelete.innerHTML = event.target.dataset.name;
            modalLinkDelete.href = event.target.href;
            modalDelete.show();
        })
    });
</script>
