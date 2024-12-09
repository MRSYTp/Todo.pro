<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Register/Login</title>
  <link rel="stylesheet" href="<?= site_url('assets/css/auth.css') ?>">
  <style>
  /* استایل پیام موفقیت */
  .success-message {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    background-color: #d4edda; /* رنگ پس‌زمینه موفقیت */
    color: #155724; /* رنگ متن */
    border: 1px solid #c3e6cb; /* رنگ حاشیه */
    border-radius: 5px; /* گوشه‌های گرد */
    padding: 15px 20px;
    font-family: Arial, sans-serif;
    font-size: 16px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 80%;
    max-width: 400px;
    text-align: center;
  }
  /* استایل پیام خطا */
  .error-message {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    background-color: #f8d7da; /* رنگ پس‌زمینه خطا */
    color: #721c24; /* رنگ متن */
    border: 1px solid #f5c6cb; /* رنگ حاشیه */
    border-radius: 5px; /* گوشه‌های گرد */
    padding: 15px 20px;
    font-family: Arial, sans-serif;
    font-size: 16px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 80%;
    max-width: 400px;
    text-align: center;
  }
</style>

</head>
<body>
<!-- partial:index.partial.html -->
<div id="background">
	<div id="panel-box">
		<div class="panel">
			<div class="auth-form on" id="login">
				<div id="form-title">Log In</div>
				<form action="<?= site_url('auth.php?action=login') ?>" method="POST">
					<input name="email" type="email" required="required" placeholder="Email"/>
					<input name="password" type="password" required="required" placeholder="Password"/>
					<button type="Submit">Log In</button>
				</form>
			</div>
			<div class="auth-form" id="signup" >
				<div id="form-title">Register</div>
				<form action="<?= site_url('auth.php?action=register') ?>" method="POST">
					<input name="name" type="text" required="required" placeholder="Username"/>
                    <input name="email" type="email" required="required" placeholder="Email"/>
					<input name="password" type="password" required="required" placeholder="Password"/>
					<button type="Submit">Sign Up</button>
				</form>
			</div>
		</div>
		<div class="panel">
			<div id="switch">Sign Up</div>
			<div id="image-overlay"></div>
			<div id="image-side"></div>
		</div>
	</div>
</div>
<!-- partial -->
  <script src='https://code.jquery.com/jquery-3.3.1.min.js'></script>
  <script>
    $('#switch').click(function(){
      $(this).text(function(i, text){
        return text === "Sign Up" ? "Log In" : "Sign Up";
      });
      $('#login').toggleClass("on");
      $('#signup').toggleClass("on");
      $(this).toggleClass("two");
      $('#background').toggleClass("two");
      $('#image-overlay').toggleClass("two");
  })
</script>

</body>
</html>