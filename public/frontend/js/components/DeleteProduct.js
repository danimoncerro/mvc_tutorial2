const DeleteProduct = {
    name: 'DeleteProduct',
    emits: ['show-products'],
    template: `
        <button class="btn btn-danger btn-sm" @click="deleteProduct()" title="Șterge produsul">
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
        const deleteProduct = () => {
            if (!confirm('Ești sigur că vrei să ștergi acest produs?')) {
                return;
            }

            axios.post(props.deletelink, {})
                .then(response => {
                    console.log('Product deleted:', response.data);
                    // Refresh the product list
                    emit('show-products');
                    alert('Produs șters cu succes!');
                })
                .catch(error => {
                    console.error('Error deleting product:', error);
                    alert('Eroare la ștergerea produsului!');
                });
        }

        return {
            deleteProduct
        };
    }
};