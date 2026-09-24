<?php
require_once APP_ROOT . '/app/models/Category.php';
require_once APP_ROOT . '/app/models/Product.php';



class ImportController
{
    public function categories()
    {
        require_once APP_ROOT .'/app/views/admin/importCategory/index.php';
    }

    public function products()
    {
        require_once APP_ROOT .'/app/views/admin/importProduct/index.php';
    }

    

    public function importCategories()
    {
        $file = $_FILES['csvfile'];
        $uploadDir = APP_ROOT.'/public/uploads/';
        $sourceFile = $file['tmp_name'];
        $destFile = $uploadDir.$file['name'];

        if(move_uploaded_file($sourceFile, $destFile)){
            //echo 'Csv uploaded';

            $handle = fopen($destFile, 'r');
            fgetcsv($handle);

            $categoryModel = new Category();
            while (($row = fgetcsv($handle)) !== false) {
                if (!isset($row[0])) {
                    continue;
                }

                $categoryName = $row[0];
                echo "$categoryName <br>";

                $categoryExist = $categoryModel->search($categoryName);

                if ($categoryExist) {
                    echo ' already exists!<br>';
                    continue;
                }
                $categoryModel->create(
                    $categoryName
                );

            }

            fclose($handle);
            


        } else {
            echo 'Csv did not upload';
        }
    }

    public function importProducts(){
        $file = $_FILES['csvfile'];
        $uploadDir = APP_ROOT.'/public/uploads/';
        $sourceFile = $file['tmp_name'];
        $destFile = $uploadDir.$file['name'];

        if (move_uploaded_file($sourceFile, $destFile)){
            $handle = fopen($destFile, 'r');
            fgetcsv($handle);
            
            $productModel = new Product();
            while (($row = fgetcsv($handle)) !== false) {
                if (!isset($row[0])) {
                    continue;
                }
                $productName = $row[0];
                $productPrice = $row[1];
                echo "$productName  ";
                echo "$productPrice <br>";

                $productExist = $productModel->findByName($productName);
                
                if ($productExist){
                    echo ' already exists!<br>';
                    continue;
                }
                $productModel ->create([
                    'name' => $productName,
                    'price' => $productPrice,
                    'category_id' => 81,
                    'discount' => 0,
                    'price_discount' => $productPrice
                ]

                );
            }

            fclose($handle);

        } else {
            echo 'CSV did not upload';
        }




    }
}


