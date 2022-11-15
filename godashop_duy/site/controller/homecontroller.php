<?php 
    class HomeController{
        function index(){
            $productRepository = new ProductRepository();
            $cond = [];
            $sorts = ["featured" => "DESC"];
            $page = 1;
            $item_per_page = 4;
            //lấy 4 sản phẩm nổi bật
            $featuredProduct = $productRepository->getBy($cond, $sorts, $page, $item_per_page);
            //lấy 4 sản phẩm mới nhất
            $sorts = ["created_date" => "DESC"];
            $lastestProduct = $productRepository->getBy($cond, $sorts, $page, $item_per_page);
           

           //lấy tất cả sản phẩm danh mục
           $categoryRepository = new CategoryRepository();
            $categories = $categoryRepository->getAll();
            $categoriesProduct = [];
            foreach($categories as $category){
                $cond = [
                    "category_id" =>[ 
                        "type"=> "=",
                        "val" => $category->getId()]];//select from product where category_id = 1
                $products = $productRepository->getBy($cond, $sorts, $page, $item_per_page);
                $categoriesProduct[$category->getName()] = $products;
            }
            //$categoryProduct là array  chiều
            //mỗi phần tử có key là có value
            //key là tên danh mục. valua là danh sách các tên sản phẩm thuộc danh mục
            require "view/home/index.php";
        }
        
    }
?>