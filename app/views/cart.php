<?php
$title = 'Products List';
ob_start();
?>

<div id="app" class="container">
    <h1 class="my-4">Coș de cumpărături</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produs</th>
                <th>Cantitate</th>
    
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in cart" :key="item.id">
                <td>{{ item.product_id }}</td>
                <td>{{ item.quantity }}</td>
            </tr>
        </tbody>

    </table>

</div>


<!-- aici incepe partea de Vue.json_decode -->

<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    const app = createApp({
        setup() {
            const cart = ref([]);

            const getCart = () => {
                axios.get('<?= BASE_URL ?>api/cart')
                    .then(response => {
                        cart.value = response.data;
                    })
                    .catch(error => {
                        console.error('Eroare la încărcarea coșului:', error);
                    });
            };

            onMounted(() => {
                getCart();
            });

            return {
                cart
            };
        }
    });

    app.mount('#app');

</script>



<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';



