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
        <button @click="increment" class="btn btn-primary">Increment</button>
        <p>Count: {{ count }}</p>

        <p>First Name: {{ firstname }}</p>
        <p>Last Name: {{ lastname }}</p>
        <input v-model="firstname" placeholder="Enter first name" class="form-control mb-2">
        <input v-model="lastname" placeholder="Enter last name" class="form-control mb-2">

        <p>Full Name: {{ fullName }}</p>
      
    </div>

    <script>
        const { createApp, ref, computed } = Vue;

        createApp({
            setup() {
                const message = ref('Hello from Vue.js!');
                const firstname = ref('Dani');
                const lastname = ref('Popescu');
                const stoc = ref(10);
                const price = ref(100);
                const fruits = ref(['Apple', 'Banana', 'Cherry']);


                const fullName = computed( function()  {
                    return `${firstname.value} ${lastname.value}`;

                })

                const stocmessage = computed(() => {
                    return stoc.value > 0 ? 'In stoc' : 'Stoc epuizat';
                });

                const pricetva = computed(() => {
                    return price.value * 1.19
                });

                const count = ref(0);

                function increment() {
                    count.value++;
                }
                return { 
                    message, 
                    count, 
                    increment, 
                    fullName, 
                    firstname, 
                    lastname,
                    stoc,
                    stocmessage,
                    price,
                    pricetva,
                    fruits
                };
            }
        }).mount('#app');



    </script>
</body>
</html>