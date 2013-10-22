# Jebson CMS

Up until now I've always used Wordpress to manage this site; mainly because it's easy to use and it's what I learned on. It's great in that regard but lately I've been realizing how slow it is. I realized that I didn't even need the media library, special SEO plugins, dynamic nav menus, and widgets. Then I realized, I don't even need a database. I wanted a system that was fast to load, and simple to add content or modify template files. Basically I wanted a static site, but I wasn't too keen on learning a bunch of new terminal commands and re-building every time I write a new post.

Blah blah blah, so I wrote my own CMS. While it still technically builds each page dynamically it's quick loading, and there's no database to fuss with. I call it `Jebson`, named after one of my pups. Jebson is a very lightweight databaseless CMS that is geared towards simplicity and swift load times. Note that currently I'm writing this is so I will have some documentation for myself so it may not be complete. You'll notice I borrow some stuff from popular static site generators, that's just how I roll.

## Setup

`Jebson` is written in PHP and is setup to use Apache. To install place all of Jebson's files into your webroot. Now say "yea I did it!".

### Directory Structure


    .
       .htaccess
       assets/
       cache/
       content/
          2013-08-22-sample-post.html
          sample-page.html
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


All of the settings for Jebson can be found in the Config class (`config.php`).

## Views

These are the common files that get loaded on each page like the header, footer, etc... All views are `.php` files and live in the `/views` directory. Below are the variables that you'll need to echo in your view to output content and other available data.

### View Variables

Variable Description

`self::$request`
Contains an array of the segments in the request URL.

`self::$content`
The juice. This holds the content.

`self::$excerpt`
Holds a post excerpt.

`self::$loadTime`
Holds the number of seconds it took to load the page.

`self::$pageData`
Array of data defined in the YAML block of the content.

`self::$date`
Contains the date for blog posts in this format: MM-DD-YYYY. If not available, this variable will be set to `false`.

To define the load order for the views use the config array `Config::$viewLoadOrder`

### View Methods

Method Description

`self::renderContent()`
Typically will be placed in the body of your site. This method checks the requested URL and sets up the appropriate content.

## Writing Content

All content is saved as HTML files in the `/content` directory. I know HTML and prefer not to have to think about using any other type of markup, so boom. Note that no PHP will be parsed from these files. Naming conventions are as follows:

### Posts

For posts which are to be sorted by date:


    2013-08-22-url-of-the-post.html


This will result in a post url like so:


    website.com/2013/08/22/url-of-the-post


### Pages

For pages, that is content that is not considered a dated post, just leave out the date:


    title-of-my-page.html


Which results in this url:


    website.com/title-of-my-page


Each file starts off with some basic YAML to define some stuff:


    title: Title of the post or page
    description: Description meta data
    keywords: Keyword meta data


Currently, `title` will serve as the document title as well as the post/page title. `description` and `keywords` are what get placed in the respective meta tags. You can add your own key/value pairs to be used in your views. For instance, I use a key called `comments` to control whether or not disqus comments are displayed in my views.


    comments: on


This YAML data is accesible in the `self::$pageData` array within view files.