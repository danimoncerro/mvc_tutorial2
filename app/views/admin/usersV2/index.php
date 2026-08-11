<?php
$title = 'Lista de utilizatori - V2';
ob_start();
?>

<style>
.users-v2-table thead th {
    background: linear-gradient(180deg, #d9ecff 0%, #b8dcff 100%);
    color: #212529;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 2px solid #8fbce8;
    box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.75);
}

.users-v2-table thead th:first-child {
    border-top-left-radius: 0.4rem;
}

.users-v2-table thead th:last-child {
    border-top-right-radius: 0.4rem;
}
</style>

<div id="app" class="container">
   
    <table class="users-v2-table table table-striped table-hover table-bordered">
        <thead class="table-light">

            <select v-model="selectedRole" @change="showUsers">
                <option value="">Toate rolurile</option>
                <option v-for="role in roles" :key="role" :value="role">
                    {{ role }}
                </option>
                
            </select>

            <tr>
                <th>ID</th>
                <th>
                    EMAIL 
                </th>
                <th>
                    ROLE
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
            </tr>
        </tbody>
    </table>
</div>

<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    

    const app = createApp({
        setup() {
            const title = ref('Lista de utilizatori - V2')
            const users = ref([])
            const roles = ref([])
            const selectedRole = ref('')

            const showUsers = () => {

                axios.get('<?= BASE_URL ?>api/v2/users', {
                    params: {
                        role: selectedRole.value 
                    }
                })
                    .then(response => {
                        users.value = response.data
                    })
            }

            const getRoles =() => {
                axios.get('<?= BASE_URL ?>api/v2/users/roles')
                    .then(response => {
                        roles.value = response.data
                    })
            }

            onMounted(() => {
                showUsers()
                getRoles()
            })

            return{
                title,
                users,
                roles,
                selectedRole,
                showUsers,
                getRoles
            }
        }              

    })

    app.mount('#app');
</script>
                   

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';