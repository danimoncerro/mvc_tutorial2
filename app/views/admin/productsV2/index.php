<?php
$title = 'Lista de produse v2';
ob_start();
?>

<style>
.products-v2-table thead th {
    background: linear-gradient(180deg, #d9ecff 0%, #b8dcff 100%);
    color: #212529;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 2px solid #8fbce8;
    box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.75);
}

.products-v2-table thead th:first-child {
    border-top-left-radius: 0.4rem;
}

.products-v2-table thead th:last-child {
    border-top-right-radius: 0.4rem;
}
</style>

<div id="app" class="container">

   
    <table class="products-v2-table table table-striped table-hover table-bordered">
        <thead class="table-light">
            <select v-model="selectedCategory" @change="showProducts">
                <option value="">Toate categoriile</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{category.name}}
                </option> 
            </select>
            <h1>
                {{selectedCategory}}
            </h1>


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
            </tr>
        </thead>
        <tbody>
            <tr v-for="product in products" :key="product.id">
                <td>
                    {{ product.id }}
                </td>
                <td>
                    {{ product.name }}
                </td>
                <td>
                    <span class="price-display">
                        {{ product.price }} RON
                    </span>
                </td>
                <td>
                    {{ product.category_name }}
                </td> 
                <td>
                    {{ product.discount }}
                </td>
                
            </tr>
        </tbody>
    </table>
</div>

<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    const app = createApp({
        setup() {
            const title = ref('Lista de produse - v2')
            const products = ref([])
            const categories = ref([])
            const selectedCategory = ref('')
            
            const showProducts = () => {
                         
                axios.get('<?= BASE_URL ?>api/v2/products', {
                    params: {
                        category_id: selectedCategory.value
                    }
                } )
                    .then(response => {
                        products.value = response.data
                    })
            }

            const getCategories = () => {
                axios.get('<?= BASE_URL ?>api/v2/categories')
                .then(response => {
                    categories.value = response.data
                })
            }

            onMounted(() => {
                showProducts()
                getCategories()
            })

            return{
                title,
                products,
                categories,
                selectedCategory,
                showProducts
             
            }
        }              

    })

    app.mount('#app');
</script>
                   

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';