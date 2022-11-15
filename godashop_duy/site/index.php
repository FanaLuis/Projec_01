<?php 
session_start();
//load models
require "../config.php";
require "../connectDB.php";
require_once "../boostrap.php";

//router
$c = $_GET["c"] ?? "home";
$a = $_GET["a"] ?? "index";
$controllerName = ucfirst($c). "Controller";
require "controller/" . $controllerName . ".php";
$controller = new $controllerName();//new StudentController();
$controller->$a();//$controller->list();
?>