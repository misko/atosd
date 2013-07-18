<!DOCTYPE html>
<html>
  <head>
    <title>Life of Atos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>

<body>


<div class="container">

<div class="row">
 <div class="span8 offset2">
  <?php require('navbar.php') ?>
  <div class="row">
   <div class="span5" style="text-align: center">
    <a class="yourButton" href="inapp://capture"><button class="btn btn-large btn-danger" type="button">iOS record</button></a>
    <form class="form-horizontal" action="upload.php" method="post" enctype="multipart/form-data">
     <legend>Upload a mp3</legend>
     <div class="control-group">
      <label class="control-label" for="name">Name:</label>
      <div class="controls">
       <input type="text" name="name" id="name" placeholder="name">
      </div>
     </div>
     <div class="control-group">
      <label class="control-label" for="userfile">File:</label>
      <div class="controls">
       <input type="file" name="userfile" id="userfile"><br>
      </div>
     </div>
     <button type="submit" class="btn">Submit</button>
    </form>
   </div>
  </div>
 </div>
</div>

</div>

</body>

</html>


