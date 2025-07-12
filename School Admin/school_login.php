<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>School Admin Login</title>
  </head>

  <body>
    <h2>School Admin Login</h2>
    <form id="loginForm" method="POST">
      <label for="school_email">Email:</label>
      <input
        type="email"
        id="school_email"
        name="school_email"
        required
      /><br /><br />

      <label for="password">Password:</label>
      <input
        type="password"
        id="password"
        name="password"
        required
      /><br /><br />

      <button type="submit">Login</button>
    </form>
    <script>
      document
        .getElementById("loginForm")
        .addEventListener("submit", function (e) {
          e.preventDefault(); // prevent default form submission

          fetch("ajax/school_login.php", {
            method: "POST",
            body: new FormData(this),
          })
            .then((res) => res.json())
            .then((data) => {
              if (data.status === "success") {
                window.location.href = "dashboard.php"; // redirect on success
              } else {
                alert(data.message); // show error
              }
            })
            .catch((error) => {
              console.error("Login error:", error);
              alert("Something went wrong. Please try again.");
            });
        });
    </script>
  </body>
</html>
