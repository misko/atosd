<!DOCTYPE html>
<html>
  <head>
    <title>Life of Atos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/basic.css" type="text/css" />
    <link rel="stylesheet" href="css/galleriffic-2.css" type="text/css" />
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<script type="text/javascript">
			document.write('<style>.noscript { display: none; }</style>');
		</script>
  </head>
  <body>

<body>
<div class="container">
	<div class="row">
	  <div class="span8 offset2">
	  <?php require('navbar.php'); ?>
	  <div id="controls" class="controls"></div>
        <div class="span8">
				<!-- Start Advanced Gallery Html Containers -->
				<div id="gallery" class="content">
					<div class="slideshow-container">
						<div id="loading" class="loader"></div>
						<div id="slideshow" class="slideshow"></div>
					</div>
					<div id="caption" class="caption-container"></div>
				</div>
		<div id="thumbs" class="navigation">
			<ul class="thumbs noscript" id="gallery_list"> </ul>
		</div>
         </div>
	</div>
</div>

</body>


    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.galleriffic.js"></script>
	<script type="text/javascript" src="js/jquery.opacityrollover.js"></script>
    <script type="text/javascript">

	function init_gallery() {
			// We only want these styles applied when javascript is enabled
			$('div.navigation').css({'width' : '200px', 'float' : 'left'});
			$('div.content').css('display', 'block');

			// Initially set opacity on thumbs and add
			// additional styling for hover effect on thumbs
			var onMouseOutOpacity = 0.67;
			$('#thumbs ul.thumbs li').opacityrollover({
				mouseOutOpacity:   onMouseOutOpacity,
				mouseOverOpacity:  1.0,
				fadeSpeed:         'fast',
				exemptionSelector: '.selected'
			});
			
			// Initialize Advanced Galleriffic Gallery
			var gallery = $('#thumbs').galleriffic({
				delay:                     2500,
				numThumbs:                 15,
				preloadAhead:              10,
				enableTopPager:            true,
				enableBottomPager:         true,
				maxPagesToShow:            7,
				imageContainerSel:         '#slideshow',
				controlsContainerSel:      '#controls',
				captionContainerSel:       '#caption',
				loadingContainerSel:       '#loading',
				renderSSControls:          true,
				renderNavControls:         true,
				playLinkText:              'Play Slideshow',
				pauseLinkText:             'Pause Slideshow',
				prevLinkText:              '&lsaquo; Previous Photo',
				nextLinkText:              'Next Photo &rsaquo;',
				nextPageLinkText:          'Next &rsaquo;',
				prevPageLinkText:          '&lsaquo; Prev',
				enableHistory:             false,
				autoStart:                 false,
				syncTransitions:           true,
				defaultTransitionDuration: 900,
				onSlideChange:             function(prevIndex, nextIndex) {
					// 'this' refers to the gallery, which is an extension of $('#thumbs')
					this.find('ul.thumbs').children()
						.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
						.eq(nextIndex).fadeTo('fast', 1.0);
				},
				onPageTransitionOut:       function(callback) {
					this.fadeTo('fast', 0.0, callback);
				},
				onPageTransitionIn:        function() {
					this.fadeTo('fast', 1.0);
				}
			});
	}

	function populate_gallery() {	
		$.get('get_gallery.php',function(data) {
			$('#gallery_list').empty();
			$.each(data.split('\n'), function(index, value) {
				ar = value.split('\t');
				if (ar.length==3) {
					url = ar[0];
					thumb300 = ar[1];
					thumb75 = ar[2];
					var i = $('<a class="thumb">');
					i.attr('href',ar[1]);
					i.attr('title',ar[1]);
				
					var ms_since_epoch=ar[0].split('.')[0].split('gallery/')[1];
					var d = new Date(ms_since_epoch*1000);
					//d.setUTCSeconds(ms_since_epoch/1000);
					var time = d.getFullYear() +"/"+(d.getMonth()+1)+"/"+d.getDate()+ " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();

					i.append($('<img>').attr('src',ar[2]));

					var c = $('<div class="caption">').append($('<div class="download">').append($('<p>'+time+'</p><a href="'+ar[0]+'">Download Original</a>')));
		
					$('#gallery_list').append($('<li>').append(i).append(c));
				}
			});
			init_gallery();
		});	
	}
	
	

	ns=0;
	$(document).ready(function() {
		populate_gallery();
	});	
    </script>
  </body>
</html>
