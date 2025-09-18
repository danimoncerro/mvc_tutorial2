const EditProduct = {
    name: 'EditProduct',
    emits: ['show-products'],
    template: 
    `
    <!-- Modal pentru editarea produselor -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Editeaza produsul</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updateProduct()">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Nume Produs</label>
                            <input type="text" class="form-control" id="productName" v-model="product.name" required>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Preț</label>
                            <input type="number" class="form-control" id="productPrice" v-model="product.price" step="0.01" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Categorie</label>
                            <select class="form-select" id="productCategory" v-model="product.category_id" required>
                                <option value="">Selectează o categorie</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                    <button type="button" class="btn btn-primary" @click="updateProduct()">
                        <i class="bi bi-check-circle"></i> Editeaza Produs
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
        product: {
            type: Object,
            required: true
        },
        categories: {
            type: Array,
            default: () => []
        }
    },
    setup(props, { emit }) {
        const { ref, watch } = Vue;
        

        const updateProduct = () => {
            axios.post(props.updatelink + '?id=' + props.product.id, props.product)
            .then(response => {
                console.log('Product modified:', response.data);
                
                // Închide modalul
                const modal = bootstrap.Modal.getInstance(document.getElementById('editProductModal'));
                modal.hide();
                
                // Emit event to refresh product list
                emit('show-products');
            })
            .catch(error => {
                console.error('Error updating product:', error);
                alert('Eroare la modificarea produsului!');
            });
        };

        return {
            updateProduct
        };
    }
};