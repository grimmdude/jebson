Up until now I've always used Wordpress to manage this site; mainly because it's easy to use and it's what I learned on. It's great in that regard but lately I've been realizing how slow it is. I realized that I didn't even need the media library, special SEO plugins, dynamic nav menus, and widgets. Then I realized, I don't even need a database. I wanted a system that was fast to load, and simple to add content or modify template files. Basically I wanted a static site, but I wasn't too keen on learning a bunch of new terminal commands and re-building everytime I write a new post. 

Blah blah blah, so I wrote my own CMS. While it still technically builds each page dynamically it's quick loading, and there's no database to fuss with. I call it `Jebson`, named after one of my pups. Now I'll describe how to use it, if not for anyone else at least I'll have it in case I forget. You'll notice I borrow some stuff from popular static site generators, that's just how I roll. 

## Setup

`Jebson` is written in PHP and is setup to use Apache. I don't think it would be hard to use other web servers though. You'll just need to replace the `.htaccess` files to what work with whatever your web server uses. 

## Templates

The main work in setup is going to be setting up your templates. These are the common files that get loaded on each page like the header, footer, etc... All templates are `.php` files and live in the `/templates` directory. Below are the variables that you'll need to echo in your templates to output content and other available data. 

### Template Variables

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>
        Variable
      </th>
      
      <th>
        Description
      </th>
    </tr>
  </thead>
  
  <tbody>
    <tr>
      <td>
        <code>self::$content</code>
      </td>
      
      <td>
        The juice. This holds the content.
      </td>
    </tr>
    
    <tr>
      <td>
        <code>self::$load_time</code>
      </td>
      
      <td>
        Holds the number of seconds it took to load the page.
      </td>
    </tr>
  </tbody>
</table>

To define the load order for the templates use the array in \*\*\*|\*\*

## Writing Content

All content is saved as html files in the `/content` directory. Naming conventions are as follows: 

### Posts

For posts which are to be sorted by date:

<pre>2013-08-22-url-of-the-post.html
</pre>

This will result in a post url like so:

<pre>website.com/2013/08/22/url-of-the-post
</pre>

### Pages

For pages, that is content that is not considered a dated post, just leave out the date:

<pre>title-of-my-page.html
</pre>

Which results in this url:</pre> 
<pre>website.com/title-of-my-page
</pre>

Each file starts off with some YAML to define some stuff:

<pre>title: Title of the post or page
description: Description meta data
keywords: Keyword meta data

</pre>