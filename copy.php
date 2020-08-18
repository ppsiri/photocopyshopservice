<!DOCTYPE html>
<html lang = "en">
<head>
<?php
$con = mysqli_connect("localhost","root","","photocopyshopservice");
$con -> set_charset("utf8")
?>
    <meta charset    = "UTF-8">
    <meta name       = "viewport" content        = "width=device-width, initial-scale=1.0">
    <meta http-equiv = "X-UA-Compatible" content = "ie=edge">
    <title>การจัดการระบบ</title>
    <link rel  = "stylesheet" href                                                 = "copy.css">
    <link href = "https://fonts.googleapis.com/css?family=Prompt&display=swap" rel = "stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Maitree&display=swap" rel="stylesheet">
</head>
<body class = "bg">

<?php

ini_set('display_errors', 1);
error_reporting(~0);

$strKeyword = null;

if(isset($_POST["txtKeyword"]))
{
  $strKeyword = $_POST["txtKeyword"];
}
if(isset($_GET["txtKeyword"]))
{
  $strKeyword = $_GET["txtKeyword"];
}
?>
<div class = "container">
      <div>
      <header class = "header">
      <a>ร้านถ่ายเอกสารหอใน</a><br>
          <a>มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน </a><br>
          <table class = "F">
          <form  class = "BN" method = "post" action     = "<?php echo $_SERVER['SCRIPT_NAME'];?>">
          <div class="tooltip">
          <input name   = "txtKeyword" size="20%" type = "text" placeholder="ค้นหา..." id = "txtKeyword" value = "">
          <span class="tooltiptext">ใส่ชื่อของผู้ที่ต้องการค้นหา</span>
    </div>
          <div class="tooltip1">
          <button class="button" >ค้นหา</button>
          <span class="tooltiptext1">ค้นหาผู้ที่มาใช้บริการ</span>
    </div>
</form>
<div class="tooltip2">
<button class="button" onclick = "window.location.href='insert.html'">เพิ่มข้อมูล</button>
<span class="tooltiptext2">เพิ่มข้อมูลผู้ที่มาใช้บริการ</span>
    </div>
</table>    
</header>
  <?php

$sql = "SELECT serv.ServiceID ,serv.ServDate, cust.TitleName ,cust.FirstName as FirstNameC ,cust.LastName,cust.Major ,w.WorkName ,sta.FirstName FROM
Customer AS cust INNER JOIN
(SELECT * FROM service) AS serv 
ON serv.CustSSN = cust.SSN 
INNER JOIN
(SELECT * FROM work) AS w 
ON serv.WorkID = w.WorkID
INNER JOIN
(SELECT * FROM staff) AS sta
ON serv.StaffID = sta.ID

where serv.ServiceID LIKE '%".$strKeyword."%' OR serv.ServDate LIKE '%".$strKeyword."%' OR cust.TitleName LIKE '%".$strKeyword."%' 
  OR cust.FirstName LIKE '%".$strKeyword."%' OR cust.LastName LIKE '%".$strKeyword."%' OR cust.Major LIKE '%".$strKeyword."%' 
  OR w.WorkName LIKE '%".$strKeyword."%' OR sta.FirstName LIKE '%".$strKeyword."%'  ";

$query = mysqli_query($con, $sql);

$num_rows = mysqli_num_rows($query);
$per_page = 8;
$page     = 1;
if(isset($_GET["Page"]))
	{
		$page = $_GET["Page"];
	}

	$prev_page = $page-1;
	$next_page = $page+1;

	$row_start = (($per_page*$page)-$per_page);
	if($num_rows<=$per_page)
	{
		$num_pages = 1;
	}
	else if(($num_rows % $per_page)==0)
	{
		$num_pages = ($num_rows/$per_page) ;
	}
	else
	{
		$num_pages = ($num_rows/$per_page)+1;
		$num_pages = (int)$num_pages;
	}
	$row_end = $per_page * $page;
	if($row_end > $num_rows)
	{
		$row_end = $num_rows;
	}

  $sql.=" ORDER BY serv.ServiceID ASC LIMIT $row_start ,$per_page ";
  
    $query = mysqli_query($con,$sql);
    


?>
      <?php
                  function  show_tdate($date_in) 
                  {
                  $month_arr = array("มกราคม" , "กุมภาพันธ์" , "มีนาคม" , "เมษายน" , "พฤษภาคม" , "มิถุนายน" , "กรกฎาคม" , "สิงหาคม" , "กันยายน" , "ตุลาคม" ,"พฤศจิกายน" , "ธันวาคม" ) ; 
                  $tok = strtok($date_in, "-");
                  $year = $tok ; 
                  $tok  = strtok("-");
                  $month = $tok ;
                  $tok = strtok("-");
                  $day = $tok ;
                  $year_out = $year + 543;
                  $cnt = $month-1 ;
                  $month_out = $month_arr[$cnt] ;
                  $t_date = $day." ".$month_out." ".$year_out ;
                  return $t_date ;
                  
                  }
            ?>
      
           <div class = "main">
           <table  style = "width: 100% " id    = "customers">
            <tr>
              <th style = "width: 5% ">ลำดับ</th>
              <th style = "width: 15% ">วันที่ใช้บริการ</th>
              <th style = "width: 20% ">ชื่อ</th>
              <th style = "width: 20% ">นามสกุล</th>
              <th style = "width: 10% ">สาขา</th>
              <th style = "width: 20% ">มาใช้บริการด้าน</th>
              <th style = "width: 10% ">ผู้ให้บริการ</th>
            </tr>
            <?php 
            while ($result = mysqli_fetch_assoc($query)) { ?>
            <tr>
              <td><?php echo $result['ServiceID']; ?></td>
              <td><?php echo show_tdate($result['ServDate']); ?></td>
              <td><?php echo $result['TitleName']." ".$result['FirstNameC'];?></td>
              <td><?php echo $result['LastName']; ?> </td>
              <td><?php echo $result['Major']; ?></td>
              <td><?php echo $result['WorkName']; ?></td>
              <td><?php echo $result['FirstName']; ?></td>

            </tr>
            <?php } ?>
              
          </table>
            </div>
   
    
    <div class = "page">
    <?php
        if($prev_page >= 1)
        {
      
          echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$prev_page&txtKeyword=$strKeyword'><< Back</a> ";
         
   
        }
        else
        {
          echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$page&txtKeyword=$strKeyword'><< Back</a> ";
        }
        
        for($i=1; $i<=$num_pages; $i++){
          
            echo "<a href='$_SERVER[SCRIPT_NAME]?Page=$i&txtKeyword=$strKeyword'>$i</a>";
          
        }
        if($page!=$num_pages)
        {
          echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$next_page&txtKeyword=$strKeyword'>Next>></a> ";
        }
        else
        {
          echo "<a href='$_SERVER[SCRIPT_NAME]?Page=$num_pages&txtKeyword=$strKeyword'>Next>></a>";
        }
$con = null;
?>  

    </div> 
   
   
 
    
</body>
</html>