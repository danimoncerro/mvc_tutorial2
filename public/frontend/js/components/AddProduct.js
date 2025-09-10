const AddProduct = {
    name: 'AddProduct',
    emits: ['show-products'],
    template: `

        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-circle"></i> Adauga produs
            </button>
        </div>

       

        <!-- Modal pentru adăugarea produselor -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Adaugă Produs Nou</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="addProduct()">
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
                        <button type="button" class="btn btn-primary" @click="addProduct()">
                            <i class="bi bi-check-circle"></i> Salvează Produs
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
        },
        categories: {
            type: Array,
            required: true
        }
        
    },
    setup(props, { emit }) {
        const { reactive } = Vue;
        
        const product = reactive({
            name: '',
            description: '',
            price: 0,
            category_id: null
        });

        const addProduct = () => {
                // Validare simplă
                if (!product.name || !product.price || !product.category_id) {
                    alert('Te rog completează toate câmpurile!');
                    return;
                }

                axios.post(props.savelink, {
                    name: product.name, 
                    price: product.price,
                    category_id: product.category_id
                })
                .then(response => {
                    console.log('Product added:', response.data);
                    
                    // Reset the product form
                    product.name = '';
                    product.price = 0;
                    product.category_id = '';
                    
                    // Închide modalul
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
                    modal.hide();
                    
                    // Refresh the product list
                    emit('show-products');
                    
                    // Afișează mesaj de succes (opțional)
                    alert('Produs adăugat cu succes!');
                })
                .catch(error => {
                    console.error('Error adding product:', error);
                    alert('Eroare la adăugarea produsului!');
                });
        }

        return {
            product,
            addProduct
        };

    
    }
       
    
};