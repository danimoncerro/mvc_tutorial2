<?php
$title = 'Lista de produse - exercitiu';
ob_start();
?>

<div id="app">

    <h1> {{ title }} </h1>

</div>

<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    const app = createApp({
        setup() {
            const title = ref('Lista de produse - exercitiu2')
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