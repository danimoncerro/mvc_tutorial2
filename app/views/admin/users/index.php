<?php
$title = 'Users List';
ob_start();
?>

<script src="<?= BASE_URL ?>frontend/js/components/AddUser.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/DeleteUser.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/EditUser.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/SearchUser.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/UserDetail.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/ShippingAddressDetail.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/BillingAddressDetail.js"></script>



<div id="app" class="container">
    <h1>Users 
        <span class="badge bg-secondary" v-if="users.length">{{ totalusers }}</span>
    </h1>

    <add-user :savelink="'<?= BASE_URL ?>api/users/store'" @show-users="showUsers"></add-user>

    <!--  Componenta de cautare  -->
    <search-user 
        @search-users="searchUsers"
        @show-users="showUsers">
    </search-user>

    <user-detail :user="selectedUser">
    </user-detail>

    <shipping-addresses  :shipping="shippingAddressDetail">
    </shipping-addresses>

    <billing-addresses :billing="billingAddressDetail"></billing-addresses>

   



    <table class="table table-striped table-hover table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>
                    Email 
                </th>
                <th>
                    Rol
                </th>
                <th>
                    Acțiuni
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="user in users" :key="user.id">
                <td>
                    {{ user.id }}
                </td>
                <td>
                    {{ user.email }}
                </td>
                <td>
                    {{ user.role }}
                </td>
                <td>
                    {{ user.actions }}

                    <button class="btn btn-warning btn-sm me-2"  data-bs-toggle="modal" data-bs-target="#editUserModal" @click="editUser(user)" title="Editează utilizatorul">
                        <i class="bi bi-pencil"></i>
                        Editează
                    </button>

                    <button 
                        @click="showUserDetails(user)" 
                        class="btn btn-warning btn-sm me-2" 
                        data-bs-toggle="modal" 
                        data-bs-target="#userDetail" 
                        title="Detalii utilizator"
                    >
                        <i class="bi bi-pencil"></i>
                        Detalii utilizator
                    </button>

                    <button 
                        @click="showShippingAddress(user)"
                        class="btn btn-warning btn-sm me-2" 
                        data-bs-toggle="modal"
                        data-bs-target="#shippingAddressModal"
                        title="Detalii adresa livrare"
                    >
                        <i class="bi bi-pencil"></i>
                        Detalii adresa livrare
                    </button>

                    <button 
                        @click="showBillingAddress(user)"
                        class="btn btn-warning btn-sm me-2" 
                        data-bs-toggle="modal"
                        data-bs-target="#billingAddressModal"
                        title="Detalii adresa facturare"
                    >
                        <i class="bi bi-pencil"></i>
                        Detalii adresa facturare
                    </button>

                    <delete-user :deletelink="'<?= BASE_URL ?>api/users/delete?id=' + user.id" @show-users="showUsers"></delete-user>
                    <!--
                    <button class="btn btn-danger btn-sm" @click="deleteUser(user.id)" title="Șterge utilizatorul">
                        <i class="bi bi-trash"></i>
                        Sterge
                    </button>
                    -->
                </td> 
                
            </tr>
        </tbody>
    </table>

    <!-- Componenta EditUser -->
    <edit-user 
        :updatelink="'<?= BASE_URL ?>api/users/edit'" 
        :editing-user="editingUser"
        @show-users="showUsers">

    </edit-user>

    <!-- Paginare -->


    <nav aria-label="Paginare users">
        <ul class="pagination">
            <li class="page-item" style="cursor: pointer" :class="{disabled: currentPage ===1 }">
                <a class="page-link" @click="goToPage(currentPage - 1)" tabindex="-1">Previous</a>
            </li>
            <!-- tratam prima pagina -->
            <li class="page-item active" style="cursor: pointer" v-if="currentPage < 2">
                <a class="page-link" @click="goToPage(currentPage)">
                    {{ currentPage }}
                </a>
            </li>
            
            <li class="page-item" style="cursor: pointer" v-if="currentPage < 2 && totalPages>1">
                <a class="page-link" @click="goToPage(currentPage + 1)">
                    {{ currentPage + 1}}
                </a>
            </li>

            <li class="page-item" style="cursor: pointer" v-if="currentPage < 2 && totalPages>2">
                <a class="page-link" @click="goToPage(currentPage + 2)">
                    {{ currentPage + 2 }}
                </a>
            </li>

            <!-- tratam pagina > 1 -->
            <li class="page-item" style="cursor: pointer" v-if="currentPage>1 && currentPage<totalPages && totalPages>1">
                <a class="page-link" @click="goToPage(currentPage - 1)">
                    {{ currentPage - 1}}
                </a>
            </li>
            <li class="page-item active"  style="cursor: pointer"  v-if="currentPage>1 && currentPage<totalPages">
                <a class="page-link" @click="goToPage(currentPage)">
                    {{ currentPage}}
                </a>
            </li>
            <li class="page-item" style="cursor: pointer"  v-if="currentPage>1 && currentPage<totalPages && totalPages>1">
                <a class="page-link" @click="goToPage(currentPage + 1)">
                    {{ currentPage + 1 }}
                </a>
            </li>

            <!-- tratam utlima pagina -->
            <li class="page-item" style="cursor: pointer" v-if="currentPage === totalPages && totalPages>2" >
                <a class="page-link" @click="goToPage(currentPage - 2)">
                    {{ currentPage - 2}}
                </a>
            </li>
            <li class="page-item"  style="cursor: pointer"  v-if="currentPage === totalPages && totalPages">
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
            <h5>Users/page</h5>
            <div class="row g-3">
            
                <div class="col-md-3">
                    <select v-model="filters.per_page" class="form-select" @change="showUsers(1)">
                        <option value="5">5 users</option>
                        <option v-for="page in perPages" :key="page" :value="page">
                            {{ page }} users
                        </option>
                    </select>
                </div>

            </div>
        </div>

        <div class="text-muted mt-2">
            Pagina {{ currentPage }} din {{ totalPages }} (Total: {{ totalusers }} users)
        </div>

    </nav>



