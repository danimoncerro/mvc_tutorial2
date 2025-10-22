<?php
$title = 'Orders List';
ob_start();
?>

<div id="app" class="container">
    <h1>Orders 
        <span class="badge bg-secondary" v-if="orders.length">{{ totalorders }}</span>
    </h1>

    

    <table class="table table-striped table-hover table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>
                    User 
                </th>
                <th>
                    Status
                </th>
                <th>
                    Data
                </th>
                <th>
                    Total order
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="order in orders" :key="order.id">
                <td>
                    {{ order.id }}
                </td>
                <td>
                    {{ order.user_email }}
                </td>
                <td>
                    {{ order.status }}
                </td>
                <td>
                    {{ order.created_at }}
                </td> 
                <td>
                    {{ order.total_order }}
                </td> 
                
            </tr>
        </tbody>
    </table>
</div>

<!-- Aici incepe Vue.js -->
<script>
        const { createApp, ref, onMounted} = Vue;   
        
        const app = createApp({
            
        setup() {
           
            const orders = ref([]);
            const totalorders = ref(0);
            

            const showOrders = () => {
                axios.get('<?= BASE_URL ?>api/orders', {
                    params: {
                        email: 'dani3@email.com'
                    }
                })
                .then(response => {
                    console.log('Orders:', response.data);
                    orders.value = response.data.orders || [];
                    totalorders.value = response.data.total_orders || 0;
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

             onMounted(() => {
                showOrders();
            });



            return{
                orders,
                showOrders,
                totalorders,

            };
        }
    });                     
    
    app.mount('#app');


</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';