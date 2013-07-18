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
      <div class="span5">
        <div class="well">
        <div id="webcam" style="text-align: center">
          <img id="snapshot" src="get_image.php" />
        </div> 
        </div>
      </div>
      <div class="span3">
	<div class="well">
		<ul class="nav nav-list">
			<li class="nav-header">Camera Move</li>
			<li>
				<button onclick="moveUp()" type="button" class="btn btn-success"><i class="icon-search icon-arrow-up"></i></button>
				<button onclick="moveDown()" type="button" class="btn btn-danger"><i class="icon-search icon-arrow-down"></i></button>
			</li>
			<li class="nav-header">Camera Use</li>
			<li><button onclick="snapshot_now()" type="button" class="btn btn-warning"><i class="icon-search icon-camera"></i>Snapshot</button></li>
			<li class="nav-header">Give Cookies</li>
			<li><button onclick="giveCookieA()" type="button" class="btn btn-info">Cookie Bone</button></li>
			<li><button onclick="giveCookieB()" type="button" class="btn btn-info">Cookie Square</button></li>
			<li class="nav-header">Play Sound</li>
			<div id="sounds"> 
				<li> Sounds to go here </li>
			</div>	
		</ul>
	</div>
      </div>
    </div>
  </div>
</div>
</body>


    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">

	ns=0;

	function giveCookieB() {
		$.get('give_cookie_B.php');
	}

	function giveCookieA() {
		$.get('give_cookie_A.php');
	}

	function moveUp() {
		$.get('camera_up.php');
	}
	function moveDown() {
		$.get('camera_down.php');
	}
	function snapshot_now() {
		$.get('snapshot.php');
	}

	function refresh_image() {
		ns=ns+1;
		var i = $('<img id="snapshot" />').attr('src',"get_image.php?ns="+ns).load(function(data, status,other) {
			$('#snapshot').remove();
			$(this).appendTo("#webcam");
			$('#status').empty();
			setTimeout(refresh_image,100);
		}).error(function () {
			var d = new Date();
    			var time = d.getFullYear() +"/"+(d.getMonth()+1)+"/"+d.getDate()+ " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
			var i = $('<div class="alert alert-error">');
			//i.append('<button type="button" class="close" data-dismiss="alert">x</button>');
			i.append('<strong>' +time+'  Connection lost! reconnecting ... </strong>');
			$('#status').empty();
			$('#status').append(i);
			setTimeout(refresh_image,1500);
		});
	}


	function findBaseName(url) {
	    var fileName = url.substring(url.lastIndexOf('/') + 1);
	    var dot = fileName.lastIndexOf('.');
	    return dot == -1 ? fileName : fileName.substring(0, dot);
	}

	function playsound(index) {
		$.get('play_sound.php?x='+index, function(data) {
				var i = $('<div class="alert alert-info">');
				i.append('<button type="button" class="close" data-dismiss="alert">x</button>');
				i.append('<strong>Sound played!</strong>');
				//$('#alerts').append(i);
			});
	}

	function read_sounds_list() {
		$.get('get_sounds.php',function (data) {
			$('#sounds').empty();
			$.each(data.split('\n'), function(index, value)  {
				var name=findBaseName(value);
				if (name) {
					var i = $('<li>	</li>').append($('<button onclick="playsound(' + index +')" type="button" class="btn btn-info">'+name+'</button>'));
					$('#sounds').prepend(i);
				}
			});
		});
	}

	$(document).ready(function() {
		read_sounds_list();
		
		refresh_image();
	});

    </script>
  </body>
</html>