</div>

                    

<!-- Aici incepe Vue.js -->
<script>
        const { createApp, ref, computed, onMounted, reactive, watch } = Vue;   
        
        const app = createApp({
            components: {
            'add-user': AddUser,
            'delete-user': DeleteUser,
            'edit-user': EditUser,
            'search-user': SearchUser,
            'user-detail': UserDetail,
            'shipping-addresses': ShippingAddressDetail,
            'billing-addresses': BillingAddressDetail,
            
        },
        setup() {
           
            const users = ref([]);
            const totalusers = ref(0);
            const search = ref('');
            
            const editingUser = reactive({
                id: null,
                email: '',
                role: ''
            });
            const selectedUser = ref(null);
            const shippingAddressDetail = ref(null);
            const billingAddressDetail = ref(null);

            const currentUserId = <?= isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'null' ?>;

            const perPages = ref([2, 10, 15, 20]);
            const currentPage = ref(1);
            const totalPages = ref(1);
            const filters = reactive({
                search: '',
                per_page: 5
            });

            const showUsers = (page) => {
                axios.get('<?= BASE_URL ?>api/users', {
                    params: {
                        per_page: filters.per_page,
                        page: page,
                        sort: 'id',
                        order: 'desc',
                        role: '',                  }
                })
                .then(response => {
                    users.value = response.data.users;
                    totalusers.value = response.data.total_users;
                    totalPages.value = response.data.total_pages;

                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

            const showUserDetails = (user) => {

                selectedUser.value = user;

            }

            const goToPage = (page) => {
                console.log('Going to page:', page);
                
                if (page < 1 || page > totalPages.value) {
                    console.log('TotalPages = ', totalPages.value)
                    console.log('Invalid page:', page);
                    return;  // Previne navigarea la pagini invalide
                }
                
                currentPage.value = page;
                console.log('Current Page din .then (response:', currentPage.value);
                showUsers(page);
            }

            const showShippingAddress = (user) => {

                axios.get('<?= BASE_URL ?>api/shipping?user_id=' + user.id)
                    .then(response => {
                        console.log('Shipping address response:', response.data);
                        // Păstrăm toate adresele returnate de API
                        shippingAddressDetail.value = response.data;
                        console.log('shippingAddressDetail.value:', shippingAddressDetail.value);
                    })
                    .catch(error => {
                        console.error('Error fetching shipping address:', error);
                        shippingAddressDetail.value = [];
                    });
            }

            const showBillingAddress = (user) => {

                axios.get('<?= BASE_URL ?>api/billing?user_id=' + user.id)
                    .then(response => {
                        console.log('Billing address response:', response.data);
                        // Păstrăm toate adresele returnate de API
                        billingAddressDetail.value = response.data;
                        console.log('billingAddressDetail.value:', billingAddressDetail.value);
                    })
                    .catch(error => {
                        console.error('Error fetching shipping address:', error);
                        billingAddressDetail.value = [];
                    });
            }

            

            const searchUsers = (s) => {

                if (s === '') {
                    showUsers();
                    return;
                }
                
                axios.get('<?= BASE_URL ?>api/users/search?search=' + s)
                .then(response => {
                    users.value = response.data.users;
                    totalusers.value = response.data.users.length;
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

            const editUser = (u) => {
                // Populează editingUser cu datele utilizatorului selectat
                editingUser.id = u.id;
                editingUser.email = u.email;
                editingUser.role = u.role;

                // Deschide modalul de editare
                //const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                //modal.show();
            }



             onMounted(() => {
                showUsers();
            });

            // Watch pentru căutare în timp real

            watch(search, (value) => {
                if (value.length > 2) {
                    searchUsers();
                }
                if (value.length === 0) {
                    showUsers();
                }
            });



            return{
                users,
                showUsers,
                totalusers,
                editingUser,
                editUser,
                search,
                searchUsers,
                selectedUser,
                showUserDetails,
                shippingAddressDetail,
                showShippingAddress,
                showBillingAddress,
                billingAddressDetail,
                 perPages,
                currentPage,
                totalPages,
                goToPage,
                filters
            };
        }
    });                     
    
    app.mount('#app');


</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';