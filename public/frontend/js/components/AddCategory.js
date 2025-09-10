const AddCategory = {
    name: 'AddCategory',
    emits: ['show-categories'],
    template: `
        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-circle"></i> Adauga categorie
            </button>
        </div>

        <!-- Modal pentru adăugarea categoriilor -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Adaugă Categorie Nouă</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="addCategory()">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Nume Categorie</label>
                                <input type="text" class="form-control" id="categoryName" v-model="category.name" required>
                            </div>
                            <div class="mb-3">
                                <label for="categoryDescription" class="form-label">Descriere</label>
                                <textarea class="form-control" id="categoryDescription" v-model="category.description" rows="3" required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                        <button type="button" class="btn btn-primary" @click="addCategory()">
                            <i class="bi bi-check-circle"></i> Salvează Categorie
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `,
    props: {
        savelink: {
            type: String,
            required: true
        }
        
    },
    setup(props, { emit }) {
        const { reactive } = Vue;
        
        const category = reactive({
            name: '',
            description: ''
        });

        const addCategory = () => {
            // Validare simplă
            if (!category.name || !category.description) {
                alert('Te rog completează toate câmpurile!');
                return;
            }

            axios.post(props.savelink, {
                name: category.name,
                description: category.description
            })
            .then(response => {
                console.log('Category added:', response.data);

                // Reset the category form
                category.name = '';
                category.description = '';

                // Închide modalul
                const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
                modal.hide();

                // Refresh the category list
                emit('show-categories');

                // Afișează mesaj de succes (opțional)
                alert('Categorie adăugată cu succes!');
            })
            .catch(error => {
                console.error('Error adding category:', error);
                alert('Eroare la adăugarea categoriei!');
            });
        }

        return {
            category,
            addCategory
        };
    }
};