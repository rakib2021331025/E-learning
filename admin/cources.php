<?php 
include('./admininclude/header.php');
include('../dbconnection.php');
?>

<div class="mx-5 mt-5 text-center">
  <p class="bg-dark text-white p-2">List of Courses</p>

  <?php 
    //  Delete only selected row
    if (isset($_POST['delete']) && isset($_POST['id'])) {
        $del_id = intval($_POST['id']); // sanitize input
        $sql = "DELETE FROM course WHERE course_id = $del_id";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-success">Course Deleted Successfully</div>';
        } else {
            echo '<div class="alert alert-danger">Failed to Delete Course</div>';
        }
    }

    //  Fetch course list
    $sql = "SELECT * FROM course";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
  ?>
    <table class="table" style="margin-left:90px;width:1450px;">
      <thead>
        <tr>
          <th scope="col">Course Id</th>
          <th scope="col">Name</th>
          <th scope="col">Author</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?php echo $row['course_id']; ?></td>
            <td><?php echo $row['course_name']; ?></td>
            <td><?php echo $row['course_author']; ?></td>
            <td>
              <!--  Delete Button -->
              <form method="POST" class="d-inline">
                <input type="hidden" name="id" value="<?php echo $row['course_id']; ?>">
                <button type="submit" class="btn btn-danger btn-sm" name="delete" onclick="return confirm('Are you sure?');">
                  <i class="far fa-trash-alt"></i>
                </button>
              </form>

              <!-- Edit Button -->
              <form method="POST" class="d-inline" action="editcourse.php">
                <input type="hidden" name="id" value="<?php echo $row['course_id']; ?>">
                <button class="btn btn-primary btn-sm" name="edit" title="Edit">
                  <i class="fas fa-pen"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php 
    } else {
      echo "0 Results";
    }
  ?>
</div>

<!--  Add Course Button -->
<a href="addcourse.php" class="btn btn-primary rounded-circle"
   style="position: fixed; bottom: 10px; right: 10px; width: 50px; height: 50px;
          display: flex; justify-content: center; align-items: center; font-size: 24px; z-index: 1000;"
   title="Add New Course">
   <i class="fas fa-plus"></i>
</a>
