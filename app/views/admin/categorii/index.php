<?php
$title = 'Categories List';
ob_start();
?>

<script src="<?= BASE_URL ?>frontend/js/components/ArataTitluCategorie.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/StergeCategorie.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/AdaugaCategorie.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/EditeazaCategorie.js"></script>

<div id="app" class="container">
    <arata-titlu-categorie :total="totalcategorii"></arata-titlu-categorie>
    <adauga-categorie :savelink="'<?= BASE_URL ?>api/categories/store'" @arata-categorie="arataCategorie"></adauga-categorie> 
    <editeaza-categorie 
        :updatelink="'<?= BASE_URL ?>api/categories/edit'"
        :edit-categorie="editCategorie"
        @arata-categorie="arataCategorie">
    </editeaza-categorie>
    
    <table class="table table-striped table-hover table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>
                    Nume 
                </th>
                <th>
                    Actiuni
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="categorie in categorii" :key="categorie.id">
                <td>{{categorie.id}}</td>
                <td>{{categorie.name}}</td>
                <td>
                    <button class="btn btn-warning btn-sm me-2"
                    data-bs-toggle="modal" 
                    data-bs-target="#editCategoryModal"  
                    @click="editeazaCategorie(categorie)" title="Editează categoria">
                        <i class="bi bi-pencil"></i>
                        Editează
                    </button> 
                    <sterge-categorie :deletelink="'<?= BASE_URL ?>api/categories/delete?id=' + categorie.id" @arata-categorii="arataCategorie">
                    </sterge-categorie>
                </td>
            </tr>
        </tbody>
    </table>

</div>


<!-- Aici incepe Vue.js -->
<script>

    const User = {
        first_name: "Daniel",
        last_name: "Baimareanul"
    }

    const { last_name } = User;


    const { createApp, ref, computed, onMounted, reactive, watch } = Vue;
    const app = createApp({
        components: {
            'arata-titlu-categorie': Placinta,
            'sterge-categorie': StergeCategorie,
            'adauga-categorie': AdaugaCategorie,
            'editeaza-categorie': EditeazaCategorie,
        },

        setup () {

            const categorii = ref([])
            const totalcategorii = computed(() => categorii.value.length);
            const editCategorie = reactive({
                id: '',
                name: '',
                description: ''
            });

            const arataCategorie = () =>  {
                axios.get('<?= BASE_URL ?>api/categories', {
                    params: {
                        per_page: 20,
                        page: 1,
                        sort: 'name',
                        order: 'asc'
                    }
                })
                .then(response => {
                    categorii.value = response.data.categories;
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

            const editeazaCategorie = (categorie) => {
                editCategorie.id = categorie.id,
                editCategorie.name = categorie.name,
                editCategorie.description = categorie.description || '';

            }


            onMounted(() => {
                arataCategorie();
            });

            return {
                categorii,
                totalcategorii,
                arataCategorie,
                editeazaCategorie,
                editCategorie

            };
        }
    });


    app.mount('#app');
</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';
?>