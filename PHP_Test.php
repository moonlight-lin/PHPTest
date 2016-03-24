<?php
  /*
   cookie 用于识别用户，是留在用户计算机中的小文件，相同的计算机通过浏览器请求页面时，它同时会发送 cookie
   cookie 是保存在客户端的
   cookie 不是及时生效的，只有再次刷新时才生效
   cookie 的限制
   1、必须在 HTML 文件的内容输出之前设置
   2、不同的浏览器的处理不一致，有时会出现错误的结果
   3、浏览器能创建的 cookie 最多30个且每个不超过 4KB

   Setcookie(string name, string value, int expire, string path, string domain, int secure);

   要删除一个已经存在的 cookie，有两个办法：
   1、SetCookie("user", "");
   2、setcookie("user", "", time()-3600); 
  */
  if($_POST["name"]) {
     setcookie("user", $_POST["name"], time()+3600);
  }

  /*
   session 变量用于存储有关用户会话的信息
   Session 工作机制：为每个访问者创建唯一 id，基于这个 ID 存储变量，ID 存在 cookie 中，或通过 URL 进行传导
   Session 信息是存放在 server 端
   session_start() 函数必须位于 <html> 标签之前
   可以通过 session_destroy() 函数彻底终结 session
  */
  session_start();
  if($_POST["name"]) {
     if(isset($_SESSION["user"]) && $_POST["name"] == $_COOKIE["user"]){
        $_SESSION["user"] = $_SESSION["user"] + 1;
     }
     else {
        $_SESSION["user"] = 1;
     } 
  }
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<center><h3>PHP TEST</h3></center>
<input type='text' value="PHP">
<br>

<?php
  if (isset($_COOKIE["user"])) {
    echo "<br>Cookie is user : " . $_COOKIE["user"];
    if (isset($_SESSION["user"]))
      echo " (" . $_SESSION["user"] . ")";
    echo "<br>";
  }
  else {
    echo "<br>no cookie<br>";
  }
?>

<?php
  function customErrorHandler($error_level, $error_message, $error_file, $error_line, $error_context){
    echo "<br><br><b><font color='red'>";
    echo "Error:<br>";
    echo "error_level: $error_level" . "<br>";
    echo "error_message: $error_message" . "<br>";
    echo "error_file: $error_file" . "<br>";
    echo "error_line: $error_line" . "<br>";
    echo "error_context: $error_context" . "<br>";
    
    if($error_level == E_USER_ERROR){
       // error_log - 发送错误信息到某个地方
       // 0	message 发送到 PHP 的系统日志 (由 php.ini 配置)
       // 1     message 发送到参数 destination 设置的邮件地址
       // 3     message 被发送到位置为 destination 的文件里
       error_log("$error_file(line:$error_line):$error_message\n", 3, "/var/www/html/php_error.log");

       // 当错误发生时在用的每个变量以及它们的值
       foreach($error_context as $key=>$value){
          echo $key . "=" . $value . "<br>";
       }

       // 终止脚本
       die("<br>customErrorHandler: critical error</font></b>");
    }
    else{
       echo "</font></b>";
       echo "<br>";
    }
  }

  // 错误类型：
  // 2	E_WARNING
  // 8	E_NOTICE
  // 256	E_USER_ERROR
  // 512	E_USER_WARNING
  // 1024	E_USER_NOTICE
  // 4096	E_RECOVERABLE_ERROR	
  // 8191	E_ALL

  // 默认错误处理程序是内建的错误处理程序
  // 可以修改错误处理程序
  set_error_handler("customErrorHandler");   // 捕获所有错误类型
  // set_error_handler("customErrorHandler", E_USER_ERROR); // 只捕获 E_USER_ERROR 错误

  // trigger error
  echo($test);

  $test=1;
  if ($test>1)
  {
    // trigger_error 可触发错误，默认类型是 E_USER_NOTICE
    //trigger_error("变量 test 大于 1");
    trigger_error("变量 test 大于 1", E_USER_ERROR);    
  }



  // try-throw-catch
  function checkNum($number)
  {
    if($number>1)
       throw new Exception("变量 test 大于 1");
  }

  try
  {
    checkNum(2);
  }
  catch(Exception $e)
  {
    echo '<b><font color="blue">Catch Exception:<br>' . $e->getMessage() . '</font></b><br>';
  }

  // 自定义的 Exception 类
  class customException extends Exception
  {
    private $error;

    function customException($error){
      $this->error = $error;
    }

    public function errorMessage() {
      $errorMsg = 'File ' . $this->getFile() . ' line ' . $this->getLine() . ' : ' . $this->error;
      return $errorMsg;
    }
  }

  // 设置顶层异常处理
  function customExceptionHandler($exception)
  {
    echo "<br><b><font color='red'>Exception:<br>" . $exception->errorMessage() . "</font></b><br>";
  }

  set_exception_handler('customExceptionHandler');

  //会终止程序
  //throw new customException("uncaught customException");

