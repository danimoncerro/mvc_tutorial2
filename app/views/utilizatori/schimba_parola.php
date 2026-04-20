<?php
$title = 'Schimbare parola';
ob_start();
?>

<div id="app">

    <h1> {{ title }} </h1>

</div>

<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    

    const app = createApp({
        setup() {
            const title = ref('Schimbare parola')
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