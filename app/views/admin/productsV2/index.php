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
            
            const showProducts = () => {
                         
                axios.get('<?= BASE_URL ?>api/v2/products')
                    .then(response => {
                        products.value = response.data
                    })
            }

            onMounted(() => {
                showProducts()
            })

            return{
                title,
                products,
             
            }
        }              

    })

    app.mount('#app');
</script>
                   

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';