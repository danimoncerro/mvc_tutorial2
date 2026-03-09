<?php
$title = 'Lista de produse - exercitiu';
ob_start();
?>

<script src="<?= BASE_URL ?>frontend/js/components/AdaugaProdus.js"></script>

<div id="app">

    <h1> {{ title }} </h1>

    <adauga-produs :savelink="'<?= BASE_URL ?>api/products/store'" :categories="categories"></adauga-produs>

</div>

<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue; 

    const app = createApp({

        components: {
            'adauga-produs': AdaugaProdus
        },

        setup() {
            const title = ref('Lista de produse - exercitiu2')
            const categories = ref([])

            const showCategories = () => {
                axios.get('<?= BASE_URL ?>api/categories', {
                    params: {
                        per_page: 20,
                        page: 1,
                        sort: 'name',
                        order: 'asc'
                    }
                })
                .then(response => {
                    categories.value = response.data.categories;
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

            onMounted(() => {
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