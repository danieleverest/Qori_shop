<?php
// Include your database connection code
include "../config/db.php";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset ($_POST['category_id'])) {
    $categoryId = mysqli_real_escape_string($conn, $_POST['category_id']);

    $getParentId_sql = "SELECT parentId FROM categories WHERE id = '$categoryId'";
    $getParentId_result = mysqli_query($conn, $getParentId_sql);

    if ($getParentId_result) {
        $parent_row = mysqli_fetch_assoc($getParentId_result);
        $parentId = $parent_row['parentId'];

        // Perform the deletion of the category
        $delete_sql = "DELETE FROM categories WHERE id = '$categoryId'";

        // Check if the parent ID is not 0, then delete materials with that parent ID
        if ($parentId != 0) {
            $delete_sql .= " OR parentId = '$categoryId'";
        }

        $delete_result = mysqli_query($conn, $delete_sql);

        if ($delete_result) {
            // Deletion successful
            echo "Deleted Successfully!";
        } else {
            // Error in deletion
            echo "Error deleting category: " . mysqli_error($conn);
        }
    } else {
        // Error in retrieving parent ID
        echo "Error retrieving parent ID: " . mysqli_error($conn);
    }

    // Perform the deletion of the category
    // $delete_sql = "DELETE FROM categories WHERE id = '$categoryId'";
    // $delete_result = mysqli_query($conn, $delete_sql);

    // if ($delete_result) {
    //     // Deletion successful
    //     echo "Deleted Successfully!";
    // } else {
    //     // Error in deletion
    //     echo "Error deleting category: " . mysqli_error($conn);
    // }
} else {
    // Invalid request
    echo "Invalid request.";
}

// Close the database connection
mysqli_close($conn);
?>