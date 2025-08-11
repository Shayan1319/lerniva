<?php
require '../sass/db_config.php';
$action = $_REQUEST['action'];

if ($action == 'insert') {
  $stmt = $conn->prepare("INSERT INTO school_tasks (school_id, task_title, task_description, assigned_to_type, assigned_to_id, due_date) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("isssis", $_POST['school_id'], $_POST['task_title'], $_POST['task_description'], $_POST['assigned_to_type'], $_POST['assigned_to_id'], $_POST['due_date']);
  $stmt->execute();
  echo json_encode(["status" => "success", "message" => "Task Created"]);
}

if ($action == 'update') {
  $stmt = $conn->prepare("UPDATE school_tasks SET task_title=?, task_description=?, assigned_to_type=?, assigned_to_id=?, due_date=? WHERE id=?");
  $stmt->bind_param("sssisi", $_POST['task_title'], $_POST['task_description'], $_POST['assigned_to_type'], $_POST['assigned_to_id'], $_POST['due_date'], $_POST['id']);
  $stmt->execute();
  echo json_encode(["status" => "success", "message" => "Task Updated"]);
}

if ($action == 'delete') {
  $stmt = $conn->prepare("DELETE FROM school_tasks WHERE id=?");
  $stmt->bind_param("i", $_POST['id']);
  $stmt->execute();
  echo json_encode(["status" => "success", "message" => "Task Deleted"]);
}

if ($action == 'get') {
  $stmt = $conn->prepare("SELECT * FROM school_tasks WHERE id=?");
  $stmt->bind_param("i", $_POST['id']);
  $stmt->execute();
  $res = $stmt->get_result()->fetch_assoc();
  echo json_encode($res);
}

if ($action == 'list') {
  $res = $conn->query("SELECT * FROM school_tasks ORDER BY created_at DESC");
  echo "<table class='table table-bordered'>
          <tr class='table-dark'><th>Title</th><th>Type</th><th>Person ID</th><th>Due</th><th>Status</th><th>Actions</th></tr>";
  while ($row = $res->fetch_assoc()) {
    echo "<tr>
      <td>{$row['task_title']}</td>
      <td>{$row['assigned_to_type']}</td>
      <td>{$row['assigned_to_id']}</td>
      <td>{$row['due_date']}</td>
      <td>{$row['status']}</td>
      <td>
        <button class='btn btn-warning btn-sm edit-task' data-id='{$row['id']}'>Edit</button>
        <button class='btn btn-danger btn-sm delete-task' data-id='{$row['id']}'>Delete</button>
      </td>
    </tr>";
  }
  echo "</table>";
}