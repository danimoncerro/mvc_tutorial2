<?php
$title = 'Products List';
ob_start();
?>

<div id="app" class="container">

    <h1>Products 
        <span class="badge bg-secondary" v-if="products.length">{{ products.length }}</span>
    </h1>

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
                <td>{{ product.category }}</td> 
                <td></td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Aici incepe Vue.js -->
<script>
    const { createApp, ref, computed, onMounted } = Vue;

    const app = createApp({
        setup() {
            
            const products = ref([]);

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

                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

            onMounted(() => {
                showProducts();
            });


            return{
                products
            };
        }
    });                     
    
    app.mount('#app');

</script>
                   

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';