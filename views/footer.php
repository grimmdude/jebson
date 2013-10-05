				</div>
				<div class="col-md-4" id="sidebar">
					<h2>Sidebar Stuff!</h2>
					<ul>
						<li><a href="http://grimmdude.com/2013/08/22/jebson-cms" target="_blank">Jebson Documentation</a></li>
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