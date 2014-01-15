<?php include('header.php');?>
<!--galery link-->
		<link rel="stylesheet" href="gallery/css/galleriffic-2.css" type="text/css" />
		<script type="text/javascript" src="gallery/js/jquery.galleriffic.js"></script>
		<script type="text/javascript" src="gallery/js/jquery.opacityrollover.js"></script>
		<!-- We only want the thunbnails to display when javascript is disabled -->
		<script type="text/javascript">
			document.write('<style>.noscript { display: none; }</style>');
		</script>
<!-- /galery link-->        
        
      <div class="clr"></div>
  
  <div class="content">
    <div class="content_resize">
      <div class="mainbar" style="width:960px;">
        <div class="article">
         <h2>Guide to MiFID</h2>
         <p>&nbsp;</p>
		  <!---Image galary--->
      
      <!--<div id="page">-->
			<div id="container" >
								<!-- Start Advanced Gallery Html Containers -->
				<div id="gallery" class="content" style="float:left;width:81%">
					<div id="controls" class="controls" style="margin-left:155px;"></div>
					<div class="slideshow-container">
						<div id="loading" class="loader"></div>
						<div id="slideshow" class="slideshow"></div>
					</div>
					<div id="caption" class="caption-container"></div>
				</div>
				<div id="thumbs" class="navigation">
					<ul class="thumbs noscript" style="display:none;">
						<li>
							<a style="display:none;" class="thumb" name="leaf" href="gallery/images/Page1.jpg" >
								<img src="" alt="Title #1"  style="display:none;"/>
							</a>
						</li>

						<li>
							<a class="thumb" name="drop" href="gallery/images/Page2.jpg" >
								<img src="" alt="Title #2" />
							</a>
						</li>

						<li>
							<a class="thumb" name="bigleaf" href="gallery/images/Page3.jpg" >
								<img src="" alt="Title #3" />
							</a>
							
						</li>
                        <li>
							<a class="thumb" name="bigleaf" href="gallery/images/Page4.jpg">
								<img src="" alt="Title #4" />
							</a>
						</li>
                        <li>
							<a class="thumb" name="bigleaf" href="gallery/images/Page5.jpg" >
								<img src="" alt="Title #5" />
							</a>
						</li>
                        <li>
							<a class="thumb" name="bigleaf" href="gallery/images/Page6.jpg" >
								<img src="" alt="Title #6" />
							</a>
						</li>
                        <li>
							<a class="thumb" name="bigleaf" href="gallery/images/Page7.jpg">
								<img src="" alt="Title #7" />
							</a>
						</li>
                        <li>
							<a class="thumb" name="bigleaf" href="gallery/images/Page8.jpg" >
								<img src="" alt="Title #8" />
							</a>
						</li>
                        <li>
							<a class="thumb" name="bigleaf" href="gallery/images/Page9.jpg" >
								<img src="" alt="Title #9" />
							</a>
						</li>
                        <li>
							<a class="thumb" name="bigleaf" href="gallery/images/Page10.jpg" >
								<img src="" alt="Title #10" />
							</a>
						</li>
                        <li>
							<a class="thumb" name="bigleaf" href="gallery/images/Page11.jpg" >
								<img src="" alt="Title #11" />
							</a>
						</li>
                        <li>
							<a class="thumb" name="bigleaf" href="gallery/images/Page12.jpg" >
								<img src="" alt="Title #12" />
							</a>
						</li>
                        <li>
							<a class="thumb" name="bigleaf" href="gallery/images/Page13.jpg" >
								<img src="" alt="Title #13" />
							</a>
						</li>

						
					</ul>
				</div>
				<div style="clear: both;"></div>
			</div>
            <div style="clear: both;"></div>
		<!--</div>-->
		
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				// We only want these styles applied when javascript is enabled
				$('div.navigation').css({'width' : '300px', 'float' : 'left'});
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
			});
		</script>
      
      <div style="clear: both;">&nbsp;</div>
      
      <!---/ Image galary--->
      
      
      
      
      
         
          
          <div class="clr"></div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <?php include('footer.php');?>