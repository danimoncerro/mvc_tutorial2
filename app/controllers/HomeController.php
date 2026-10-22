<?php




class HomeController
{
    public function index()
    {
        // Include the view file for the home page
        require_once APP_ROOT . '/app/views/home.php';
    }

    public function thankyou()
    {
        require_once APP_ROOT . '/app/views/thankyou.php';
    }
}