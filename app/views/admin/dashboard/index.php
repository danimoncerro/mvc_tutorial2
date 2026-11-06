<?php
$title = 'Dashboard';
ob_start();
?>

<div id="app">

    <h1> {{ title }} </h1>
    <h2> Total comenzi: {{totalOrders}} </h2>
    <h2> Valoare totala comenzi: {{valueOrders}} lei </h2>

</div>

<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    const app = createApp({
        setup() {
            const title = ref('Dashboard')
            const totalOrders = ref('')
            const valueOrders = ref('')

            const showOrders = () => {
                         
                axios.get('<?= BASE_URL ?>api/order/dashboard')
                    .then(response => {
                        totalOrders.value = response.data.total_order
                        valueOrders.value = response.data.value_order
                    })
            }

            onMounted(() => {
                showOrders()
            })

            return{
                title,
                totalOrders,
                valueOrders
            }
        }              

    })

    app.mount('#app');
</script>


<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';