<?php
$title = 'Users List';
ob_start();
?>
<div id="app" class="container">
    <h1>Users 
        <span class="badge bg-secondary" v-if="users.length">{{ totalusers }}</span>
    </h1>

    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-plus-circle"></i> Adauga user
        </button>
    </div>

    <!-- Modal pentru adăugarea utilizatorilor -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Adaugă utilizator nou</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="addUser()">
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email utilizator</label>
                            <input type="text" class="form-control" id="userEmail" v-model="user.email" required>
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                    <button type="button" class="btn btn-primary" @click="addUser()">
                        <i class="bi bi-check-circle"></i> Salvează utilizator
                    </button>
                </div>
            </div>
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
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    const app = createApp({
        setup() {
           
            const users = ref([]);
            const totalusers = ref(0);
            const user = reactive({
                email: ''
            });
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

            const addUser = () => {
                // Validare simplă
                if (!user.email) {
                    alert('Te rog completează toate câmpurile!');
                    return;
                }

                axios.post('<?= BASE_URL ?>api/users/store', {
                    email: user.email
                    
                })
                .then(response => {
                    console.log('User added:', response.data);

                    // Reset the user form
                    user.email = '';
               
                    
                    // Închide modalul
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                    modal.hide();
                    
                    // Refresh the users list
                    showUsers();
                    
                    // Afișează mesaj de succes (opțional)
                    alert('Utilizator adăugat cu succes!');
                })
                .catch(error => {
                    console.error('Error adding user:', error);
                    alert('Eroare la adăugarea utilizatorului!');
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


            return{
                users,
                showUsers,
                totalusers,
                addUser,
                user,
                editingUser,
                deleteUser,
                editUser,
                updateUser
            };
        }
    });                     
    
    app.mount('#app');


</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';