<?php 
    class ProductController{
        function index(){
            $productRespository = new ProductRepository();
            $categoryRepository = new CategoryRepository();
            $item_per_page = ITEM_PER_PAGE;
            $page = $_GET['page'] ?? 1;
            $conds = [];
            $sorts = [];
            $categoryName = "Tất cả sản phẩm ";
            //toán tử 3 ngôi thông thường
            // $category_id = !empty($_GET["category_id"]) ? $_GET["category_id"] : null;

            // toán tử 3 ngôi rút gọn
            $category_id = $_GET["category_id"] ?? null;
            if($category_id){
                $conds=[
                    "category_id" => [
                    "type" => "=",
                    "val" => $category_id
                    ]
                ];
                $category = $categoryRepository->find($category_id);
                $categoryName = $category->getName();
            }//select * .... where category_id=5

            $price_range = $_GET["price-range"] ?? null;
            if($price_range){
                
                $tmp = explode("-", $price_range);
                $start = $tmp[0];
                $end = $tmp[1];
                $conds=[
                    "sale_price" => [
                    "type" => "BETWEEN",
                    "val" => "$start AND $end"
                    ]
                ];
                if($end == "greater"){
                    $conds=[
                        "sale_price" => [
                        "type" => ">",
                        "val" => $start
                        ]
                    ];
                }
            }//select * .... where sale_price betwwen 100000 AND 200000

            $sort = $_GET["sort"] ?? null;
        if ($sort) {
            $tmp = explode("-", $sort);
            $first = $tmp[0];
            $second = $tmp[1];
            $mapCol = ["price" => "sale_price", "alpha" => "name", "created" => "created_date"];

            $column = $mapCol[$first];
            $order = $second;
            $sorts = [$column => $order];
        }

            $search = $_GET["search"] ?? null;
            if($search){
                $conds=[
                    "name" => [
                    "type" => "LIKE",
                    "val" => "'%$search%'"
                    ]
                ];
            }//select * .... where name LIKE %$search%
            $products = $productRespository->getBy($conds, $sorts, $page, $item_per_page);

            $totalProducts = $productRespository->getBy($conds, $sorts);
            $pageNumber = count($totalProducts) / $item_per_page;
            //lấy tất cả sản phẩm danh mục
            $categoryRepository = new CategoryRepository();
            $categories = $categoryRepository->getAll();
            require "view/product/index.php";
        }

        function show(){
            $id = $_GET["id"];
            $productRespository = new productRepository();
            $product = $productRespository->find($id);
            $category_id = $product->getCategoryId();

            //lấy tất cả các danh mục
            $categoryRepository = new CategoryRepository();
            $categories = $categoryRepository->getAll(); 

            $conds = [
                "category_id" => [
                    "type" => "=",
                    "val" => $product->getCategoryId() 
                ],
                "id"=>[
                    "type" => "!=",
                    "val" => $id
                ]
            ];
            $relatedProducts = $productRespository->getBy($conds);
            require "view/product/show.php";

        }

        function ajaxSearch(){
            $pattern = $_GET["pattern"];
            $productRespository = new   ProductRepository();
            $products = $productRespository->getByPattern($pattern);
            require "view/product/ajaxSearch.php";
        }
        function storeComment(){

            $data = [
                "email"=> $_POST["email"],
                "fullname"=> $_POST["fullname"],
                "star" => $_POST["rating"],
                "created_date" => date("Y-m-d H:i:s"),
                "description" => $_POST["description"],
                "product_id" => $_POST["product_id"],
            ];
            $commentRepository = new CommentRepository();
            $commentRepository->save($data);
            $productRespository = new   ProductRepository();
            $product = $productRespository->find($_POST["product_id"]);
            $comments = $product->getComments();
            require "layout/comments.php";
        }
    }
