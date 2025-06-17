<?php

class Database
{
    public static function connect()
    {
        try {
            return new PDO("mysql:host=db;dbname=mvc_tutorial2", "root", "root");
        } catch (PDOException $e) {
            die("❌ Eroare conexiune DB: " . $e->getMessage());
        }
    }
}



