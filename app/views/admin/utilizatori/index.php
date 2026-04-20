<?php
$title = 'Users List';
ob_start();
?>

<script src="<?= BASE_URL ?>frontend/js/components/AdaugaUtilizator.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/StergeUtilizator.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/EditeazaUtilizator.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/EditeazaParola.js"></script>


<div id="app" class="container">

    <adauga-utilizator
        :savelink="'<?= BASE_URL ?>api/users/store'" @afiseaza-utilizatori="afiseazaUtilizatori"
    >
    </adauga-utilizator>

    <editeaza-utilizator
        :updatelink="'<?= BASE_URL ?>api/users/edit'"
        :utilizator="editareUtilizator"
        @afiseaza-utilizatori="afiseazaUtilizatori">
    </editeaza-utilizator>

    <editeaza-parola
        :updatelink="'<?= BASE_URL ?>api/users/edit'"
        :utilizator="editareParola"
        @afiseaza-utilizatori="afiseazaUtilizatori">
    </editeaza-parola>




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
                   <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editeazaUtilizatorModal" 
                        @click="editeazaUtilizator(user)" title="Editează utilizator">
                        <i class="bi bi-pencil"></i>
                        Editează
                    </button>


                    <sterge-utilizator 
                        :deletelink="'<?= BASE_URL ?>api/users/delete?id=' + user.id" 
                        @afiseaza-utilizatori="afiseazaUtilizatori">
                    </sterge-utilizator>

                    <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editeazaParolaModal" 
                        @click="editeazaParola(user)" title="Editează parola">
                        <i class="bi bi-pencil"></i>
                        Editează parola
                    </button>

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
            'editeaza-utilizator': EditeazaUtilizator,
            'editeaza-parola': EditeazaParola,

           
            
            },
            setup() {
            
                const utilizatori = ref([]);
                
                const editareUtilizator = reactive({
                    id: null,
                    email: '',
                    role: ''
                });
                const editareParola = reactive({
                    id: null,
                    password: ''
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
                        

                    })
                    .catch(error => {
                        console.error('API Error:', error);
                    });
                }  

                const editeazaUtilizator = (u) => {
                    editareUtilizator.id = u.id;
                    editareUtilizator.email = u.email;
                    editareUtilizator.role = u.role;
                }

                const editeazaParola = (u) => {
                    editareParola.id = u.id;
                    editareParola.password = '';
                }
                
                onMounted(() => {
                    afiseazaUtilizatori(1);
                });

                return {
                    afiseazaUtilizatori,
                    utilizatori,
                    editareUtilizator,
                    editeazaUtilizator,
                    editareParola,
                    editeazaParola
                };
            }
            
        });


    app.mount('#app');

</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';