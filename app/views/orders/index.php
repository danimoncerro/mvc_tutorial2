<?php
$title = 'Orders List';

ob_start();
?>


<script src="<?= BASE_URL ?>frontend/js/components/Order7Detail.js"></script>


<div id="app" class="container">
    <h1>Orders 
        <span class="badge bg-secondary" v-if="orders.length">{{ totalorders }}</span>
    </h1>

    <div class="mb-3">
        <h5>Filtrare comenzi</h5>
        <div class="row g-3">
           
            <div class="col-md-3">
                <select v-model="filters.status" class="form-select" @change="showOrders(1)">
                    <option value="">Toate statusurile</option>
                    <option v-for="status in allStatuses" :key="status" :value="status">
                        {{ status }}
                    </option>
                </select>
            </div>

        </div>
        

    </div>

    <order7-detail :order="selectedOrder" 
                   :marks="marks">
    </order7-detail>

    <table class="table table-striped table-hover table-bordered">
        <thead class="table-light">
            <tr>
                <th
                    @click="sortOrder('id')"
                    style="cursor: pointer;"
                    title="Click pentru sortare dupa ID"
                >
                    ID
                </th>
                <th
                    @click="sortOrder('user_id')"
                    style="cursor: pointer;"
                    title="Click pentru sortare dupa username"
                >
                    User 
                </th>
                <th
                    @click="sortOrder('status')"
                    style="cursor: pointer;"
                    title="Click pentru sortare dupa status"
                >
                    Status
                </th>
                <th
                    @click="sortOrder('created_at')"
                    style="cursor: pointer;"
                    title="Click pentru sortare dupa data"
                >
                    Data
                </th>
                <th 
                    @click="sortOrder('total_order')"
                    style="cursor: pointer;"
                    title="Click pentru sortare dupa total order"
                >
                    Total order
                </th>
                <th>
                    Actiuni
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

                <td>   

                        <!-- Buton pentru deschidere modal -->
                    
                    <button 
                        @click="showOrderDetails(order)" 
                        class="btn btn-warning btn-sm me-2" 
                        data-bs-toggle="modal" 
                        data-bs-target="#order7DetailModal" 
                        title="Detalii comanda"
                    >
                        <i class="bi bi-pencil"></i>
                        Detalii comanda
                    </button>

                    <!-- Editeaza comanda -->

                    <a :href="'<?= BASE_URL ?>/orders/edit?id=' + order.id"
                    class="btn btn-primary">
                        Editeaza
                    </a>

                    <button class="btn btn-danger btn-sm" type="button" 
                        @click="deleteOrder(order.id)"
                        v-if="order.total_order==0"
                        >Sterge</button>                   
                </td>
                
            </tr>
        </tbody>
    </table>

    <!-- Paginare -->


    <nav aria-label="Paginare comenzi">
        <ul class="pagination">
            <li class="page-item" style="cursor: pointer" :class="{disabled: currentPage ===1 }">
                <a class="page-link" @click="goToPage(currentPage - 1)" tabindex="-1">Previous</a>
            </li>
            <!-- avem in total 6 pagini -->
            <!-- tratam prima pagina -->
            <li class="page-item active" style="cursor: pointer" v-if="currentPage < 2">
                <a class="page-link" @click="goToPage(currentPage)">
                    {{ currentPage }}
                </a>
            </li>
            
            <li class="page-item" style="cursor: pointer" v-if="currentPage < 2">
                <a class="page-link" @click="goToPage(currentPage + 1)">
                    {{ currentPage + 1}}
                </a>
            </li>

            <li class="page-item" style="cursor: pointer" v-if="currentPage < 2">
                <a class="page-link" @click="goToPage(currentPage + 2)">
                    {{ currentPage + 2 }}
                </a>
            </li>

            <!-- tratam pagina > 1 -->
            <li class="page-item" style="cursor: pointer" v-if="currentPage>1 && currentPage<totalPages">
                <a class="page-link" @click="goToPage(currentPage - 1)">
                    {{ currentPage - 1}}
                </a>
            </li>
            <li class="page-item active"  style="cursor: pointer"  v-if="currentPage>1 && currentPage<totalPages">
                <a class="page-link" @click="goToPage(currentPage)">
                    {{ currentPage}}
                </a>
            </li>
            <li class="page-item" style="cursor: pointer"  v-if="currentPage>1 && currentPage<totalPages">
                <a class="page-link" @click="goToPage(currentPage + 1)">
                    {{ currentPage + 1 }}
                </a>
            </li>

            <!-- tratam utlima pagina -->
            <li class="page-item" style="cursor: pointer" v-if="currentPage === totalPages">
                <a class="page-link" @click="goToPage(currentPage - 2)">
                    {{ currentPage - 2}}
                </a>
            </li>
            <li class="page-item"  style="cursor: pointer"  v-if="currentPage === totalPages">
                <a class="page-link" @click="goToPage(currentPage - 1)">
                    {{ currentPage - 1}}
                </a>
            </li>
            <li class="page-item active" style="cursor: pointer"  v-if="currentPage === totalPages">
                <a class="page-link" @click="goToPage(currentPage)">
                    {{ currentPage }}
                </a>
            </li>
             
            <li class="page-item" style="cursor: pointer" :class="{disabled: currentPage === totalPages }">
                <a class="page-link"  @click="goToPage(currentPage + 1)">Next</a>
            </li>
        </ul>

        <div class="mb-3">
            <h5>Orders/page</h5>
            <div class="row g-3">
            
                <div class="col-md-3">
                    <select v-model="filters.page" class="form-select" @change="showOrders(1)">
                        <option v-for="page in perPages" :key="page" :value="page">
                            {{ page }} orders
                        </option>
                    </select>
                </div>

            </div>
        </div>

        <div class="text-muted mt-2">
            Pagina {{ currentPage }} din {{ totalPages }} (Total: {{ totalorders }} comenzi)
        </div>

    </nav>


