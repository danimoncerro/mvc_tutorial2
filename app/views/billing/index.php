<?php
$title = 'billing';

ob_start();
?>

<div id="app">

    <h1>{{title}}</h1>

    <form @submit.prevent="saveBillingAddress()">
        <div class="mb-3">
            <label for="" class="form-label">Adresa</label>
            <input type="text" class="form-control" v-model="address.address" required>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Codul zip</label>
            <input type="text" class="form-control" v-model="address.zip_code" required>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Oras</label>
            <input type="text" class="form-control" v-model="address.city" required>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Judet</label>
            <input type="text" class="form-control" v-model="address.county" required>
        </div>
        <button type="submit">Adauga adresa de facturare</button>
        
    </form>

    <table class="table table-striped table-hover table-bordered">
        <thead class="table-light">
            <tr>
                <th>User id</th>
                <th>Adresa</th>
                <th>Codul Zip</th>
                <th>Oras</th>
                <th>Judet</th>
                <th>Actiuni</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="address in addresses" :key="address.id">
                <td>{{address.user_id}}</td>
                <td>{{address.address}}</td>
                <td>{{address.zip_code}}</td>
                <td>{{address.city}}</td>
                <td>{{address.county}}</td>
                <td>
                    <button class="btn btn-danger btn-sm" @click="removeFromBilling(address.id)">Șterge</button>
                </td>
            </tr>
    </table>

</div>

<!-- Aici incepe Vue.js -->
<script>
    const { createApp, ref, reactive, onMounted } = Vue;

    const app = createApp({
        setup() {
            const title = "Billing address";
            const currentUserId = <?= isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'null' ?>;
            const address = reactive({
                address:'',
                zip_code:'',
                city:'',
                county:'',
                user_id: currentUserId

            }) 

            const addresses = ref([])
            const getAddresses = () => {
                const params = {
                    user_id: currentUserId,
                };
                console.log(currentUserId)
                axios.get('<?= BASE_URL ?>api/billing?user_id=' + currentUserId)
                    .then(response => {
                        addresses.value = response.data
                    })
            }

            const saveBillingAddress = () => {
                axios.post('<?= BASE_URL ?>api/billing/store', address)
                    .then(response => {
                        console.log('Billing address added:', response.data);
                        getAddresses();
                        address.city = "";
                        address.zip_code = "";
                        address.county = "";
                        address.address = "";
                    })
            }

            const removeFromBilling = (addressId) => {
                if (!addressId) return;
                axios.post('<?= BASE_URL ?>api/billing/delete', { address_id: addressId })
                    .then(res => {
                        if (res.data.success) getAddresses();
                        else alert('Eroare la ștergere: ' + (res.data.message || ''));
                    })
                    .catch(() => alert('Eroare la ștergerea adresei !'));
            };

            onMounted(() => {
                getAddresses();
            });


            return { 
                title,
                address,
                saveBillingAddress,
                getAddresses,
                addresses,
                removeFromBilling
            }
        }

    });                     
    
app.mount('#app');
</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';