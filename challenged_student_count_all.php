<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
$sql="Select * from
( Select 
  stat2.sch_name, 
  stat2.u_name, 
  stat2.u_id, 
  stat2.c_name, 
  stat2.c_id, 
  COUNT(DISTINCT students.stu_no) AS challenged_student 
FROM 
  (
    Select 
      schools.sch_name, 
      users.u_name, 
      users.u_id, 
      teachers.t_email, 
      classes.c_name, 
      classes.c_id 
    From 
      (
        (
          (
            chippy.schools 
            join chippy.teachers on chippy.schools.sch_id = chippy.teachers.sch_id
          ) 
          join chippy.users on chippy.teachers.u_id = chippy.users.u_id 
            AND (
            users.u_id >= 36 
            OR users.u_id = 32
          )
        ) 
        join chippy.classes on chippy.teachers.t_id = chippy.classes.tea_id and classes.c_name<>'000'
      )
  ) AS stat2 
  join chippy.students on stat2.c_id = students.c_id 
  AND students.s_name <> 'test' 
  join chippy.execution on students.stu_no = execution.stu_no 
group by 
  stat2.sch_name, 
  stat2.u_name, 
  stat2.u_id, 
  stat2.c_name, 
  stat2.c_id 
order by 
  stat2.u_id, 
  stat2.c_id
  )as t
where t.challenged_student >1
";

echo '
<style>
table, td, th {
  border: 1px solid;
}

table {
  width: 100%;
  border-collapse: collapse;
}
</style>
<table >
  <tr>
    <th>學校</th>
    <th>老師</th>
    <th>u_id</th>   
	<th>班級名稱</th>
    <th>c_id</th>
    <th>挑戰人數</th>
  </tr>';


for($i=30;$i<50;$i++){
	if($i==31){
		continue;
	}

//echo "<h3>140.122.251.".$i."的作答班級學生人數</h3>";
$servername = "140.122.251.".$i.":3306";
$username = "viplab";
$password = "chippy2022";
$db ="chippy";
// Create connection
//$conn = new mysqli($servername, $username, $password,$db);
$conn=mysqli_connect($servername,$username,$password,$db); 
// Check connection
if (!$conn) {
    continue;
	//die("Connection failed: " .mysqli_error($conn) );
	//die("連接錯誤: " . mysqli_connect_error()); 
}
	
	$result = mysqli_query($conn, $sql);






	if($i!=35){
		//$row = mysqli_fetch_array($result);
		//$row = mysqli_fetch_array($result);
		
		
	}




while($row = mysqli_fetch_array($result)){
	$stu_no = $row[0];
	$stu_id = $row[1];
	echo '  <tr>
    <td>'.$row[0].'</td>
    <td>'.$row[1].'</td>
	<td>'.$row[2].'</td>
	<td>'.$row[3].'</td>
	<td>'.$row[4].'</td>
	<td>'.$row[5].'</td>
  </tr>';

	//echo .','.$row[1].','.$row[2].','.$row[3].','.$row[4].','.$row[5]."<br>";
}


	
	
	
}
echo '</table>';
exit;

?>