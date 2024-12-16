<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="../styles/contacts.css">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/homepage.css">
</head>


<body>
    <div class="container">
        <h1>Contact Us</h1>
        <form action="#" method="post">
            <p>Name: </p>
            <input type="text" id="name" name="name" placeholder="Enter Name" required>

            <p>Email: </p>
            <input type="email" id="email" name="email" placeholder="Enter Email" required>

            <p>Message: </p>
            <textarea type="message" name="message" rows="4" placeholder="Enter your Message" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>