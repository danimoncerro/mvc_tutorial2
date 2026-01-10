# Copilot Instructions - MVC Tutorial Project

## Architecture Overview

This is a **custom PHP MVC e-commerce application** running in Docker with Vue.js frontend components. The architecture follows a lightweight, non-framework approach:

- **MVC Pattern**: Manual routing via custom `Router` class, no frameworks (no Laravel/Symfony)
- **Database**: MySQL 8.0 via PDO, accessed through singleton `Database::connect()` 
- **Frontend**: Vue.js 3 (Composition API) with Axios for AJAX, Bootstrap 5 for styling
- **Containerization**: Docker Compose with web (Apache/PHP 8.2), MySQL, and phpMyAdmin services

## Critical Directory Structure

```
app/
  controllers/           # Regular page controllers
    management/          # Admin-only CRUD controllers
    Api*Controller.php   # JSON API endpoints (no "Api" prefix in management/)
  core/Router.php        # Custom routing engine
  models/                # PDO-based models (no ORM)
  views/                 # PHP views with layout.php wrapper
config/
  routes.php             # Centralized route definitions (BASE_URL defined here)
  database.php           # PDO connection singleton
public/
  index.php              # Entry point (bootstraps session, autoloader, router)
  frontend/js/components/ # Vue.js single-file-like components
```

## Routing Conventions

**Routes are defined in** [config/routes.php](config/routes.php):
- Format: `$router->get('path', 'ControllerName@method')` or `$router->post(...)`
- Controllers in subdirectories: `'management/ProductController@index'`
- Query params accessed via `$_GET`, body data via `$_POST` or `file_get_contents('php://input')`

**Examples:**
- Regular page: `$router->get('products', 'management/ProductController@index')`
- API endpoint: `$router->get('api/products', 'ApiProductController@index')`

## Controller Patterns

### Page Controllers (HTML Views)
- Render views via `require_once APP_ROOT . '/app/views/cart.php'`
- Views use output buffering (`ob_start()`) and include [app/views/layout.php](app/views/layout.php)
- Access session data directly: `$_SESSION['user']`

### API Controllers (JSON)
- **Always** set header: `header('Content-Type: application/json; charset=utf-8')`
- Read JSON body: `json_decode(file_get_contents('php://input'), true)`
- Fallback to `$_POST` for form data
- Return JSON: `echo json_encode(['status' => 'success', 'data' => $result])`

**Example pattern** (see [app/controllers/management/ApiProductController.php](app/controllers/management/ApiProductController.php)):
```php
public function store() {
    $input = json_decode(file_get_contents('php://input'), true);
    $data = $input ?? $_POST; // Fallback to form data
    
    $model = new Product();
    $model->create($data);
    
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
}
```

## Model Patterns

Models use PDO directly (no ORM):
- Constructor initializes: `$this->db = Database::connect()`
- **Common methods**: `all()`, `find($id)`, `create($data)`, `update($id, $data)`, `delete($id)`
- Pagination: `getPaginated($limit, $offset)`, `countAll()`
- Use prepared statements: `$stmt = $this->db->prepare(...); $stmt->execute([...])`
- Join categories: `LEFT JOIN categories ON products.category_id = categories.id`

**Example** (see [app/models/Product.php](app/models/Product.php)):
```php
public function create($data) {
    $stmt = $this->db->prepare("INSERT INTO products (name, price, category_id) VALUES (:name, :price, :category_id)");
    $stmt->execute($data);
}
```

## Vue.js Frontend Integration

Views embed Vue apps using CDN (no build step):
- **Template**: Vue Composition API (`createApp`, `setup()`, `ref`, `computed`)
- **Data fetching**: Axios calls to API routes (e.g., `axios.get('<?= BASE_URL ?>api/cart')`)
- **Components**: Separate files in [public/frontend/js/components/](public/frontend/js/components/) loaded via `<script src="...">`
- **State management**: Local refs, no Vuex/Pinia

**Example** (see [app/views/cart.php](app/views/cart.php)):
```javascript
const app = createApp({
    setup() {
        const cart = ref([]);
        const getCart = () => {
            axios.get('<?= BASE_URL ?>api/cart')
                .then(response => cart.value = response.data);
        };
        onMounted(getCart);
        return { cart, getCart };
    }
});
app.mount('#app');
```

## Development Workflow

### Running the Application
```powershell
# Start containers
docker-compose up -d

# Access application: http://localhost:8080
# phpMyAdmin: http://localhost:8081 (root/root)
```

### Database Connection
- **Host**: `db` (Docker service name, not `localhost`)
- **Credentials**: root/root (see [config/database.php](config/database.php))
- **Database**: mvc_tutorial2

### File Organization Rules
- New controllers go in `app/controllers/` (or `management/` if admin-only)
- API controllers prefix with `Api` (e.g., `ApiCartController.php`)
- Models in `app/models/` match table names (singular, e.g., `Product.php` for `products` table)
- Always require models at top of controllers: `require_once APP_ROOT . '/app/models/Product.php'`

## Session Management

- Sessions start in [public/index.php](public/index.php): `session_start()`
- User data stored in `$_SESSION['user']` after login
- Check authentication in controllers:
  ```php
  if (!isset($_SESSION['user'])) {
      header("Location: " . BASE_URL . "auth/login");
      exit;
  }
  ```
- Role-based access: `$_SESSION['user']['role'] === 'admin'`

## Common Gotchas

1. **No namespaces**: All classes are global, avoid duplicate names
2. **Manual requires**: No autoloading - require dependencies at file top
3. **APP_ROOT constant**: Defined in [public/index.php](public/index.php), use for absolute paths
4. **BASE_URL in routes**: Defined in [config/routes.php](config/routes.php), accessible in views
5. **Router subdirectories**: Controller path `'management/ProductController'` loads from `app/controllers/management/`
6. **Docker host**: Use `db` not `localhost` for MySQL connections inside containers
7. **Apache rewrites**: `.htaccess` redirects to `public/index.php?url=...` (mod_rewrite enabled)

## Testing & Debugging

- Check Docker logs: `docker-compose logs -f web`
- MySQL queries via phpMyAdmin or: `docker exec -it mvc_mysql_db mysql -uroot -proot mvc_tutorial2`
- PHP errors display inline (no dedicated error handler)
- Vue debugging: Browser DevTools + Vue DevTools extension
