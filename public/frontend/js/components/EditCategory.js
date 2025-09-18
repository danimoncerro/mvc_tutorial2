const EditCategory = {
    name: 'EditCategory',
    emits: ['show-categories'],
    template: `
        <!-- Modal pentru editarea categoriilor -->
        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Editează categoria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="updateCategory()">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Nume Categorie</label>
                                <input type="text" class="form-control" id="categoryName" v-model="editingCategory.name" required>
                            </div>
                            <div class="mb-3">
                                <label for="categoryDescription" class="form-label">Descriere</label>
                                <textarea class="form-control" id="categoryDescription" v-model="editingCategory.description" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                        <button type="button" class="btn btn-primary" @click="updateCategory()">
                            <i class="bi bi-check-circle"></i> Actualizează Categoria
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `,
    props: {
        updatelink: {
            type: String,
            required: true
        },
        editingCategory: {
            type: Object,
            required: true
        }
    },
    setup(props, { emit }) {
        
        const updateCategory = () => {
            axios.post(props.updatelink + '?id=' + props.editingCategory.id, props.editingCategory)
            .then(response => {
                console.log('Category modified:', response.data);

                // Reset the editing category form
                props.editingCategory.name = '';
                props.editingCategory.description = '';
                props.editingCategory.id = '';
                
                // Închide modalul - metoda simplificată și sigură
                const modal = bootstrap.Modal.getInstance(document.getElementById('editCategoryModal'));
                modal.hide();

                // Refresh the category list
                emit('show-categories');
            })
            .catch(error => {
                console.error('Error updating category:', error);
                alert('Eroare la actualizarea categoriei!');
            });
        }

        return {
            updateCategory
        };
    }
};