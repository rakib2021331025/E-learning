$('#admin-login').click(function () {
  let adminemail = $('#admin-email').val().trim();
  let adminpass = $('#admin-pass').val().trim();

  if (adminemail !== '' && adminpass !== '') {
    $.ajax({
      url: "admin/addadmin.php", // Make sure this path is correct
      method: 'POST',
      dataType: 'json',
      data: {
        adminemail: adminemail,
        adminpass: adminpass
      },
      success: function (data) {
        console.log(data);
        if (data == 1) {
          alert("Login Successful");
          window.location.href = "admin/admindashboard.php";
        } else {
          alert("Invalid Email or Password");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", status, error);
      }
    });
  } else {
    alert("Please fill all fields.");
  }
});
