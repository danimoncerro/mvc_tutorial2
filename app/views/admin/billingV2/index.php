<?php
$title = 'billing';

ob_start();
?>

<p>Hello World!</p>

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

    <h1>
                
    </h1>

    <select v-model="selectedCity" @change="showShippingAddress">
        <option value="">Toate localitatile</option>
        <option v-for="city in cities" :key="city" :value="city">
            {{city}}
        </option>
    </select>

    <table class="shipping-v2-table table table-striped table-hover table-bordered">
        <thead class="table-light">
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
            <tr v-for="item in billing" :key="item.id">
                <td>
                   {{item.id}}
                </td>
                <td>
                    {{item.address}}
                </td>
                
                <td>
                    {{item.city}}
                </td> 
                <td>
                    {{item.county}}
                </td>
                
            </tr>
        </tbody>
    </table>


</div>

<script>

    const { createApp, ref, computed, onMounted, reactive } = Vue;

    const app = createApp({
        setup() {

            const billing = ref([])

            const showBillingAddress = () => {
                axios.get('<?=BASE_URL ?>api/v2/billing_address')
                    .then(response => {
                        billing.value = response.data
                    })
            }

            onMounted(() =>{
                showBillingAddress()
            })

            return{
                billing
            }






        }      
    })

    app.mount("#app");

</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';