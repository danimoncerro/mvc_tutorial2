<?php
$title = 'Shipping';

ob_start();
?>

<div id="app"> 

    <h1>{{title}}</h1>

    <form @submit.prevent="addBillingAdress()">
        <div class="mb-3">
            <label for="" class="form-label">Adresa</label>
            <input type="text" class="form-control" v-model="address.address" required>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Oras</label>
            <input type="text" class="form-control" v-model="address.city" required>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Judet</label>
            <input type="text" class="form-control" v-model="address.county" required>
        </div>
        
    </form>

    <button type="button" @click="saveAddress()">Adauga adresa de livrare</button>

    <table class="table table-striped table-hover table-bordered">
        <thead class="table-light">
            <tr>
                <th>User id</th>
                <th>Adresa</th>
                <th>Oras</th>
                <th>Judet</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="address in addresses" :key="address.id">
                <td>{{address.user_id}}</td>
                <td>{{address.address}}</td>
                <td>{{address.city}}</td>
                <td>{{address.county}}</td>
            </tr>
    </table>


</div>

<!-- Aici incepe Vue.js -->
<script>
    const { createApp, ref, reactive, onMounted } = Vue;

    const app = createApp({
        setup() {
            const title = "shipping address";
            const address = reactive({
                address:'',
                city:'',
                county:'',
                user_id: 1

            }) 

            const addresses = ref([])
            const getAddresses = () => {
                axios.get('<?= BASE_URL ?>api/shipping?user_id=1')
                    .then(response => {
                        addresses.value = response.data
                    })
            }

            const saveAddress = () => {
                axios.post('<?= BASE_URL ?>api/shipping/store', address)
                    .then(response => {
                        console.log('Shipping address added:', response.data);
                        getAddresses();
                        address.city = "";
                        address.county = "";
                        address.address = "";
                    })
            }

            onMounted(() => {
                getAddresses();
            });


            return { 
                title,
                address,
                saveAddress,
                getAddresses,
                addresses
            }
        }

    });                     
    
app.mount('#app');
</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';