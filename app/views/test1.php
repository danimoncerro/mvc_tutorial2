<?php
$title = 'Lista de produse - test1';
ob_start();
?>

<div id="app">

    <h1> {{ title }} </h1>
    <h2> {{contor}} </h2>
    <button @click="contor++">Adauga</button> <p>
    <button @click="scadere">Scade</button>
    <h3>{{ haplea }}</h3>
    <textarea v-model="haplea"></textarea>
    <h1>per page:</h1><input v-model="per_page" type="number" step="5">
    
    <h4 v-for= "product in products">
        {{product.name}} - {{product.price}} lei
</h4>
</div>

<script>
    const { createApp, ref, computed, onMounted, reactive, watch } = Vue;

    

    const app = createApp({
        setup() {
            const title = ref('Lista de produse - test1');
            const contor = ref(3) 
            const haplea = ref(0)
            const products = ref([])
            const per_page = ref(10)

            const scadere = () => {
                contor.value--
            }

            const showProducts = () => {
                axios.get('<?= BASE_URL ?>api/products', {
                    params: {
                        per_page: per_page.value,
                        page: 1,
                        sort: 'price',
                        order: 'desc'

                    }

                })
                .then(response =>{
                    products.value = response.data.products;
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

            watch(per_page, () => {
                showProducts();
            })

            onMounted(() => {
                showProducts();
            })

            return{
                title,
                contor,
                scadere,
                haplea,
                products,
                per_page
            }

            
        }              

    })

    app.mount('#app');
</script>
                   

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';