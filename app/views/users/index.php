<?php
$title = 'Users List';
ob_start();
?>

<script src="<?= BASE_URL ?>frontend/js/components/AddUser.js"></script>


<div id="app" class="container">
    <h1>Users 
        <span class="badge bg-secondary" v-if="users.length">{{ totalusers }}</span>
    </h1>

    <add-user :savelink="'<?= BASE_URL ?>api/users/store'" @show-users="showUsers"></add-user>

    <div class="mb-3">
        <div class="col-md-3">
                <input v-model="search" type="text" class="form-control" placeholder="Search for users  ...">
        </div>
    </div>

    

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
                    
                    <button class="btn btn-danger btn-sm" @click="deleteUser(user.id)" title="Șterge utilizatorul">
                        <i class="bi bi-trash"></i>
                        Sterge
                    </button>
                </td> 
                
            </tr>
        </tbody>
    </table>

    <!-- Modal pentru editarea utilizatorilor -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Editează utilizator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updateUser()">
                        <div class="mb-3">
                            <label for="editUserEmail" class="form-label">Email utilizator</label>
                            <input type="text" class="form-control" id="editUserEmail" v-model="editingUser.email" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                    <button type="button" class="btn btn-primary" @click="updateUser()">
                        <i class="bi bi-check-circle"></i> Salvează modificările
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

                    

<!-- Aici incepe Vue.js -->
<script>
        const { createApp, ref, computed, onMounted, reactive, watch } = Vue;   
        
        const app = createApp({
            components: {
            'add-user': AddUser
        },
        setup() {
           
            const users = ref([]);
            const totalusers = ref(0);
            const search = ref('');
            
            const editingUser = reactive({
                id: null,
                email: ''
            });

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

            

            const searchUsers = () => {
                if (search.value.trim() === '') {
                    showUsers();
                    return;
                }
                
                axios.get('<?= BASE_URL ?>api/users/search?search=' + search.value)
                .then(response => {
                    users.value = response.data.users;
                    totalusers.value = response.data.users.length;
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

            const deleteUser = (userId) => {
                if (!confirm('Ești sigur că vrei să ștergi acest utilizator?')) {
                    return;
                }

                axios.post('<?= BASE_URL ?>api/users/delete?id=' + userId, {
                })
                .then(response => {
                    console.log('User deleted:', response.data);
                     //Refresh the user list
                    showUsers();
                    alert('Utilizator șters cu succes!');
                })
                .catch(error => {
                    console.error('Error deleting user:', error);
                    alert('Eroare la ștergerea utilizatorului!');
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

            const updateUser = () => {
                axios.post('<?= BASE_URL ?>api/users/edit?id=' + editingUser.id, {
                    email: editingUser.email
                })
                .then(response => {
                    console.log('User modified:', response.data);

                    // Reset the user form
                    editingUser.email = '';

                    // Închide modalul
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                    modal.hide();

                    // Refresh the user list
                    showUsers();

                    // Afișează mesaj de succes (opțional)
                    //alert('Produs modificat cu succes!');
                })
                .catch(error => {
                    console.error('Error adding product:', error);
                    alert('Eroare la adăugarea produsului!');
                });
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
                deleteUser,
                editUser,
                updateUser,
                search,
                searchUsers

            };
        }
    });                     
    
    app.mount('#app');


</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';