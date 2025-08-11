<?php
session_start();
require_once '../sass/db_config.php';

$admin_id = $_SESSION['admin_id'];

$sql = "SELECT fl.*, f.full_name 
        FROM faculty_leaves fl 
        JOIN faculty f ON fl.faculty_id = f.id 
        WHERE fl.school_id = ? AND fl.status = 'Pending'
        ORDER BY fl.start_date";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<table class='table table-bordered table-striped'>
<thead>
  <tr class='table-dark'>
    <th>Teacher</th>
    <th>Leave Type</th>
    <th>From</th>
    <th>To</th>
    <th>Reason</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
</thead><tbody>";

while ($row = $result->fetch_assoc()) {
  echo "<tr>
    <td>{$row['full_name']}</td>
    <td>{$row['leave_type']}</td>
    <td>{$row['start_date']}</td>
    <td>{$row['end_date']}</td>
    <td>{$row['reason']}</td>
    <td><span class='badge bg-warning text-dark'>{$row['status']}</span></td>
    <td>
      <button class='btn btn-success btn-sm action-btn' data-id='{$row['id']}' data-status='Approved'>Approve</button>
      <button class='btn btn-danger btn-sm action-btn' data-id='{$row['id']}' data-status='Rejected'>Reject</button>
    </td>
  </tr>";
}
echo "</tbody></table>";
?>