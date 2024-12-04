<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
        <link rel="stylesheet" href="style.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 20px;
            }

            .container {
                max-width: 800px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            h1, h2 {
                color: #333;
                text-align: center;
            }

            form {
                margin-bottom: 20px;
            }

            form label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }

            form input[type="text"],
            form select {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }

            button {
                padding: 10px 20px;
                background-color: #28a745;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            button:hover {
                background-color: #218838;
            }

        </style>
    </head>
    <body>

        <div class="container">
            <h1>Admin Dashboard</h1>

            <!-- Add Category Section -->
            <section class="form-section">
                <h2>Add New Category</h2>
                <form action="add_category.php" method="POST">
                    <label for="category_name">Category Name:</label>
                    <input type="text" id="category_name" name="category_name" required>
                    <button type="submit">Add Category</button>
                </form>
            </section>

            <!-- Add Subcategory Section -->
            <section class="form-section">
                <h2>Add New Subcategory</h2>
                <form action="add_subcategory.php" method="POST">
                    <label for="subcategory_name">Subcategory Name:</label>
                    <input type="text" id="subcategory_name" name="subcategory_name" required>

                    <label for="category">Select Category:</label>
                    <select id="category" name="category_id" required>
                        <?php
                        // Fetch categories from the database to populate the dropdown
                        include 'db.php';
                        $categories = mysqli_query($conn, "SELECT * FROM Tbl_Category");
                        while ($row = mysqli_fetch_assoc($categories)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Name'] . "</option>";
                        }
                        ?>
                    </select>

                    <button type="submit">Add Subcategory</button>
                </form>
            </section>

        </div>

    </body>
</html>
