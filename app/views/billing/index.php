<?php
$title = 'billing';

ob_start();
?>

<div id="app">

<h1>{{title}}</h1>

</div>

<script>

    const {createApp, ref} = Vue;

    const app = createApp({
        setup() {
            const title = "Billing address";
        
            return {
                title
            }
        }
    });

    app.mount("#app")

</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';