<?php
$title = 'Users List';
ob_start();
?>

<script src="<?= BASE_URL ?>frontend/js/components/AdaugaUtilizator.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/StergeUtilizator.js"></script>



<div id="app" class="container">

    <adauga-utilizator
        :savelink="'<?= BASE_URL ?>api/users/store'" @afiseaza-utilizatori="afiseazaUtilizatori"
    >
    </adauga-utilizator>


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
            <tr v-for="user in utilizatori" :key="user.id">
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
                    <sterge-utilizator 
                        :deletelink="'<?= BASE_URL ?>api/users/delete?id=' + user.id" 
                        @afiseaza-utilizatori="afiseazaUtilizatori">
                    </sterge-utilizator>
                </td> 
                
            </tr>
        </tbody>
    </table>

</div>

<!-- Aici incepe Vue.js -->
<script>
        const { createApp, ref, computed, onMounted, reactive, watch } = Vue;   
        
        const app = createApp({
            components: {
            'adauga-utilizator': AdaugaUtilizator,
            'sterge-utilizator': StergeUtilizator,
            //'edit-user': EditeazaUtilizator,
            //'user-detail': DetaliiUtilizator,
           
            
            },
            setup() {
            
                const utilizatori = ref([]);
                
                const editareUtilizator = reactive({
                    id: null,
                    email: '',
                    role: ''
                });
                const selectedUser = ref(null);

                const currentUserId = <?= isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'null' ?>;

                const afiseazaUtilizatori = (page) => {
                    axios.get('<?= BASE_URL ?>api/users', {
                          params: {
                            per_page: 100
                          }              
                    })
                    .then(response => {
                        utilizatori.value = response.data.users;
                        //totalusers.value = response.data.total_users;
                        

                    })
                    .catch(error => {
                        console.error('API Error:', error);
                    });
                }  
                
                onMounted(() => {
                    afiseazaUtilizatori(1);
                });

                return {
                    afiseazaUtilizatori,
                    utilizatori,
                   // editareUtilizator
                };
            }
            
        });


    app.mount('#app');

</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';