<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: logout.php");
    exit;
}
$admin_id = $_SESSION['admin_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Notes Board</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="p-4">

    <div class="container">
        <h2>📝 Notes Board (Admin)</h2>

        <form id="noteForm" class="mb-4">
            <input type="hidden" name="note_id" id="note_id" value="">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" id="content" rows="4" class="form-control" required></textarea>
            </div>
            <input type="hidden" name="school_id" value="<?= $admin_id ?>">
            <input type="hidden" name="author_id" value="<?= $admin_id ?>">
            <input type="hidden" name="author_role" value="admin">
            <button type="submit" class="btn btn-primary">Save Note</button>
            <button type="reset" class="btn btn-secondary">Clear</button>
        </form>

        <h4>📄 All Notes</h4>
        <div id="notesList"></div>
    </div>

    <script>
    function loadNotes() {
        $.get("ajax/fetch_notes.php", function(data) {
            $("#notesList").html(data);
        });
    }

    $("#noteForm").on("submit", function(e) {
        e.preventDefault();
        $.post("ajax/save_note.php", $(this).serialize(), function(res) {
            alert(res.message);
            $("#noteForm")[0].reset();
            $("#note_id").val('');
            loadNotes();
        }, "json");
    });

    $(document).on("click", ".editNote", function() {
        const note = $(this).data();
        $("#note_id").val(note.id);
        $("#title").val(note.title);
        $("#content").val(note.content);
    });

    $(document).on("click", ".deleteNote", function() {
        if (confirm("Delete this note?")) {
            const id = $(this).data("id");
            $.post("ajax/delete_note.php", {
                id: id
            }, function(res) {
                alert(res.message);
                loadNotes();
            }, "json");
        }
    });

    $(document).ready(loadNotes);
    </script>

</body>

</html>