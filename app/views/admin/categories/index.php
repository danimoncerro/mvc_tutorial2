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

    <!-- Paginare -->


    <nav aria-label="Paginare comenzi">
        <ul class="pagination">
            <li class="page-item" style="cursor: pointer" :class="{disabled: currentPage ===1 }">
                <a class="page-link" @click="goToPage(currentPage - 1)" tabindex="-1">Previous</a>
            </li>
            <!-- tratam prima pagina -->
            <li class="page-item active" style="cursor: pointer" v-if="currentPage < 2">
                <a class="page-link" @click="goToPage(currentPage)">
                    {{ currentPage }}
                </a>
            </li>
            
            <li class="page-item" style="cursor: pointer" v-if="currentPage < 2">
                <a class="page-link" @click="goToPage(currentPage + 1)">
                    {{ currentPage + 1}}
                </a>
            </li>

            <li class="page-item" style="cursor: pointer" v-if="currentPage < 2">
                <a class="page-link" @click="goToPage(currentPage + 2)">
                    {{ currentPage + 2 }}
                </a>
            </li>

            <!-- tratam pagina > 1 -->
            <li class="page-item" style="cursor: pointer" v-if="currentPage>1 && currentPage<totalPages">
                <a class="page-link" @click="goToPage(currentPage - 1)">
                    {{ currentPage - 1}}
                </a>
            </li>
            <li class="page-item active"  style="cursor: pointer"  v-if="currentPage>1 && currentPage<totalPages">
                <a class="page-link" @click="goToPage(currentPage)">
                    {{ currentPage}}
                </a>
            </li>
            <li class="page-item" style="cursor: pointer"  v-if="currentPage>1 && currentPage<totalPages">
                <a class="page-link" @click="goToPage(currentPage + 1)">
                    {{ currentPage + 1 }}
                </a>
            </li>

            <!-- tratam utlima pagina -->
            <li class="page-item" style="cursor: pointer" v-if="currentPage === totalPages">
                <a class="page-link" @click="goToPage(currentPage - 2)">
                    {{ currentPage - 2}}
                </a>
            </li>
            <li class="page-item"  style="cursor: pointer"  v-if="currentPage === totalPages">
                <a class="page-link" @click="goToPage(currentPage - 1)">
                    {{ currentPage - 1}}
                </a>
            </li>
            <li class="page-item active" style="cursor: pointer"  v-if="currentPage === totalPages">
                <a class="page-link" @click="goToPage(currentPage)">
                    {{ currentPage }}
                </a>
            </li>
             
            <li class="page-item" style="cursor: pointer" :class="{disabled: currentPage === totalPages }">
                <a class="page-link"  @click="goToPage(currentPage + 1)">Next</a>
            </li>
        </ul>

        <div class="mb-3">
            <h5>Categories/page</h5>
            <div class="row g-3">
            
                <div class="col-md-3">
                    <select v-model="filters.per_page" class="form-select" @change="showCategories(1)">
                        <option value="5">5 categories</option>
                        <option v-for="page in perPages" :key="page" :value="page">
                            {{ page }} categories
                        </option>
                    </select>
                </div>

            </div>
        </div>

        <div class="text-muted mt-2">
            Pagina {{ currentPage }} din {{ totalPages }} (Total: {{ totalcategories }} categories)
        </div>

    </nav>


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
            const perPages = ref([10, 15, 20]);
            const currentPage = ref(1);
            const totalPages = ref(1);
            const filters = reactive({
                search: '',
                per_page: 5
            });
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

            const showCategories = (page) => {

                const params = {
            
                    page: page
                };

                //if (filters.page) {
                //    params.per_page = filters.page;
                //}

                axios.get('<?= BASE_URL ?>api/categories', {
                    params: {
                        per_page: filters.per_page,
                        page: 1,
                        sort: 'id',
                        order: 'desc'
                    }
                })
                .then(response => {
                    categories.value = response.data.categories;
                    totalcategories.value = response.data.total_categories;
                    totalPages.value = response.data.total_pages;
                    console.log('Total Pages din showCategories', totalPages.value );
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

            
            const goToPage = (page) => {
                console.log('Going to page:', page);
                
                if (page < 1 || page > totalPages.value) {
                    console.log('TotalPages = ', totalPages.value)
                    console.log('Invalid page:', page);
                    return;  // Previne navigarea la pagini invalide
                }
                
                currentPage.value = page;
                console.log('Current Page din .then (response:', currentPage.value);
                showCategories(page);
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
                showCategoryDetails,
                perPages,
                currentPage,
                totalPages,
                goToPage,
                filters

            };
        }
    });                     
    
    app.mount('#app');

</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';
?>