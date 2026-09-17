<?php
require_once APP_ROOT . '/app/models/Category.php';
require_once APP_ROOT . '/app/models/User.php';
require_once APP_ROOT . '/app/models/Product.php';
require_once APP_ROOT . '/app/models/Order.php';

class ExportController
{
    public function categories()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getAllSortedPaginated('name', 'asc', 100);
        // CSV filename
        $filename = "categories_" . date("Y-m-d") . ".csv";

        // Tell browser to download CSV
        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // Open output stream
        $output = fopen("php://output", "w");
        fputcsv($output, ['Id', 'Category name', 'Category description', 'Nr. product']);

        foreach ($categories as $category) {
            fputcsv($output, [
                $category['id'],
                $category['name'],
                $category['description'],
                $category['nr_product']
            ]);
        }
    
        fclose($output);

    }


    public function users()
    {
        $userModel = new User();
        $users = $userModel->all();
        // CSV filename
        $filename = "users_" . date("Y-m-d") . ".csv";

        // Tell browser to download CSV
        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // Open output stream
        $output = fopen("php://output", "w");

        fputcsv($output, [
            'id',
            'email',
            'role'
        ] );

        foreach($users as $user) {
            fputcsv($output,[
                $user['id'],
                $user['email'],
                $user['role']


            ]);

        }

        fclose($output);


    }

    public function products()
    {
        $productModel = new Product();
        $products = $productModel->all();
        // CSV filename
        $filename = "products_" . date("Y-m-d") . ".csv";

        // Tell browser to download CSV
        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // Open output stream
        $output = fopen("php://output", "w");

        fputcsv($output, [
            'id',
            'name',
            'price'
        ] );

        foreach($products as $product) {
            fputcsv($output,[
                $product['id'],
                $product['name'],
                $product['price']
            ]);

        }

        fclose($output);

    }

    public function orders()
    {
        $orderModel = new Order();
        $totalOrders = $orderModel->countAll();
        $orders = $orderModel->all(null, 1, 'id', 'DESC', max(1, $totalOrders));
        // CSV filename
        $filename = "orders_" . date("Y-m-d") . ".csv";

        // Tell browser to download CSV
        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // Open output stream
        $output = fopen("php://output", "w");

        fputcsv($output, [
            'id',
            'status',
            'total_order'
        ] );

        foreach($orders as $order) {
            fputcsv($output,[
                $order['id'],
                $order['status'],
                $order['total_order']
            ]);

        }

        fclose($output);

    }



}

