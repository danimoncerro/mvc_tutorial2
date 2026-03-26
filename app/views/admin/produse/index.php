<?php
$title = 'Lista de produse - exercitiu';
ob_start();
?>

<script src="<?= BASE_URL ?>frontend/js/components/AdaugaProdus.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/StergeProdus.js"></script>

<div id="app">

    <h1> {{ title }} - {{total_products}} </h1>

    <adauga-produs :savelink="'<?= BASE_URL ?>api/products/store'" :categories="categories" 
        @arata-produse="showProducts"></adauga-produs>

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
                <th>Discount</th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="product in products" :key="product.id">
                <td>{{product.id}}</td>
                <td>{{product.name}}</td>
                <td>{{product.price}}</td>
                <td>{{product.category_name}}</td>
                <td>{{product.price_discount}}</td>
                <td><sterge-produs :deletelink="'<?= BASE_URL ?>api/products/delete?id=' + product.id" @arata-produse="showProducts"></sterge-produs></td>
        </tbody>
    </table>



</div>

<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue; 

    const app = createApp({

        components: {
            'adauga-produs': AdaugaProdus,
            'sterge-produs': StergeProdus
        },

        setup() {
            const title = ref('Lista de produse')
            const categories = ref([])
            const products = ref([])
            const total_products = ref(0)

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

            const showProducts = () => {
                axios.get('<?= BASE_URL ?>api/products', {
                    params: {
                        per_page: 100
                    }
                })
                .then(response => {
                    products.value = response.data.products;
                    total_products.value = response.data.total_products;
                })
            } 

            onMounted(() => {
                showCategories()
                showProducts()
            })


            return{
                title,
                categories,
                products,
                total_products,
                showProducts

            }
        }              

    })

    app.mount('#app');
</script>
                   

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';