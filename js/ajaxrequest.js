$(document).ready(function () {
 
    // Email Check
    $('#stuemail').on("keypress blur", function () {
        let stuemail = $('#stuemail').val();
        if (stuemail !== '') {
            $.ajax({
                url: 'student/addstudent.php',
                method: 'POST',
                dataType: 'json',
                data: { stuemail: stuemail },
                success: function (data) {
                    if (data > 0) {
                        $('#stuemailmsg').html('<small style="color:red;">Email already exists!</small>');
                        $('#signup').prop('disabled', true);
                    } else {
                        $('#stuemailmsg').html('');
                        $('#signup').prop('disabled', false);
                    }
                }
            });
        }
    });

    // Registration
    $('#signup').click(function () {
      console.log("signup click");
        let stuname = $('#stuname').val().trim();
        let stuemail = $('#stuemail').val().trim();
        let stupass = $('#stupass').val().trim();
        let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Validation
        if (stuname === "") {
            $('#stunamemsg').html(`<small style="color:red;text-align:center;">Please Enter your Name!</small>`);
            $('#stuname').focus();
            return false;
        } else {
            $('#stunamemsg').html('');
        }

        if (stuemail === "") {
            $('#stuemailmsg').html(`<small style="color:red;text-align:center;">Please Enter your email!</small>`);
            $('#stuemail').focus();
            return false;
        } else if (!regex.test(stuemail)) {
            $('#stuemailmsg').html(`<small style="color:red;text-align:center;">Please enter a valid email!</small>`);
            $('#stuemail').focus();
            return false;
        } else {
            $('#stuemailmsg').html('');
        }

        if (stupass === "") {
            $('#stupassmsg').html(`<small style="color:red;text-align:center;">Please Enter your password!</small>`);
            $('#stupass').focus();
            return false;
        } else {
            $('#stupassmsg').html('');
        }

        // AJAX registration
        $.ajax({
            url: 'student/addstudent.php',
            method: 'POST',
            dataType: 'json',
            data: {
                stuname: stuname,
                stuemail: stuemail,
                stupass: stupass
            },
            success: function (data) {
                console.log(data);
                if (data === "OK") {
                    $('#showmsg').html('<div class="alert alert-success">Registration Successful!</div>');
                    $('#stuname').val('');
                    $('#stuemail').val('');
                    $('#stupass').val('');
                    setTimeout(() => {
                        $('#showmsg').html('');
                    }, 2000);
                } else if (data === "EmailExists") {
                    $('#showmsg').html('<div class="alert alert-warning">Email already registered!</div>');
                } else {
                    $('#showmsg').html('<div class="alert alert-danger">Registration Failed!</div>');
                }
            },
            error: function (xhr, status, error) {
                $('#showmsg').html('<div class="alert alert-danger">Something went wrong. Try again!</div>');
                console.log("Status: " + status);
                console.log("Error: " + error);
                console.log("Response Text: " + xhr.responseText);
            }
        });
    });
});
//students Login

$('#stulog').click(function(){
  console.log("stulog click");
    let stulogemail=$('#stulogemail').val();
    let stulogpass=$('#stulogpass').val();
    $.ajax({
     url:"student/addstudent.php",
     method:'POST',
     dataType:'json',
     data:{
      stulogemail:stulogemail,
      stulogpass:stulogpass,
     },
     success:function(data){
        console.log(data);
        if(data===1){
                  //  $('#logshowmsg').html('<div class="spinner-border text-success alert alert-success">Registration Successful!</div>');
                    $('#stulogemail').val('');
                    $('#stulogpass').val('');
                    setInterval(() => {
                        window.location.href="index.php";

                    }, 1000);

                         


        }
        else
                    $('#logshowmsg').html('<div class="alert alert-danger">Invalid password or email id!</div>');
         $('#stulogemail').val('');
                    $('#stulogpass').val('');
     }

    });
});