const DeleteCategory = {
    name: 'DeleteCategory',
    emits: ['show-categories'],
    template: `
        <button class="btn btn-danger btn-sm" @click="deleteCategory()" title="Șterge categoria">
            <i class="bi bi-trash"></i>
            Sterge
        </button>
    `,
    props: {
        deletelink: {
            type: String,
            required: true
        }
    },
    setup(props, { emit }) {
        const deleteCategory = () => {
            if (!confirm('Ești sigur că vrei să ștergi această categorie?')) {
                return;
            }

            axios.post(props.deletelink, {})
                .then(response => {
                    console.log('Category deleted:', response.data);
                    // Refresh the category list
                    emit('show-categories');
                    alert('Categorie ștearsă cu succes!');
                })
                .catch(error => {
                    console.error('Error deleting category:', error);
                    alert('Eroare la ștergerea categoriei!');
                });
        }

        return {
            deleteCategory
        };
    }
};