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
                    <h5 class="mb-0">Pret</h5>
                </div>
                <div class="price-filter px-3 py-3 border-bottom">
                    <div class="row g-2 align-items-end">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Minim</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">RON</span>
                                <input
                                    type="number"
                                    class="form-control"
                                    v-model.number="min_price"
                                    :min="priceMinLimit"
                                    :max="priceMaxLimit"
                                    step="1"
                                    placeholder="0"
                                >
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Maxim</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">RON</span>
                                <input
                                    type="number"
                                    class="form-control"
                                    v-model.number="max_price"
                                    :min="priceMinLimit"
                                    :max="priceMaxLimit"
                                    step="1"
                                    placeholder="0"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="dual-range-slider mt-3">
                        <label class="form-label small text-muted mb-1">Slider minim</label>
                        <input
                            type="range"
                            class="form-range"
                            v-model.number="min_price"
                            :min="priceMinLimit"
                            :max="priceMaxLimit"
                            step="1"
                        >
                        <label class="form-label small text-muted mb-1 mt-2">Slider maxim</label>
                        <input
                            type="range"
                            class="form-range"
                            v-model.number="max_price"
                            :min="priceMinLimit"
                            :max="priceMaxLimit"
                            step="1"
                        >
                        <div class="d-flex justify-content-between mt-1 price-slider-limits">
                            <small>{{ priceMinLimit }} RON</small>
                            <small>{{ priceMaxLimit }} RON</small>
                        </div>
                    </div>

                    <p v-if="priceValidationMessage" class="price-validation-message mt-2 mb-0">{{ priceValidationMessage }}</p>
                    <div class="price-filter-summary mt-2">
                        Interval selectat: <strong>{{ min_price }}</strong> - <strong>{{ max_price }}</strong> RON
                    </div>
                </div>
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
                                <small>Preț cu discount: {{ product.price_discount }} RON</small>
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
    const { createApp, ref, onMounted, onBeforeUnmount, watch } = Vue;

    const app = createApp({
        setup() {
            const title = ref("Home page");

            const products = ref([]);
            const totalproducts = ref(0);
            const categories = ref([]);
            const selectedCategory = ref(0);
            const priceMinLimit = ref(0);
            const priceMaxLimit = ref(1000);
            const min_price = ref(5);
            const max_price = ref(10);
            const priceValidationMessage = ref('');
            let priceDebounceTimer = null;
            let isAdjustingPrice = false;

            const showProducts = () => {
                axios.get('<?= BASE_URL ?>api/products', {
                    params: {
                        per_page: 30,
                        page: 1,
                        sort: 'price',
                        order: 'asc',
                        category_id: selectedCategory.value,
                        min_price: min_price.value,
                        max_price: max_price.value
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

            const scheduleProductsRefresh = () => {
                if (priceDebounceTimer) {
                    clearTimeout(priceDebounceTimer);
                }

                priceDebounceTimer = setTimeout(() => {
                    showProducts();
                }, 350);
            }

            const normalizePriceValue = (value, fallbackValue) => {
                const numericValue = Number(value);

                if (Number.isNaN(numericValue)) {
                    return fallbackValue;
                }

                if (numericValue < priceMinLimit.value) {
                    return priceMinLimit.value;
                }

                if (numericValue > priceMaxLimit.value) {
                    return priceMaxLimit.value;
                }

                return numericValue;
            }

            const applyPriceValidation = (source) => {
                const initialMin = min_price.value;
                const initialMax = max_price.value;

                min_price.value = normalizePriceValue(min_price.value, priceMinLimit.value);
                max_price.value = normalizePriceValue(max_price.value, priceMaxLimit.value);

                if (min_price.value > max_price.value) {
                    if (source === 'min') {
                        max_price.value = min_price.value;
                    } else {
                        min_price.value = max_price.value;
                    }

                    priceValidationMessage.value = 'Intervalul a fost ajustat automat pentru ca minimul sa nu depaseasca maximul.';
                } else if (initialMin !== min_price.value || initialMax !== max_price.value) {
                    priceValidationMessage.value = `Valorile trebuie sa fie intre ${priceMinLimit.value} si ${priceMaxLimit.value} RON.`;
                } else {
                    priceValidationMessage.value = '';
                }
            }

            watch(min_price, () => {
                if (isAdjustingPrice) {
                    return;
                }

                isAdjustingPrice = true;
                applyPriceValidation('min');
                isAdjustingPrice = false;
                scheduleProductsRefresh();
            })

            watch(max_price, () => {
                if (isAdjustingPrice) {
                    return;
                }

                isAdjustingPrice = true;
                applyPriceValidation('max');
                isAdjustingPrice = false;
                scheduleProductsRefresh();
            })

            onMounted(() => {
                applyPriceValidation('max');
                showProducts();
                showCategories();
            })

            onBeforeUnmount(() => {
                if (priceDebounceTimer) {
                    clearTimeout(priceDebounceTimer);
                }
            })

            return {
                title,
                totalproducts,
                products,
                setCategory,
                selectedCategory,
                categories,
                addToCart,
                min_price,
                max_price,
                priceMinLimit,
                priceMaxLimit,
                priceValidationMessage
            }
        }
    })

    app.mount('#app');
</script>



<?php
$content = ob_get_clean();
require_once 'layout.php';