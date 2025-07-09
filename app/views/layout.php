<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'My MVC App' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>frontend/bootstrap/bootstrap.min.css">
    <script src="<?= BASE_URL ?>frontend/js/axios.min.js"></script>
    <script src="<?= BASE_URL ?>frontend/js/vue.js"></script>
    <script src="<?= BASE_URL ?>frontend/js/main.js"></script>
    <script src="<?= BASE_URL ?>frontend/js/components/ShowTitle.js"></script>
    <script src="<?= BASE_URL ?>frontend/js/components/IncrementComponent.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="<?= BASE_URL ?>">Home</a>
        <?php if (isset($_SESSION['user'])): ?>
            <a class="nav-link" href="<?= BASE_URL ?>products">Products</a> 
            <a class="nav-link ms-3" href="<?= BASE_URL ?>categories">Categories</a> 
            <a class="nav-link ms-3" href="<?= BASE_URL ?>users">Users</a>
            <span class="ms-3"><?= htmlspecialchars($_SESSION['user']['email']) ?></span>
            <a href="<?= BASE_URL ?>auth/logout" class="btn btn-danger btn-sm ms-3">Logout</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>auth/login" class="btn btn-primary btn-sm">Login</a>
        <?php endif; ?>
        

    </nav>
    <div class="container mt-4" id="app">
           
        <button @click="showcategories">Arata categoriile</button>

        <ul>
            <li v-for="category in categories" :key="category.id">
                {{ category.name }} - {{ category.id }}   
            </li>
        <hr>
        <ul> 
            <li v-for="fruit in fruits" :key="fruit">{{ fruit }}</li>
        </ul>
        <p>stoc: {{ stocmessage }}</p>
        <p>
            Stoc: <input type="number" v-model="stoc" class="form-control mb-2" min="0" step="1"> 

        </p>
            
        <p>
            Price: <input type="number" v-model="price" class="form-control mb-2" min="0" step="1">
            Price with TVA: {{ pricetva }} RON
        </p>
        <button @click="stoc++" class="btn btn-success">Adaugă la stoc</button>
        <button @click="stoc--" class="btn btn-danger">Scade din stoc</button>
        <hr>
        <increment-component></increment-component>

        <p>First Name: {{ firstname }}</p>
        <p>Last Name: {{ lastname }}</p>
        <input v-model="firstname" placeholder="Enter first name" class="form-control mb-2">
        <input v-model="lastname" placeholder="Enter last name" class="form-control mb-2">

        <p>Full Name: {{ fullName }}</p>
        <show-title></show-title> 
        <increment-component></increment-component>
        

        <!--teste, exercitii tema de casa -->

        <p> Stoc1: 
            <div :class="stocClass">{{ stocmessage1 }}</div>
        </p>
        <input type="number" v-model="stoc1" class="form-control mb-2" min="0" step="1">

        <hr>
        <h4>Selectează un produs:</h4>
        <select v-model="selectedProduct" class="form-select w-50 mb-3">
        <option disabled value="">-- Alege un produs --</option>
        <option v-for="product in products" :value="product.name" :key="product.name">
            {{ product.name }}
        </option>
        </select>

        <div v-if="selectedImage">
            <img :src="selectedImage" :alt="selectedProduct" style="max-width: 200px; border-radius: 8px;">
        </div>
      
    </div>

    <script>
        const { createApp, ref, computed, onMounted } = Vue;

        const app = createApp({
            setup() {
                const showtitle = ref(true);
                const message = ref('Hello from Vue.js!');
                const firstname = ref('Dani');
                const lastname = ref('Popescu');
                const stoc = ref(10);
                const price = ref(100);
                const fruits = ref(['Apple', 'Banana', 'Cherry']);
                const categories = ref([]);


                const fullName = computed( function()  {
                    return `${firstname.value} ${lastname.value}`;

                })

                const stocmessage = computed(() => {
                    return stoc.value > 0 ? 'In stoc' : 'Stoc epuizat';
                });

                const pricetva = computed(() => {
                    return price.value * 1.19
                });

                              
                const stoc1 = ref(1);

                const stocmessage1 = computed(() => {
                    if (stoc1.value === 0) return 'Alerta stoc';
                    if (stoc1.value < 10) return 'Stoc limitat';
                    return 'Stoc suficient';   
                });

                const stocClass = computed(() => {
                    if (stoc1.value === 0) return 'stoc-alerta';
                    if (stoc1.value < 10) return 'stoc-limitat';
                    return 'stoc-suficient';
                });

                const products = ref([
                    { name: 'Măr', img: 'https://upload.wikimedia.org/wikipedia/commons/1/15/Red_Apple.jpg' },
                    { name: 'Banana', img: 'https://upload.wikimedia.org/wikipedia/commons/8/8a/Banana-Single.jpg' },
                    { name: 'Cireșe', img: 'https://upload.wikimedia.org/wikipedia/commons/b/bb/Cherry_Stella444.jpg' }
                ]);

                const selectedProduct = ref('');
                const selectedImage = computed(() => {
                    const product = products.value.find(p => p.name === selectedProduct.value);
                    return product ? product.img : '';    
                });     

                const showcategories = () => {
                    axios.get('<?= BASE_URL ?>api/categories')
                        .then(response => {
                            categories.value = response.data.categories;
                           
                        })
                }        

                onMounted(() => {
                    showcategories();
                });

                
                return { 
                    message, 
                    fullName, 
                    firstname, 
                    lastname,
                    stoc,
                    stocmessage,
                    price,
                    pricetva,
                    fruits,
                    stoc1,
                    stocmessage1,
                    stocClass,
                    products,
                    selectedProduct,
                    selectedImage,
                    showcategories,
                    categories                    
                };
            }
        });
        
        app.component('show-title', ShowTitle);
        app.component('increment-component', IncrementComponent);
        app.mount('#app');



    </script>
<style scoped>
.stoc-suficient {
  background-color: green;
  color: white;
  font-weight: bold;
  padding: 6px;
  border-radius: 5px;
}

.stoc-limitat {
  background-color: yellow;
  color: red;
  font-weight: bold;
  padding: 6px;
  border-radius: 5px;
}

.stoc-alerta {
  background-color: red;
  color: white;
  font-weight: bold;
  padding: 6px;
  border-radius: 5px;
}
</style>
</body>
</html>