</div>

<!-- Aici incepe Vue.js -->
<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;
    const app = createApp({    
        setup() {
           
            const orders = ref([]);
            const totalorders = ref(0);
            const allStatuses = ref(['pending', 'delivered', 'canceled']);
            const perPages = ref([5, 7, 10, 15, 20]);
            const currentPage = ref(1);
            const totalPages = ref(1);
            const filters = ref({
                status: '',
                page: ''
            });
            const editingStatus = ref('');
            const editingStatusId = ref(null);
            const currentUserId = <?= isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'null' ?>;
            const orderDirection = ref('desc');
            const orderColumn = ref('id');
            const selectedOrder = ref(null); 
            const detaillink = ref('');
            const marks = ref([]);

            const showOrders = (page) => {
                
                //page = currentPage.value;
                console.log('Current User ID:', currentUserId);
                console.log('totalPages:' , totalPages.value);
                console.log('Showing page:', page);
                
    
                if (!currentUserId) {
                    console.error('User ID is null - user not logged in?');
                    return;
                }

                const params = {
                    user_id: currentUserId,
                    order_column: orderColumn.value,
                    order_direction: orderDirection.value,
                    page: page
                };

                // Adaugă filtrul de status dacă este setat
                if (filters.value.status) {
                    params.status = filters.value.status;
                }

                if (filters.value.page) {
                    params.per_page = filters.value.page;
                    console.log('Params:', params);
                }



                axios.get('<?= BASE_URL ?>api/orders', {
                    params: params
                })
                .then(response => {
                    console.log('Orders:', response.data);
                    orders.value = response.data.orders || [];
                    totalorders.value = response.data.total_orders || 0;
                    currentPage.value = response.data.current_page || 1;
                    totalPages.value = response.data.total_pages || 1;

                    
                    console.log('Total Pages din .then (response:', totalPages.value);
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

            const deleteOrder = (orderid) => {
               
                axios.post('<?= BASE_URL ?>api/orders/delete?id=' + orderid)
                    .then(response => {
                        console.log("Comanda stearsa.")

                        //axios.get('<?= BASE_URL ?>api/orders/updatetotal?order_id=' + orderid)

                        showOrders(1);
                    })

            }

            const goToPage = (page) => {
                console.log('Going to page:', page);
                
                if (page < 1 || page > totalPages.value) {
                    console.log('Invalid page:', page);
                    return;  // Previne navigarea la pagini invalide
                }
                
                currentPage.value = page;
                console.log('Current Page din .then (response:', currentPage.value);
                showOrders(page);
            }

            const sortOrder = (column) => {

                orderColumn.value = column;

                if (orderDirection.value == 'desc') {
                    orderDirection.value = 'asc';
                }

                else {
                    orderDirection.value = 'desc';
                }

                showOrders(1);
            }

            const searchOrders = (searchTerm) => {
                if (searchTerm.length > 2) {
                    axios.get('<?= BASE_URL ?>api/orders', {
                        params: {
                            per_page: 20,
                            page: 1,
                            sort: 'id',
                            order: 'desc',
                            status: filters.value.status
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

            const startSortingTotalOrder = () => {
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

                axios.get('<?= BASE_URL ?>api/total_order', {
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
            };

            const showOrderDetails = (order) => {
                console.log('Afisez detalii pentru comanda:', order);
                selectedOrder.value = order;
                detaillink.value='<?= BASE_URL ?>api/orderdetail?order_id=' + order.id; 


                axios.get('<?= BASE_URL ?>api/orderdetail?order_id=' + order.id )
                    .then(response => {
                        marks.value = response.data;
                    })
                    .catch(error => {
                        console.error('API Error:', error);
                    });


            };


            onMounted(() => {
                showOrders(1);
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
                startSortingTotalOrder,
                orderDirection,
                orderColumn,
                sortOrder,
                perPages,
                currentPage,
                totalPages,
                goToPage,
                selectedOrder,
                showOrderDetails,
                detaillink,
                marks,
                deleteOrder
            };
        }
    });   
    
    
    app.component('order7-detail', Order7Detail);

    app.mount('#app');
</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';