?>

<?php 

  // PHP 有两种单行注释
  #  PHP 中函数，类，关键字等对大小写不敏感
  // PHP 的所有变量都对大小写敏感
  
  /*
   echo 和 print 都用于输出
   可以用 echo，print 或 echo(), print()
  
   echo - 能够输出一个以上的字符串
   print - 只能输出一个字符串，并始终返回 1
   如 echo "Hello ", "World";
   而 print "Hello ", "World"; 则会出错
  
   符号 . 可用于链接字符串，如
   echo "Hello " . "World";
   print "Hello " . "World";
  
   echo 比 print 稍快，因为它不返回任何值。
  */

  /*
   $_REQUEST 获取 POST 方法和 GET 方法提交的数据，但是速度比较慢 
   $_GET     获取 GET 方法提交的数据
   $_POST    获取 POST 方法提交的数据
   
   GET 把参数加在提交表单的 action 所指的 URL 中，在 URL 可以看到，安全性不好，传送数据量小，不能大于 2KB 
   POST 将表单的字段放在 HTTP HEADER 传到 action 所指的 URL 中，用户看不到，安全性略高，且大小不受限制。

   JQuery 方法: $.get()，$.post()，$.ajax()，get 和 post 调用 ajax 函数, ajax 默认用 get。
   HTML 方法: <form action="PHP_Test.php" method="post"> 或 <form action="PHP_Test.php" method="get">
  */
  if($_GET["a1"]){
     echo  "<br> 通过 JQuery ajax 调的 PHP<br>";
     echo  "<br> Hello " . $_REQUEST["a1"] . " !";
     print "<br> Welcome to " . $_REQUEST["a2"] . " !";
     echo  "<br>", "<br>";
  }
  else if($_POST["p1"]){
     echo  "<br> 通过 JQuery post 调的 PHP<br>";
     echo  "<br> Hello " . $_POST["p1"] . " !"; 
     print "<br> Welcome to " . $_POST["p2"] . " !"; 
     echo  "<br>", "<br>";
  }
  else if($_POST["name"]){
     echo  "<br> 通过 method post 调的 PHP<br>";
     echo  "<br> Hello " . $_POST["name"] . " !";
     print "<br> Welcome to " . $_POST["email"] . " !";
     echo  "<br>", "<br>";
  }
  else if($_GET["name"]){
     echo  "<br> 通过 method get 调的 PHP<br>";
     echo  "<br> Hello " . $_GET["name"] . " !";
     print "<br> Welcome to " . $_GET["email"] . " !";
     echo  "<br>", "<br>";
  }
  else if($_FILES["file"]["name"]){
     // 貌似超过 2MB 的文件就传不上了，就进不了这个分支
     if ((($_FILES["file"]["type"] == "image/gif") ||
          ($_FILES["file"]["type"] == "image/jpeg") ||
          ($_FILES["file"]["type"] == "image/pjpeg") ||
          ($_FILES["file"]["type"] == "text/plain"))
        &&($_FILES["file"]["size"] < 1000000))
     {
        if ($_FILES["file"]["error"] > 0) {
           echo "<br>Return Code: " . $_FILES["file"]["error"] . "<br />";
        }
        else {
           echo "<br>";
           echo "Upload: " . $_FILES["file"]["name"] . "<br />";
           echo "Type: " . $_FILES["file"]["type"] . "<br />";
           echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
           echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

           if (file_exists("upload/" . $_FILES["file"]["name"])) {
              echo "<br>" . $_FILES["file"]["name"] . " already exists. ";
           }
           else {
              move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
              echo "<br>Stored in: " . "upload/" . $_FILES["file"]["name"];
           }
        }
     }
     else {
        echo "<br>" . $_FILES["file"]["type"];
        echo "<br>" . $_FILES["file"]["size"];
        echo "<br>Invalid file<br>";
     }
     return;
  }
  else{
     echo  "<br> 没传参数 <BR>";
     return;
  }

  // ============
  // 基本数据类型
  // ============
  $n = 123;
  $f = 3.14;
  $str = "Test";
  $b = false;
  $x=null;
  
  echo "整数: $n<br>";
  var_dump($n); echo "<br>";
  echo "浮点: $f<br>";
  var_dump($f); echo "<br>";
  echo "字符: $str<br>";
  var_dump($str); echo "<br>";
  $str .= " PHP";
  echo "执行 \$str .= \" PHP\" 后: $str <br>";
  var_dump($str); echo "<br>";
  echo "逻辑: $b<br>";
  var_dump($b); echo "<br>";
  echo "null: $x<br>";
  var_dump($x); echo "<br><br>";

  // ======
  //  常量
  // ======
  define("HelloWorld1", "这是一个大小写敏感的常量 Welcome to W3School.com.cn!");
  echo HelloWorld1 . "<br>";
  define("helloworld2", "这是一个大小写不敏感的常量 Welcome to W3School.com.cn!", true);
  echo HelloWorld2 . "<br><br>";

  // ======
  //  数组
  // ======
  $cars=array("Volvo","BMW","QQ");
  var_dump($cars);
  echo "<br>";
  echo "My car is a {$cars[0]} <br>";
  echo "My car is a $cars[1] <br>";
  foreach ($cars as $value) {
    echo "$value <br>";
  }
  $cars[2] = "Audi";
  foreach($cars as $x=>$x_value) {
    echo "Key=" . $x . ", Value=" . $x_value. "<br>";
  }
  for($i=0; $i<count($cars); $i++) {
    echo $i . " : " . $cars[$i] . "<br>";
  }
  echo "<br>";

  // ==========
  //  数组操作
  // ==========
  $index = array("a","b","c");
  $combineArray = array_combine($index, $cars);
  echo "after array_combine<BR>";
  var_dump($combineArray);
  $mergeArray = array_merge($index, $cars);
  echo "<BR>after array_merge<BR>";
  var_dump($mergeArray);
  array_push($index, "d", "e");
  echo "<BR>after array_push<BR>";
  var_dump($index);
  $index[5] = "f";
  echo "<BR>添加新项<BR>";
  var_dump($index);
 
  // ====================
  //  索引为字符串的数组 
  // ====================
  $company=array("name"=>"ericsson","city"=>"guangzhou","employees"=>1000);
  echo "<br><br>";
  var_dump($company);
  echo "<br>name is " . $company["name"], "<br>";
  foreach ($company as $value) {
    echo "$value <br>";
  }
  $company["employees"] = 2000;
  foreach($company as $x=>$x_value) {
    echo "Key=" . $x . ", Value=" . $x_value. "<br>";
  }
  $company["depart"] = "R&D";
  echo "添加新项<BR>";
  var_dump($company);    echo "<BR><BR>";

  // ==========
  //  数组排序
  // ==========
  $a = array(8,16,21,10,5);
  var_dump($a);    echo "<BR>";
  sort($a);
  var_dump($a);    echo "<BR>";
  rsort($a);
  var_dump($a);    echo "<BR>";
  $a = array("c"=>8, "a"=>16, "e"=>3, "b"=>27, "d"=>12);
  var_dump($a);    echo "<BR>";
  asort($a);
  var_dump($a);    echo "<BR>";
  ksort($a);
  var_dump($a);    echo "<BR>";
  sort($a);
  var_dump($a);

  // ==========
  //  多维数组
  // ==========
  echo "<BR><BR>";
  $company = array(
     array("qq", "shenzhen", 1000),
     array("ericsson", "guangzhou", 2000),
     array("google", "shanghai", 3000)
  );
  for ($i = 0; $i<count($company); $i++) {
     for ($j = 0; $j<count($company[$i]); $j++)
        echo $company[$i][$j] . " ";
     echo "<BR>";
  }
 
  // ========
  //  对象类
  // ========
  class Rect
  {
    /*
     class 中的变量需要指定 public protected 或 private
     var 定义的变量如果没有加 protected 或 private 则默认为 public
     php4 中一般是用 var
     php5 中一般是用 public
     新版本建议用 public 不要用 var
     函数也可以指定 protected 或 private，默认是 public
    */

    //var $x;
    public $x;
    private $y;

    function Rect($x=3, $y=4) {
      $this->x = $x;
      $this->y = $y;
    }

    function getX(){ return $this->x; }
    function getY(){ return $this->y; }
    function setX($x){ $this->x = $x; }
    function setY($y){ $this->y = $y; }

    function display(){
      echo "x=$this->x, y=$this->y<br>";
      echo "周长 = " . ($this->x + $this->y) * 2 . "<br>";
      echo "面积 = " . ($this->x * $this->y) . "<br>";
    }
  }

  $rect = new Rect();
  echo "<br>";
  var_dump($rect);   // 可以输出 public 和 private 变量
  echo "<br>";
  foreach(get_object_vars($rect) as $property => $value){
    echo "$property : $value<br>";   // 只能输出 public 变量
  }
  $rect->display();

  $rect2 = new Rect(10,20);
  var_dump($rect2);
  echo "<br>x is " . $rect2->x . "; y is " . $rect2->getY() . "<br>";
  $rect2->x = 7;   // 直接获取或设置 y 会出错，因为 y 是 private 变量
  $rect2->setY(8);
  $rect2->display();

  // ============
  //  变量作用域
  // ============
  $a = 5;
  function funcNoGlobal() {
    $b = 10;
    echo "<br>函数内部：";
    echo "<br>变量 a 是：$a";
    echo "<br>变量 b 是：$b";
  }
  echo "<br>没有使用 global";
  funcNoGlobal(); 
  echo "<br>函数外部：";
  echo "<br>变量 a 是：$a";
  echo "<br>变量 b 是：$b";

  function funcWithGlobal() {
    global $a;
    $b = 10;
    echo "<br>函数内部：";
    echo "<br>变量 a 是：$a";
    echo "<br>变量 b 是：$b";
    $a = $a + $b;
  }
  echo "<br><br>使用 global";
  funcWithGlobal();
  echo "<br>函数外部：";
  echo "<br>变量 a 是：$a";

  function funcWithStatic(){
    static $x=1;
    echo "<br>" . $x;
    $x++;
  }
  echo "<br><br>使用 static";
  funcWithStatic();
  funcWithStatic();
  funcWithStatic();
  
  // ============
  //  字符串函数
  // ============
  $str = "Hello PHP";
  echo "<br><br>str: $str <br>";
  echo "strlen(\$str): " . strlen($str) . "<br>";
  echo "strpos(\$str,\"PHP\"): " . strpos($str, "PHP") . "<br>";
  echo "var_dump(strpos(\$str,\"Python\")): ";
  echo var_dump(strpos($str, "Python")) . "<br>";
  echo "strcmp(\$str,\"Hello PHP\"): " . strcmp($str,"Hello PHP") . "<br>";
  echo "strcmp(\$str,\"Hello Python\"): " . strcmp($str,"Hello Python") . "<br>";
  echo "str_replace(\"PH\", \"LAM\", \$str): " . str_replace("PH", "LAM", $str) . "<br>";
  echo "str: $str <br><br>";

  // ==========
  //  比较运算
  // ==========
  $a = "100";
  $b = 100;
  var_dump($a);
  echo "<br>";
  var_dump($b);
  echo "<br>var_dump(\$a == \$b) : ";
  var_dump($a == $b);
  echo "<br>var_dump(\$a === \$b) : ";
  var_dump($a === $b);
  echo "<br>var_dump(\$a != \$b) : ";
  var_dump($a != $b);
  echo "<br>var_dump(\$a !== \$b) : ";
  var_dump($a !== $b);

  $a = array("a" => "red", "b" => "green"); 
  $b = array("c" => "blue", "d" => "yellow"); 
  $c = array("b" => "green", "a" => "red");
  $d = $a + $b;   // $x 与 $y 的联合, 不会覆盖重复的键
  echo "<br>";
  echo "<br>a: "; var_dump($a);
  echo "<br>b: "; var_dump($b);
  echo "<br>c: "; var_dump($c);
  echo "<br>d=a+b: "; var_dump($d);
  echo "<br>var_dump(\$a == \$c) : ";
  var_dump($a == $c);
  echo "<br>var_dump(\$a === \$c) : ";
  var_dump($a === $c);
  echo "<br>var_dump(\$a != \$c) : ";
  var_dump($a != $c);
  echo "<br>var_dump(\$a !== \$c) : ";
  var_dump($a !== $c);
  echo "<br>var_dump(\$a <> \$c) : ";
  var_dump($a <> $c);
 
  // ==========
  //  获取日期
  // ==========
  echo "<br><br>";
  var_dump(getdate());
  $hour = date("H");   // "h" 则返回 12 小时制
  $min = date("i");
  $sec = date("s");
  echo "<br>hour is $hour, min is $min, sec is $sec<br>";

  // ==========
  //  控制语句
  // ==========
  if ($sec >= 15 && $sec <= 30) {
    echo "sec >= 15 && sec <= 30<br>";
  }
  elseif ($sec >= 45 || $sec <= 10) {
    echo "sec >= 45 || sec <= 10<br>";
  }
  else {
    echo "other sec<br>";
  }

  switch($sec % 2){
    case 0:
         echo "sec is even<br>";
         break;
    case 1:
         echo "sec is odd<br>";
         break;
    default:
         echo "error<br>";
  }

  $x=1; 
  while($x <= 5) {
    echo "$x ";
    $x++;
  } 
  echo "<BR>";
  $x=1;
  do {
    echo "$x ";
    $x++;
  } while ($x <= 5);
  echo "<BR>";

  $a = array("a","b","c","d","e");
  for ($x=0; $x<5; $x++) {
    echo $a[$x] . " ";
  } 
  echo "<BR>";
  foreach ($a as $x) {
    echo "$x ";
  }

  // ==============
  //  GLOBALS 变量
  // ==============
  function funcWithGLOBALSName(){
    echo "<BR><BR>\$GLOBALS[\'x\'] : " . $GLOBALS['x'];
  }
  funcWithGLOBALSName();

  // ==============
  //  _SERVER 变量
  // ==============
  echo "<br>\$_SERVER[\'PHP_SELF\'] : " . $_SERVER['PHP_SELF'];
  echo "<br>\$_SERVER[\'SERVER_NAME\'] : " . $_SERVER['SERVER_NAME'];
  echo "<br>\$_SERVER[\'HTTP_HOST\'] : " . $_SERVER['HTTP_HOST'];
  echo "<br>\$_SERVER[\'HTTP_REFERER\'] : " . $_SERVER['HTTP_REFERER'];
  echo "<br>\$_SERVER[\'HTTP_USER_AGENT\'] : " . $_SERVER['HTTP_USER_AGENT'];
  echo "<br>\$_SERVER[\'SCRIPT_NAME\'] : " . $_SERVER['SCRIPT_NAME'];
  echo "<br>\$_SERVER[\'REQUEST_METHOD\'] : " . $_SERVER["REQUEST_METHOD"];

  /*
   r   打开文件为只读。文件指针在文件的开头开始。
   w   打开文件为只写。删除文件的内容或创建一个新的文件，如果它不存在。文件指针在文件的开头开始。
   a   打开文件为只写。文件中的现有数据会被保留。文件指针在文件结尾开始。创建新的文件，如果文件不存在。
   x   创建新文件为只写。返回 FALSE 和错误，如果文件已存在。
   r+  打开文件为读/写、文件指针在文件开头开始。
   w+  打开文件为读/写。删除文件内容或创建新文件，如果它不存在。文件指针在文件开头开始。
   a+  打开文件为读/写。文件中已有的数据会被保留。文件指针在文件结尾开始。创建新文件，如果它不存在。
   x+  创建新文件为读/写。返回 FALSE 和错误，如果文件已存在。
  */
  echo "<BR><BR>";
  $filename = "PHP_FILE_TEST.txt";
  if(file_exists($filename) && filesize($filename) != 0){
     echo "文件 " . $filename . " 存在<BR>";
     echo "以只读方式打开文件<br>";
     $myfile = fopen($filename, "r");

     $size = filesize($filename);
     echo "文件大小是: " . $size . "<br>";

     echo "读取所有内容:<br>";
     $text = fread($myfile, $size);
     echo $text . "<br>";

     echo "行读取:<br>";
     fseek($myfile, 0, SEEK_SET);
     while(!feof($myfile)){
       echo fgets($myfile) . "<br>";
     }

     echo "字符读取:<br>";
     fseek($myfile, 0, SEEK_SET);
     while(!feof($myfile)){
       echo fgetc($myfile);
     }

     fclose($myfile);
  }
  else{
     // 需要注意有没有写权限
     echo "文件 " . $filename . " 不存在或者是空文件<br>";
     echo "以只写方式打开文件<br>";
     $myfile = fopen($filename, "w") or die("$myfile Unable to open file!");
     
     echo "写入内容<br>";
     $text = "Happy Monkey Year\n";
     fwrite($myfile, $text);
     $text = "Welcome to PHP world\n";
     fwrite($myfile, $text);
     $text = "PHP = PHP Hypertext Preprocessor\n";
     fwrite($myfile, $text);
     $text = "Good Good Study, Day Day Up";
     fwrite($myfile, $text);
     
     fclose($myfile);
  }

  // ==========
  //  过滤变量
  // ==========
  $int = 12.3;
  if(!filter_var($int, FILTER_VALIDATE_INT))
     echo("<br><br>$int 不是整数<br><br>");
  else
     echo("<br><br>$int 是整数<br><br>");

  /*
  if(!filter_has_var(INPUT_GET, "email")) {
     echo("Input type does not exist");
  }
  else {
     if (!filter_input(INPUT_GET, "email", FILTER_VALIDATE_EMAIL))
        echo "E-Mail is not valid";
     else
        echo "E-Mail is valid";
  }
  */


  // ===========================
  // PHP 和 MySQL 之间需要配置，不同操作系统配置方式不一样
  // Ubuntu 下
  //   1. 安装 apache2
  //      sudo apt-get install apache2
  //      sudo /etc/init.d/apache2 restart
  //      默认 web 目录在 /var/www
  //   2. 安装 PHP
  //      sudo apt-get install libapache2-mod-php5 php5
  //      sudo /etc/init.d/apache2 restart
  //   3. 安装 MySQL
  //      sudo apt-get install mysql-server mysql-client
  //   4. 安装 phpmyadmin
  //      sudo apt-get install phpmyadmin
  //      sudo ln -s /usr/share/phpmyadmin /var/www
  //      http://localhost/phpmyadmin
  // ===========================

  // Ubuntu 上使用 MySQL
  // mysql -uroot -p123456
  // show databases;
  // use my_db;
  // show tables;
  // select * from Persons;
  // drop table Persons;

  // 报错:
  // The mysql extension is deprecated and will be removed in the future: use mysqli or PDO instead
  // PHP MySQLi = PHP MySQL Improved!
  // 高 PHP 版本要求用 MySQLi
  //$con = mysql_connect("localhost","root","123456");

  // mysqli 两种使用方法
  // 1. 结构编程
  /* 
  $mysqli = mysqli_connect('localhost', 'root', '123456');  
  if (! $mysqli){
     die('can not connect to mysql');
  } else {
     echo "connect mysql successfully<br>";
  }

  if(! mysqli_query($mysqli, 'CREATE DATABASE my_db')){
     echo "Error creating database: " . mysqli_error($mysqli);
  } else {
     echo "create database successfully<br>";
  }
  */
  
  // 2. 对象编程
  $mysqli = new mysqli('localhost', 'root', '123456', 'my_db');
  if($mysqli->connect_error){
     echo "can not connect to mysql my_db : [" . $mysqli->connect_errno . "] " .  $mysqli->connect_error . "<br>";
  } else {
     echo "connect mysql my_db successfully<br>";
  }

  // 另一种初始化方法
  // $mysqli = mysqli_init();
  // $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 2);  //设置超时时间
  // $mysqli->real_connect('localhost', 'root', '123456', 'my_db');

  // 如果要新创建数据库
  // $mysqli = new mysqli('localhost', 'root', '123456');
  // $mysqli->query("CREATE DATABASE my_db");
  // $mysqli->select_db("my_db");

  // 创建新表
  $sql = "CREATE TABLE Persons
          (
             ID int NOT NULL AUTO_INCREMENT primary key,
             CardID int unique key,
             FirstName varchar(10),
             LastName varchar(10),
             Age int,
             Company varchar(20),
             City varchar(10)
          )";
  $mysqli->query($sql);
  if($mysqli->error){
     echo "can not create table : " . $mysqli->error . "<br>";
  } else {
     echo "create table successfully<br>";
  }

  // 插入数据
  function insertData($mysqli, $sql){
    $mysqli->query($sql);
    if($mysqli->error){
       echo "can not insert data : " . $mysqli->error . "<br>";
    } else {
       echo "insert data successfully<br>";
    }
  }

  $sql = "insert into Persons(CardID, FirstName, LastName, Age, Company, City) values(678, 'Zhang', 'San', 28, 'QQ', 'Guangzhou')";
  insertData($mysqli, $sql);

  $sql = "insert into Persons(CardID, FirstName, LastName, Age, Company, City) values(123, 'Zhao', 'Jiaren', 32, 'Ali', 'Hangzhou')";
  insertData($mysqli, $sql);

  $sql = "insert into Persons(CardID, FirstName, LastName, Age, Company, City) values(456, 'Li', 'Si', 25, 'Google', 'Beijing')";
  insertData($mysqli, $sql);

  // 选择
  function printSelectedResult($mysqli, $sql){
     $result = $mysqli->query($sql);
     if($mysqli->error){
        echo "can not select data : " . $mysqli->error . "<br>";
     } else {
        echo "<br>
              <table border='1'>
                <tr>
                  <th>ID</th>
                  <th>CardID</th>
                  <th>FirstName</th>
                  <th>LastName</th>
                  <th>Age</th>
                  <th>Company</th>
                  <th>City</th>
                </tr>";
        while($row = $result->fetch_array()) {
           echo "<tr>";
           echo "<td>" . $row['ID'] . "</td>";
           echo "<td>" . $row['CardID'] . "</td>";
           echo "<td>" . $row['FirstName'] . "</td>";
           echo "<td>" . $row['LastName'] . "</td>";
           echo "<td>" . $row['Age'] . "</td>";
           echo "<td>" . $row['Company'] . "</td>";
           echo "<td>" . $row['City'] . "</td>";
           echo "</tr>";
        }
        echo "</table>";
     }
  }

  $sql = "select * from Persons";
  printSelectedResult($mysqli, $sql);

  $sql = "select * from Persons Order By Age";
  printSelectedResult($mysqli, $sql);

  $sql = "insert into Persons(CardID, FirstName, LastName, Age, Company, City) values(88, 'Li', 'Si', 25, 'Google', 'Beijing')";
  $mysqli->query($sql);

  $sql = "Update Persons SET Age = '36' WHERE City = 'Hangzhou'";
  $mysqli->query($sql);

  $sql = "select * from Persons";
  printSelectedResult($mysqli, $sql);

  $sql = "select * from Persons Where Age <= 30 Order By Age, CardID";
  printSelectedResult($mysqli, $sql);

  $sql = "DELETE FROM Persons WHERE CardID = 88";
  $mysqli->query($sql);

  $sql = "Update Persons SET Age = '32' WHERE City = 'Hangzhou'";
  $mysqli->query($sql);

  $sql = "select * from Persons";
  printSelectedResult($mysqli, $sql);

  // 脚本结束也会自动关闭
  $mysqli->close();


  // ===================
  //  有三种 XML parser
  //  1. Expat Parser
  //  2. DOM Parser
  //  3. SimpleXML
  // ===================
  echo "<br>";

  // 1. Expat Parser
  $parser = xml_parser_create();
  
  function start($parser, $element_name, $element_attrs){
    if($element_name == "NOTE")
       echo "-- Note --<br>";
    else
       echo ucfirst(strtolower($element_name)) . ": ";
       //echo strtolower($element_name) . ": ";
  }

  function stop($parser, $element_name){
    echo "<br>";
  }

  function char($parser, $data){
    echo $data;
  }

  xml_set_element_handler($parser, "start", "stop");
  xml_set_character_data_handler($parser, "char");

  $fp=fopen("test.xml", "r");

  while ($data=fread($fp, 4096)){
    xml_parse($parser, $data, feof($fp)) or 
    die (sprintf("XML Error: %s at line %d", xml_error_string(xml_get_error_code($parser)), xml_get_current_line_number($parser)));
  }

  xml_parser_free($parser);

  // 2. DOM Parser
  $xmlDoc = new DOMDocument();
  $xmlDoc->load("test.xml");

  echo $xmlDoc->saveXML() . "<br>";  

  $x = $xmlDoc->documentElement;
  foreach ($x->childNodes as $item) {
     echo $item->nodeName . " = " . $item->nodeValue . "<br>";
  }

  // 3. SimpleXML
  $xml = simplexml_load_file("test.xml");
  echo "<br>" . ucfirst(strtolower($xml->getName())) . "<br>";
  foreach($xml->children() as $child){
    echo ucfirst(strtolower($child->getName())) . ": " . $child . "<br>";
  }


  // ==============
  //echo phpinfo();
  // ============== 

?>


<br><br><br>
<input type="button" value="测试"><br>
<br><br><br>


