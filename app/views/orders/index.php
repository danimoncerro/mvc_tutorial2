<?php
$title = 'Orders List';

ob_start();
?>

<div id="app" class="container">
    <h1>Orders 
        <span class="badge bg-secondary" v-if="orders.length">{{ totalorders }}</span>
    </h1>

    <div class="mb-3">
        <h5>Filtrare comenzi</h5>
        <div class="row g-3">
           
            <div class="col-md-3">
                <select v-model="filters.status" class="form-select" @change="showOrders()">
                    <option value="">Toate statusurile</option>
                    <option v-for="status in allStatuses" :key="status" :value="status">
                        {{ status }}
                    </option>
                </select>
            </div>

        </div>

         <!--  Componenta de cautare  -->
        

    </div>

    

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
                    <span v-if="editingStatusId !== order.id" @dblclick="startEditStatus(order)">
                        {{ order.status }}
                    </span>
                    <div v-else class="d-flex align-items-center">
                        <select 
                            class="form-select form-select-sm me-2" 
                            v-model="editingStatus"
                            @keyup.enter="saveStatus(order)"
                            @keyup.escape="cancelEditStatus()"
                        >
                            <option v-for="status in allStatuses" :key="status" :value="status">
                                {{ status }}
                            </option>
                        </select>
                        <button @click="saveStatus(order)" class="btn btn-success btn-sm me-1" title="Salvează status">
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <button @click="cancelEditStatus()" class="btn btn-secondary btn-sm" title="Anulează">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
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
            const allStatuses = ref(['pending', 'delivered', 'canceled']);
            const filters = ref({
                status: ''
            });
            const editingStatus = ref('');
            const editingStatusId = ref(null);
            const currentUserId = <?= isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'null' ?>;

            const showOrders = () => {

                console.log('Current User ID:', currentUserId);
    
                if (!currentUserId) {
                    console.error('User ID is null - user not logged in?');
                    return;
                }

                const params = {
                    user_id: currentUserId
                };

                // Adaugă filtrul de status dacă este setat
                if (filters.value.status) {
                    params.status = filters.value.status;
                }

                axios.get('<?= BASE_URL ?>api/orders', {
                    params: params
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

            const searchOrders = (searchTerm) => {
                if (searchTerm.length > 2) {
                    axios.get('<?= BASE_URL ?>api/orders', {
                        params: {
                            per_page: 20,
                            page: 1,
                            sort: 'id',
                            order: 'desc',
                            status: status
                        }
                    })
                    .then(response => {
                        orders.value = response.data.orders;
                     
                    })
                    .catch(error => {
                        console.error('Search Error:', error);
                    });
                } else if (searchTerm.length === 0) {
                    showOrders();
                }
            };


            const startEditStatus = (order) => {
                editingStatusId.value = order.id;
                editingStatus.value = order.status;
            };

            const cancelEditStatus = () => {
                editingStatusId.value = null;
                editingStatus.value = '';
            };

            const saveStatus = (order) => {
                const newStatus = editingStatus.value;
                
                axios.post('<?= BASE_URL ?>api/orders/update-status', {
                    id: order.id,
                    status: newStatus
                })
                .then(response => {
                    console.log('Status actualizat cu succes:', response.data);
                    
                    const index = orders.value.findIndex(o => o.id === order.id);
                    if (index !== -1) {
                        orders.value[index].status = newStatus;
                    }
                    
                    cancelEditStatus();
                })
                .catch(error => {
                    console.error('Eroare la actualizarea statusului:', error);
                    alert('Eroare la actualizarea statusului. Te rog încearcă din nou!');
                });
            };

            onMounted(() => {
                showOrders();
            });

            return{
                orders,
                showOrders,
                searchOrders, // ADAUGĂ ACEASTA
                totalorders,
                editingStatus,
                editingStatusId,
                allStatuses,
                filters,
                startEditStatus,
                cancelEditStatus,
                saveStatus,
            };
        }
    });                     
    
    app.mount('#app');
</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';