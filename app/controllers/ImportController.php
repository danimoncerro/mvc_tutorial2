<?php
require_once APP_ROOT . '/app/models/Category.php';


class ImportController
{
    public function categories()
    {
        require_once APP_ROOT .'/app/views/admin/importCategory/index.php';
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
                $categoryModel->create(
                    $categoryName
                );

            }

            fclose($handle);
            


        } else {
            echo 'Csv did not upload';
        }
    }
}


