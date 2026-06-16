<?php
$title = 'Home Page';
ob_start();
?>


<div id="app" class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h1 class="mb-0">{{ title }}</h1>
                <span class="badge bg-primary fs-6">Total produse: {{ totalproducts }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Categorii</h5>
                </div>
                <div class="list-group list-group-flush">
                    <button
                        type="button"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                        :class="{ active: selectedCategory === 0 }"
                        @click="setCategory(0)"
                    >
                        <span>Toate categoriile</span>
                    </button>

                    <button
                        type="button"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                        v-for="category in categories"
                        :key="category.id"
                        :class="{ active: selectedCategory === Number(category.id) }"
                        @click="setCategory(category.id)"
                    >
                        <span>{{ category.name }}</span>
                        <span class="badge bg-secondary rounded-pill">{{ category.nr_product }}</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="row" v-if="products.length > 0">
                <div class="col-md-6 col-xl-4 mb-4" v-for="product in products" :key="product.id">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title">{{ product.name }}</h5>
                            <p class="card-text mb-2">
                                <strong>Preț:</strong> {{ product.price }} RON
                            </p>
                            <p class="card-text mb-2" v-if="product.category_name">
                                <small class="text-muted">Categorie: {{ product.category_name }}</small>
                            </p>
                            <p class="card-text mb-1 text-danger" v-if="product.discount">
                                <small>Discount: {{ product.discount }}%</small>
                            </p>
                            <p class="card-text text-danger" v-if="product.discount">
                                <small>Preț cu discount: {{ product.price_discount.toFixed(2) }} RON</small>
                            </p>
                        </div>
                        <?php
                        if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'client'): ?>
                            <div class="card-footer bg-white border-0 pt-0">
                                <button class="btn btn-warning btn-sm" @click="addToCart(product)" title="Adaugă în coș">
                                    <i class="bi bi-cart-plus"></i>
                                    Adaugă în coș
                                </button>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-5 bg-light rounded-3">
                <p class="text-muted mb-0">Nu există produse disponibile pentru categoria selectată.</p>
            </div>
        </div>
    </div>

</div>


<!-- Aici incepe Vue.js -->

<script>
    const { createApp, ref, onMounted } = Vue;

    const app = createApp({
        setup() {
            const title = ref("Home page");

            const products = ref([]);
            const totalproducts = ref(0);
            const categories = ref([]);
            const selectedCategory = ref(0);

            const showProducts = () => {
                axios.get('<?= BASE_URL ?>api/products', {
                    params: {
                        per_page: 30,
                        page: 1,
                        sort: 'price',
                        order: 'asc',
                        category_id: selectedCategory.value
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

            const setCategory = (categoryid) => {
                        selectedCategory.value = Number(categoryid);
                        showProducts();

            }

            const addToCart = (product) => {
                axios.get('<?= BASE_URL ?>cart/add', {
                    params: {
                        product_id: product.id,
                        quantity: 1
                    }
                })
                .then(() => {
                    alert(`Produsul "${product.name}" a fost adaugat in cos!`);
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }


            onMounted(() => {
                showProducts();
                showCategories();
            })

            return {
                title,
                totalproducts,
                products,
                setCategory,
                selectedCategory,
                categories,
                addToCart
            }
        }
    })

    app.mount('#app');
</script>



<?php
$content = ob_get_clean();
require_once 'layout.php';