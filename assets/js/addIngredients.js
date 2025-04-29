document.addEventListener('DOMContentLoaded', () => {
    const collectionHolder = document.getElementById('ingredients-collection');
    const addButton = document.getElementById('add-ingredient-to-recipe');

    if (!collectionHolder || !addButton) return;

    let index = collectionHolder.querySelectorAll('fieldset').length;

    const createDeleteButton = (wrapper) => {
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'btn btn-lg mt-2 bg-transparent remove-ingredient-btn';
        deleteBtn.title = 'Supprimer cet ingrédient';

        const icon = document.createElement('i');
        icon.className = 'bi bi-trash text-danger';

        deleteBtn.appendChild(icon);

        deleteBtn.addEventListener('click', ()=> {
            wrapper.remove();
        });
        return deleteBtn;
    }

    addButton.addEventListener('click', () => {
        const prototype = collectionHolder.dataset.prototype;
        if(!prototype) return;

        const newForm = prototype.replace(/__name__/g, index);

        const wrapper = document.createElement('fieldset');
        wrapper.classList.add('ingredient-item', 'border', 'p-3', 'my-2', 'rounded');
        wrapper.innerHTML = newForm;

        const deleteBtn = createDeleteButton(wrapper);
        wrapper.appendChild(deleteBtn);

        const createButton = document.createElement('button');
        createButton.type = 'button';
        createButton.className = 'btn btn-sm btn-outline-secondary mt-2 add-ingredient-btn';
        createButton.textContent = 'Créer un ingrédient';
        createButton.setAttribute('data-bs-toggle', 'modal');
        createButton.setAttribute('data-bs-target', '#modalAddIngredient');

        wrapper.appendChild(createButton);

        collectionHolder.appendChild(wrapper);
        index++;
    });

    const modalEl = document.getElementById('modalAddIngredient');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

    document.addEventListener('click', e => {
        if (e.target.classList.contains('add-ingredient-btn')) {
            fetch('/ingredient/ajouter-modal')
                .then(res => res.text())
                .then(html => {
                    const modalContent = document.getElementById('ingredient-modal-content');
                    modalContent.innerHTML = html;
                    modal.show();

                    setTimeout(() => {
                        const form = document.querySelector('#ingredient-form');
                        if (form) {
                        form.addEventListener('submit', (e) => {
                            e.preventDefault();

                            const data = new FormData(form);

                            fetch(form.action, {
                                method: 'POST',
                                body: data
                            })
                            .then(async res => {
                                const contentType = res.headers.get('Content-Type');
                                
                                if (contentType.includes('application/json')) {
                                    const result = await res.json();
                                    if (result.success) {
                                        const inputs = document.querySelectorAll('input[list="ingredient-list"]');
                                        const lastInput = inputs[inputs.length - 1];
                                        if (lastInput) {
                                            lastInput.value = result.name;
                                        }
                                        modal.hide();
                                    }
                                } else {
                                    const html = await res.text();
                                    document.getElementById('ingredient-modal-content').innerHTML = html;
                                }
                            });
                        });
                    }
                    }, 300);
                });
        }
    });
    // Supprimer les ingrédients déjà présents (chargés avec la page)
    document.querySelectorAll('.remove-ingredient-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('fieldset').remove();
    });
});

});
