<?php
$title = 'Home Page';
ob_start();
?>


<div id="app" class="container">

    <h1>{{ message }}</h1>
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
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">
                            <button class="btn btn-warning btn-sm me-2"  
                            @click="addToCart(product)" title="Adaugă în coș">
                                <i class="bi bi-cart-plus"></i>
                                Adaugă în coș
                            </button>
                        </small>
                    </div>
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


            const showProducts = () => {
                axios.get('<?= BASE_URL ?>api/products', {
                    params: {
                        per_page: 20,
                        page: 1,
                        sort: 'id',
                        order: 'desc'
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

            const addToCart = (product) => {
                alert(`Produsul "${product.name}" a fost adăugat în coș!`);
            };


            onMounted(() => {
                showProducts();
            });

            return {
                message,
                products,
                totalproducts,
                addToCart
            };
        }
    });

    

    app.mount('#app');
</script>



<?php
$content = ob_get_clean();
require_once 'layout.php';