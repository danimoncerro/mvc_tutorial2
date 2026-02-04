<?php
$title = 'Products List';
ob_start();
?>

<script src="<?= BASE_URL ?>frontend/js/components/ShowProductTitle.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/AddProduct.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/DeleteProduct.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/EditProduct.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/SearchProduct.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/ProductDetail.js"></script>

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

    

    <!--<h1>Products 
        <span class="badge bg-secondary" v-if="products.length">{{ totalproducts }}</span>
    </h1> -->
    <show-product-title :total="totalproducts"></show-product-title> {{totalproducts}}
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
            <!--  Componenta de cautare  -->
            <search-product 
                @search-products="searchProducts"
                @show-products="showProducts">
            </search-product>

        </div>
    </div>

    <product-detail :product="selectedProduct">
    </product-detail>

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
                    <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editProductModal" 
                        @click="editProduct(product)" title="Editează produsul">
                        <i class="bi bi-pencil"></i>
                        Editează
                    </button>

                    <button 
                        @click="showProductDetails(product)" 
                        class="btn btn-warning btn-sm me-2" 
                        data-bs-toggle="modal" 
                        data-bs-target="#productDetail" 
                        title="Detalii produs"
                    >
                        <i class="bi bi-pencil"></i>
                        Detalii produs
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
        :product="editingProduct"
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

    <!-- Paginare -->


    <nav aria-label="Paginare comenzi">
        <ul class="pagination">
            <li class="page-item" style="cursor: pointer" :class="{disabled: currentPage ===1 }">
                <a class="page-link" @click="goToPage(currentPage - 1)" tabindex="-1">Previous</a>
            </li>
            <!-- avem in total 6 pagini -->
            <!-- tratam prima pagina -->
            <li class="page-item active" style="cursor: pointer" v-if="currentPage < 2">
                <a class="page-link" @click="goToPage(currentPage)">
                    {{ currentPage }}
                </a>
            </li>
            
            <li class="page-item" style="cursor: pointer" v-if="currentPage < 2">
                <a class="page-link" @click="goToPage(currentPage + 1)">
                    {{ currentPage + 1}}
                </a>
            </li>

            <li class="page-item" style="cursor: pointer" v-if="currentPage < 2">
                <a class="page-link" @click="goToPage(currentPage + 2)">
                    {{ currentPage + 2 }}
                </a>
            </li>

            <!-- tratam pagina > 1 -->
            <li class="page-item" style="cursor: pointer" v-if="currentPage>1 && currentPage<totalPages">
                <a class="page-link" @click="goToPage(currentPage - 1)">
                    {{ currentPage - 1}}
                </a>
            </li>
            <li class="page-item active"  style="cursor: pointer"  v-if="currentPage>1 && currentPage<totalPages">
                <a class="page-link" @click="goToPage(currentPage)">
                    {{ currentPage}}
                </a>
            </li>
            <li class="page-item" style="cursor: pointer"  v-if="currentPage>1 && currentPage<totalPages">
                <a class="page-link" @click="goToPage(currentPage + 1)">
                    {{ currentPage + 1 }}
                </a>
            </li>

            <!-- tratam utlima pagina -->
            <li class="page-item" style="cursor: pointer" v-if="currentPage === totalPages">
                <a class="page-link" @click="goToPage(currentPage - 2)">
                    {{ currentPage - 2}}
                </a>
            </li>
            <li class="page-item"  style="cursor: pointer"  v-if="currentPage === totalPages">
                <a class="page-link" @click="goToPage(currentPage - 1)">
                    {{ currentPage - 1}}
                </a>
            </li>
            <li class="page-item active" style="cursor: pointer"  v-if="currentPage === totalPages">
                <a class="page-link" @click="goToPage(currentPage)">
                    {{ currentPage }}
                </a>
            </li>
             
            <li class="page-item" style="cursor: pointer" :class="{disabled: currentPage === totalPages }">
                <a class="page-link"  @click="goToPage(currentPage + 1)">Next</a>
            </li>
        </ul>

        <div class="mb-3">
            <h5>Orders/page</h5>
            <div class="row g-3">
            
                <div class="col-md-3">
                    <select v-model="filters.per_page" class="form-select" @change="showProducts(1)">
                        <option value="5">5 products</option>
                        <option v-for="page in perPages" :key="page" :value="page">
                            {{ page }} products
                        </option>
                    </select>
                </div>

            </div>
        </div>

        <div class="text-muted mt-2">
            Pagina {{ currentPage }} din {{ totalPages }} (Total: {{ totalproducts }} produse)
        </div>

    </nav>

</div>

