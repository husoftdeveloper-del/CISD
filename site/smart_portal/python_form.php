<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
<!DOCTYPE html>
<html>

<head>
    <title>Python Course Admission Form</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #e8f0fe;
            padding: 40px;
        }

        .form-container {
            background: white;
            max-width: 700px;
            margin: auto;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        button {
            margin-top: 25px;
            width: 100%;
            padding: 14px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #27ae60;
        }
    </style>
</head>

<body>
<?php portal_chrome_bar(); ?>


    <div class="form-container">
        <h2>Python Course Admission Form</h2>
        <form action="save_form.php" method="post">
            <input type="hidden" name="course_name" value="Python Course">

            <label for="name">Student Name:</label>
            <input type="text" name="name" required>

            <label for="father_name">Father's Name:</label>
            <input type="text" name="father_name" required>

            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="">-- Select Gender --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" name="date_of_birth" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" required>

            <label for="qualification">Qualification:</label>
            <input type="text" name="qualification" required>

            <label for="address">Address:</label>
            <textarea name="address" rows="3" required></textarea>

            <button type="submit">Submit Admission</button>
        </form>
    </div>

</body>

</html>