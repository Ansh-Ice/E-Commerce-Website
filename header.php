<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Navbar Example</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        header {
            background-color: #2c3e50;
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center; /* Aligns items vertically */
        }

        h1{
            color: white;
        }
        nav {
            display: flex;
            width: 100%;
            justify-content: space-between; /* Space between profile and navbar */
        }

        .profile {
            margin-right: auto; /* Pushes the profile to the left */
            display: flex;
            align-items: center; /* Centers icon and text vertically */
        }

        .profile a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s;
            display: flex; /* Flexbox for icon and text alignment */
            align-items: center; /* Centers icon and text vertically */
        }

        .profile a i {
            margin-right: 5px; /* Space between icon and text */
        }

        .profile img {
            width: 30px; /* Adjust the size as needed */
            height: 30px;
            border-radius: 50%; /* Makes the image round */
            margin-left: 5px; /* Space between text and image */
        }

        .profile a:hover {
            background-color: #ffffff;
            color: #2c3e50;
        }

        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            position: relative;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #2c3e50;
            background-color: white;
        }

        nav ul li a::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            height: 2px;
            width: 0;
            background-color: white;
            transition: width 0.3s ease, left 0.3s ease;
        }

        nav ul li a:hover::after {
            width: 100%;
            left: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>WOODLAND WONDERS</h1>
        <nav>
            <div class="profile">
                <a href="#profile"><i class="fas fa-user"></i> Profile</a>
            </div>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#cart">Cart</a></li>
                <li><a href="#wishlist">Wishlist</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>
