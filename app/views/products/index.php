<?php
$title = 'Products List';
ob_start();
?>

<div id="app" class="container">

    <h1>Products 
        <span class="badge bg-secondary" v-if="products.length">{{ totalproducts }}</span>
    </h1>
    <h2>{{dinamictext}}</h2>
    <h1>{{dinamictext2}}</h1>
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus-circle"></i> Adauga produs
        </button>
    </div>
    <div class="mb-3">
        <input v-model="dinamictext" type="text" class="form-control" placeholder="Introdu textul dinamic...">
    </div>
    <div class="mb-3">
        <input type="text" class="form-control" v-model="dinamictext2">
    </div>
    <div class="mb-3">
        <button class="btn btn-secondary" @click="increments()">{{incrementsnumber}}</button>
    </div>

    <!-- Modal pentru adăugarea produselor -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">`
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

    <div class="mb-3">
        <div class="alert alert-info" v-if="hoveredProductName">
            <strong>Produs selectat:</strong> {{ hoveredProductName }}
        </div>
        
    </div>

    <div class="mb-3">
        <h5>Filtrare Produse</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <input v-model="filters.search" type="text" class="form-control" placeholder="Caută produs...">
            </div>
            <div class="col-md-2">
                <input v-model="filters.min_price" type="number" class="form-control" placeholder="Preț minim">
            </div>
            <div class="col-md-2">
                <input v-model="filters.max_price" type="number" class="form-control" placeholder="Preț maxim">
            </div>
            <div class="col-md-3">
                <select v-model="filters.category_id" class="form-select" @change="showProducts()">
                    <option value="">Toate categoriile</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-secondary w-100" @click="showProducts()">
                    <i class="bi bi-search"></i> Caută
                </button>
            </div>
        </div>
    </div>

    <table class="table table-striped table-hover table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>
                    Nume 
                </th>
                <th>
                    Pret
                </th>
                <th>Categorie</th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="product in products" :key="product.id">
                <td>{{ product.id }}</td>
                <td 
                    class="product-name-cell"
                    @mouseenter="hoveredProductName = product.name"
                    @mouseleave="hoveredProductName = ''"
                >
                    {{ product.name }}
                </td>
                <td>{{ product.price }}</td>
                <td>{{ product.category_name }}</td> 
                <td></td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Aici incepe Vue.js -->
<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    const app = createApp({
        setup() {
            
            const products = ref([]);
            const totalproducts = ref(0);
            const hoveredProductName = ref('');
            const product = reactive({
                name: '',
                price: 0,
                category_id: ''
            });
            const categories = ref([]);
            const filters = reactive({
                category_id: '',
                min_price: 0,
                max_price: 999,
                search: ''
            });
            const dinamictext = ref('');
            const dinamictext2 = ref('');
            const incrementsnumber = ref(0);

            const showProducts = () => {
                axios.get('<?= BASE_URL ?>api/products', {
                    params: {
                        per_page: 20,
                        page: 1,
                        sort: 'id',
                        order: 'desc',
                        category_id: filters.category_id,
                        min_price: filters.min_price,
                        max_price: filters.max_price,
                        search: filters.search
                    }
                })
                .then(response => {
                    products.value = response.data.products;
                    totalproducts.value = response.data.total_products;

                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

            const getCategories = () => {
                axios.get('<?= BASE_URL ?>api/categories')
                    .then(response => {
                        categories.value = response.data.categories;
                    })
                    .catch(error => {
                        console.error('API Error:', error);
                    });
            }

            const addProduct = () => {
                // Validare simplă
                if (!product.name || !product.price || !product.category_id) {
                    alert('Te rog completează toate câmpurile!');
                    return;
                }

                axios.post('<?= BASE_URL ?>api/products/store', {
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
                    showProducts();
                    
                    // Afișează mesaj de succes (opțional)
                    alert('Produs adăugat cu succes!');
                })
                .catch(error => {
                    console.error('Error adding product:', error);
                    alert('Eroare la adăugarea produsului!');
                });
            }

            const increments = () => {
                incrementsnumber.value++;
            }

            onMounted(() => {
                showProducts();
                getCategories();
                //increments(); // Call increments to initialize the incrementsnumber
            });


            return{
                products,
                totalproducts,
                hoveredProductName,
                product,
                addProduct,
                categories,
                filters,
                showProducts,
                dinamictext,
                dinamictext2,
                incrementsnumber,
                increments
            };
        }
    });                     
    
    app.mount('#app');

</script>
                   

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';