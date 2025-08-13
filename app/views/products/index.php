<?php
$title = 'Products List';
ob_start();
?>

<div id="app" class="container">

    <h1>Products 
        <span class="badge bg-secondary" v-if="products.length">{{ totalproducts }}</span>
    </h1>

    <div class="mb-3">
        Titlu:<input type="text" class="form-control mb-2" v-model="product.name">
        Pret: <input type="number" class="form-control mb-2" v-model="product.price">
        Categorie: <input class="form-control mb-2" v-model="product.category_id">
        <button class="btn btn-primary mb-2" @click="addProduct()">Adauga produs</button>

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
                <td>{{ product.name }}</td>
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
            const product = reactive({
                name: '',
                price: 0,
                category_id: ''
            });

            const showProducts = () => {
                axios.get('<?= BASE_URL ?>api/products', {
                    params: {
                        per_page: 20,
                        page: 1,
                        sort: 'id',
                        order: 'asc',
                        category_id: '',
                        min_price: 0,
                        max_price: 999,
                        search: ''
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

            const addProduct = () => {
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
                    // Refresh the product list
                    showProducts();
                })
                .catch(error => {
                    console.error('Error adding product:', error);
                });
            }

            onMounted(() => {
                showProducts();
            });


            return{
                products,
                totalproducts,
                product,
                addProduct
            };
        }
    });                     
    
    app.mount('#app');

</script>
                   

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';