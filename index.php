<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<!--
   首页不一定非得是 index.html，如果没有 index.html 的话，index.php 可以是主页
   如果保存为 html 文件，会无法使用 php
-->

<head>
<style>
.error {color: #FF0000;}
</style>
</head>

<body> 

<?php
   // 定义变量并设置为空值
   $nameErr = $emailErr = $genderErr = $websiteErr = "";
   $name = $email = $gender = $comment = $website = "";
   
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["name"])) {
         $nameErr = "姓名是必填的";
      } else {
         $name = test_input($_POST["name"]);
         // 检查姓名是否包含字母和空白字符
         if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameErr = "只允许字母和空格";
         }
      }
      
      if (empty($_POST["email"])) {
         $emailErr = "电邮是必填的";
      } else {
         $email = test_input($_POST["email"]);
         // 检查电子邮件地址语法是否有效
         if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
            $emailErr = "无效的 email 格式"; 
         }
      }
        
      if (empty($_POST["website"])) {
         $website = "";
      } else {
         $website = test_input($_POST["website"]);
         // 检查 URL 地址语法是否有效（正则表达式也允许 URL 中的斜杠）
         if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website)) {
            $websiteErr = "无效的 URL"; 
         }
      }
   
      if (empty($_POST["comment"])) {
         $comment = "";
      } else {
         $comment = test_input($_POST["comment"]);
      }
   
      if (empty($_POST["gender"])) {
         $genderErr = "性别是必选的";
      } else {
         $gender = test_input($_POST["gender"]);
      }

      if (!empty($nameErr) || !empty($emailErr) || !empty($genderErr) || !empty($websiteErr)) {
         $name = $email = $gender = $comment = $website = "";
      }
   }
   
   function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
   }
?>

<h2>PHP 验证实例</h2>
<p><span class="error">* 必需的字段</span></p>

<!--
   $_SERVER["PHP_SELF"] 返回当前执行脚本的文件名
   因此，$_SERVER["PHP_SELF"] 将表单数据发送到页面本身，而不是跳转到另一张页面

   htmlspecialchars() 函数把特殊字符转换为 HTML 实体
   这意味着 < 和 > 之类的 HTML 字符会被替换为 &lt; 和 &gt; 
   这样可防止攻击者通过在表单中注入 HTML 或 JavaScript 对代码进行利用

   假设我们的一张名为 "test_form.php" 的页面中有如下表单：
       <form method="post" action="< ?php echo $_SERVER["PHP_SELF"];?>">
   现在，如果用户进入的是地址栏中正常的 URL：
       http://www.example.com/test_form.php
   上面的代码会转换为：
       <form method="post" action="test_form.php">
   到目前，一切正常。
   不过，如果用户在地址栏中键入了如下 URL：
       http://www.example.com/test_form.php/%22%3E%3Cscript%3Ealert('hacked')%3C/script%3E
   在这种情况下，上面的代码会转换为：
       <form method="post" action="test_form.php"/><script>alert('hacked')</script>
   这段代码加入了一段脚本和一个提示命令。并且当此页面加载后，就会执行 JavaScript 代码。
   这仅仅是一个关于 PHP_SELF 变量如何被利用的简单无害案例。
   您应该意识到 <script> 标签内能够添加任何 JavaScript 代码！
   黑客能够把用户重定向到另一台服务器，更改全局变量，将表单提交到其他地址以保存用户数据，等等。
-->

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
   姓名：<input type="text" name="name">
   <span class="error">* <?php echo $nameErr;?></span>
   <br><br>
   电邮：<input type="text" name="email">
   <span class="error">* <?php echo $emailErr;?></span>
   <br><br>
   网址：<input type="text" name="website">
   <span class="error"><?php echo $websiteErr;?></span>
   <br><br>
   评论：<textarea name="comment" rows="5" cols="40"></textarea>
   <br><br>
   性别：
   <input type="radio" name="gender" value="female">女性
   <input type="radio" name="gender" value="male">男性
   <span class="error">* <?php echo $genderErr;?></span>
   <br><br>
   <input type="submit" name="submit" value="提交"> 
</form>

<?php
   echo "<h2>您的输入：</h2>";
   echo $name . "<br>";
   echo $email . "<br>";
   echo $website . "<br>";
   echo $comment . "<br>";
   echo $gender;
?>

</body>
</html>