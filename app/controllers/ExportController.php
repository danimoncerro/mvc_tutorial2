<?php
require_once APP_ROOT . '/app/models/Category.php';
require_once APP_ROOT . '/app/models/User.php';

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
        fputcsv($output, ['Id', 'Category name', 'Nr. product']);

        foreach ($categories as $category) {
            fputcsv($output, [
                $category['id'],
                $category['name'],
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
}

