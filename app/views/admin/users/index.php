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
            
        },
        setup() {
           
            const users = ref([]);
            const totalusers = ref(0);
            const search = ref('');
            
            const editingUser = reactive({
                id: null,
                email: ''
            });
            const selectedUser = ref(null);
            const shippingAddressDetail = ref(null);

            const currentUserId = <?= isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'null' ?>;

            const showUsers = () => {
                axios.get('<?= BASE_URL ?>api/users', {
                    params: {
                        per_page: 20,
                        page: 1,
                        sort: 'id',
                        order: 'desc',
                        role: '',                  }
                })
                .then(response => {
                    users.value = response.data.users;
                    totalusers.value = response.data.total_users;

                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

            const showUserDetails = (user) => {

                selectedUser.value = user;

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
            };
        }
    });                     
    
    app.mount('#app');


</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';