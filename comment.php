<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_comments";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding a new place
if (isset($_POST['add_place'])) {
    $new_place = trim($_POST['new_place']);
    
    if (!empty($new_place)) {
        // Prevent duplicate place entries
        $stmt = $conn->prepare("INSERT INTO places (name) VALUES (?) ON DUPLICATE KEY UPDATE name=name");
        $stmt->bind_param("s", $new_place);
        if ($stmt->execute()) {
            echo "<script>alert('New place added successfully!');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please enter a place name.');</script>";
    }
}

// Fetch places
$places = $conn->query("SELECT * FROM places");

// Get selected place
$selected_place = isset($_POST['place_id']) ? $_POST['place_id'] : '';

// Handle new comment submission
if (isset($_POST["submit"])) {
    $place_id = $_POST["place_id"];
    $user = htmlspecialchars($_POST["username"]);
    $comment = htmlspecialchars($_POST["comment"]);

    if (!empty($user) && !empty($comment) && !empty($place_id)) {
        $stmt = $conn->prepare("INSERT INTO comments (place_id, username, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $place_id, $user, $comment);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "<script>alert('Please fill all fields!');</script>";
    }
}

// Fetch comments for the selected place
$comments = [];
if (!empty($selected_place)) {
    $stmt = $conn->prepare("SELECT * FROM comments WHERE place_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $selected_place);
    $stmt->execute();
    $comments = $stmt->get_result();
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment on a Place</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        textarea, input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        button {
            background: #28a745;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
            cursor: pointer;
        }
        .comment-box {
            text-align: left;
            background: #f1f1f1;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Comment on a Place</h2>
        
        <h3>Add a New Place</h3>
        <form method="POST">
            <input type="text" name="new_place" placeholder="Enter new place name" required>
<?php
// Database connection
$servername = "localhost";
$username = "root";  // Your database username
$password = "";      // Your database password
$dbname = "travel_comments";  // Name of your database

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST["category"];  // Get selected category from the dropdown
    $comment = $_POST["comment"];  // Get comment text

    // Insert the category and comment into the database
    if (!empty($category) && !empty($comment)) {
        $sql = "INSERT INTO comments (category, comment_text) VALUES ('$category', '$comment')";

        if ($conn->query($sql) === TRUE) {
            echo "Comment added successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Both fields are required!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Comment Form</title>
</head>
<body>
    <h1>Submit Your Travel Comment</h1>
    <form method="POST" action="">
        <label for="category">Select a Category:</label>
        <select name="category" id="category" required>
            <option value="Beaches">Beaches</option>
            <option value="Mountains">Mountains</option>
            <option value="History">History</option>
            <option value="Adventure">Adventure</option>
            <option value="Culture">Culture</option>
        </select><br><br>

        <label for="comment">Your Comment:</label><br>
        <textarea name="comment" id="comment" rows="4" cols="50" required></textarea><br><br>
            <button type="submit" name="add_place">Add Place</button>
        </form>

        <h3>Select a Place</h3>
        <form method="POST">
            <select name="place_id" required onchange="this.form.submit()">
                <option value="">-- Choose a place --</option>
                <?php while ($row = $places->fetch_assoc()): ?>
                    <option value="<?= $row['id']; ?>" <?= $selected_place == $row['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($row['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($selected_place): ?>
            <h3>Leave a Comment</h3>
            <form method="POST">
                <input type="hidden" name="place_id" value="<?= $selected_place; ?>">
                <input type="text" name="username" placeholder="enter username" required>
                <textarea name="comment" placeholder="Write your comment..." required></textarea>
                <button type="submit" name="submit">Post Comment</button>
            </form>

            <h3>Comments</h3>
            <?php while ($row = $comments->fetch_assoc()): ?>
                <div class="comment-box">
                    <strong><?= htmlspecialchars($row["username"]); ?></strong>
                    <p><?= nl2br(htmlspecialchars($row["comment"])); ?></p>
                    <small><?= $row["created_at"]; ?></small>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</body>
</html>
