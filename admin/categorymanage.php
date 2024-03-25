<?php
include "../config/db.php";

$category = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset ($_POST["newcategory1"])) {
        $category = $_POST["category"];

        $check_sql = "SELECT * FROM categories WHERE category = '$category'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $errorMessage = "Category already exists!";
            header("location: category.php?errorMessage=" . urlencode($errorMessage));
        } else {
            // Insert the category into the database
            $sql = "INSERT INTO categories (category, parentId) VALUES ('$category', 0)";
            $result = mysqli_query($conn, $sql);

            if (!$result) {
                die("Invalid query : " . mysqli_error($conn));
            }

            $successMessage = "Successfully Added!";
            header("location: category.php?successMessage=" . urlencode($successMessage));
            exit;
        }
    }
    if (isset ($_POST["newcategory2"])) {
        $category1 = $_POST["category1"];
        $category2 = $_POST["category2"];

        $check_sql = "SELECT * FROM categories WHERE category = '$category2' AND parentId = $category1";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $errorMessage = "Category already exists!";
            header("location: category.php?errorMessage=" . urlencode($errorMessage));
        } else {
            // Insert the category into the database
            $sql = "INSERT INTO categories (category, parentId) VALUES ('$category2', '$category1')";
            $result = mysqli_query($conn, $sql);

            if (!$result) {
                die("Invalid query : " . mysqli_error($conn));
            }

            $successMessage = "Successfully Added!";
            header("location: category.php?successMessage=" . urlencode($successMessage));
            exit;
        }
    }

    if (isset ($_POST["editcategory"])) {
        $category_id = $_POST["edit_category_id"];
        $category = $_POST["edit_category"];

        $check_sql = "SELECT * FROM categories WHERE category = '$category'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            // $errorMessage = "Category already exists!";
            $errorMessage = mysqli_num_rows($check_result);
            header("location: category.php?errorMessage=" . urlencode($errorMessage));
        } else {
            // Insert the category into the database
            $sql = "UPDATE categories SET category = '$category' WHERE id = '$category_id'";
            $result = mysqli_query($conn, $sql);
            // echo "SQL Query: " . $sql . "<br>";
            if (!$result) {
                $errorMessage = "Error updating category: " . mysqli_error($conn);
                header("location: category.php?errorMessage=" . urlencode($errorMessage));
                exit;
            }

            // $successMessage = "SQL Query: " . $sql . "<br>";
            $successMessage = "Successfully Updated!";
            header("location: category.php?successMessage=" . urlencode($successMessage));
            exit;
        }
    }
} else {
    echo "ELse";
}

?>