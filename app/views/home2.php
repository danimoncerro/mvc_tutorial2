<?php
$title = 'Home Page';
ob_start();
?>


<div id="app" class="container">

    <h1>{{ message }}</h1>
    <div>
        <h4 @click="setCategory(0)">
            Toate categoriile
        </h4>    
        <div v-for="category in categories" :key="category.id" :value="category.id" 
    
            @click="setCategory(category.id)">
                <h4 v-if="category.nr_product>0">
                    {{ category.name }}
                    ({{category.nr_product}})
                </h4>
        </div>
    <div>
        <!-- Afișare produse pe 3 coloane -->
        <div class="row" v-if="products.length > 0">
            <div class="col-md-4 mb-4" v-for="product in products" :key="product.id">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ product.name }}</h5>
                        <p class="card-text">
                            <strong>Preț:</strong> {{ product.price }} RON
                        </p>
                        <p class="card-text" v-if="product.category_name">
                            <small class="text-muted">Categorie: {{ product.category_name }}</small>
                        </p>
                        <p class="card-text" v-if="product.discount">
                            <small style="color: red;">Discount: {{ product.discount }}%</small>
                        </p>
                        <p class="card-text" v-if="product.discount">
                            <small style="color: red;">Preț cu discount: {{ product.price_discount.toFixed(2) }} RON</small>
                        </p>
                    </div>
                    <?php
                    if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'client'): ?>
                        <div class="card-footer">
                            <small class="text-muted">
                                <button class="btn btn-warning btn-sm me-2"  
                                @click="addToCart(product)" title="Adaugă în coș">
                                    <i class="bi bi-cart-plus"></i>
                                    Adaugă în coș
                                </button>
                            </small>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
        
        <!-- Mesaj dacă nu există produse -->
        <div v-else class="text-center">
            <p class="text-muted">Nu există produse disponibile.</p>
        </div>
        
        <!-- Total produse -->
        <div class="row mt-4" v-if="totalproducts > 0">
            <div class="col-12">
                <p class="text-center">
                    <span class="badge bg-primary">Total produse: {{ totalproducts }}</span>
                </p>
            </div>
        </div>
    </div>

</div>


<!-- Aici incepe Vue.js -->

<script>
    const { createApp, ref, onMounted } = Vue;

    const app = createApp({
        setup() {
            const message = ref('Hello, Vue.js in Home Page!');
            const products = ref({});
            const totalproducts = ref(0);
            const categories = ref([]);
            const selectedCategory = ref(0);


            const showProducts = () => {
                axios.get('<?= BASE_URL ?>api/products', {
                    params: {
                        per_page: 20,
                        page: 1,
                        sort: 'price',
                        order: 'asc',
                        category_id: selectedCategory.value,
                    }
                })
                .then(response => {
                    products.value = response.data.products;
                    totalproducts.value = response.data.total_products;
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            };

            const setCategory = (cid) => {
                selectedCategory.value = cid;
                showProducts();
            }

            const showCategories = () => {
                axios.get('<?= BASE_URL ?>api/categories', {
                    params: {
                        per_page: 20,
                        page: 1,
                        sort: 'nr_product',
                        order: 'desc'
                    }
                })
                .then(response => {
                    categories.value = response.data.categories;
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

            const addToCart = (product) => {
                axios.get('<?= BASE_URL ?>cart/add', {
                    params: {
                        product_id: product.id,
                        quantity: 1
                    }
                })
                .then(response => {
                    alert(`Produsul "${product.name}" a fost adăugat în coș!`);
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            };


            onMounted(() => {
                showProducts();
                showCategories();
            });

            return {
                message,
                products,
                totalproducts,
                addToCart,
                categories,
                selectedCategory,
                setCategory
            };
        }
    });

    

    app.mount('#app');
</script>



<?php
$content = ob_get_clean();
require_once 'layout.php';