<!-- Aici incepe Vue.js -->
<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    

    const app = createApp({

        components: {
           'show-product-title': ShowProductTitle,
           'add-product': AddProduct,
           'delete-product': DeleteProduct,
           'edit-product': EditProduct,
           'search-product': SearchProduct,
           'product-detail': ProductDetail,
        },

        setup() {
            
            const products = ref([]);
            const totalproducts = ref(0);
            const hoveredProductName = ref('');
            const showTableData = ref(true);
            const selectedProduct = ref(null);
            const editingPriceId = ref(null);
            const editingPrice = ref(0);
            const perPages = ref([10, 15, 20]);
            const currentPage = ref(1);
            const totalPages = ref(1);
            const product = reactive({
                id:'',
            });
            const categories = ref([]);
            const filters = reactive({
                search: '',
                per_page: 5
            });
            const dinamictext = ref('');
            const dinamictext2 = ref('');
            const incrementsnumber = ref(0);
            const editingProduct = reactive({
                id: '',
                name: '',
                price: '',
                category_id: ''
            });


            const showProducts = (page) => {

                
                axios.get('<?= BASE_URL ?>api/products', {
                    params: {
                        page: page,
                        per_page: filters.per_page
                    }
                 
                })
                .then(response => {
                    products.value = response.data.products;
                    totalproducts.value = response.data.total_products;
                    totalPages.value = response.data.total_pages || 1;
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            };

            const goToPage = (page) => {
                console.log('Going to page:', page);
                
                if (page < 1 || page > totalPages.value) {
                    console.log('Invalid page:', page);
                    return;  // Previne navigarea la pagini invalide
                }
                
                currentPage.value = page;
                console.log('Current Page din .then (response:', currentPage.value);
                showProducts(page);
            }

            const showProductDetails = (product) => {

                selectedProduct.value = product;

            }

            const searchProducts = (searchTerm) => {
                if (searchTerm.length > 2) {
                    axios.get('<?= BASE_URL ?>api/products', {
                        params: {
                            per_page: 20,
                            page: 1,
                            sort: 'id',
                            order: 'desc',
                            search: searchTerm
                        }
                    })
                    .then(response => {
                        products.value = response.data.products;
                        totalproducts.value = response.data.total_products;
                    })
                    .catch(error => {
                        console.error('Search Error:', error);
                    });
                } else if (searchTerm.length === 0) {
                    showProducts();
                }
            };

            const editProduct = (p) => {
                // Populează editingProduct cu datele produsului selectat
                editingProduct.id = p.id;
                editingProduct.name = p.name;
                editingProduct.price = p.price;
                editingProduct.category_id = p.category_id;

                // Deschide modalul de editare
                //const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                //modal.show();
            }

            const increments = () => {
                incrementsnumber.value++;
            };

            const showCategories = () => {
                axios.get('<?= BASE_URL ?>api/categories', {
                    params: {
                        per_page: 20,
                        page: 1,
                        sort: 'name',
                        order: 'asc'
                    }
                })
                .then(response => {
                    categories.value = response.data.categories;
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

            const startEditPrice = (product) => {
                editingPriceId.value = product.id;
                editingPrice.value = product.price;
                
                // Focus pe input după ce DOM-ul se actualizează
                setTimeout(() => {
                    const input = document.querySelector('.editing-price input');
                    if (input) {
                        input.focus();
                        input.select(); // Selectează tot textul pentru editare rapidă
                    }
                }, 50);
            };

            const savePrice = (product) => {
                // Validează prețul
                const newPrice = parseFloat(editingPrice.value);
                if (isNaN(newPrice) || newPrice < 0) {
                    alert('Te rog introdu un preț valid!');
                    return;
                }

                // Trimite request-ul de actualizare
                axios.post('<?= BASE_URL ?>api/products/update-price', {
                    id: product.id,
                    price: newPrice
                })
                .then(response => {
                    console.log('Preț actualizat cu succes:', response.data);
                    
                    // Actualizează prețul în lista locală
                    const productIndex = products.value.findIndex(p => p.id === product.id);
                    if (productIndex !== -1) {
                        products.value[productIndex].price = newPrice;
                    }
                    
                    // Ieși din modul de editare
                    cancelEditPrice();
                    
                    // Opțional: afișează mesaj de succes
                    // showSuccessMessage('Prețul a fost actualizat cu succes!');
                })
                .catch(error => {
                    console.error('Eroare la actualizarea prețului:', error);
                    alert('Eroare la actualizarea prețului. Te rog încearcă din nou!');
                });
            };

            const cancelEditPrice = () => {
                editingPriceId.value = null;
                editingPrice.value = 0;
            };

            onMounted(() => {
                showProducts();
                showCategories();
            });

            return {
                products,
                totalproducts,
                hoveredProductName,
                showTableData,
                selectedProduct,
                editingPriceId,
                editingPrice,
                product,
                categories,
                filters,
                dinamictext,
                dinamictext2,
                incrementsnumber,
                showProducts,
                searchProducts,
                increments,
                editingProduct,
                editProduct,
                startEditPrice,      // ✅ Definită
                savePrice,           // ✅ Definită
                cancelEditPrice,
                showProductDetails,    // ✅ Adăugată
                perPages,
                currentPage,
                totalPages,
                goToPage

            };
        }
    });                     
    
    app.mount('#app');

</script>
                   

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';