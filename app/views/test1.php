<?php
$title = 'Lista de produse - test1';
ob_start();
?>

<div id="app">

    <h1> {{ title }} </h1>
    <h2> {{contor}} </h2>
    <button @click=contor++>Adauga</button> <p>
    <button @click="scadere">Scade</button>
    <h3>{{ haplea }}</h3>
    <textarea v-model="haplea"></textarea>
</div>

<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    

    const app = createApp({
        setup() {
            const title = ref('Lista de produse - test1');
            const contor = ref(3) 
            const haplea = ref(0)
            const scadere = () => {
                contor.value--
            }

            const showProducts = () => {
                axios.get('<?= BASE_URL?>/apiproducts',{
             

                })
            }
            .then ( response => {
                products.value = response.data.products
            })
            return{
                title,
                contor,
                scadere,
                haplea
            }

            const afiseazaText = (text) => {
                haplea.value = text
            }
        }              

    })

    app.mount('#app');
</script>
                   

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';