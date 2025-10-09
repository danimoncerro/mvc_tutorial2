<?php
$title = 'Categories List';
ob_start();

// Inițializează sortarea dacă nu vine din controller
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'asc';
?>
<h1>Categories</h1>
<a href='<?= BASE_URL ?>categories/create' class='btn btn-primary'>Adaugă categorie</a>





<script>


// Exemplu de request cu axios
axios.get('http://localhost:8080/api/categories', {
        params: {
            per_page: 5, // sau orice altă valoare dorită
            page: 1, // pagina curentă
            sort: 'id', // câmpul după care se sortează
            order: 'asc' // ordinea de sortare
        }
    })
    .then(function(response) {
        afiseazaTabelDate(response.data.categories);
    })
    .catch(function(error) {
        document.getElementById('tabel-ajax').innerHTML = 'Eroare la preluarea datelor!';
    });
</script>
<div id="tabel-ajax">Se incarca ...</div>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';