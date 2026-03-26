<?php
$title = 'Categories List';
ob_start();
?>

<script src="<?= BASE_URL ?>frontend/js/components/ArataTitluCategorie.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/StergeCategorie.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/AdaugaCategorie.js"></script>

<div id="app" class="container">
    <arata-titlu-categorie :total="totalcategorii"></arata-titlu-categorie>
    <adauga-categorie :savelink="'<?= BASE_URL ?>api/categories/store'" @arata-categorii="arataCategorie"></adauga-categorie> 

    
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
                    <sterge-categorie :deletelink="'<?= BASE_URL ?>api/categories/delete?id=' + categorie.id" @arata-categorii="arataCategorii">
                    </sterge-categorie>
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
            'arata-titlu-categorie': Placinta,
            'sterge-categorie': StergeCategorie,
            'adauga-categorie': AdaugaCategorie,
        },

        setup () {

            const categorii = ref([])
            const totalcategorii = computed(() => categorii.value.length);

            const arataCategorii = () => {
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

            onMounted(() => {
                arataCategorii();
            });

            return {
                categorii,
                totalcategorii,
                arataCategorii

            };
        }
    });


    app.mount('#app');
</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';
?>