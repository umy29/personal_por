<?php

session_start();


if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['submit'])) {
     
        $username = $_POST['username'];
        $password = $_POST['password'];

        $conn = new mysqli("localhost", "root", "", "portfolios");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check username and password
        $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();
        $conn->close();

        // Verify password
        if ($hashed_password && md5($password) == $hashed_password) {
            $_SESSION['admin_logged_in'] = true;
            echo "<script>alert('Login Successfully'); location.reload();</script>";
        } else {
            echo "<script>alert('Invalid username or password');</script>";
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login page</title>
    </head>
    <body>
        <form method="POST">
            <h1>Login</h1>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="submit">Login</button>
        </form>
    </body>
    </html>

    <?php
    exit();
}
?>

<?php

require_once 'projects.php';
require_once 'contacts.php';

// Add Project
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $image = $_POST['image'];
    $link = $_POST['link'];

    $project = new Projects();
    $result = $project->insert($title, $desc, $image, $link);

    if ($result == "Success") {
        echo "<script>alert('Inserted Successfully');</script>";
    } else {
        echo "<script>alert('Error: $result');</script>";
    }
}

// Update Project
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $image = $_POST['image'];
    $link = $_POST['link'];

    $project = new Projects();
    $result = $project->update($id, $title, $desc, $image, $link);

    if ($result == "Success") {
        echo "<script>alert('Updated Successfully');</script>";
    } else {
        echo "<script>alert('Error: $result');</script>";
    }
}

// Delete Project
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    $project = new Projects();
    $result = $project->delete($id);

    if ($result == "Success") {
        echo "<script>alert('Deleted Successfully');</script>";
    } else {
        echo "<script>alert('Error: $result');</script>";
    }
}

// Get Contacts
$contact = new Contacts();
$contacts = $contact->getContacts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <header>
        <div class="header">
            <h1>Admin Dashboard</h1>
            <form method="post">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </header>
    <main>
        <div class="project-wrapper">
        


<form method="post">
                <h2>Add Project</h2>
                <input type="text" name="title" placeholder="Project Title" required>
                <textarea name="description" placeholder="Project Description" required></textarea>
                <input type="text" name="image" placeholder="Image URL">
                <input type="text" name="link" placeholder="Project Link">
                <button type="submit" name="add">Add</button>
            </form>

           >
            <form method="post">
                <h2>Update Project</h2>
                <input type="text" name="id" placeholder="Project ID" required>
                <input type="text" name="title" placeholder="Project Title" required>
                <textarea name="description" placeholder="Project Description" required></textarea>
                <input type="text" name="image" placeholder="Image URL">
                <input type="text" name="link" placeholder="Project Link">
                <button type="submit" name="update">Update</button>
            </form>

           
            <form method="post">
                <h2>Delete Project</h2>
                <input type="text" name="id" placeholder="Project ID" required>
                <button type="submit" name="delete">Delete</button>
            </form>
        </div>

        <div class="contacts-wrapper">
            <h2>Contact Messages</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $row): ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['subject']; ?></td>
                            <td><?php echo $row['message']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php
    
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: admin.php");
        exit();
    }
    ?>
</body>
</html>