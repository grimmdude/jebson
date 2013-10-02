				</div>
				<div class="col-md-4" id="sidebar">
					<h2>Wordpress Plugins</h2>
					<ul>
						<li><a href="/2011/06/11/wordpress-simple-auto-delay-popup-plugin">Simple Auto Delay Popup Plugin</a></li>
						<li><a href="/2009/01/29/wordpress-simple-popup-plugin">Simple Popup Plugin</a></li>
						<li><a href="/2010/02/28/simple-static-google-maps-wordpress-plugin">Simple Static Google Maps</a></li>
						<li><a href="/2009/11/04/simple-select-all-text-box-wordpress-plugin">Simple Select All Text Box</a></li>
						<li><a href="/2012/12/20/initial-letter-wordpress-plugin">Initial Letter</a></li>
					</ul>
					<h2>Online Music Tools</h2>
					<ul>
						<li><a href="http://www.musictheorysite.com/namethatchord/">Name That Chord</a></li>
						<li><a href="http://www.musictheorysite.com/namethatkey/">Name That Key</a></li>
						<li><a href="http://www.musictheorysite.com/scale-generator/">Scale Generator</a></li>
					</ul>
					<h2>GitHub Projects</h2>
					<ul>
						<li><a href="https://github.com/grimmdude/Scale-Generator">Scale Generator</a></li>
						<li><a href="https://github.com/grimmdude/Raphael-Keyboard">Raphael Keyboard</a></li>
						<li><a href="https://github.com/grimmdude/Raphael-Guitar">Raphael Guitar</a></li>
						<li><a href="https://github.com/grimmdude/jQuery-menuFlip">jQuery menuFlip Plugin</a></li>
					</ul>
				</div>
			</div>
			<footer>
				<p>Page built with <a href="https://github.com/grimmdude/jebson">Jebson</a></p>
				<p>Load time: <?php echo self::$loadTime; ?></p>
			</footer>
		</div>
		<?php if (false): ?>
			<!--Analytics-->
			<script type="text/javascript">

			  var _gaq = _gaq || [];
			  _gaq.push(['_setAccount', 'UA-1454657-6']);
			  _gaq.push(['_trackPageview']);

			  setTimeout("_gaq.push(['_trackEvent', '15_seconds', 'read'])", 15000); // To fix 'blog' bounce rates

			  (function() {
			    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			  })();
			</script>		
		<?php endif ?>		
	</body>
</html>