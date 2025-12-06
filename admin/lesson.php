<?php
session_start();

include('./admininclude/header.php');
include('../dbconnection.php');


// Delete lesson handling
if (isset($_POST['delete'])) {
    $lesson_id = intval($_POST['lesson_id']);
    $sql_delete = "DELETE FROM lesson WHERE lesson_id = $lesson_id";
    if ($conn->query($sql_delete) === TRUE) {
        echo "<script>alert('Lesson deleted successfully'); window.location.reload();</script>";
    } else {
        echo "<div class='alert alert-danger'>Delete failed: " . $conn->error . "</div>";
    }
}
?>

<div class="container-fluid" style="margin-left:260px; padding-top:70px;width:1400px;">
    <div class="row">
        <div class="col">
            <form action="" method="GET" class="mt-3 row g-3 align-items-center">
                <div class="col-auto">
                    <label for="checkid" class="col-form-label">Enter Course ID:</label>
                </div>
                <div class="col-auto">
                    <input type="number" class="form-control" id="checkid" name="checkid" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-danger">Search</button>
                </div>
            </form>

            <?php
            if (isset($_GET['checkid'])) {
                $cid = intval($_GET['checkid']);
                $sql = "SELECT * FROM course WHERE course_id = $cid";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $course = $result->fetch_assoc();
                    $_SESSION['course_id'] = $course['course_id'];
                    $_SESSION['course_name'] = $course['course_name'];
            ?>
                    <div class="mt-4">
                        <h3>
                            Course ID: <?php echo $course['course_id']; ?><br>
                            Course Name: <?php echo htmlspecialchars($course['course_name']); ?>
                        </h3>
                    </div>

                    <table class="table mt-3 table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Lesson ID</th>
                                <th>Lesson Name</th>
                                <th>Lesson Video</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_lessons = "SELECT * FROM lesson WHERE course_id = $cid";
                            $result_lessons = $conn->query($sql_lessons);

                            if ($result_lessons->num_rows > 0) {
                                while ($lesson = $result_lessons->fetch_assoc()) {
                                    $videoPath = "" . $lesson['lesson_link'];
                            ?>
                                    <tr>
                                        <td><?php echo $lesson['lesson_id']; ?></td>
                                        <td><?php echo htmlspecialchars($lesson['lesson_name']); ?></td>
                                        <td>
                                            <a href="<?php echo htmlspecialchars($videoPath); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                                View Video
                                            </a>
                                        </td>
                                        <td>
                                            <!-- Delete Button -->
                                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this lesson?');">
                                                <input type="hidden" name="lesson_id" value="<?php echo $lesson['lesson_id']; ?>">
                                                <button type="submit" name="delete" class="btn btn-danger btn-sm">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>

                                            <!-- Edit Button -->
                                            <form method="POST" class="d-inline" action="editlesson.php?lesson_id=<?php echo $lesson['lesson_id']; ?>">
                                                <input type="hidden" name="lesson_id" value="<?php echo $lesson['lesson_id']; ?>">
                                                <button type="submit" name="edit" class="btn btn-primary btn-sm" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-center">No lessons found for this course.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>

            <?php
                } else {
                    echo "<p class='text-danger mt-3'>Invalid Course ID: $cid</p>";
                }
            }
            ?>

            <!-- Add Lesson Button -->
            <a href="addlesson.php" class="btn btn-danger" style="position: fixed; bottom: 20px; right: 20px; z-index: 999;">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </div>
</div>
