<?php
$title = 'Demo API pentru incepatori';
ob_start();
?>

<div id="app" class="container">
    <h1 class="mb-4">Demo API + Endpoint (de la 0)</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">1) Test GET endpoint</h5>
            <p class="text-muted">Apasa butonul si citeste raspunsul JSON de la server.</p>
            <button class="btn btn-primary" @click="callGetEndpoint">Apeleaza GET /api/demo/message</button>
            <pre class="mt-3 p-3 bg-light border rounded">{{ getResponseText }}</pre>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">2) Test POST endpoint</h5>
            <p class="text-muted">Scrie un nume. Frontend il trimite catre endpoint, iar backend-ul il intoarce in JSON.</p>

            <form @submit.prevent="callPostEndpoint">
                <div class="mb-3">
                    <label class="form-label">Nume</label>
                    <input type="text" class="form-control" v-model="name" placeholder="Ex: Dani">
                </div>
                <button type="submit" class="btn btn-success">Trimite POST /api/demo/echo</button>
            </form>

            <pre class="mt-3 p-3 bg-light border rounded">{{ postResponseText }}</pre>
        </div>
    </div>
</div>

<script>
    const { createApp, ref } = Vue;

    createApp({
        setup() {
            const name = ref('');
            const getResponseText = ref('Inca nu ai apelat endpoint-ul GET.');
            const postResponseText = ref('Inca nu ai trimis date catre endpoint-ul POST.');

            const callGetEndpoint = async () => {
                try {
                    const response = await axios.get('<?= BASE_URL ?>api/demo/message');
                    getResponseText.value = JSON.stringify(response.data, null, 2);
                } catch (error) {
                    const payload = error.response ? error.response.data : { error: error.message };
                    getResponseText.value = JSON.stringify(payload, null, 2);
                }
            };

            const callPostEndpoint = async () => {
                try {
                    const response = await axios.post('<?= BASE_URL ?>api/demo/echo', {
                        name: name.value
                    });
                    postResponseText.value = JSON.stringify(response.data, null, 2);
                } catch (error) {
                    const payload = error.response ? error.response.data : { error: error.message };
                    postResponseText.value = JSON.stringify(payload, null, 2);
                }
            };

            return {
                name,
                getResponseText,
                postResponseText,
                callGetEndpoint,
                callPostEndpoint
            };
        }
    }).mount('#app');
</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';
