<?php

class Database
{
    public static function connect()
    {
        try {
            return new PDO("mysql:host=localhost;dbname=mvc_tutorial", "root", "");
        } catch (PDOException $e) {
            die("❌ Eroare conexiune DB: " . $e->getMessage());
        }
    }
}



