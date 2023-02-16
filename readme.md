<h1>Jebson CMS</h1>
<p>
	Up until now I've always used Wordpress to manage my website; mainly because it's easy to use and it's what I learned on.  It's great in that regard
	but lately I've been realizing how slow it is.  I realized that I didn't even need the media library, special SEO plugins, dynamic nav menus, and widgets.  Then I realized,
	I don't even need a database.  I wanted a system that was fast to load, and simple to add content or modify template files.  Basically I wanted a static site, but I wasn't too keen 
	on learning a bunch of new terminal commands and re-building every time I write a new post.
</p>
<p>
	Blah blah blah, so I wrote my own CMS.  While it still technically builds each page dynamically it's quick loading, and there's no database to fuss with.  
	I call it <code>Jebson</code>, named after one of my pups.  Jebson is a very lightweight databaseless CMS that is geared towards simplicity and swift load times.  Note that currently I'm writing this is so I will have some documentation for myself so it may not be complete.  
	You'll notice I borrow some stuff from popular static site generators, that's just how I roll.
</p>
<h2>Setup</h2>
<p>
	<code>Jebson</code> is written in PHP and is setup to use Apache.  To install place all of Jebson's files into your webroot.  Now say "yea I did it!".
</p>
<h3>Directory Structure</h3>
<pre>
.
   .htaccess
   assets/
   cache/
   content/
      2013-08-22-sample-post.php
      sample-page.php
   config.php
   index.php
   lib/
   views/
      404.php
      body.php
      excerpt.php
      footer.php
      header.php
      post.php
</pre>
<p>All of the settings for Jebson can be found in the Config class (<a href="https://github.com/grimmdude/jebson/blob/master/config.php" target="_blank">config.php</a>).</p>
<h2>Views</h2>
<p>
	These are the common files that get loaded on each page like the header, footer, etc...  
	All views are <code>.php</code> files and live in the <code>/views</code> directory.  Below are the variables that you'll need to echo in your view 
	to output content and other available data.
</p>
<h3>View Variables</h3>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Variable</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><code class="language-php">self::$request</code></td>
			<td>Contains an array of the segments in the request URL.</td>
		</tr>
		<tr>
			<td><code class="language-php">self::$content</code></td>
			<td>The juice.  This holds the content.</td>
		</tr>
		<tr>
			<td><code class="language-php">self::$excerpt</code></td>
			<td>Holds a post excerpt.</td>
		</tr>
		<tr>
			<td><code class="language-php">self::$loadTime</code></td>
			<td>Holds the number of seconds it took to load the page.</td>
		</tr>
		<tr>
			<td><code class="language-php">self::$pageData</code></td>
			<td>Array of data defined at the top of each piece of content.</td>
		</tr>
		<tr>
			<td><code class="language-php">self::$date</code></td>
			<td>Contains the date for blog posts in this format: MM-DD-YYYY.  If not available, this variable will be set to <code>false</code>.</td>
		</tr>
	</tbody>
</table>
<p>To define the load order for the views use the config array <code class="language-php">Config::$viewLoadOrder</code><p>
<h3>View Methods</h3>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Method</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><code class="language-php">self::renderContent()</code></td>
			<td>Typically will be placed in the body of your site.  This method checks the requested URL and sets up the appropriate content.</td>
		</tr>
	</tbody>
</table>
<h2>Writing Content</h2>
<p>
	All content is saved as <code>.php</code> files in the <code>/content</code> directory and markup is standard HTML.  Naming conventions are as follows:
</p>
<h3>Posts</h3>
<p>For posts which are to be sorted by date:</p>
<pre>
2013-08-22-url-of-the-post.php
</pre>
<p>This will result in a post url like so:</p>
<pre>
website.com/2013/08/22/url-of-the-post
</pre>
<h3>Pages</h3>
<p>For pages, that is content not considered a dated post, just leave out the date:</p>
<pre>
title-of-my-page.php
</pre>
<p>Which results in this url:</pre>
<pre>
website.com/title-of-my-page
</pre>
<p>Each file starts off with some basic properties to define stuff:</p>
<pre>
<code class="language-php">
&lt;?php
self::$pageData['title'] = 'Title of the post or page.';
self::$pageData['keywords'] = 'Comma separated keywords.';
self::$pageData['description'] = 'Description';
?&gt;
</code>
</pre>
<p>
	These variables are available in the views and can be used how you wish.  Obviously I'd use these basic definitions for 
	page meta data, but you can also add your own custom definitions to trigger things like comments, layouts, scripts, css, etc.
</p></article>