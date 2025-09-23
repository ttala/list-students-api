<html>
<head>
    <title>Students List App</title>
</head>
<body>
    <h1>Students App</h1>

    <!-- Form: Add Student -->
    <h2>Add a Student</h2>
    <form action="" method="POST">
        <input type="hidden" name="action" value="add">
        <label>Student Name:</label>
        <input type="text" name="student_name" required />
        <label>Age:</label>
        <input type="number" name="student_age" required />
        <button type="submit">Add Student</button>
    </form>

    <hr>

    <!-- Form: List Students -->
    <h2>List Students</h2>
    <form action="" method="POST">
        <input type="hidden" name="action" value="list">
        <button type="submit">Show Students</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) {

        $username = getenv('USERNAME') ?: 'fake_username';
        $password = getenv('PASSWORD') ?: 'fake_password';
        $context = stream_context_create([
            "http" => [
                "header" => "Authorization: Basic " . base64_encode("$username:$password"),
            ]
        ]);

        $action = $_POST['action'];

        if ($action == "list") {
            // Fetch list of students
            $url = 'http://student-api:5000/api/v1.0/get_student_ages';
            $list = json_decode(file_get_contents($url, false, $context), true);

            echo "<p style='color:red; font-size: 20px;'>List of students with age:</p>";
            foreach ($list["student_ages"] as $key => $value) {
                echo "- $key is $value years old ";
                // Delete form for each student
                echo "<form action='' method='POST' style='display:inline; margin-left:30px;'>
                        <input type='hidden' name='action' value='delete'>
                        <input type='hidden' name='student_name' value='$key'>
                        <button type='submit'>Delete</button>
                      </form><br>";
            }
        }

        if ($action == "add") {
            $name = $_POST['student_name'];
            $age = $_POST['student_age'];

            $url = 'http://student-api:5000/api/v1.0/add_student';
            $data = ["student_name" => $name, "student_age" => (int)$age];
            $options = [
                "http" => [
                    "header"  => "Authorization: Basic " . base64_encode("$username:$password") . "\r\n" .
                                 "Content-Type: application/json\r\n",
                    "method"  => "POST",
                    "content" => json_encode($data),
                ]
            ];
            $contextAdd = stream_context_create($options);
            $result = file_get_contents($url, false, $contextAdd);

            echo "<p style='color:green;'>Student $name added successfully!</p>";
        }

        if ($action == "delete") {
            $name = $_POST['student_name'];

            $url = "http://student-api:5000/api/v1.0/del_student/$name";
            $options = [
                "http" => [
                    "header" => "Authorization: Basic " . base64_encode("$username:$password"),
                    "method" => "DELETE",
                ]
            ];
            $contextDelete = stream_context_create($options);
            $result = file_get_contents($url, false, $contextDelete);

            echo "<p style='color:orange;'>Student $name deleted successfully!</p>";
        }
    }
    ?>
</body>
</html>
