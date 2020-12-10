<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'shorten_db';
$base_url='http://localhost/myapp/'; 

if(isset($_GET['url']) && $_GET['url']!="")
{ 
$url=urldecode($_GET['url']);
if (filter_var($url, FILTER_VALIDATE_URL)) 
{
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
} 
$slug=GetShortUrl($url);
?>
<br><br><center>
<?php
echo 'Short URL:  <a href="index.php?redirect='.$slug.'" target="_blank">'.$base_url.$slug.'</a>';
?> <br><br><?php
echo '<a href="index.php">Click Here To Return To Home</a>';
?> </center><?php
} 
else 
{
die("$url is not a valid URL");
}
 
}
else
{	?>
<!DOCTYPE html>
<html>
<head>
	<!-- Bootstrap CDN -->
    <link rel="stylesheet"  
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">   
     <style>   
    table {  
        border-collapse: collapse;  
    }  
        .inline{   
            display: inline-block;   
            float: right;   
            margin: 20px 0px;   
        }   
         
        input, button{   
            height: 34px;   
        }   
  
    .pagination {   
        display: inline-block;   
    }   
    .pagination a {   
        font-weight:bold;   
        font-size:18px;   
        color: black;   
        float: left;   
        padding: 8px 16px;   
        text-decoration: none;   
        border:1px solid black;   
    }   
    .pagination a.active {   
            background-color: pink;   
    }   
    .pagination a:hover:not(.active) {   
        background-color: skyblue;   
    }   
        </style>   
</head>
<body>
<center>
<h1>Enter Your Url</h1>
<form>
<p><input style="width:500px" type="url" name="url" required /></p>
<p><input type="submit" value="Save" /></p>
</form>
</center>
<?php  
				    $conn = mysqli_connect('localhost', 'root', '', 'shorten_db');  
				        if (! $conn) {  
				    die("Connection failed" . mysqli_connect_error());  
				    }  
				        else {  
				    mysqli_select_db($conn, 'pagination');  
				    }    
    
        $per_page_record = 20;  // Number of entries to show in a page.   
        // Look for a GET variable page if not found default is 1.        
        if (isset($_GET["page"])) {    
            $page  = $_GET["page"];    
        }    
        else {    
          $page=1;    
        }    
    
        $start_from = ($page-1) * $per_page_record;     
    
        $query = "SELECT * FROM url_shorten LIMIT $start_from, $per_page_record";     
        $rs_result = mysqli_query ($conn, $query) or die( mysqli_error($conn));    
    ?>    
  
    <div class="container">   
      <br>   
      <div>   
        <h1>All Urls</h1>    
        <table class="table table-striped table-condensed    
                                          table-bordered">   
          <thead>   
            <tr>   
              <th width="10%">ID</th>     
              <th>Url</th>   
              <th>Short Url</th>  
              <th>Url Visit Count</th>   
            </tr>   
          </thead>   
          <tbody>   
    <?php     
            while ($row = mysqli_fetch_array($rs_result)) {    
                  // Display each field of the records.    
            ?>     
            <tr>     
             <td><?php echo $row["id"]; ?></td>     
            <td><?php echo $row["url"]; ?></td>  
            <?php
            $url = $row["url"];
            $slug=GetShortUrl($url);?> 
            <td><?php echo '<a href="index.php?redirect='.$slug.'" target="_blank">'.$base_url.$slug.'</a>';?></td>   
          	<td><?php echo $row["hits"]; ?></td>                                        
            </tr>     
            <?php     
                };    
            ?>     
          </tbody>   
        </table>   
  
     <div class="pagination">    
      <?php  
        $query = "SELECT COUNT(*) FROM url_shorten";     
        $rs_result = mysqli_query($conn, $query);     
        $row = mysqli_fetch_row($rs_result);     
        $total_records = $row[0];     
          
    echo "</br>";     
        // Number of pages required.   
        $total_pages = ceil($total_records / $per_page_record);     
        $pagLink = "";       
      
        if($page>=2){   
            echo "<a href='index.php?page=".($page-1)."'>  Prev </a>";   
        }       
                   
        for ($i=1; $i<=$total_pages; $i++) {   
          if ($i == $page) {   
              $pagLink .= "<a class = 'active' href='index1.php?page="  
                                                .$i."'>".$i." </a>";   
          }               
          else  {   
              $pagLink .= "<a href='index.php?page=".$i."'>   
                                                ".$i." </a>";     
          }   
        };     
        echo $pagLink;   
  
        if($page<$total_pages){   
            echo "<a href='index.php?page=".($page+1)."'>  Next </a>";   
        }   
  
      ?>    
      </div>  
  
  
      <div class="inline">   
      <input id="page" type="number" min="1" max="<?php echo $total_pages?>"   
      placeholder="<?php echo $page."/".$total_pages; ?>" required>   
      <button onClick="go2Page();">Go</button>   
     </div>    
    </div>   
  </div>  
</center>   
  <script>   
    function go2Page()   
    {   
        var page = document.getElementById("page").value;   
        page = ((page><?php echo $total_pages; ?>)?<?php echo $total_pages; ?>:((page<1)?1:page));   
        window.location.href = 'index.php?page='+page;   
    }   
  </script>  
</body>
</html>
<?php
}


function GetShortUrl($url){
 global $conn;
 $query = "SELECT * FROM url_shorten WHERE url = '".$url."' "; 
 $result = $conn->query($query);
 if ($result->num_rows > 0) {
$row = $result->fetch_assoc();
 return $row['short_code'];
} else {
$short_code = generateUniqueID();
$sql = "INSERT INTO url_shorten (url, short_code, hits)
VALUES ('".$url."', '".$short_code."', '0')";
if ($conn->query($sql) === TRUE) {
return $short_code;
} else { 
die("Unknown Error Occured");
}
}
}

function generateUniqueID(){
 global $conn; 
 $token = substr(md5(uniqid(rand(), true)),0,6); $query = "SELECT * FROM url_shorten WHERE short_code = '".$token."' ";
 $result = $conn->query($query); 
 if ($result->num_rows > 0) {
 generateUniqueID();
 } else {
 return $token;
 }
}

if(isset($_GET['redirect']) && $_GET['redirect']!="")
{ 
$slug=urldecode($_GET['redirect']);
 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$url= GetRedirectUrl($slug);
$conn->close();
header("location:".$url);
exit;
}

function GetRedirectUrl($slug){
 global $conn;
 $query = "SELECT * FROM url_shorten WHERE short_code = '".addslashes($slug)."' "; 
 $result = $conn->query($query);
 if ($result->num_rows > 0) {
$row = $result->fetch_assoc();
$hits=$row['hits']+1;
$sql = "update url_shorten set hits='".$hits."' where id='".$row['id']."' ";
$conn->query($sql);
return $row['url'];
}
else 
 { 
die("Invalid Link!");
}
}
?>