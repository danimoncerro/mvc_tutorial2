<?php
$title = 'Categories List';
ob_start();
?>

<script src="<?= BASE_URL ?>frontend/js/components/ShowCategoryTitle.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/AddCategory.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/DeleteCategory.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/EditCategory.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/SearchCategory.js"></script>
<script src="<?= BASE_URL ?>frontend/js/components/CategoryDetail.js"></script>

<div id="app" class="container">

    <show-category-title :categories="categories"></show-category-title>
    <add-category :savelink="'<?= BASE_URL ?>api/categories/store'" @show-categories="showCategories"></add-category>

<!--  Componenta de cautare  -->
    <search-category @search-categories="searchCategories"></search-category>

    <category-detail :category="selectedCategory">
    </category-detail>
    

    


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
                    <button class="btn btn-warning btn-sm me-2"
                    data-bs-toggle="modal" 
                    data-bs-target="#editCategoryModal"  
                    @click="editCategory(category)" title="Editează categoria">
                        <i class="bi bi-pencil"></i>
                        Editează
                    </button>
                    <button 
                        @click="showCategoryDetails(category)" 
                        class="btn btn-warning btn-sm me-2" 
                        data-bs-toggle="modal" 
                        data-bs-target="#categoryDetail" 
                        title="Detalii categorie"
                    >
                        <i class="bi bi-pencil"></i>
                        Detalii categorie
                    </button>

                    <delete-category :deletelink="'<?= BASE_URL ?>api/categories/delete?id=' + category.id" @show-categories="showCategories"></delete-category>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Componenta EditCategory -->
    <edit-category 
        :updatelink="'<?= BASE_URL ?>api/categories/edit'" 
        :editing-category="editingCategory"
        @show-categories="showCategories">
    </edit-category>

</div>


<!-- Aici incepe Vue.js -->
<script>
    const { createApp, ref, computed, onMounted, reactive, watch } = Vue;

    const app = createApp({
        components: {
            'show-category-title': ShowCategoryTitle,
            'add-category': AddCategory,
            'delete-category': DeleteCategory,
            'edit-category': EditCategory,
            'search-category': SearchCategory,
            'category-detail': CategoryDetail,

        },
        setup() {
            
            const categories = ref([]);
            const totalcategories = ref(0);
            const showTableData = ref(true);
            const selectedCategory = ref(null);
            const hoveredCategoryName = ref('');
            const incrementsnumber = ref(0);
            const editingCategory = reactive({
                id: '',
                name: '',
                description: ''
            });
            
            
            const searchCategories = (search) => {
            axios.get('<?= BASE_URL ?>api/categories/search?search=' + search)
            .then(response => {
                categories.value = response.data.categories;
                totalcategories.value = response.data.total_categories;
            })
            .catch(error => {
                console.error('API Error:', error);
            });
            }

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

            const showCategoryDetails = (category) => {

                selectedCategory.value = category;

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

            

            

            const editCategory = (category) => {
                editingCategory.id = category.id;
                editingCategory.name = category.name;
                editingCategory.description = category.description || '';
            }

            onMounted(() => {
                showCategories();
            });

           


            return{
                categories,
                totalcategories,
                showTableData,
                selectedCategory,
                hoveredCategoryName,
                incrementsnumber,
                showCategories,
                editCategory,
                hideTable,
                showTable,
                increments,
                editingCategory,  // Adaugă această linie
                searchCategories,
                showCategoryDetails

            };
        }
    });                     
    
    app.mount('#app');

</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';
?>