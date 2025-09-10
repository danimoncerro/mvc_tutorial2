<?php
$title = 'Categories List';
ob_start();
?>

<script src="<?= BASE_URL ?>frontend/js/components/ShowCategoryTitle.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/AddCategory.js"></script>

<div id="app" class="container">

    <show-category-title :categories="categories"></show-category-title>
    <add-category :savelink="'<?= BASE_URL ?>api/categories/store'" @show-categories="showCategories"></add-category>

    <div class="mb-3">
        <div class="col-md-3">
                <input v-model="search" type="text" class="form-control" placeholder="Caută categorie   ...">
        </div>
    </div>

    


    <table v-if="showTableData" class="table table-striped table-hover table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>
                    Nume 
                </th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="category in categories" :key="category.id">
                <td>{{ category.id }}</td>
                <td 
                    class="category-name-cell"
                    @mouseenter="hoveredCategoryName = category.name"
                    @mouseleave="hoveredCategoryName = ''"
                    @click="hideTable(category)"
                    style="cursor: pointer;"
                >
                    {{ category.name }}
                </td>
                <td>
                    <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editCategoryModal" @click="editCategory(category)" title="Editează categoria">
                        <i class="bi bi-pencil"></i>
                        Editează
                    </button>
                    <button class="btn btn-danger btn-sm" @click="deleteCategory(category.id)" title="Șterge categoria">
                        <i class="bi bi-trash"></i>
                        Sterge
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

        <!-- Modal pentru editarea categoriilor -->
        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Editează categoria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="updateCategory()">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Nume Categorie</label>
                                <input type="text" class="form-control" id="categoryName" v-model="editingCategory.name" required>
                            </div>
                            <div class="mb-3">
                                <label for="categoryDescription" class="form-label">Descriere</label>
                                <textarea class="form-control" id="categoryDescription" v-model="editingCategory.description" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                        <button type="button" class="btn btn-primary" @click="updateCategory()">
                            <i class="bi bi-check-circle"></i> Actualizează Categoria
                        </button>
                    </div>
                </div>
            </div>
        </div>


</div>

<!-- Aici incepe Vue.js -->
<script>
    const { createApp, ref, computed, onMounted, reactive, watch } = Vue;

    const app = createApp({
        components: {
            'show-category-title': ShowCategoryTitle,
            'add-category': AddCategory
        },
        setup() {
            
            const categories = ref([]);
            const totalcategories = ref(0);
            const showTableData = ref(true);
            const selectedCategory = ref(null);
            const hoveredCategoryName = ref('');
            const incrementsnumber = ref(0);
            const search = ref('');
            const editingCategory = reactive({
                id: '',
                name: '',
                description: ''
            });
            
            
            const showCategories = () => {
                axios.get('<?= BASE_URL ?>api/categories', {
                    params: {
                        per_page: 20,
                        page: 1,
                        sort: 'id',
                        order: 'desc'
                    }
                })
                .then(response => {
                    categories.value = response.data.categories;
                    totalcategories.value = response.data.total_categories;
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }


            const increments = () => {
                incrementsnumber.value++;
            }

            const hideTable = (category) => {
                selectedCategory.value = category;
                showTableData.value = false;
            }

            const showTable = () => {
                showTableData.value = true;
                selectedCategory.value = null;
            }

            const deleteCategory = (categoryId) => {
                if (!confirm('Ești sigur că vrei să ștergi această categorie?')) {
                    return;
                }

                axios.post('<?= BASE_URL ?>api/categories/delete?id=' + categoryId, {

                })
                .then(response => {
                    console.log('Category deleted:', response.data);
                     //Refresh the category list
                    showCategories();
                    alert('Categorie șters cu succes!');
                })
                .catch(error => {
                    console.error('Error deleting category:', error);
                    alert('Eroare la ștergerea categoriei!');
                });
            }

            const searchCategories = () => {
                axios.get('<?= BASE_URL ?>api/categories/search?search=' + search.value)
                .then(response => {
                    categories.value = response.data.categories;
                    totalcategories.value = response.data.total_categories;
                })
                .catch(error => {
                    console.error('API Error:', error);
                });
            }

            const editCategory = (cat) => {
                editingCategory.name = cat.name;
                editingCategory.description = cat.description;
                editingCategory.id = cat.id;
            }
            
            const updateCategory = () => {
                axios.post('<?= BASE_URL ?>api/categories/edit?id=' + editingCategory.id, {
                    name: editingCategory.name, 
                    description: editingCategory.description
                })
                .then(response => {
                    console.log('Category modified:', response.data);

                    // Reset the editing category form
                    editingCategory.name = '';
                    editingCategory.description = '';
                    editingCategory.id = '';
                    
                    // Închide modalul
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editCategoryModal'));
                    modal.hide();

                    // Refresh the category list
                    showCategories();
                })
                .catch(error => {
                    console.error('Error adding category:', error);
                    alert('Eroare la adăugarea categoriei!');
                });
            }

            onMounted(() => {
                showCategories();
            });

            watch(search, (value) => {
                if (value.length > 2) {
                    searchCategories();
                }
                if (value.length === 0) {
                    showCategories();
                }
            });


            return{
                categories,
                totalcategories,
                showTableData,
                selectedCategory,
                hoveredCategoryName,
                incrementsnumber,
                editingCategory,
                showCategories,
                editCategory,
                updateCategory,
                deleteCategory,
                hideTable,
                showTable,
                increments,
                search,
                searchCategories
            };
        }
    });                     
    
    app.mount('#app');

</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';