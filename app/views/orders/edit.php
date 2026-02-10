<?php

$title = 'Editeaza comanda';
ob_start();


?>

<div id="app" class="container">

    <h1 class="mb-4">{{title}} {{id}}</h1> 

    <form action="<?= BASE_URL ?>orders/store" method="POST" class="mx-auto p-4 border rounded shadow-sm bg-white">

        <table class="table table-striped table-hover table-bordered" >
            <thead class="table-light">
                <tr>
                    <th>Nume produs</th>
                    <th>Cantitate</th>
                    <th>Pret</th>
                    <th>Actiuni</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td>{{product.product_name}}</td>
                    <td>{{product.qty}}</td>
                    <td>{{product.product_price_db}}</td>
                    <td>
                        <button type="button" @click="deleteProductFromOrder(product.id)">Sterge</button>
                    </td>

                </tr>

            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Actualizeaza comanda</button>
    </form>

</div>


<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;
    const app = createApp({    
        setup() {
            const title = ref("Editeaza comanda");
            const id = ref(<?= $_GET['id'] ?>);
            const products = ref([]);

            const getOrder = () => {

                axios.get('<?= BASE_URL ?>api/orderdetail?order_id=' + id.value )
                    .then(response => {
                        products.value = response.data 
                    })
                    .catch(error => {
                        console.error('API Error:', error);
                    });
            };

            const deleteProductFromOrder = (localid) => {
               
                axios.post('<?= BASE_URL ?>api/orderitems/delete?id=' + localid)
                    .then(response => {
                        console.log("Produs sters din comanda.")

                        axios.get('<?= BASE_URL ?>api/orders/updatetotal?order_id=' + id.value)

                        getOrder();
                    })

            }

            onMounted(() => {
                getOrder();
            });

            return {
                title,
                getOrder,
                id,
                products,
                deleteProductFromOrder
            }
        }
    })
    app.mount('#app');
</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';
?>
