<?php
$title = 'Products List';
ob_start();
?>

<script src="<?= BASE_URL ?>frontend/js/components/AddProduct.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/DeleteProduct.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/EditProduct.js"></script>

<style>
.editing-price {
    background-color: #fff3cd !important;
    border: 2px solid #ffc107 !important;
}

.price-display {
    display: inline-flex;
    align-items: center;
}

.price-display:hover {
    background-color: #f8f9fa;
    border-radius: 4px;
    padding: 2px 4px;
}

.price-display:hover .bi-pencil-square {
    color: #0d6efd !important;
}
</style>

<div id="app" class="container">

    

    <h1>Products 
        <span class="badge bg-secondary" v-if="products.length">{{ totalproducts }}</span>
    </h1>
    <h2>{{dinamictext}}</h2>
    <h1>{{dinamictext2}}</h1>
   
    <div class="mb-3">
        <input v-model="dinamictext" type="text" class="form-control" placeholder="Introdu textul dinamic...">
    </div>
    <div class="mb-3">
        <input type="text" class="form-control" v-model="dinamictext2">
    </div>
    <div class="mb-3">
        <button class="btn btn-secondary" @click="increments()">{{incrementsnumber}}</button>
    </div>
    <add-product :savelink="'<?= BASE_URL ?>api/products/store'" :categories="categories" @show-products="showProducts"></add-product>

    

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

    <table v-if="showTableData" class="table table-striped table-hover table-bordered">
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
                    @click="hideTable(product)"
                    style="cursor: pointer;"
                >
                    {{ product.name }}
                </td>
                <td 
                    @click="startEditPrice(product)"
                    style="cursor: pointer;"
                    :class="{ 'editing-price': editingPriceId === product.id }"
                >
                    <span v-if="editingPriceId !== product.id" class="price-display">
                        {{ product.price }} RON
                        <i class="bi bi-pencil-square text-muted ms-1" style="font-size: 0.8em;"></i>
                    </span>
                    <div v-else class="d-flex align-items-center">
                        <input 
                            type="text" 
                            class="form-control form-control-sm me-2" 
                            v-model="editingPrice"
                            @keyup.enter="savePrice(product)"
                            @keyup.escape="cancelEditPrice()"
                            style="width: 100px;"
                            ref="priceInput"
                            pattern="[0-9]+([.][0-9]+)?"
                            placeholder="0.00"
                        >
                        <button @click="savePrice(product)" class="btn btn-success btn-sm" title="Salvează prețul">
                            <i class="bi bi-check-lg me-1"></i>
                            Salvează
                        </button>
                    </div>
                </td>
                <td>{{ product.category_name }}</td> 
                <td>
                    <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editProductModal" @click="editProduct(product)" title="Editează produsul">
                        <i class="bi bi-pencil"></i>
                        Editează
                    </button>
                    
                    <delete-product :deletelink="'<?= BASE_URL ?>api/products/delete?id=' + product.id" @show-products="showProducts"></delete-product>
                    <!--
                    <button class="btn btn-danger btn-sm" @click="deleteProduct(product.id)" title="Șterge produsul">
                        <i class="bi bi-trash"></i>
                        Sterge
                    </button>
                    -->
            </tr>
        </tbody>
    </table>

    <!-- Componenta EditProduct -->
    <edit-product 
        :updatelink="'<?= BASE_URL ?>api/products/edit'" 
        :product="product"
        :categories="categories"
        @show-products="showProducts">

    </edit-product>

    <div v-if="!showTableData && selectedProduct" class="card">
        <div class="card-header">
            <h2><i class="bi bi-box-seam"></i> {{ selectedProduct.name }}</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h4>Descriere produs</h4>
                    <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5>Detalii produs</h5>
                            <p><strong>ID:</strong> {{ selectedProduct.id }}</p>
                            <p><strong>Preț:</strong> {{ selectedProduct.price }} RON</p>
                            <p><strong>Categorie:</strong> {{ selectedProduct.category_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary" @click="showTable()">
                    <i class="bi bi-arrow-left"></i> Înapoi la lista de produse
                </button>
                <button class="btn btn-success ms-2">
                    <i class="bi bi-cart-plus"></i> Adaugă în coș
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Aici incepe Vue.js -->
<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    

    const app = createApp({

        components: {
           'add-product': AddProduct,
           'delete-product': DeleteProduct,
           'edit-product': EditProduct
        },

        setup() {
            
            const products = ref([]);
            const totalproducts = ref(0);
            const hoveredProductName = ref('');
            const showTableData = ref(true);
            const selectedProduct = ref(null);
            const editingPriceId = ref(null);
            const editingPrice = ref(0);
            const product = reactive({
                name: '',
                price: 0,
                category_id: '',
                id:'',
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
              
                showTable();
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

            

            const increments = () => {
                incrementsnumber.value++;
            }

            const hideTable = (product) => {
                selectedProduct.value = product;
                showTableData.value = false;
            }

            const showTable = () => {
                showTableData.value = true;
                selectedProduct.value = null;
            }

            

            const editProduct = (p) => {
                
                product.name = p.name;
                product.price = p.price;
                product.category_id = p.category_id;
                product.id = p.id;

            }
            

            // Funcții pentru editarea inline a prețului
            const startEditPrice = (product) => {
                editingPriceId.value = product.id;
                editingPrice.value = product.price;
                
                // Focus pe input după următorul tick
                setTimeout(() => {
                    const input = document.querySelector('input[type="number"]');
                    if (input) {
                        input.focus();
                        input.select();
                    }
                }, 50);
            }

            const savePrice = (product) => {
                // Convertește la număr și validează
                const newPrice = parseFloat(editingPrice.value);
                
                if (isNaN(newPrice)) {
                    alert('Te rog introdu un preț valid!');
                    return;
                }

                if (newPrice === parseFloat(product.price)) {
                    cancelEditPrice();
                    return;
                }

                // Validare preț
                if (newPrice < 0) {
                    alert('Prețul nu poate fi negativ!');
                    return;
                }

                // Actualizează prețul în backend
                axios.post('<?= BASE_URL ?>api/products/edit?id=' + product.id, {
                    name: product.name,
                    price: newPrice,
                    category_id: product.category_id
                })
                .then(response => {
                    console.log('Price updated:', response.data);
                    
                    // Actualizează prețul în lista locală
                    const productIndex = products.value.findIndex(p => p.id === product.id);
                    if (productIndex !== -1) {
                        products.value[productIndex].price = newPrice;
                    }
                    
                    // Resetează editarea
                    cancelEditPrice();
                })
                .catch(error => {
                    console.error('Error updating price:', error);
                    alert('Eroare la actualizarea prețului!');
                });
            }

            const cancelEditPrice = () => {
                editingPriceId.value = null;
                editingPrice.value = 0;
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
                showTableData,
                selectedProduct,
                product,
                categories,
                filters,
                showProducts,
                dinamictext,
                dinamictext2,
                incrementsnumber,
                increments,
                hideTable,
                showTable,
                editProduct,
                editingPriceId,
                editingPrice,
                startEditPrice,
                savePrice,
                cancelEditPrice
            };
        }
    });                     
    
    app.mount('#app');

</script>
                   

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';