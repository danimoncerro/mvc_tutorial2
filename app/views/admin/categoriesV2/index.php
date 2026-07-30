<?php
$title = 'Lista de categorii v2';
ob_start();
?>

<style>
.categories-v2-table thead th {
    background: linear-gradient(180deg, #d9ecff 0%, #b8dcff 100%);
    color: #212529;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 2px solid #8fbce8;
    box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.75);
}

.categories-v2-table thead th:first-child {
    border-top-left-radius: 0.4rem;
}

.categories-v2-table thead th:last-child {
    border-top-right-radius: 0.4rem;
}
</style>

<div id="app" class="container">


    <table class="categories-v2-table table table-striped table-hover table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>
                    Nume 
                </th>
                <th>
                    Descriere
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="category in categories" :key="category.id">
                <td>
                    {{ category.id }}
                </td>
                <td>
                    {{ category.name }}
                </td>
                <td>
                    {{ category.description}}
                </td> 
            </tr>
        </tbody>
    </table>
</div>


<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    

    const app = createApp({
        setup() {
            const title = ref('Lista de categorii - V2')
            const categories = ref([])

            const showCategories = () => {

                axios.get('<?= BASE_URL ?>api/v2/categories')
                    .then(response => {
                        categories.value = response.data
                    })

            }

            onMounted(()=>{
                showCategories()
            })

            return{
                title,
                categories
            }
        }              

    })

    app.mount('#app');
</script>
                   

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';