<?php
$title = 'Dashboard';
ob_start();
?>

<div id="app">

    <h1> {{ title }} </h1>

    <div v-if="loading">
        <h1> Se incarca datele ... </h1>
    </div>
    <div v-else>
        <h2> Total comenzi: {{totalOrders}} </h2>
        <h2> Valoare totala comenzi: {{valueOrders}} lei </h2>
    </div>

</div>

<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    const app = createApp({
        setup() {
            const title = ref('Dashboard')
            const loading = ref(true)
            const totalOrders = ref('')
            const valueOrders = ref('')

            const showOrders = () => {
                         
                axios.get('<?= BASE_URL ?>api/order/dashboard')
                    .then(response => {
                        totalOrders.value = response.data.total_order
                        valueOrders.value = response.data.value_order

                        loading.value = false
                    })
            }

            onMounted(() => {
                showOrders()
            })

            return{
                title,
                totalOrders,
                valueOrders,
                loading
            }
        }              

    })

    app.mount('#app');
</script>


<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';