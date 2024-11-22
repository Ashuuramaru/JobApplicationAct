<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'core/dbConfig.php';
require_once 'core/models.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Job Application System</h1>
    
    <?php if (isset($_SESSION['message'])) { ?>
        <h2 style="color: green;"><?php echo $_SESSION['message']; ?></h2>
        <?php unset($_SESSION['message']); ?>
    <?php } ?>

    <form action="index.php" method="GET">
        <input type="text" name="searchInput" placeholder="Search Applicants">
        <button type="submit" name="searchBtn">Search</button>
    </form>
    <p><a href="insert.php">Add New Applicant</a></p>

    <table border="1">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Experience</th>
            <th>Specialization</th>
            <th>Degree</th>
            <th>Contact</th>
            <th>Action</th>
        </tr>
        <?php 
        if (isset($_GET['searchBtn'])) {
            $applicants = searchForAUser($pdo, $_GET['searchInput']);
        } else {
            $applicants = getAllApplicants($pdo);
        }

        foreach ($applicants as $applicant) { ?>
            <tr>
                <td><?php echo htmlspecialchars($applicant['first_name']); ?></td>
                <td><?php echo htmlspecialchars($applicant['last_name']); ?></td>
                <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                <td><?php echo htmlspecialchars($applicant['experience']); ?></td>
                <td><?php echo htmlspecialchars($applicant['specialization']); ?></td>
                <td><?php echo htmlspecialchars($applicant['degree']); ?></td>
                <td><?php echo htmlspecialchars($applicant['contact']); ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $applicant['id']; ?>">Edit</a> |
                    <a href="delete.php?id=<?php echo $applicant['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
        <div class="greeting">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <a href="core/handleForms.php?logoutUserBtn=1">Logout</a>
        </div>
    </table>
</body>
</html>
