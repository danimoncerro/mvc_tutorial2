<?php
$title = 'Lista de adrese de livrare - exercitiu';
ob_start();
?>

<style>
.shipping-v2-table thead th {
    background: linear-gradient(180deg, #d9ecff 0%, #b8dcff 100%);
    color: #212529;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 2px solid #8fbce8;
    box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.75);
}

.shipping-v2-table thead th:first-child {
    border-top-left-radius: 0.4rem;
}

.shipping-v2-table thead th:last-child {
    border-top-right-radius: 0.4rem;
}
</style>


<div id="app" class="container">
    <h1>{{shipping}}</h1>
    <h2>Pauza</h2>
    <h1>{{shipping.city}}</h1>

    <table class="shipping-v2-table table table-striped table-hover table-bordered">
        <thead class="table-light">
            <select v-model="selectedCity" @change="showShippingAddress">
                <option value="">Toate localitatile</option>
                <option v-for="item in shipping" :key="shipping.id" :value="city">
                    {{ item.city }} - {{ item.address}}
                </option>
            </select>
            <h1>
                {{selectedShipping}}
            </h1>


            <tr>
                <th>ID</th>
                <th>
                    Strada 
                </th>
                <th>
                    Orasul
                </th>
                <th>
                    Judetul
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="product in products" :key="product.id">
                <td>
                   
                </td>
                <td>
                </td>
                
                <td>
                    
                </td> 
                <td>
                    
                </td>
                
            </tr>
        </tbody>
    </table>


</div>

<script>

    const {createApp, ref, computed, onMounted, reactive} = Vue;

    const app = createApp({
        setup(){
            const shipping = ref([])
            const selectedShipping = ref('')

            const showShippingAddress = () => {
                axios.get('<?=BASE_URL ?>api/v2/shipping_address')
                    .then(response => {
                        shipping.value = response.data
                    })
            }

            onMounted(() => {
                showShippingAddress()
            })

            return {
                shipping,
                showShippingAddress
            }
        }
    })

    app.mount('#app')

</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT.'/app/views/layout.php';
