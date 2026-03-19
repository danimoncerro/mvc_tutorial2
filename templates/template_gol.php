<?php
$title = 'Lista de produse - exercitiu';
ob_start();
?>

<div id="app">

    <h1> {{ title }} </h1>

</div>

<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    

    const app = createApp({
        setup() {
            const title = ref('Lista de produse - exercitiu2')
            return{
                title
            }
        }              

    })

    app.mount('#app');
</script>
                   

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';